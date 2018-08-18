<?php
include __DIR__ . '/includes/setup.php';
$mediaFiles = \MyApp\Libs\Helper::getMediaDirFiles(__DIR__ . '/media');
$messages = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $max = 500 *  1024;
    $destination = __DIR__ . '/media/';
    try {
        $upload = new \MyApp\Libs\MyUploader($destination);
        $upload->setMaxSize($max);
        $upload->allowAllTypes();
        $upload->upload();
        $messages = $upload->getMessages();
        header('Location: ./media.php');
        exit;
    } catch (Exception $e) {
        $messages[] = $e->getMessage();
    }
}
?>
<?php include 'tpl/header.php'; ?>
<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading fs-18"><span class="glyphicon glyphicon-film"></span> مكتبة الصور </div>
        <div class="panel-body">

            <div class="media-container">
                <?php if ($mediaFiles == false): ?>
                    <p class="media-message">لا يوجد ملفات صور او فيديو لعرضها</p>

                <?php else: ?>
                    <div class="row">
                    <?php foreach ($mediaFiles as $file): ?>
                        <div class="col-md-3">
                            <div class="thumbnail" style="width: 100%; height: 100px;">
                                <img src="<?php echo URL_ROOT . 'media/' . $file; ?>"  class="img-responsive" style="max-height: 70px;">
                                <p class="text-center"><a href="delete_media.php?fname=<?= $file; ?>" class="delete-img">Delete</a></p>
                            </div>
                        </div>

                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div><!-- ./media-container -->

            <div class="media-upload-container">
                <p>
                    ملفات الوسائط يمكن رفعها من هنا كما يمكن رفع اكثر من ملف فى نفس الوقت                 </p>
                <div class="upload-form">
                    <form action="media.php" class="form-horizontal" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="media_file" class="col-sm-3 control-label">اختر الملف:</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" id="media_file" name="filename[]" multiple>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="upload-btn">رفع</button>
                        </div>
                    </form>
                </div><!-- ./upload-form -->

            </div><!-- ./media-upload-container -->

        </div><!-- ./ panel-body -->
    </div>
</div>

<?php
include 'tpl/footer.php';
