<?php
include __DIR__ . '/../../includes/setup.php';
$assets['js'][] = 'handlebars.js';
$assets['js'][] = 'jquery.xdomainrequest.min.js';
$assets['js'][] = 'typeahead.bundle.js';
$assets['css'][] = 'bootstrap-datetimepicker.min.css';
$assets['js'][] = 'moment-with-locales.min.js';
$assets['js'][] = 'bootstrap-datetimepicker.min.js';
$assets['custom_script_date'] = true;

$task_id = (int) $_GET['task_id'];

function get_users_make_action($table, $task_id) {
    $db = DB::connect();
    $query = "SELECT owner_id FROM $table WHERE task_id = $task_id";
    $result = $db->query($query);
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
    $users_make_action = [];
    foreach ($rows as $row) {
        $users_make_action[] = $row['owner_id'];
    }
    if ($users_make_action && count($users_make_action)) {
        return $users_make_action;
    }
    return [];
}

if ($task_id)
{
    $db = DB::connect();

    $userModel = new \MyApp\Models\User();
    $users = $userModel->getAll();
    $twitterApp = new \MyApp\Controllers\Tweets();
    $task_info = $twitterApp->get_task($task_id);
    $retweet_replay_task = $twitterApp->get_replay_retweet_task($task_id);
    $task_run_time = unserialize($task_info['task_time']);

    $users_make_action = get_users_make_action('replay_users', $task_id);
    if (empty($users_make_action)) {
        $users_make_action = get_users_make_action('retweets_users', $task_id);
    }



    $task_title = 'Edit Task';
    if (isset($retweet_replay_task['replay_message'])) {
        $task_title = 'Edit Replay Task';
        $redirect = URL_ROOT . 'admin/tasks/all_tasks.php?task=replay';
    } else {
        $task_title = 'Edit Retweet Fav Task';
        $redirect = URL_ROOT . 'admin/tasks/all_tasks.php?task=retweet';

    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $task_name = $_POST['replay_task_name'];
        $owners_id = $_POST['owner_id'];

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
        $serialize_owners = serialize($owners_id);
        $twitterApp->edit_task(['task_name' => $task_name, 'target_twitter_id' => $serialize_owners, 'task_time' => $serialize_task_time], $task_id);
        $message = '';
        if (isset($_POST['replay_message'])) {
            $message = $_POST['replay_message'];
        }

        if ($message != '') {
            $messages = explode(',', $message);
            $twitterApp->delete_owners_when_update($owners_id, $task_id, 'replay_users');
//var_dump($owners_id);exit;
            foreach ($owners_id as $id => $owner_id)
            {
                if ($messages[$id]) {
                    $message = $messages[$id];
                } else {
                    $message = 'أفضل استقدام من مكتب السلام للاستقدام';
                }

                if ($twitterApp->check_owner_id_exist($owner_id, $task_id, 'replay_users'))
                {
//                    echo 'Exist: ' . $owner_id;

                    $twitterApp->edit_replay_user($message, $owner_id, $task_id);
                }
                else
                {
//                echo '<br>Not Exist: ' . $owner_id . '<br>';
                    $twitterApp->makeReplayUser($message, $owner_id, $task_id);
                }
            }
        }
        else
        {
            $twitterApp->delete_owners_when_update($owners_id, $task_id, 'retweets_users');
            foreach ($owners_id as $owner_id) {

                if ($twitterApp->check_owner_id_exist($owner_id, $task_id, 'retweets_users'))
                {

                    $twitterApp->makeRetweetFavuser('update', $owner_id, $task_id);
                }
                else
                {
                    $twitterApp->makeRetweetFavuser('insert', $owner_id, $task_id);
                }

            }
        }

        header('Location: ' . $redirect);
        exit;

    }


    include __DIR__ . '/../tpl/header.php';

    ?>



    <div class="col-md-12">
    <h1 class="page-header"> <?= $task_title ?></h1>
    <div class="replay-container">
        <form action="" class="form-horizontal" method="post" id="twitter-search">
            <!--                -->
            <div class="form-group">
                <label for="replay_task_name" class="control-label col-sm-3">Task Name</label>
                <div class="col-sm-9">
                    <input type="text" id="replay_task_name" name="replay_task_name" class="form-control" value="<?= $task_info['task_name'] ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="account_name" class="control-label col-sm-3">Account You want replay to it</label>
                <div class="col-sm-9">
                    <input type="text" id="search-account" class="Typeahead-input" placeholder="Search Twitter User" name="account_name" style="display: block;"
                           value="<?= $retweet_replay_task['screen_name'] ?>">
                    <img class="Typeahead-spinner" src="<?= URL_ROOT ?>assets/images/spinner.gif">
                </div>
            </div>

            <?php if (isset($retweet_replay_task['replay_message'])): ?>
            <div class="form-group">
                <label for="replay_message" class="control-label col-sm-3">The Replay You Want to Add</label>
                <?php
                $replay_message =$twitterApp->get_replay_messages($task_id);
                ?>
                <div class="col-sm-9">
                    <textarea name="replay_message" id=""  class="form-control"><?= $replay_message; ?></textarea>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="task_run_time" class="control-label col-sm-3">Task Run Time1</label>
                <div class="col-sm-9">
                    <div class="col-sm-3 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="start time" name="start_time_1"
                               value="<?php if ($task_run_time['start_time_1']) {echo $task_run_time['start_time_1'];} ?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    <div class="col-sm-3 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="End Time" name="end_time_1"
                                value="<?php if ($task_run_time['end_time_1']) {echo $task_run_time['end_time_1'];} ?>">
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
                        <input type="text" class="form-control" placeholder="start time" name="start_time_2"
                                value="<?php if ($task_run_time['start_time_2']) {echo $task_run_time['start_time_2'];} ?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    <div class="col-sm-3 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="End Time" name="end_time_2"
                                value="<?php if ($task_run_time['end_time_2']) {echo $task_run_time['end_time_2'];} ?>">
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
                        <input type="text" class="form-control" placeholder="start time" name="start_time_3"
                                value="<?php if ($task_run_time['start_time_3']) {echo $task_run_time['start_time_3'];} ?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                    <div class="col-sm-3 col-sm-push-1 input-group date datetimepicker" style="float: left;">
                        <input type="text" class="form-control" placeholder="End Time" name="end_time_3"
                               value="<?php if ($task_run_time['end_time_3']) {echo $task_run_time['end_time_3'];} ?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>
            </div>

            <h3 class="page-header">Accounts You want make This Task</h3>

            <div class="form-group">
                <?php foreach ($users as $user): ?>
                    <?php
                        $checked = '';
                        foreach ($users_make_action as $maker) {
                            if ($maker == $user["id"]) {$checked = 'checked';}
                        }
                    ?>
                    <div class="col-md-2">
                        <label for="<?= $user["id"] ?>">
                            <input type="checkbox" class="form-control" id="<?= $user["id"] ?>" name="owner_id[]" value="<?= $user["id"] ?>" <?= $checked; ?>>
                            <?= $user["screen_name"] ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>


            <br><br>
            <div class="form-group">
                <div class="col-md-9 col-md-offset-1">
                    <input type="submit" class="btn btn-primary btn-block" value="Edit" name="replay_task">
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


<?php

    include __DIR__ . '/../tpl/footer.php';

}