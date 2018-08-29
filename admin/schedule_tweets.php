<?php
use MyApp\Libs\Helper;

require __DIR__ . '/../includes/setup.php';


$assets = Helper::getDataTablesAssets();
$assets['css'][] = 'bootstrap-datetimepicker.min.css';
$assets['js'][] = 'moment-with-locales.min.js';
$assets['js'][] = 'bootstrap-datetimepicker.min.js';
$assets['custom_script_date'] = true;

$userModel = new \MyApp\Models\User();
$users = $userModel->getAll();

$mediaFiles = \MyApp\Libs\Helper::getMediaDirFiles(__DIR__ . '/../media');

if (Helper::isAjax()) {
    $tweets = new \MyApp\Controllers\Tweets();
    $errors = $tweets->makeScheduleTweet();
    if (count($errors) && is_array($errors)) {
        echo Helper::jsonEncode([
            'error' => true,
            'validation' => $errors,
        ]);
        exit;
    }
    echo Helper::jsonEncode(['error' => false]);
    exit;
}

?>
<?php include 'tpl/header.php'; ?>

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading fs-18"><span class="fa fa-twitter fa-fw"></span> Schedule Tweets </div>
        <div class="panel-body">

            <?php if (\MyApp\Libs\Session::exists('success')): ?>
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo \MyApp\Libs\Session::flash('success'); ?>
                </div>
            <?php endif; ?>

            <div><a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createTwittModal" id="create_tweet">New Tweet</a></div>
            <br>
            <table class="table table-bordered table-striped" id="scheduled_tweets">
                <thead>
                <tr>
                    <th>Tweet Time</th>
                    <th>Tweet Content</th>
                    <th>Attachment</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
            </table>


        </div>
    </div><!-- ./ panel panel-default -->
</div><!-- ./col-md-9 -->



<!-- Modal -->
<div class="modal fade" id="createTwittModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">عمل تغريدة مجدولة</h4>
            </div>
            <form method="post" action="schedule_tweets.php" class="form-horizontal" id="schedule-tweet">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">
                                <label for="tweet_content" class="control-label col-sm-3">التغريدة</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control <?php if (isset($errorsMsg['tweet_needed'])): ?>textarea-error <?php endif; ?>" name="tweet_content" id="tweet_content" maxlength="140" placeholder="برجاء ادخال التغريدة هنا مع ملاحظة الا تتعدى 140 حرف..." ></textarea>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="time_to_post" class="control-label col-sm-3">وقت الاسال</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control date-time" id="time_to_post" name="time_to_post">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="media" class="control-label col-sm-3">ارفاق ملف</label>
                                <div class="col-sm-9">


                                    <?php if ($mediaFiles == false): ?>
                                        <p class="media-message">لا يوجد ملفات صور او فيديو لعرضها</p>

                                    <?php else: ?>
                                        <div class="row">
                                            <?php foreach ($mediaFiles as $file): ?>
                                                <div class="col-md-3">
                                                    <div class="thumbnail" style="width: 100%; height: 100px;">
                                                        <img src="<?php echo URL_ROOT . 'media/' . $file; ?>"  class="img-responsive" style="max-height: 70px;">
                                                        <input type="checkbox" value="<?php echo $file ?>" class="form-control mycheckbox" name="tweet_media[]">
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <?php foreach ($users as $user): ?>
                                    <div class="col-md-3">
                                        <label for="<?= $user["id"] ?>"><input type="checkbox" class="form-control" id="<?= $user["id"] ?>" name="owner_id[]" value="<?= $user["id"] ?>"> <?= $user["screen_name"] ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">غلق</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="updateScheduleTweet" tabindex="-1" role="dialog" aria-labelledby="updateScheduleTweet">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">تعديل التغريدة</h4>
            </div>
            <form method="post" action="schedule_tweets.php" class="form-horizontal" id="update_schedule_tweet">
                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">
                                <label for="tweet_content" class="control-label col-sm-3">التغريدة</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control update_tweet_content <?php if (isset($errorsMsg['tweet_needed'])): ?>textarea-error <?php endif; ?>" name="tweet_content" id="tweet_content" maxlength="140" placeholder="برجاء ادخال التغريدة هنا مع ملاحظة الا تتعدى 140 حرف..." ></textarea>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="time_to_post" class="control-label col-sm-3">وقت الاسال</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control date-time update_time_to_post" id="time_to_post" name="time_to_post">
                                </div>
                            </div>




                            <div class="form-group">
                                <label for="media" class="control-label col-sm-3">ارفاق ملف</label>
                                <div class="col-sm-9">


                                    <?php if ($mediaFiles == false): ?>
                                        <p class="media-message">لا يوجد ملفات صور او فيديو لعرضها</p>

                                    <?php else: ?>
                                        <div class="row">
                                            <?php foreach ($mediaFiles as $file): ?>
                                                <div class="col-md-3">
                                                    <div class="thumbnail" style="width: 100%; height: 100px;">
                                                        <img src="<?php echo URL_ROOT . 'media/' . $file; ?>"  class="img-responsive" style="max-height: 70px;">
                                                        <input type="checkbox" value="<?php echo $file ?>" class="form-control mycheckbox" name="tweet_media[]">
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <?php foreach ($users as $user): ?>
                                    <div class="col-md-3">
                                        <label for="<?= $user["id"] ?>"><input type="checkbox" class="form-control" id="<?= $user["id"] ?>" name="owner_id[]" value="<?= $user["id"] ?>"> <?= $user["screen_name"] ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>


                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="tweet_id" class="update_tweet_id">
                    <button type="button" class="btn btn-default" data-dismiss="modal">غلق</button>
                    <button type="submit" class="btn btn-primary">تعديل</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'tpl/footer.php'; ?>
