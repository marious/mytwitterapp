<?php
if (!isset($_SESSION['logged_user']) || $_SESSION['logged_user'] == false)
{
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap Template</title>
    <script>
        root = '<?= URL_ROOT; ?>';
    </script>
    <!-- Bootstrap -->
    <link href="<?= URL_ROOT ?>assets/css/bootstrap-arabic.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= URL_ROOT ?>assets/css/bootstrap-arabic-theme.min.css">
    <?php if (isset($assets) && isset($assets['css']) && count($assets['css'])): ?>
        <?php foreach ($assets['css'] as $css): ?>
    <link rel="stylesheet" href="<?php echo URL_ROOT . 'assets/css/' . $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .wrapper {
            margin-top: 20px;
        }
        .wrapper h1 {
            color: #fff;
            font-size: 20px;
            margin: 0;
            padding: 10px;
        }
        .list-group a {
            font-size: 20px;
            color: #337ab7;
        }
        .list-group a span {
            font-size: 18px;
            color: #666;
            display: inline-block;
            margin-left: 4px;
        }
        .fs-18 {
            font-size: 18px;
        }
        .error-msg {
            color: red;
        }
        .media-container {
            width: 90%;
            min-height: 100px;
            border: 1px solid #ccc;
            padding: 20px;
            margin: 0 auto;
        }
        .media-message {
            color: red;
            font-size: 18px;
            text-align: center;
            padding: 20px 0;
        }
        .media-upload-container {
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            margin: 60px auto 0;
        }
        .upload-btn {
            padding: 10px 40px;
            border-radius: 10px;
            font-size: 24px;
            color: #fff;
            background-color: #3cf;
            border: 0;
            border-bottom: 5px solid #1c8e90;
            cursor: pointer;
            font-weight: 700;
            transition: all .2s;
            display: inline-block;
            margin: 20px 150px 0 0;
        }
        .upload-btn:hover {
            color: #fff;
            text-decoration: none;
            background-color: #1c8e90;
        }
        textarea {
            resize: none;
            height: 80px !important;
        }
        .textarea-error {
         border: 1px solid red;
         background-color: #F6E7DE;
        }
        .mycheckbox {
            width: 15px !important;
            display: block;
            margin: 0 auto !important;
        }
        .errorMessage {
            color: red;
            font-size: 18px;
        }

        /* Typeahead */

        .Typeahead {
            position: relative;
            *z-index: 1;
            width: 500px;
            margin: 50px auto 0 auto;
            padding: 15px;
            text-align: left;
            background-color: #0097cf;
            background-image: -moz-linear-gradient(top, #04a2dd, #03739c);
            background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#04a2dd), to(#03739c));
            background-image: -webkit-linear-gradient(top, #04a2dd, #03739c);
            background-image: -o-linear-gradient(top, #04a2dd, #03739c);
            background-image: linear-gradient(top, #04a2dd, #03739c);
            background-repeat: repeat-x;
            border: 1px solid #024e6a;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            -webkit-box-shadow: 0 0 2px #111;
            -moz-box-shadow: 0 0 2px #111;
            box-shadow: 0 0 2px #111;
        }

        .Typeahead-spinner {
            position: absolute;
            top: 7px;
            left: 180px;
            display: none;
            width: 28px;
            height: 28px;
        }

        .Typeahead-hint,
        .Typeahead-input {
            width: 100%;
            padding: 5px 8px;
            font-size: 24px;
            line-height: 30px;
            border: 1px solid #024e6a;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
        }

        .Typeahead-hint {
            position: absolute;
            top: 0;
            left: 0;
            color: #ccd6dd;
            opacity: 1;
        }

        .Typeahead-input {
            position: relative;
            /*background-color: transparent;*/
            background-image: url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7);
            text-align: right;
            outline: none;
        }

        .Typeahead-menu {
            position: absolute;
            top: 95%;
            left: 2.5%;
            z-index: 100;
            display: none;
            width: 95%;
            margin-bottom: 20px;
            overflow: hidden;
            background-color: #fff;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 8px;
            box-shadow: 0px 0px 0px 1px green;
            -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
            -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
            box-shadow: 0 5px 10px rgba(0,0,0,.2);
        }

        .Typeahead-menu.is-open {
            display: block;
        }

        .Typeahead-selectable {
            cursor: pointer;
        }

        .Typeahead-selectable + .Typeahead-selectable {
            border-top: 1px solid #ccd6dd;
        }

        /* ProfileCard */

        .ProfileCard {
            position: relative;
            padding: 8px;
            padding-bottom: 20px;
        }

        .ProfileCard-avatar {
            position: absolute;
            top: 8px;
            left: 8px;
            width: 52px;
            height: 52px;
            border: 2px solid #ccd6dd;
            border-radius: 5px;
        }

        .ProfileCard:hover .ProfileCard-avatar {
            border-color: #f5f8fa;
        }

        .ProfileCard-details {
            min-height: 60px;
            padding-left: 60px;
        }

        .ProfileCard-realName,
        .ProfileCard-screenName {
            display: inline-block;
        }

        .ProfileCard-realName {
            font-weight: 700;
        }

        .ProfileCard-screenName {
            color: #8899a6;
        }

        .ProfileCard-description {
            margin-top: 5px;
            font-size: 14px;
            line-height: 18px;
        }

        .ProfileCard-stats {
            float: right;
            text-align: right;
        }

        .ProfileCard-stat {
            display: inline-block;
            font-size: 12px;
            line-height: 16px;
            text-transform: uppercase;
        }

        .ProfileCard-stat-label {
            color: #8899a6;
            font-weight: 500;
        }

        .ProfileCard-stat + .ProfileCard-stat {
            margin-left: 5px;
        }

        .ProfileCard:hover,
        .ProfileCard.is-active {
            color: #fff;
            background: #55acee;
        }

        .ProfileCard:hover .ProfileCard-screenName,
        .ProfileCard:hover .ProfileCard-stat-label,
        .ProfileCard.is-active .ProfileCard-screenName,
        .ProfileCard.is-active .ProfileCard-stat-label {
            color: #fff;
        }

        /* EmptyMessage */

        .EmptyMessage {
            position: relative;
            padding: 10px;
            font-size: 24px;
            line-height: 30px;
            text-align: center;

        }
        .twitter-typeahead {
            width: 75%;
            display: block;
        }
    </style>
</head>
<body>

<div class="container wrapper">
    <nav class="navbar navbar-inverse">
        <h1><i class="glyphicon glyphicon-home"></i> My Tweets</h1>
    </nav>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?= URL_ROOT ?>index.php" class="list-group-item"><span class="glyphicon glyphicon-home"></span>الرئيسية </a>
                <?php if (\MyApp\Libs\Helper::auth()): ?>
                <a href="<?= URL_ROOT ?>tweets.php" class="list-group-item"><span class="fa fa-twitter fa-fw"></span> التغريد </a>
                <a href="<?= URL_ROOT ?>schedule_tweets.php" class="list-group-item"><span class="fa fa-twitter fa-fw"></span> التغريدات المجدولة </a>
                <a href="<?= URL_ROOT ?>tweets/retweets/index.php" class="list-group-item"><span class="fa fa-twitter fa-fw"></span>  اعادة التغريد التلقائى </a>
                <a href="<?= URL_ROOT ?>tweets/favorites/index.php" class="list-group-item"><span class="fa fa-twitter fa-fw"></span>  التفضيل التلقائى </a>
                <a href="<?= URL_ROOT ?>tweets/replay/index.php" class="list-group-item"><span class="fa fa-twitter fa-fw"></span>  الرد التلقائى </a>
                <a href="<?= URL_ROOT ?>proxy.php" class="list-group-item"><span class="fa fa-twitter fa-fw"></span>  البروكسى </a>
                <a href="<?= URL_ROOT ?>media.php" class="list-group-item"><span class="glyphicon glyphicon-film"></span>مكتبة الوسائط </a>
                <a href="<?= URL_ROOT ?>settings.php" class="list-group-item"><span class="glyphicon glyphicon-cog"></span>الاعدادات </a>
                <?php endif; ?>
            </div>
        </div><!-- ./col-md-4 -->