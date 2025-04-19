if ($_GET['action'] == 'get_expense') {
	$id = $_POST['id'];
	$qry = $conn->query("SELECT * FROM running_balance WHERE id = '{$id}'");
	if ($qry->num_rows > 0) {
		$row = $qry->fetch_assoc();
		echo json_encode(['status' => 'success', 'data' => $row]);
	} else {
		echo json_encode(['status' => 'failed', 'msg' => 'Expense not found.']);
	}
}