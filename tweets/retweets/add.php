<?php
include '../../includes/setup.php';

$assets['js'][] = 'handlebars.js';
$assets['js'][] = 'jquery.xdomainrequest.min.js';
$assets['js'][] = 'typeahead.bundle.js';
//$assets['js'][] = 'bootstrap3-typeahead.min.js';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $twitterApp = new \MyApp\Controllers\Tweets();
    $twitterApp->makeRetweetUser();
    exit;
}


?>
<?php include '../../tpl/header.php'; ?>

<div class="col-md-9">
    <div class="panel panel-default">
        <div class="panel-heading fs-18"><span class="fa fa-twitter fa-fw"></span>حساب التغريد التلقائى         </div>

        <div class="panel-body">

            <?php echo \MyApp\Libs\Helper::printFlashMsg('error'); ?>
            <?php echo \MyApp\Libs\Helper::printFlashMsg('success'); ?>

            <form action="" class="form-horizontal" method="post" id="twitter-search">
<!--                -->
                <div class="form-group">
                    <label for="account_name" class="control-label col-sm-3">اسم الحساب</label>
                    <div class="col-sm-9">
                        <input type="text" id="search-account" class="Typeahead-input" placeholder="Search Twitter User"                             name="account_name" style="display: block;">
                        <img class="Typeahead-spinner" src="<?= URL_ROOT ?>assets/images/spinner.gif">
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

<?php include '../../tpl/footer.php'; ?>
