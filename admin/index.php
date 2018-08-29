<?php
include __DIR__ . '/../includes/setup.php';
include __DIR__ . '/tpl/header.php';

$userModel = new \MyApp\Models\User();
$users = $userModel->getAll();
?>

<div class="col-md-12">
    <a href="<?php echo URL_ROOT . 'twitter_login.php'; ?>" class="btn btn-primary">Add New Twitter Account</a>
    <h2>Accounts</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissable">
            <button name="button" class="close" data-ddismiss="alert" aria-label="close"><apan aria-hidden="true">x</apan></button>
            <?php echo $_SESSION['error']; ?>
        </div>

        <?php unset($_SESSION['error']);  endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissable">
            <button name="button" class="close" data-ddismiss="alert" aria-label="close"><apan aria-hidden="true">x</apan></button>
            <?php echo $_SESSION['success']; ?>
        </div>

        <?php unset($_SESSION['success']);  endif; ?>


    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="20%">Screen Name</th>
                <th width="15%">Profile Image</th>
                <th width="2%">Following</th>
                <th width="2%">Followers</th>
                <th>Proxy</th>
                <th>Action</th>
            </tr>
        </thead>
        <?php if ($users && count($users)): ?>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['screen_name'] ?></td>
                    <td>
                        <img src="<?= $user['profile_image_url'] ?>" alt="<?= $user['screen_name'] ?>" class="img-responsive">
                    </td>
                    <td>
                        <?= $user['friends_count'] ?>
                    </td>
                    <td>
                        <?= $user['followers_count'] ?>
                    </td>
                    <td>
                        <?= $user['proxy'] ?>
                    </td>
                    <td>
                        <a href="" class="btn btn-warning btn-sm update" id="<?= $user['id'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <?php endif; ?>
    </table>
</div>


    <div id="userModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="user_form" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update User</h4>
                    </div>
                    <div class="modal-body">
                        <label>Enter Proxy Server</label>
                        <input type="text" name="proxy" id="proxy_user" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="user_id" id="user_id" />
                        <input type="hidden" name="operation" id="operation" />
                        <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php
$edit_script = true;
?>
<?php include __DIR__ . '/tpl/footer.php'; ?>
<script>

    $(document).on('submit', '#user_form', function(e) {
        e.preventDefault();
        var proxy = $('#proxy_user').val();
        var user_id = $('#user_id').val();
        // if (proxy != '') {
            $.ajax({
                url: "<?php echo URL_ROOT . 'admin/ajax/update_proxy.php' ?>",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#user_form')[0].reset();
                    $('#userModal').hide();
                    location.reload(true);
                }
            });
        // }
    });

    $(document).on('click', '.update', function(e) {
        e.preventDefault();
        var user_id = $(this).attr('id');
        $.ajax({
            url: "<?php echo URL_ROOT . 'admin/ajax/fetch_user.php' ?>",
            method: "POST",
            data: {user_id: user_id},
            dataType: "json",
            success: function(data) {
                $('#userModal').modal('show');
                $('#user_id').val(user_id);
                $('#proxy_user').val(data.proxy);
            }
        });
    });
</script>

</body>
</html>
