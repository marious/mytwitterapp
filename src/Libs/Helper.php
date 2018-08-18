<?php

namespace MyApp\Libs;


use PDO;

class Helper
{

    public static function getTwInstance()
    {
        $twitterApp = new MyTwitterApp();
        return $twitterApp->getTwitterInstance();
    }

    public static function getCodeBirdInstance()
    {
        $twitterApp = new MyTwitterApp();
        return $twitterApp->getCodeBirdInstance();
    }


    public static function getMediaDirFiles($dir)
    {
        $files = [];
        $dir = scandir($dir);
        foreach ($dir as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $files[] = $file;
        }
        if (count($files) && !empty($files)) {
            return $files;
        }
        return false;
    }


    public static function getErrorMsg($errors = [], $key = null)
    {
        if (count($errors) && isset($key))
        {
            if (isset($errors[$key])) {
                return '<span class="error-msg">' . $errors[$key][0] . '</span>';
            }
            return '';
        }
        return '';
    }

    /**
     * Check the submitted date is valid
     *
     * @param $month
     * @param $day
     * @param $year
     * @return bool
     */
    public static function checkDate($month, $day, $year)
    {
        return checkdate((int) $month, (int) $day, (int) $year);
    }



    public static function guard($page = 'index.php')
    {
        if (!isset($_SESSION['user_key']))
        {
            header('Location: ' . $page);

        }
    }


    public static function sanitize($string)
    {
        return htmlentities(trim(stripslashes(strip_tags($string))), ENT_QUOTES, "UTF-8");
    }




    public static function jsonEncode($value = null)
    {
        if (defined('JSON_UNESCAPED_UNICODE')) {

            return json_encode(
                $value,
                JSON_HEX_TAG |
                JSON_HEX_APOS |
                JSON_HEX_QUOT |
                JSON_HEX_AMP |
                JSON_UNESCAPED_UNICODE);


        } else {


            return json_encode(
                $value,
                JSON_HEX_TAG |
                JSON_HEX_APOS |
                JSON_HEX_QUOT |
                JSON_HEX_AMP
            );
        }
    }


    /**
     * Check if the getting connection is throug Ajax
     * @return bool
     */
    public static function isAjax()
    {
        /* AJAX check  */
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }

        return false;
    }







    public static function getTotalRecords($table)
    {
        $db = \DB::connect();
        $stmt = $db->prepare('SELECT * FROM ' . $table);
        $stmt->execute();
        $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $stmt->rowCount();
    }



    public static function security($value)
    {
        $value = addslashes(strip_tags((trim($value))));
        return $value;
    }



    public static function getDataTablesAssets()
    {
        $assets = [
            'css' => [
                'dataTables.bootstrap.min.css',
            ],
            'js' => [
                'jquery.dataTables.min.js',
                'dataTables.bootstrap.min.js',
            ],
        ];
        return $assets;
    }


    public static function printFlashMsg($name)
    {   $output = '';
        if ($name == 'error' ) {
            $class = 'danger';
        } else if ($name == 'success') {
            $class = 'success';
        }

         if(\MyApp\Libs\Session::exists($name)) {
             $output .= '<div class="alert alert-'.$class.'">';
             $output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
             $output .= Session::flash($name);
             $output .= '</div>';
             return $output;
         }
    }



    public static function save_user_cached_data($connection, $owner_id)
    {
//        $profiles = array();
// Get the ids of all followers.
        $db = \DB::connect();

        $results = array_merge($connection->followers_list(), $connection->friends_list());
//        var_dump($results);exit;
//        var_dump($results);exit;
// Chunk the ids in to arrays of 100.
//var_dump($ids);exit;
//        $ids_arrays = array_chunk($ids->ids, 100);
// Loop through each array of 100 ids.
//        foreach($ids_arrays as $implode) {
            // Perform a lookup for each chunk of 100 ids.
//            $results = $connection->get('users/lookup', array('user_id' => implode(',', $implode)));
            // Loop through each profile result.
            foreach($results['users'] as $profile) {
                // Use screen_name as key for $profiles array.
//                $profiles[$profile['screen_name']] = $profile;

                $vf = 0;
                $pc = 0;

                if ($profile['verified']) {
                    $vf=  1;
                }

                if (($profile['protected'] == true) or ($profile['protected'] == 1) or ($profile['protected'] == 'true')) {
                    $pc = 1;
                }

                $lsd = '0000-00-00 00:00:00';
                if (date("Y-m-d", strtotime($profile['status']['created_at'])) != '1970-01-01') {
                    $lsd = date("Y-m-d H:i:s", strtotime($profile['status']['created_at']));
                }

                $tw_user_cache = array(
                    'twitter_id' => $profile['id_str'],
                    'profile_image_url' => $profile['profile_image_url_https'],
                    'screen_name' => $profile['screen_name'],
                    'actual_name' => $profile['name'],
                    'tweet_count' => $profile['statuses_count'],
                    'profile_created' => date("Y-m-d H:i:s", strtotime($profile['created_at'])),                      'description' => $profile['description'],
                    'location' => $profile['location'],
                    'time_zone' => $profile['time_zone'],                                                                   'background_image_url' => $profile['profile_background_image_url_https'],
                    'background_color' => $profile['profile_background_color'],
                    'last_status' => $profile['status']['text'],
                    'last_status_date' => $lsd,
                    'last_status_device' => str_replace('<a ', '<a target="_blank" ',                           $profile['status']['source']),
                    'verified'          => $vf,
                    'protected_ac'      => $pc,
                    'is_suspended'      => 0,
                    'followers_count'   => $profile['followers_count'],
                    'friends_count'     => $profile['friends_count'],
                    'last_updated'      => date("Y-m-d H:i:s"),
                    'owner_id'          => $owner_id
                    );
//                var_dump($tw_user_cache);exit;

                try {
                    $query = "INSERT INTO user_cache (" . implode(', ', array_keys($tw_user_cache)) . ") 
                        VALUES (" . ':' . implode(',:', array_keys($tw_user_cache)) . ")";
                    $stmt = $db->prepare($query);
                    foreach ($tw_user_cache as $key => $value) {

                        if ($key == 'actual_name' || $key == 'last_status' || $key == 'description') {
                            $value =  preg_replace('/[^\p{L}\p{N}\s]/u', '', $value);
                        }
                        $stmt->bindValue(':' . $key, $value);
                    }
                    $stmt->execute();
                } catch (\PDOException $e) {
                    var_dump($tw_user_cache);
                    die($e->getMessage());
                }

            }
//        }



        return $tw_user_cache;
//        var_dump($profiles);
    }




    public static function auth()
    {
        return isset($_SESSION['logged_user']);
    }

}
