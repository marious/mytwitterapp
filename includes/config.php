<?php
define('ENV', 'dev');

switch (ENV) {
    case 'dev':
        define('DB_NAME','mtwitterapp');
        define('DB_USER','root');
        define('DB_PASSWORD','2634231');
        define('DB_HOST', '127.0.0.1');
        define('URL_ROOT', 'http://127.0.0.1/mytwitterapp/');
        define('IS_PROD',false);
    break;
    case 'prod':
        define('DB_NAME','b24_20817550_my_twitter_app');
        define('DB_USER','b24_20817550');
        define('DB_PASSWORD','2634231f16');
        define('DB_HOST', 'sql303.byethost24.com');
        define('URL_ROOT', 'http://mytwitter.byethost24.com/');
        define('IS_PROD',false);
        break;

}




define('TWITTER_UPLOADS_POST_MAX_IMG', 4);
define('TWITTER_API_LIST_FW', 5000);
define('TWITTER_API_MAX_RETRIES',5);
define('TWITTER_API_DAYS_BEFORE_RECACHE',7);
define('UPLOAD_PATH', 'media/');
