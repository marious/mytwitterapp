<?php
include __DIR__ . '/../../includes/setup.php';
$assets['js'][] = 'handlebars.js';
$assets['js'][] = 'jquery.xdomainrequest.min.js';
$assets['js'][] = 'typeahead.bundle.js';
$assets['css'][] = 'bootstrap-datetimepicker.min.css';
$assets['js'][] = 'moment-with-locales.min.js';
$assets['js'][] = 'bootstrap-datetimepicker.min.js';
$assets['custom_script_date'] = true;

include __DIR__ . '/../tpl/header.php';


$userModel = new \MyApp\Models\User();
$users = $userModel->getAll();
$twitterApp = new \MyApp\Controllers\Tweets();

if (isset($_POST['replay_task'])) {
    $task_name = $_POST['replay_task_name'];
    $owners_id = ($_POST['owner_id']);


    $task_run_array = [
            'start_time_1' => 0,
            'start_time_2' => 0,
            'start_time_3' => 0,
            'end_time_1' => 0,
            'end_time_2' => 0,
            'end_time_3' => 0,
    ];

    if (isset($_POST['start_time_1'], $_POST['end_time_1'])) {
        $task_run_array['start_time_1'] = trim($_POST['start_time_1']);
        $task_run_array['end_time_1'] = trim($_POST['end_time_1']);

        if (isset($_POST['start_time_2'], $_POST['end_time_2'])) {
            $task_run_array['start_time_2'] = trim($_POST['start_time_2']);
            $task_run_array['end_time_2'] = trim($_POST['end_time_2']);
        }

        if (isset($_POST['start_time_3'], $_POST['end_time_3'])) {
            $task_run_array['start_time_3'] = trim($_POST['start_time_3']);
            $task_run_array['end_time_3'] = trim($_POST['end_time_3']);
        }
    }

    $serialize_task_time = serialize($task_run_array);


    $serialize_owner_id = serialize($owners_id);
    $task_id = $twitterApp->create_task(['task_name' => $task_name, 'target_twitter_id' => $serialize_owner_id, 'task_time' => $serialize_task_time,
        'task_type' => 'replay']);
    $message = explode(',', trim($_POST['replay_message']));

    foreach ($owners_id as $id => $owner_id) {
        if (! isset($message[$id])) {
            $message_owner = 'أفضل استقدام من مكتب السلام للاستقدام';
        } else {
            $message_owner = $message[$id];
        }
        $twitterApp->makeReplayUser($message_owner, $owner_id, $task_id);

    }
    header('Location: ' . URL_ROOT . 'admin/tasks/all_tasks.php?task=replay');
}

if (isset($_POST['add_retweet_task']))
{
    $task_name = $_POST['retweet_fav_task_name'];
    $owners_id = ($_POST['owner_id']);

    $task_run_array = [
        'start_time_1' => 0,
        'start_time_2' => 0,
        'start_time_3' => 0,
        'end_time_1' => 0,
        'end_time_2' => 0,
        'end_time_3' => 0,
    ];

    if (isset($_POST['start_time_1'], $_POST['end_time_1'])) {
        $task_run_array['start_time_1'] = trim($_POST['start_time_1']);
        $task_run_array['end_time_1'] = trim($_POST['end_time_1']);

        if (isset($_POST['start_time_2'], $_POST['end_time_2'])) {
            $task_run_array['start_time_2'] = trim($_POST['start_time_2']);
            $task_run_array['end_time_2'] = trim($_POST['end_time_2']);
        }

        if (isset($_POST['start_time_3'], $_POST['end_time_3'])) {
            $task_run_array['start_time_3'] = trim($_POST['start_time_3']);
            $task_run_array['end_time_3'] = trim($_POST['end_time_3']);
        }
    }

    $serialize_task_time = serialize($task_run_array);
    $serialize_owner_id = serialize($owners_id);
    $task_id = $twitterApp->create_task(['task_name' => $task_name, 'target_twitter_id' => $serialize_owner_id, 'task_time' => $serialize_task_time,
        'task_type' => 'retweet']);

    foreach ($owners_id as $id => $owner_id) {
        $twitterApp->makeRetweetFavuser('insert', $owner_id, $task_id);
    }

    header('Location: ' . URL_ROOT . 'admin/tasks/all_tasks.php?task=retweet');

}
?>

<div class="col-md-12">
    <?php if (isset($_GET['task']) && $_GET['task'] == 'replay'): ?>
    <h1 class="page-header">Replay Task</h1>
    <div class="replay-container bg-disabled">
        <form action="" class="form-horizontal" method="post" id="twitter-search">
            <!--                -->
            <div class="form-group">
                <label for="replay_task_name" class="control-label col-sm-3">Task Name</label>
                <div class="col-sm-9">
                    <input type="text" id="replay_task_name" name="replay_task_name" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="account_name" class="control-label col-sm-3">Account You want replay to it</label>
                <div class="col-sm-9">
                    <input type="text" id="search-account" class="Typeahead-input" placeholder="Search Twitter User" name="account_name" style="display: block;">
                    <img class="Typeahead-spinner" src="<?= URL_ROOT ?>assets/images/spinner.gif">
                </div>
            </div>

            <div class="form-group">
                <label for="replay_message" class="control-label col-sm-3">The Replay You Want to Add</label>
                <div class="col-sm-9">
                    <textarea name="replay_message" id=""  class="form-control" required></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="task_run_time" class="control-label col-sm-3">Task Run Time1</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="start time" name="start_time_1">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    <div class="col-sm-3 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="End Time" name="end_time_1">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label for="task_run_time" class="control-label col-sm-3">Task Run Time2</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="start time" name="start_time_2">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    <div class="col-sm-3 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="End Time" name="end_time_2">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="task_run_time" class="control-label col-sm-3">Task Run Time3</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="start time" name="start_time_3">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    <div class="col-sm-3 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="End Time" name="end_time_3">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>
            </div>

            <h3 class="page-header">Accounts You want make This Task</h3>

            <div class="form-group">
                <?php foreach ($users as $user): ?>
                    <div class="col-md-2">
                        <label for="<?= $user["id"] ?>"><input type="checkbox" class="form-control" id="<?= $user["id"] ?>" name="owner_id[]" value="<?= $user["id"] ?>"> <?= $user["screen_name"] ?></label>
                    </div>
                <?php endforeach; ?>
            </div>


            <br><br>
            <div class="form-group">
                <div class="col-md-3 col-md-offset-3">
                    <input type="submit" class="btn btn-primary btn-block" value="Add" name="replay_task">
                </div>
            </div>


            <!--                <div class="Typeahead Typeahead--twitterUsers">-->
            <!--                    <div class="u-posRelative">-->
            <!--                        <input class="Typeahead-input" id="search-account" type="text" name="q" placeholder="Search Twitter users...">-->
            <!--                        <img class="Typeahead-spinner" src="img/spinner.gif">-->
            <!--                    </div>-->
            <!--                    <div class="Typeahead-menu"></div>-->
            <!--                </div>-->

        </form>
    </div>
    <?php  endif; ?>


    <?php if (isset($_GET['task']) && $_GET['task'] == 'retweet'): ?>
    <h1 class="page-header">Retweet Favorite Task Task</h1>
    <div class="retweet-container bg-disabled">
        <form action="" class="form-horizontal" method="post" id="twitter-search">
            <!--                -->
            <div class="form-group">
                <label for="retweet_fav_task_name" class="control-label col-sm-3">Task Name</label>
                <div class="col-sm-9">
                    <input type="text" id="retweet_fav_task_name" name="retweet_fav_task_name" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="account_name" class="control-label col-sm-3">Retweet Fav Account</label>
                <div class="col-sm-9">
                    <input type="text" id="search-account" class="Typeahead-input" placeholder="Search Twitter User" name="account_name" style="display: block;">
                    <img class="Typeahead-spinner" src="<?= URL_ROOT ?>assets/images/spinner.gif">
                </div>
            </div>



            <div class="form-group">
                <label for="task_run_time" class="control-label col-sm-3">Task Run Time1</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="start time" name="start_time_1">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    <div class="col-sm-3 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="End Time" name="end_time_1">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label for="task_run_time" class="control-label col-sm-3">Task Run Time2</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="start time" name="start_time_2">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    <div class="col-sm-3 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="End Time" name="end_time_2">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="task_run_time" class="control-label col-sm-3">Task Run Time3</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="start time" name="start_time_3">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    <div class="col-sm-3 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="End Time" name="end_time_3">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>
            </div>


            <h3 class="page-header">Accounts You want make This Task</h3>

            <div class="form-group">
                <?php foreach ($users as $user): ?>
                    <div class="col-md-2">
                        <label for="<?= $user["id"] ?>">
                            <input type="checkbox" class="form-control" id="<?= $user["id"] ?>" name="owner_id[]" value="<?= $user["id"]; ?>">
                            <?= $user["screen_name"] ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <br><br>
            <div class="form-group">
                <div class="col-md-3 col-md-offset-3">
                    <input type="submit" class="btn btn-primary btn-block" value="Add" name="add_retweet_task">
                </div>
            </div>


            <!--                <div class="Typeahead Typeahead--twitterUsers">-->
            <!--                    <div class="u-posRelative">-->
            <!--                        <input class="Typeahead-input" id="search-account" type="text" name="q" placeholder="Search Twitter users...">-->
            <!--                        <img class="Typeahead-spinner" src="img/spinner.gif">-->
            <!--                    </div>-->
            <!--                    <div class="Typeahead-menu"></div>-->
            <!--                </div>-->

        </form>
    </div>
</div>
<?php endif; ?>

<script id="result-template" type="text/x-handlebars-template">
    <div class="ProfileCard u-cf">
        <img class="ProfileCard-avatar" src="{{profile_image_url_https}}">

        <div class="ProfileCard-details">
            <div class="ProfileCard-realName">{{name}}</div>
            <div class="ProfileCard-screenName">@{{screen_name}}</div>
            <div class="ProfileCard-description">{{description}}</div>
        </div>

        <div class="ProfileCard-stats">
            <div class="ProfileCard-stat"><span class="ProfileCard-stat-label">Tweets:</span> {{statuses_count}}</div>
            <div class="ProfileCard-stat"><span class="ProfileCard-stat-label">Following:</span> {{friends_count}}</div>
            <div class="ProfileCard-stat"><span class="ProfileCard-stat-label">Followers:</span> {{followers_count}}</div>
        </div>
    </div>
</script>

<script id="empty-template" type="text/x-handlebars-template">
    <div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div>
</script>

<?php include __DIR__ . '/../tpl/footer.php'; ?>
