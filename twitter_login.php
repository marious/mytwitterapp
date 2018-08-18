<?php
use Abraham\TwitterOAuth\TwitterOAuth;
require __DIR__ . '/includes/setup.php';

//session_destroy();exit;

// get the consumer key and consumer secret
$settings = new \MyApp\Models\Setting();
$twitterKeys = $settings->get('my_twitter_app');


if (!isset($_SESSION['access_token'])) {
    $connection = new TwitterOAuth($twitterKeys['consumer_key'], $twitterKeys['consumer_secret']);
    $connection->setTimeouts(10, 15);
    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $twitterKeys['oauth_callback']));
    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
    header('Location: ' . $url);
} else {
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth($twitterKeys['consumer_key'], $twitterKeys['consumer_secret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $user = $connection->get("account/verify_credentials");
}

if ($twitterKeys && count($twitterKeys)) {

} else {
    include __DIR__ . '/tpl/header.php';
?>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading fs-18"><span class="glyphicon glyphicon-cog"></span>الاعدادات </div>
            <div class="panel-body">
                <h3>برجاء ادخال اعدادات التطبيق الخاص بتويتر (Consumer key, Consumer Secret)</h3>
            </div>
        </div><!-- ./ panel panel-default -->
    </div><!-- ./col-md-9 -->

<?php
    include __DIR__ . '/tpl/footer.php';
}