<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
		}
	}
	function save_category(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','description'))){
				if(!empty($data)) $data .= ",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(isset($_POST['description'])){
			if(!empty($data)) $data .= ",";
			$data .= " `description`='".addslashes(htmlentities($description))."' ";
		}
	
		// ❌ Removed the category existence check
	
		if(empty($id)){
			$sql = "INSERT INTO `categories` set {$data} ";
			$save = $this->conn->query($sql);
		}else{
			$sql = "UPDATE `categories` set {$data} where id = '{$id}' ";
			$save = $this->conn->query($sql);
		}
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$this->settings->set_flashdata('success',"New Category successfully saved.");
			else
				$this->settings->set_flashdata('success',"Category successfully updated.");
		}else{
			$resp['status'] = 'failed';
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		return json_encode($resp);
	}
	
	function delete_category(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `categories` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Category successfully deleted.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function update_balance($category_id){
		$budget = $this->conn->query("SELECT SUM(amount) as total FROM `running_balance` where `balance_type` = 1 and `category_id` = '{$category_id}' ")->fetch_assoc()['total'];
		$expense = $this->conn->query("SELECT SUM(amount) as total FROM `running_balance` where `balance_type` = 2 and `category_id` = '{$category_id}' ")->fetch_assoc()['total'];
		$balance = $budget - $expense;
		$update  = $this->conn->query("UPDATE `categories` set `balance` = '{$balance}' where `id` = '{$category_id}' ");
		if($update){
			return true;
		}else{
			return $this->conn;
		}
	}
function save_budget(){
    $resp = ['status' => 'failed'];
    $post = $_POST;

    // Ensure category_id is provided
    if (!isset($post['category_id']) || empty($post['category_id'])) {
        $resp['error'] = 'Category ID is required.';
        return json_encode($resp);
    }

    // Clean up specific fields
    $post['amount'] = isset($post['amount']) ? str_replace(',', '', $post['amount']) : 0;
    $post['remarks'] = isset($post['remarks']) ? addslashes(htmlentities($post['remarks'])) : '';

    $data = "";
    foreach($post as $k => $v){
        if($k == 'id') continue;

        // Sanitize input
        $v = $this->conn->real_escape_string($v);

        if(!empty($data)) $data .= ",";
        $data .= " `{$k}`='{$v}' ";
    }

    // Append user_id
    if(!empty($data)) $data .= ",";
    $data .= " `user_id`='{$this->settings->userdata('id')}' ";

    // Create query
    if(empty($post['id'])){
        $sql = "INSERT INTO `running_balance` SET $data";
    } else {
        $sql = "UPDATE `running_balance` SET $data WHERE id ='{$post['id']}'";
    }

    $save = $this->conn->query($sql);

    if($save){
        // Now we can safely call update_balance using sanitized value
        $category_id = $this->conn->real_escape_string($post['category_id']);
        $update_balance = $this->update_balance($category_id);

        if($update_balance == 1){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success', "Budget successfully saved.");
        } else {
            $resp['error'] = 'Balance update failed: ' . $update_balance;
        }
    } else {
        $resp['error'] = 'Database Error: ' . $this->conn->error;
    }

    return json_encode($resp);
}

	function delete_budget(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `running_balance` where id = '{$id}'");
		if($del){
			$update_balance =$this->update_balance($category_id);
			if($update_balance == 1){
				$resp['status'] ='success';
				$this->settings->set_flashdata('success',"Budget successfully deleted.");
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = $update_balance;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_expense(){
		extract($_POST);
	
		$amount = floatval(str_replace(',', '', $_POST['amount']));
		$remarks = addslashes(htmlentities($_POST['remarks']));
		$_POST['amount'] = $amount;
		$_POST['remarks'] = $remarks;
	
		$user_id = $this->settings->userdata('id');
	
		// Get current M-PESA balance
		$mpesa_qry = $this->conn->query("SELECT balance FROM mpesa_balance WHERE user_id = '{$user_id}'");
		$mpesa_balance = 0;
		if ($mpesa_qry->num_rows > 0) {
			$mpesa_balance = floatval($mpesa_qry->fetch_assoc()['balance']);
		}
	
		$previous_amount = 0;
		$amount_to_deduct = $amount;
	
		if (!empty($id)) {
			// Fetch the old amount before update
			$prev_qry = $this->conn->query("SELECT amount FROM running_balance WHERE id = '{$id}' AND user_id = '{$user_id}'");
			if ($prev_qry->num_rows > 0) {
				$previous_amount = floatval($prev_qry->fetch_assoc()['amount']);
			}
			// Calculate difference
			$amount_to_deduct = $amount - $previous_amount;
		}
	
		// Only check if balance is sufficient if deduction is positive
		if ($amount_to_deduct > 0 && $amount_to_deduct > $mpesa_balance) {
			return json_encode([
				'status' => 'failed',
				'msg' => 'Expense update failed! M-PESA balance is too low for this change.'
			]);
		}
	
		// Prepare data for insert/update
		$data = "";
		foreach($_POST as $k => $v){
			if($k == 'id') continue;
			if(!empty($data)) $data .=",";
			$data .= " `{$k}`='{$v}' ";
		}
		if(!empty($data)) $data .=",";
		$data .= " `user_id`='{$user_id}' ";
	
		// Insert or update expense
		if(empty($id)){
			$sql = "INSERT INTO `running_balance` SET $data";
		} else {
			$sql = "UPDATE `running_balance` SET $data WHERE id ='{$id}'";
		}
	
		$save = $this->conn->query($sql);
		if($save){
			if ($amount_to_deduct != 0) {
				$deduct = $this->conn->query("UPDATE mpesa_balance SET balance = balance - ({$amount_to_deduct}) WHERE user_id = '{$user_id}'");
				if(!$deduct){
					return json_encode([
						'status' => 'failed',
						'msg' => 'Error adjusting M-PESA balance: ' . $this->conn->error
					]);
				}
	
				$trans_code = uniqid('EXP');
				$insert_transaction = $this->conn->query("
					INSERT INTO mpesa_topups (user_id, type, amount, transaction, remarks)
					VALUES ('{$user_id}', 'debit', '{$amount_to_deduct}', '{$trans_code}', 'Expense adjustment for category ID: {$_POST['category_id']}')
				");
				if(!$insert_transaction){
					return json_encode([
						'status' => 'failed',
						'msg' => 'Error saving M-PESA transaction: ' . $this->conn->error
					]);
				}
			}
	
			// Update budget balance
			$update_balance = $this->update_balance($_POST['category_id']);
			if($update_balance == 1){
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success', "Expense saved successfully.");
			} else {
				$resp['status'] = 'failed';
				$resp['error'] = $update_balance;
			}
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
	
		return json_encode($resp);
	}
	
	
	public function get_user_balance($user_id) {
		$query = $this->conn->query("SELECT balance FROM mpesa_balance WHERE user_id = '{$user_id}'");
		if ($query->num_rows > 0) {
			return $query->fetch_assoc()['balance'];
		}
		return 0; // Default balance if no record exists
	}

	
	function delete_expense(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `running_balance` where id = '{$id}'");
		if($del){
			$update_balance =$this->update_balance($category_id);
			if($update_balance == 1){
				$resp['status'] ='success';
				$this->settings->set_flashdata('success',"Expense successfully deleted.");
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = $update_balance;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_category':
		echo $Master->save_category();
	break;
	case 'delete_category':
		echo $Master->delete_category();
	break;
	case 'save_budget':
		echo $Master->save_budget();
	break;
	case 'delete_budget':
		echo $Master->delete_budget();
	break;
	case 'save_expense':
		echo $Master->save_expense();
	break;
	case 'delete_expense':
		echo $Master->delete_expense();
	break;
	default:
		// echo $sysset->index();
		break;
}