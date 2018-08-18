<?php
use MyApp\Libs\Helper;

include '../../includes/setup.php';
$assets = Helper::getDataTablesAssets();
?>
<?php include '../../tpl/header.php'; ?>

<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading fs-18"><span class="fa fa-twitter fa-fw"></span>التفضيل التلقائى         </div>

        <div class="panel-body">

            <div>
                <a href="<?= URL_ROOT ?>tweets/favorites/add.php" class="btn btn-primary">اضافة حساب</a>
            </div>
            <br>

            <table class="table table-bordered table-striped" id="auto_favs">
                <thead>
                <tr>
                    <th></th>
                    <th>الحساب</th>
                    <th>اسم الحساب</th>
                    <th>المُتابَعون</th>
                    <th>متابِعون</th>
                    <th>التغريدات</th>
                    <th>تاريخ الاضافة</th>
                    <th></th>
                </tr>
                </thead>
            </table>

        </div>
    </div><!-- ./ panel panel-default -->
</div><!-- ./col-md-9 -->

<?php include '../../tpl/footer.php'; ?>
