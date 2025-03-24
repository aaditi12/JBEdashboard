<?php
require 'authentication.php'; // Include authentication

if(isset($_POST['remarks']) && isset($_POST['task_id'])) {
    $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');
    $task_id = $_POST['task_id'];

    $sql_update = "UPDATE task_info SET remarks = :remarks WHERE task_id = :task_id";
    $stmt = $obj_admin->db->prepare($sql_update);
    $stmt->execute(['remarks' => $remarks, 'task_id' => $task_id]);

    echo $remarks; // Return updated remarks for AJAX response
}
?>
