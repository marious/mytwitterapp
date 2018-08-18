<?php
include 'includes/setup.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Location: index.php');
}


?>
<?php include 'tpl/header.php'; ?>

<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading fs-18"><span class="fa fa-twitter fa-fw"></span>حساب للرد عليه تلقائيا         </div>

        <div class="panel-body">

            <?php echo \MyApp\Libs\Helper::printFlashMsg('error'); ?>
            <?php echo \MyApp\Libs\Helper::printFlashMsg('success'); ?>

            <form action="" class="form-horizontal" method="post" id="twitter-search">
                <!--                -->
                <div class="form-group">
                    <label for="account_name" class="control-label col-sm-3">البروكسى</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-3 col-md-offset-2">
                        <button type="submit" class="btn btn-primary">اضافة</button>
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

</div><!-- ./ panel panel-default -->
</div><!-- ./col-md-9 -->


<?php include 'tpl/footer.php'; ?>
