<?php
require __DIR__ . '/../includes/setup.php';
$assets['js'][] = 'jquery.xdomainrequest.min.js';
$assets['js'][] = 'typeahead.bundle.js';
$assets['css'][] = 'bootstrap-datetimepicker.min.css';
$assets['js'][] = 'moment-with-locales.min.js';
$assets['js'][] = 'bootstrap-datetimepicker.min.js';
$assets['custom_script_date'] = true;

$userModel = new \MyApp\Models\User();
$users = $userModel->getAll();
$db = DB::connect();
$query = "SELECT * FROM periodic_tweets";
$stmt = $db->query($query);
$tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$q2 = "SELECT * FROM periodic_task";
$stmt = $db->query($q2);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['action']) && $_POST['action'] == 'new')
    {
        $task_run_array = [
            'start_time_1' => 0,
            'end_time_1' => 0,
        ];

        if (isset($_POST['start_time_1'], $_POST['end_time_1'])) {
            $task_run_array['start_time_1'] = trim($_POST['start_time_1']);
            $task_run_array['end_time_1'] = trim($_POST['end_time_1']);
        }

        $serialize_task_time = serialize($task_run_array);
        $owner_id = serialize($_POST['owner_id']);

        $query = "INSERT INTO periodic_task(owner_id, task_time, periodic_time) VALUES(:owner_id, :task_time, :periodic_time)";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':owner_id', $owner_id);
        $stmt->bindValue(':task_time', $serialize_task_time);
        $stmt->bindValue(':periodic_time', $_POST['periodic_time']);
        $stmt->execute();
        header('Location: ' . URL_ROOT . 'admin/periodic_tweets.php');
    }
    else if ($_POST['action'] == 'update')
    {
            $task_run_array = [
                'start_time_1' => 0,
                'end_time_1' => 0,
            ];

            if (isset($_POST['start_time_1'], $_POST['end_time_1'])) {
                $task_run_array['start_time_1'] = trim($_POST['start_time_1']);
                $task_run_array['end_time_1'] = trim($_POST['end_time_1']);
            }

            $serialize_task_time = serialize($task_run_array);
            $owner_id = serialize($_POST['owner_id']);

            $query = "UPDATE periodic_task SET owner_id = :owner_id, task_time = :task_time,
                            periodic_time = :periodic_time WHERE id = :task_id";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':owner_id', $owner_id);
            $stmt->bindValue(':task_time', $serialize_task_time);
            $stmt->bindValue(':periodic_time', $_POST['periodic_time']);
            $stmt->bindValue(':task_id', $_POST['task_id']);
            $stmt->execute();
            header('Location: ' . URL_ROOT . 'admin/periodic_tweets.php');
    }

}

?>
<?php include 'tpl/header.php'; ?>

<div class="col-md-12">
    <h2 class="page-header">Periodic Tweets <div class="pull-right"><a href="" class="btn btn-primary new-tweet">New Periodic Tweet</a></div></h2>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Tweet</th>
            <th>Action</th>
        </tr>
        <?php if ($tweets && count($tweets)): ?>
        <?php $i = 1; foreach ($tweets as $tweet): ?>
            <tr>
                <td><?= $i; ?></td>
                <td><?= $tweet['tweet']; ?></td>
                <td>
                    <a href="" class="btn btn-sm btn-warning update-tweet" id="<?= $tweet['id'] ?>">Update</a>
                    <a href="<?= URL_ROOT . 'admin/tasks/delete_periodic_tweet?id=' . $tweet['id']; ?>" class="btn btn-sm btn-danger delete-btn">Delete</a>
                </td>
            </tr>
        <?php $i++; endforeach; ?>
        <?php endif; ?>
    </table>
    <hr>
    <br><br>


    <h2 class="page-header">Periodic Tasks <a href="" class="btn btn-primary periodic-task pull-right">Create Periodic Tweet Task</a></h2>
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Tweets Account</th>
            <th>Start Task Time</th>
            <th>Interval Message Time</th>
            <th>Action</th>
        </tr>
        <?php if ($tasks && count($tasks)): ?>
        <?php $i = 1; foreach ($tasks as $task): ?>
            <tr>
                <td><?= $i; ?></td>
                <td>
                    <?php
                    $task_users = unserialize($task['owner_id']);
                    $user_str = '';
                    foreach ($task_users as $i => $task_user) {
                        $delimiter = '';
                        if ($i > 0) {
                            $delimiter = ' - ';
                        }
                        $user = $userModel->getById($task_user);
                        $user_str .= "$delimiter {$user['screen_name']} ";
                    }
                    echo $user_str;
                    ?>
                </td>

                <td>
                    <?php
                    $task_time = unserialize($task['task_time']);
                    echo $task_time['start_time_1'] . ' to ' . $task_time['end_time_1'];
                    ?>
                </td>
                <td><?php echo 'every ' . $task['periodic_time'] . ' Minutes'; ?></td>
                <td>
                    <a href="" class="btn btn-warning btn-sm update-btn" id="<?= $task['id']; ?>">Update</a>
                    <a href="<?= URL_ROOT . 'admin/tasks/delete_periodic?id=' . $task['id']; ?>" class="btn btn-danger btn-sm delete-btn">Delete</a>
                </td>
            </tr>
        <?php $i++; endforeach; ?>
        <?php endif; ?>
    </table>

</div>


<div id="tweetModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="post-tweet" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Periodic Tweet</h4>
                </div>
                <div class="modal-body">
                    <label>Enter Tweet Message</label>
                    <textarea  name="periodic_tweet" id="periodic_tweet" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
<!--                    <input type="hidden" name="user_id" id="user_id" />-->
<!--                    <input type="hidden" name="operation" id="operation" />-->
                    <input type="hidden" name="action" value="new">
                    <input type="submit" name="" id="action" class="btn btn-success" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="tweetModalUpdate" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="post-tweet" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Periodic Tweet</h4>
                </div>
                <div class="modal-body">
                    <label>Enter Tweet Message</label>
                    <textarea  name="periodic_tweet" id="tweet_message" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <!--                    <input type="hidden" name="user_id" id="user_id" />-->
                    <!--                    <input type="hidden" name="operation" id="operation" />-->
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="tweet_id" value="" id="tweet_id">
                    <input type="submit" name="" id="action" class="btn btn-success" value="Update" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="periodicTaskModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="" action="<?php echo URL_ROOT . 'admin/periodic_tweets.php'; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Periodic Task</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="task_run_time" class="control-label col-sm-3">Task Run Time1</label>
                        <div class="col-sm-9">
                            <div class="col-sm-5 input-group date datetimepicker" style="float: left;">
                                <input type="text" class="form-control" placeholder="start time" name="start_time_1">
                                <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                            </div>
                            <div class="col-sm-5 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                                <input type="text" class="form-control" placeholder="End Time" name="end_time_1">
                                <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                            </div>
                        </div>
                    </div>

                    <br><br>

                    <div class="form-group">
                        <label for="" class="control-label col-sm-3">Repeat Every: </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="periodic_time">
                        </div>
                    </div>
                    
                    <br><br>

                    <div class="form-group">
                        <?php foreach ($users as $user): ?>
                            <div class="col-md-3">
                                <label for="<?= $user["id"] ?>"><input type="checkbox" class="form-control" id="<?= $user["id"] ?>" name="owner_id[]" value="<?= $user["id"] ?>"> <?= $user["screen_name"] ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="clearfix"></div>


                </div>
                <div class="modal-footer">
                    <!--                    <input type="hidden" name="user_id" id="user_id" />-->
                    <!--                    <input type="hidden" name="operation" id="operation" />-->
                    <input type="hidden" name="action" value="new">
                    <input type="submit" name="new_task" id="action" class="btn btn-success" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="periodicTaskModalupdate" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="" action="<?php echo URL_ROOT . 'admin/periodic_tweets.php'; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Periodic Task</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="task_run_time" class="control-label col-sm-3">Task Run Time1</label>
                        <div class="col-sm-9">
                            <div class="col-sm-5 input-group date datetimepicker" style="float: left;">
                                <input type="text" class="form-control" placeholder="start time" name="start_time_1" id="update_start_time_1">
                                <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                            </div>
                            <div class="col-sm-5 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                                <input type="text" class="form-control" placeholder="End Time" name="end_time_1" id="update_end_time_1">
                                <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                            </div>
                        </div>
                    </div>

                    <br><br>

                    <div class="form-group">
                        <label for="" class="control-label col-sm-3">Repeat Every: </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="periodic_time" id="period_time_update">
                        </div>
                    </div>

                    <br><br>

                    <div class="form-group update_owner_id">
                        <?php foreach ($users as $user): ?>
                            <div class="col-md-3">
                                <label for="<?= $user["id"] ?>"><input type="checkbox" class="form-control" id="<?= $user["id"] ?>" name="owner_id[]" value="<?= $user["id"] ?>"> <?= $user["screen_name"] ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="clearfix"></div>


                </div>
                <div class="modal-footer">
                    <!--                    <input type="hidden" name="user_id" id="user_id" />-->
                    <!--                    <input type="hidden" name="operation" id="operation" />-->
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="task_id" id="task_id">
                    <input type="submit" name="update_task" id="action" class="btn btn-success" value="Update" />
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

    $(document).on('submit', '#post-tweet', function(e) {
        e.preventDefault();
        var tweet = $('#periodic_tweet').val();
        // if (proxy != '') {
        $.ajax({
            url: "<?php echo URL_ROOT . 'admin/ajax/new_periodic_tweet.php' ?>",
            method: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(data) {
                console.log(data);
                // $('#periodic_tweet')[0].reset();
                $('#tweetModal').hide();
                location.reload(true);
            }
        });
        // }
    });

    $(document).on('click', '.new-tweet', function(e) {
        e.preventDefault();
        $('#tweetModal').modal('show');
        //var user_id = $(this).attr('id');
        //$.ajax({
        //    url: "<?php //echo URL_ROOT . 'admin/ajax/fetch_user.php' ?>//",
        //    method: "POST",
        //    data: {user_id: user_id},
        //    dataType: "json",
        //    success: function(data) {
        //        $('#userModal').modal('show');
        //        $('#user_id').val(user_id);
        //        $('#proxy_user').val(data.proxy);
        //    }
        //});
    });


    $(document).on('click', '.periodic-task', function(e) {
       e.preventDefault();
       $('#periodicTaskModal').modal('show');
    });


    $(document).on('click', '.update-btn', function(e) {
        e.preventDefault();
        var taskId = $(this).attr('id');
        $.ajax({
            url: url_root + '/admin/ajax/fetch_periodic_task.php',
            method:  "POST",
            data: {task_id: taskId},
            dataType: "json",
            success: function(data) {
                $('#periodicTaskModalupdate').modal('show');

                $('#period_time_update').val(data.periodic_time);
                $('#update_start_time_1').val(data.task_time.start_time_1);
                $('#update_end_time_1').val(data.task_time.end_time_1);
                $('#task_id').val(data.id);
                var owners_id = data.owner_id;
                owners_id.forEach(function(owner_id) {
                    $('.update_owner_id input[value="'+owner_id+'"]').prop('checked', true);
                });
                console.log(data);
            }
        });
    });



    $(document).on('click', '.update-tweet', function(e) {
        e.preventDefault();
        var tweetId = $(this).attr('id');
        $.ajax({
            url: url_root + '/admin/ajax/fetch_periodic_tweet.php',
            method:  "POST",
            data: {tweet_id: tweetId},
            dataType: "json",
            success: function(data) {
                $('#tweetModalUpdate').modal('show');

                $('#tweet_message').val(data.tweet);
                $('#tweet_id').val(data.id);
                console.log(data);
            }
        });
    });
</script>

</body>
</html>
