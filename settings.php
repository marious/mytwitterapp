<?php
use MyApp\Libs\Helper;
use MyApp\Libs\Session;

include __DIR__ . '/includes/setup.php';
include __DIR__ . '/tpl/header.php';

$errors = [];

$settings = new \MyApp\Models\Setting();
$twitterKeys = $settings->get('my_twitter_app');


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $settingController = new \MyApp\Controllers\Settings();
    $returned = $settingController->handleSettings();
    if (is_array($returned) && count($returned)) {
        $errors = $returned;
    }
}

?>

<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading fs-18"><span class="glyphicon glyphicon-cog"></span>الاعدادات </div>
        <div class="panel-body">

            <?php if (Session::exists('success')): ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php
                echo Session::flash('success');
                ?>
            </div>
            <?php endif; ?>

            <form method="post" class="form-horizontal" action="settings.php">
                <div class="form-group">
                    <label for="consumer_key" class="col-sm-3 control-label">Consumer Key</label>
                    <div class="col-sm-9">
                        <input type="text" name="consumer_key" class="form-control" id="consumer_key" placeholder="Consumer Key"
                            <?php if (isset($twitterKeys['consumer_key'])) { echo 'value=' . $twitterKeys['consumer_key']; } ?>>
                        <?php
                            echo Helper::getErrorMsg($errors, 'consumer_key')
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="consumer_secret" class="col-sm-3 control-label">Consumer Secret</label>
                    <div class="col-sm-9">
                        <input type="text" name="consumer_secret" class="form-control" id="consumer_secret" placeholder="Consumer Secret"
                            <?php if (isset($twitterKeys['consumer_secret'])) { echo 'value=' . $twitterKeys['consumer_secret']; } ?>>
                        <?php
                        echo Helper::getErrorMsg($errors, 'consumer_secret')
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="oauth_callback" class="col-sm-3 control-label">Oauth Callback</label>
                    <div class="col-sm-9">
                        <input type="text" name="oauth_callback" class="form-control" id="oauth_callback" placeholder="Oauth Callback"
                            <?php if (isset($twitterKeys['oauth_callback'])) { echo 'value=' . $twitterKeys['oauth_callback']; } ?>>
                        <?php
                        echo Helper::getErrorMsg($errors, 'oauth_callback')
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-block btn-primary fs-18">حفظ</button>
                    </div>
                </div>

            </form>
        </div>
    </div><!-- ./ panel panel-default -->
</div><!-- ./col-md-9 -->

<?php
include __DIR__ . '/tpl/footer.php';
?>
