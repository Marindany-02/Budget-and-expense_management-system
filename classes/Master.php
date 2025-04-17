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
		extract($_POST);
		$_POST['amount'] = str_replace(',','',$_POST['amount']);
		$_POST['remarks'] = addslashes(htmlentities($_POST['remarks']));
		$data = "";
		foreach($_POST as $k =>$v){
			if($k == 'id')
				continue;
			if(!empty($data)) $data .=",";
			$data .= " `{$k}`='{$v}' ";
		}
		if(!empty($data)) $data .=",";
			$data .= " `user_id`='{$this->settings->userdata('id')}' ";
		if(empty($id)){
			$sql = "INSERT INTO `running_balance` set $data";
		}else{
			$sql = "UPDATE `running_balance` set $data WHERE id ='{$id}'";
		}
		$save = $this->conn->query($sql);
		if($save){
			$update_balance =$this->update_balance($_POST['category_id']);
			
			if($update_balance == 1){
				$resp['status'] ='success';
				$this->settings->set_flashdata('success'," Budget successfully saved.");
			}else{
				$resp['status'] = 'failed';
				$resp['error'] = $update_balance;
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn;
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
	
		// ✅ Format amount and sanitize remarks
		$amount = floatval(str_replace(',', '', $_POST['amount']));
		$remarks = addslashes(htmlentities($_POST['remarks']));
		$_POST['amount'] = $amount;
		$_POST['remarks'] = $remarks;
	
		// ✅ Get current user ID
		$user_id = $this->settings->userdata('id');
	
		// ✅ Get current M-PESA balance
		$mpesa_qry = $this->conn->query("SELECT balance FROM mpesa_balance WHERE user_id = '{$user_id}'");
		$mpesa_balance = 0;
		if ($mpesa_qry->num_rows > 0) {
			$mpesa_balance = floatval($mpesa_qry->fetch_assoc()['balance']);
		}
	
		// ✅ Check if M-PESA balance is enough
		if ($amount > $mpesa_balance) {
			return json_encode([
				'status' => 'failed',
				'msg' => 'Expense failed! M-PESA balance is too low.'
			]);
		}
	
		// ✅ Prepare data for insert/update
		$data = "";
		foreach($_POST as $k => $v){
			if($k == 'id') continue;
			if(!empty($data)) $data .=",";
	
			$data .= " `{$k}`='{$v}' ";
		}
		if(!empty($data)) $data .=",";
	
		$data .= " `user_id`='{$user_id}' ";
	
		// ✅ Insert or update expense
		if(empty($id)){
			$sql = "INSERT INTO `running_balance` SET $data";
		} else {
			$sql = "UPDATE `running_balance` SET $data WHERE id ='{$id}'";
		}
	
		$save = $this->conn->query($sql);
		if($save){
			// ✅ Deduct from M-PESA balance
			$deduct = $this->conn->query("UPDATE mpesa_balance SET balance = balance - {$amount} WHERE user_id = '{$user_id}'");
			if(!$deduct){
				$resp['status'] = 'failed';
				$resp['msg'] = 'Error deducting M-PESA balance: ' . $this->conn->error;
				return json_encode($resp);
			}
	
			// ✅ Log the transaction (make sure 'transaction_code' column exists in mpesa_topups)
			$trans_code = uniqid('EXP');
			$insert_transaction = $this->conn->query("
				INSERT INTO mpesa_topups (user_id, type, amount, transaction, remarks)
				VALUES ('{$user_id}', 'debit', '{$amount}', '{$trans_code}', 'Expense for category ID: {$_POST['category_id']}')
			");
			if(!$insert_transaction){
				$resp['status'] = 'failed';
				$resp['msg'] = 'Error saving M-PESA transaction: ' . $this->conn->error;
				return json_encode($resp);
			}
	
			// ✅ Update budget balance
			$update_balance = $this->update_balance($_POST['category_id']);
			if($update_balance == 1){
				$resp['status'] = 'success';
				$this->settings->set_flashdata('success', "Expense successfully saved.");
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