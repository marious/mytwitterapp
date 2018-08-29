<?php
include __DIR__ . '/../../includes/setup.php';
include __DIR__ . '/../tpl/header.php';
$task_type = trim($_GET['task']);
$twitterApp = new \MyApp\Controllers\Tweets();
$all_tasks = $twitterApp->get_all_tasks(['task_type' => $task_type]);
?>

<div class="col-md-12">
    <a href="<?= URL_ROOT . 'admin/tasks/new_task.php?task=' . $_GET['task']; ?>" class="btn btn-primary">New Task</a>
    <h1 class="page-header"><?= $task_type ?> Tasks</h1>
    <?php if (isset($_SESSION['task_deleted'])): ?>
    <div class="alert alert-success alert-dismissable">
        <button name="button" class="close" data-ddismiss="alert" aria-label="close"><apan aria-hidden="true">x</apan></button>
        Task Deleted Successfully
    </div>

    <?php unset($_SESSION['task_deleted']);  endif; ?>
    <table class="table table-bordred table-striped">
        <thead>
            <tr>
                <th>Task Name</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($all_tasks && count($all_tasks)): ?>
        <?php foreach ($all_tasks as $task): ?>
            <tr>
                <td><?= $task["task_name"] ?></td>
                <td><?php echo date("d/m/Y H:i", strtotime($task["created_at"])) ?></td>
                <td>
                    <a href="<?= URL_ROOT . 'admin/tasks/edit.php?task_id=' . $task['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= URL_ROOT . 'admin/tasks/delete.php?task_id=' . $task['id']; ?>" class="btn btn-danger btn-sm delete-btn">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
    include __DIR__ . '/../tpl/footer.php';
?>
