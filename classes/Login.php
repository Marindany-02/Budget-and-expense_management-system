<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}
	public function login() {
		extract($_POST);
	
		$stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
	
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
	
			if (password_verify($password, $row['password'])) {
				foreach ($row as $k => $v) {
					if (!is_numeric($k) && $k != 'password') {
						$this->settings->set_userdata($k, $v);
						$_SESSION[$k] = $v;
					}
				}
	
				$_SESSION['id'] = $row['id'];
				$this->settings->set_userdata('id', $row['id']);
	
				$_SESSION['login_type'] = 1;
				$this->settings->set_userdata('login_type', 1);
	
				// ✅ Set flash success message
				$_SESSION['flashdata']['success'] = "Welcome back, " . $row['username'] . "!";
	
				// ✅ Redirect handled via JS after success
				return json_encode(['status' => 'success', 'redirect' => 'admin/dashboard.php']);
			}
		}
	
		return json_encode(['status' => 'incorrect']);
	}
	
	public function logout() {
		if ($this->settings->sess_des()) {
			// Redirect with a logout success query string
			redirect('admin/login.php?logout=success');
		}
	}
	
	function login_user(){
		extract($_POST);
		$qry = $this->conn->query("SELECT * from clients where email = '$email' and password = md5('$password') ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $k => $v) {
				$this->settings->set_userdata($k, $v);
			}
			$this->settings->set_userdata('login_type', 1);
			
			// ✅ Set success message
			$this->settings->set_flashdata('success', 'Login successful!');
		
			$resp['status'] = 'success';
		} else {
			$resp['status'] = 'incorrect';
		}
		
		if($this->conn->error){
			$resp['status'] = 'failed';
			$resp['_error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'login_user':
		echo $auth->login_user();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	default:
		echo $auth->index();
		break;
}

