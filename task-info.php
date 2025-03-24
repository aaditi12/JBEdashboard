<?php

require 'authentication.php'; // Admin authentication check 

// ✅ Prevent session start error
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Authentication check
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['security_key'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$user_role = $_SESSION['user_role']; // 1 = Admin, 2 = Employee

// Delete task functionality
if (isset($_GET['delete_task'])) {
    $action_id = $_GET['task_id'];
    $sql = "DELETE FROM task_info WHERE task_id = :id";
    $sent_po = "task-info.php";
    $obj_admin->delete_data_by_this_method($sql, $action_id, $sent_po);
}

// Add new task functionality
if (isset($_POST['add_task_post'])) {
    $obj_admin->add_new_task($_POST);
}

$page_name = "Task_Info";
include("include/sidebar.php");

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<link rel="stylesheet" href="custom.css">
<style> 
.btn-orange {
    background-color: #ff9800;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 1rem;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-orange:hover {
    background-color: #e65100;
    transform: translateY(-2px);
}

tr {
    background-color: #F8F8F8;
    color: #E65200;
}

th {
    color: #E65200;
    padding: 12px;
    text-align: left;
    font-weight: bold;
}

tr:hover {
    background-color: #FFDBBB;
    transition: 0.3s ease-in-out;
}
</style>

<!-- Modal for Assigning New Task -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog add-category-modal">
        <div class="modal-content rounded-0">
            <div class="modal-header rounded-0 d-flex">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title ms-auto">Assign New Task</h2>
            </div>

            <div class="modal-body rounded-0">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="" method="post" autocomplete="off">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label text-p-reset">Task Title</label>
                                    <div class="">
                                        <input type="text" placeholder="Task Title" name="task_title" class="form-control rounded-0" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label text-p-reset">Task Description</label>
                                    <div class="">
                                        <textarea name="task_description" placeholder="Task Description" class="form-control rounded-0" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label text-p-reset">Start Time</label>
                                    <div class="">
                                        <input type="text" name="t_start_time" id="t_start_time" class="form-control rounded-0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label text-p-reset">End Time</label>
                                    <div class="">
                                        <input type="text" name="t_end_time" id="t_end_time" class="form-control rounded-0">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label text-p-reset">Assign To</label>
                                    <div class="">
                                        <?php 
                                            // Fetch all users (admins + employees)
                                            $sql = "SELECT user_id, fullname FROM tbl_admin";
                                            $info = $obj_admin->manage_all_info($sql);
                                        ?>
                                        <select class="form-control rounded-0" name="assign_to" required>
                                            <option value="">Select a User...</option>
                                            <?php while ($row_user = $info->fetch(PDO::FETCH_ASSOC)) { ?>
                                                <option value="<?php echo $row_user['user_id']; ?>">
                                                    <?php echo $row_user['fullname']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="add_task_post" class="btn btn-primary rounded-0 btn-sm">Assign Task</button>
                                    <button type="button" class="btn btn-default rounded-0 btn-sm" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Button to Assign Task (Admin & Employee) -->
<?php if ($user_role == 1 || $user_role == 2) { ?>
<div class="container">
    <div class="d-flex justify-content-end">
        <div class="btn-group">
            <button class="btn btn-orange btn-menu" data-toggle="modal" data-target="#myModal">
                Assign New Task <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>
<?php } ?>

<!-- Task List -->
<div class="table-responsive">
    <table class="table table-condensed table-custom"  >
        <thead>
            <tr >
                <th style="background-color: #F8F8F8; color: orange;">SNo.</th>
                <th style="background-color: #F8F8F8; color: orange;">Task Title</th>
                <th style="background-color: #F8F8F8; color: orange;">Assigned To</th>
                <th style="background-color: #F8F8F8; color: orange;">Start Time</th>
                <th style="background-color: #F8F8F8; color: orange;">End Time</th>
                <th style="background-color: #F8F8F8; color: orange;">Status</th>
                <th style="background-color: #F8F8F8; color: orange;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql = "SELECT a.*, b.fullname 
                    FROM task_info a
                    INNER JOIN tbl_admin b ON a.t_user_id = b.user_id";

            if ($user_role == 2) {
                // Employees see only their tasks
                $sql .= " WHERE a.t_user_id = $user_id";
            }

            $sql .= " ORDER BY a.task_id DESC";

            $info = $obj_admin->manage_all_info($sql);
            $serial = 1;
            while ($row = $info->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo $serial++; ?></td>
                <td><?php echo $row['t_title']; ?></td>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['t_start_time']; ?></td>
                <td><?php echo $row['t_end_time']; ?></td>
                <td>
                    <?php 
    if ($row['status'] == 1) {
        echo '<small class="label label-warning">In Progress</small>';
    } elseif ($row['status'] == 2) {
        echo '<small class="label label-success">Completed</small>';
    } else {
        echo '<small class="label label-danger">Incomplete</small>';
    }
?>

                </td>
                <td>
                    <a href="edit-task.php?task_id=<?php echo $row['task_id']; ?>"><i class="fas fa-edit"></i></a> 
                    <a href="task-details.php?task_id=<?php echo $row['task_id']; ?>"><i class="fas fa-eye"></i></a>
                     <a href="task-info.php?delete_task=1&task_id=<?php echo $row['task_id']; ?>" 
       title="Delete Task" 
       onclick="return confirm('Are you sure you want to delete this task?');">
        <i class="fas fa-trash-alt text-danger"></i>
    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr('#t_start_time', { enableTime: true });
flatpickr('#t_end_time', { enableTime: true });
</script>

