<?php   
require 'authentication.php'; // Authentication check

date_default_timezone_set('Asia/Kolkata'); // Set default timezone to IST

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Auth check
$user_id = $_SESSION['admin_id'] ?? null;
$user_name = $_SESSION['name'] ?? null;
$security_key = $_SESSION['security_key'] ?? null;
$user_role = $_SESSION['user_role'] ?? null; // Admin or Employee

if (!$user_id || !$security_key) {
    header('Location: index.php');
    exit();
}

// Ensure database connection
if (!$obj_admin->db) {
    die("Database connection failed");
}

// Handle Clock In
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['clock_in'])) {
    $in_time = date("Y-m-d H:i:s");
    $current_date = date("Y-m-d");

    // Check if already clocked in today
    $sql_check = "SELECT COUNT(*) FROM attendance_info WHERE atn_user_id = :user_id AND DATE(in_time) = :current_date";
    $stmt_check = $obj_admin->db->prepare($sql_check);
    $stmt_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_check->bindParam(':current_date', $current_date, PDO::PARAM_STR);
    $stmt_check->execute();
    $clock_in_count = $stmt_check->fetchColumn();

    if ($clock_in_count > 0) {
        echo "<script>alert('You have already clocked in today!'); window.location.href='attendance-info.php';</script>";
        exit();
    }

    $sql = "INSERT INTO attendance_info (atn_user_id, in_time) VALUES (:user_id, :in_time)";
    $stmt = $obj_admin->db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':in_time', $in_time, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        header("Location: attendance-info.php");
        exit();
    } else {
        die("Error inserting Clock In data: " . implode(" | ", $stmt->errorInfo()));
    }
}

// Handle Clock Out
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['clock_out'])) {
    $out_time = date("Y-m-d H:i:s");

    $sql = "UPDATE attendance_info SET out_time = :out_time WHERE atn_user_id = :user_id AND out_time IS NULL ORDER BY aten_id DESC LIMIT 1";
    $stmt = $obj_admin->db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':out_time', $out_time, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        header("Location: attendance-info.php");
        exit();
    } else {
        die("Error updating Clock Out data: " . implode(" | ", $stmt->errorInfo()));
    }
}

// Handle Add/Edit Remarks
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_remarks'])) {
    $aten_id = $_POST['aten_id'];
    $remarks = trim($_POST['remarks']);
    
    // Update remarks for specific attendance ID
    $sql_remarks = "UPDATE attendance_info SET remarks = :remarks WHERE aten_id = :aten_id";
    $stmt_remarks = $obj_admin->db->prepare($sql_remarks);
    $stmt_remarks->bindParam(':remarks', $remarks, PDO::PARAM_STR);
    $stmt_remarks->bindParam(':aten_id', $aten_id, PDO::PARAM_INT);
    
    if ($stmt_remarks->execute()) {
        header("Location: attendance-info.php");
        exit();
    } else {
        die("Error updating remarks: " . implode(" | ", $stmt_remarks->errorInfo()));
    }
}

$page_name = "Attendance";
include("include/sidebar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
</head>
<body>
    <div class="container">
        <h2>Attendance Management</h2>

        <form method="post">
            <button type="submit" name="clock_in" class="btn btn-success">Clock In</button>
            <button type="submit" name="clock_out" class="btn btn-danger">Clock Out</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Name</th>
                    <th>In Time</th>
                    <th>Out Time</th>
                    <th>Total Duration</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT a.*, b.fullname FROM attendance_info a LEFT JOIN tbl_admin b ON a.atn_user_id = b.user_id ORDER BY a.aten_id DESC";
                $stmt = $obj_admin->db->prepare($sql);
                $stmt->execute();
                $serial = 1;
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $total_duration = (isset($row['out_time']) && $row['out_time']) ? (strtotime($row['out_time']) - strtotime($row['in_time'])) : null;
                    $hours_worked = ($total_duration !== null) ? round($total_duration / 3600, 2) . ' hrs' : '------';
                ?>
                <tr>
                    <td><?php echo $serial++; ?></td>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['in_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['out_time'] ?? '------'); ?></td>
                    <td><?php echo $hours_worked; ?></td>
                    <td><?php echo htmlspecialchars($row['remarks'] ?? 'No remarks'); ?></td>
                    <td>
                        <?php if (empty($row['remarks'])): ?>
                            <button class="btn btn-success" onclick="openRemarksModal(<?php echo $row['aten_id']; ?>, '')">Add Remarks</button>
                        <?php else: ?>
                            <button class="btn btn-warning" onclick="openRemarksModal(<?php echo $row['aten_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['remarks'])); ?>')">Edit Remarks</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Remarks Modal -->
    <div id="remarksModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:#fff; padding:20px; box-shadow: 0 0 10px #000;">
        <h4 id="remarksTitle">Add Remarks</h4>
        <form method="post">
            <input type="hidden" id="remarks_aten_id" name="aten_id">
            <textarea id="remarks_text" name="remarks" required></textarea>
            <button type="submit" name="submit_remarks" class="btn btn-primary">Save</button>
            <button type="button" onclick="closeRemarksModal()" class="btn btn-secondary">Cancel</button>
        </form>
    </div>

    <script>
        function openRemarksModal(aten_id, remarks) {
            document.getElementById('remarks_aten_id').value = aten_id;
            document.getElementById('remarks_text').value = remarks;
            document.getElementById('remarksTitle').innerText = remarks ? "Edit Remarks" : "Add Remarks";
            document.getElementById('remarksModal').style.display = 'block';
        }

        function closeRemarksModal() {
            document.getElementById('remarksModal').style.display = 'none';
        }
    </script>
</body>
</html>
