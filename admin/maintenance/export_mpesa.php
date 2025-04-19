<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once(__DIR__ . '/../../config.php');

// Assuming user_id is available via session or passed as a GET parameter
$user = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'")->fetch_assoc();
$user_id = $user['id'];

if (!$user_id) {
    echo json_encode(['error' => 'User ID is required']);
    exit;
}

$months = $_GET['months'] ?? 1;

$dateFilter = "";
if ($months !== "all") {
    $months = (int)$months;
    $fromDate = date('Y-m-d', strtotime("-$months months"));
    $dateFilter = "AND date_created >= '$fromDate'";
}

$sql = "SELECT date_created, amount, type, remarks FROM mpesa_topups WHERE user_id = $user_id $dateFilter ORDER BY date_created DESC";
$result = $conn->query($sql);

$transactions = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
} else {
    // Log DB error
    echo json_encode(['error' => $conn->error]);
    exit;
}

// Fetch latest MPESA balance for the specific user
$balResult = $conn->query("SELECT * FROM mpesa_balance WHERE user_id = $user_id ORDER BY id DESC LIMIT 1");
$balance = 0;
$phone = '';
if ($balResult && $balRow = $balResult->fetch_assoc()) {
    $balance = $balRow['balance'];
    $phone = $balRow['phone'];
}

header('Content-Type: application/json');
echo json_encode([
    'transactions' => $transactions,
    'balance' => $balance,
    'phone' => $phone
]);
?>
