<?php
ini_set('max_execution_time', 5000);

use MyApp\Libs\Helper;

include __DIR__ . '/includes/setup.php';
$mediaFiles = \MyApp\Libs\Helper::getMediaDirFiles(__DIR__ . '/media');

$errorsMsg = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $media_ids = [];
    $connection = Helper::getTwInstance();

    $tweet = \MyApp\Libs\Helper::sanitize($_POST['tweet']);
    if (!empty($tweet)) {

        if (isset($_POST['media_id']) && count($_POST['media_id'])) {
            for ($i = 0; $i <= (TWITTER_UPLOADS_POST_MAX_IMG - 1); $i++) {
                if (isset($_POST['media_id'][$i])) {
                    $content_type = mime_content_type(UPLOAD_PATH . $_POST['media_id'][$i]);
                    $size = filesize(UPLOAD_PATH . $_POST['media_id'][$i]);
                    $this_media = '';
                    $this_media =  $connection->upload('media/upload', [
                            'media' => URL_ROOT .'media/' . $_POST['media_id'][$i],
                    ]);
                    if (isset($this_media->media_id) && $this_media->media_id != '') {
                        $media_ids[] = $this_media->media_id;
                    }
                }
            }
        }

        $parameters = [];
        if (count($media_ids) > 0) {
            $parameters = [
                    'status' => $tweet,
                    'media_ids' => implode(',', $media_ids),
            ];
        } else {
            $parameters = ['status' => $tweet];
        }


        $content = $connection->post('statuses/update', $parameters);
        if ($connection->getLastHttpCode() == 200) {

            \MyApp\Libs\Session::flash('success', 'تم ارسال التغريدة بنجاح');
//            header('Location: ' . URL_ROOT . 'tweets.php');
        } else {
            echo 'error';
        }



    } else {
        $errorsMsg['tweet_needed'] = 'من فضلك قم بادخال التغريدة التى تود ارسالها';
//        header('Location: ./tweets.php');
    }
}
?>
<?php include 'tpl/header.php'; ?>

<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading fs-18"><span class="glyphicon glyphicon-film"></span>  التغريدة </div>
        <div class="panel-body">

            <?php echo \MyApp\Libs\Helper::printFlashMsg('error'); ?>
            <?php echo \MyApp\Libs\Helper::printFlashMsg('success'); ?>

            <form method="post" action="tweets.php" class="form-horizontal">
                <div class="form-group">
                    <label for="tweet" class="control-label col-sm-3">التغريدة</label>
                    <div class="col-sm-9">
                        <textarea class="form-control <?php if (isset($errorsMsg['tweet_needed'])): ?>textarea-error <?php endif; ?>" name="tweet" id="tweet" maxlength="140" placeholder="برجاء ادخال التغريدة هنا مع ملاحظة الا تتعدى 140 حرف..." ></textarea>
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
                                            <input type="checkbox" value="<?php echo $file ?>" class="form-control mycheckbox" name="media_id[]">
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-4">
                        <button class="btn btn-primary btn-block">تغريد</button>
                    </div>
                </div>

            </form>

            



        </div><!-- ./ panel-body -->
    </div>
</div>

<?php include 'tpl/footer.php'; ?>
