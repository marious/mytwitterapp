<?php
include __DIR__ . '/includes/setup.php';
include __DIR__ . '/tpl/header.php';

//session_destroy();exit;
if ($_SESSION['regular_user_twitter_id'] != NULL) {
    $_SESSION['user_id'] = $_SESSION['regular_user_twitter_id'];
    $userModel = new \MyApp\Models\User();
    $user = $userModel->get_twitter_user_data($_SESSION['regular_user_twitter_id']);
    $_SESSION['oauth_token'] = $user['oauth_token'];
    $_SESSION['oauth_token_secret'] = $user['oauth_token_secret'];
}
?>

        <div class="col-md-9">

            <?php if ($_SESSION['regular_user_twitter_id'] == NULL): ?>
            <div class="panel panel-default">
                <div class="panel-heading fs-18"><span class="glyphicon glyphicon-cog"></span>الاعدادات </div>
                <div class="panel-body">
                    <h2>برجاء الدخول بحساب تويتر</h2>
                    <p>
                        <a href="twitter_login.php">
                            <img src="<?php echo URL_ROOT . 'assets/images/twitter_sign_in.jpg'; ?>">
                        </a>
                    </p>
                </div>
            </div><!-- ./ panel panel-default -->

            <?php else: ?>
                <div class="panel panel-default">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <table class="table table-striped table-responsive">
                            <tr>
                                <th>ID</th>
                                <th>Screen Name</th>
                                <th>Profile Image</th>
                                <th>Following</th>
                                <th>Followers</th>
                            </tr>
                            <?php if ($user): ?>
                             <tr>
                                 <td><?= $user['id'] ?></td>
                                 <td><?= $user['screen_name'] ?></td>
                                 <td>
                                     <img src="<?= $user['profile_image_url'] ?>" alt="<?= $user['screen_name'] ?>" class="img-responsive">
                                 </td>
                                 <td>
                                     <?= $user['friends_count'] ?>
                                 </td>
                                 <td>
                                     <?= $user['followers_count'] ?>
                                 </td>
                             </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            <?php endif; ?>


        </div><!-- ./col-md-9 -->

<?php
include __DIR__ . '/tpl/footer.php';
?>
