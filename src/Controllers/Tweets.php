<?php

namespace MyApp\Controllers;


use MyApp\Libs\Helper;
use MyApp\Libs\Session;
use MyApp\Models\ScheduledTweet;
use PDO;

class Tweets extends AbstractController
{


    public function makeRetweetUser()
    {
        $redirectUrl = URL_ROOT . '/tweets/retweets/add.php';
        $username = Helper::security($_POST['account_name']);

        $countSelect = "SELECT id FROM retweets_users WHERE screen_name = :screen_name";

        if ($username == '') {
            Session::flash('error', 'يجب كتابة اسم الحساب بشكل صحيح');
            header('Location: ' . $redirectUrl);
        } else {
            $stmt = $this->db->prepare($countSelect);
            $stmt->bindValue(':screen_name', $username);
            if ($stmt->rowCount()) {
                Session::flash('error', 'تم اضافة الحساب مسبقا');
                header('Location: ' . $redirectUrl);
            } else {

                $twitterApi = Helper::getTwInstance();
                $user_info  = $twitterApi->get('users/show', array('screen_name' => $username));

                if (isset($user_info->errors) && $user_info->errors) {
                    $error = 'حدث خطأ اثناء الاتصال بتويتر "'.$user_info->errors[0]->message.'" ';
                    Session::flash('error', $error);
                    header('Location: ' . $redirectUrl);
                } else {

                    $query = "INSERT INTO retweets_users SET 
                        tid = :tid, 
                        name=:name,
                        screen_name = :screen_name, 
                        profile_image_url = :profile_image_url,
                        followers_count = :followers_count,
                        friends_count = :friends_count,
                        statuses_count = :statuses_count,
                        date_added = :date_added,
                        last_status_id = :last_status_id,
                        owner_id = :owner_id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindValue(':name', $user_info->name);
                    $stmt->bindValue(':tid', $user_info->id);
                    $stmt->bindValue(':screen_name', $user_info->screen_name);
                    $stmt->bindValue(':profile_image_url', $user_info->profile_image_url);
                    $stmt->bindValue(':friends_count', $user_info->friends_count);
                    $stmt->bindValue(':followers_count', $user_info->followers_count);
                    $stmt->bindValue(':statuses_count', $user_info->statuses_count);
                    $stmt->bindValue(':last_status_id', $user_info->status->id_str);
                    $stmt->bindValue(':statuses_count', $user_info->statuses_count);
                    $stmt->bindValue(':date_added', time());
                    $stmt->bindValue(':owner_id', $_SESSION['user_id']);
                    $stmt->execute();

                    Session::flash('success', 'تم اضافة الحساب بنجاح');
                    header('Location: ' . $redirectUrl);

                }

            }
        }

    }








    public function makeFavUser()
    {
        $redirectUrl = URL_ROOT . '/tweets/favorites/add.php';
        $username = Helper::security($_POST['account_name']);

        $countSelect = "SELECT id FROM favorites_users WHERE screen_name = :screen_name";

        if ($username == '') {
            Session::flash('error', 'يجب كتابة اسم الحساب بشكل صحيح');
            header('Location: ' . $redirectUrl);
        } else {
            $stmt = $this->db->prepare($countSelect);
            $stmt->bindValue(':screen_name', $username);
            if ($stmt->rowCount()) {
                Session::flash('error', 'تم اضافة الحساب مسبقا');
                header('Location: ' . $redirectUrl);
            } else {

                $twitterApi = Helper::getTwInstance();
                $user_info  = $twitterApi->get('users/show', array('screen_name' => $username));

                if (isset($user_info->errors) && $user_info->errors) {
                    $error = 'حدث خطأ اثناء الاتصال بتويتر "'.$user_info->errors[0]->message.'" ';
                    Session::flash('error', $error);
                    header('Location: ' . $redirectUrl);
                } else {

                    $query = "INSERT INTO favorites_users SET 
                        tid = :tid, 
                        name=:name,
                        screen_name = :screen_name, 
                        profile_image_url = :profile_image_url,
                        followers_count = :followers_count,
                        friends_count = :friends_count,
                        statuses_count = :statuses_count,
                        date_added = :date_added,
                        last_status_id = :last_status_id,
                        owner_id = :owner_id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindValue(':name', $user_info->name);
                    $stmt->bindValue(':tid', $user_info->id);
                    $stmt->bindValue(':screen_name', $user_info->screen_name);
                    $stmt->bindValue(':profile_image_url', $user_info->profile_image_url);
                    $stmt->bindValue(':friends_count', $user_info->friends_count);
                    $stmt->bindValue(':followers_count', $user_info->followers_count);
                    $stmt->bindValue(':statuses_count', $user_info->statuses_count);
                    $stmt->bindValue(':last_status_id', $user_info->status->id_str);
                    $stmt->bindValue(':statuses_count', $user_info->statuses_count);
                    $stmt->bindValue(':date_added', time());
                    $stmt->bindValue(':owner_id', $_SESSION['user_id']);
                    $stmt->execute();

                    Session::flash('success', 'تم اضافة الحساب بنجاح');
                    header('Location: ' . $redirectUrl);

                }

            }
        }

    }





    public function makeScheduleTweet()
    {
        // Validate user input
        $validator = new \Validator($this->request->getParams()->all());
        $rules = [
            'tweet_content' => 'trim|required',
            'time_to_post'  => 'trim|required|date',
        ];

        $errors = [];

        // Get all errors
        if (! $validator->validate($rules)) {
            $errors = $validator->getErrors();
        }

        if (count($errors)) {
            return $errors;
        }

        $data = [];
        $data['owner_id'] = $_SESSION['user_id'];
        $data['tweet_content'] = $this->setData('tweet_content', true);
        $data['time_to_post'] = $this->setData('time_to_post');
        if (isset($_POST['tweet_media'])) {
            $data['tweet_media'] = implode('-', $_POST['tweet_media']);
        } else {
            $data['tweet_media'] = 0;
        }

        $scheduletTweet = new ScheduledTweet();

        if (isset($_POST['tweet_id'])) {
            $data['tweet_id'] = $_POST['tweet_id'];
            $updateTweet = $scheduletTweet->update($data);
            if ($updateTweet) {
                Session::flash('success', 'تم تعديل التغريدة بنجاح');
                return true;
            }
        } else {
            $saveTweet = $scheduletTweet->create($data);
            if ($saveTweet) {
                Session::flash('success', 'تم اضافة التغريدة بنجاح');
                return true;
            }
        }



        $errors['error_happend'] = ['Something happend when insert new host please try again later'];
        return $errors;

    }











    function makeRetweetFavuser($action = 'insert', $owner_id, $task_id)
    {
        $query = '';
        $username = Helper::security($_POST['account_name']);
        $twitterApi = Helper::getTwInstance();
        $user_info = $twitterApi->get('users/show', array('screen_name' => $username));

        if ($action == 'insert') {
            $query .= "INSERT INTO retweets_users ";
        } else {
            $query .= "UPDATE retweets_users ";
        }
        $query .= " SET
                        name=:name,
                        tid = :tid,
                        screen_name = :screen_name, 
                        profile_image_url = :profile_image_url,
                        followers_count = :followers_count,
                        friends_count = :friends_count,
                        statuses_count = :statuses_count,
                        date_added = :date_added,
                        last_status_id = :last_status_id
                        ";
        if ($action == 'insert') {
            $query .= ' , owner_id = :owner_id, task_id = :task_id';
        }
        if ($action == 'update') {
            $query .= ' WHERE task_id = :task_id AND owner_id = :owner_id ';
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', $user_info->name);
        $stmt->bindValue(':tid', $user_info->id);
        $stmt->bindValue(':screen_name', $user_info->screen_name);
        $stmt->bindValue(':profile_image_url', $user_info->profile_image_url);
        $stmt->bindValue(':friends_count', $user_info->friends_count);
        $stmt->bindValue(':followers_count', $user_info->followers_count);
        $stmt->bindValue(':statuses_count', $user_info->statuses_count);
        $stmt->bindValue(':last_status_id', $user_info->status->id_str);
        $stmt->bindValue(':statuses_count', $user_info->statuses_count);
        $stmt->bindValue(':date_added', time());
        $stmt->bindValue(':owner_id', $owner_id);
        $stmt->bindValue(':task_id', $task_id);
        return $stmt->execute();
    }



    function makeRetweetReplayUser($user_info, $table, $action = 'insert', $owner_id, $task_id = false)
    {
        $query = '';
        if ($action == 'insert') {
            $query .= "INSERT INTO $table ";
        } else {
            $query .= "UPDATE {$table} ";
        }
        $query .= " SET
                        name=:name,
                        screen_name = :screen_name, 
                        profile_image_url = :profile_image_url,
                        followers_count = :followers_count,
                        friends_count = :friends_count,
                        statuses_count = :statuses_count,
                        date_added = :date_added,
                        last_status_id = :last_status_id,
                        tid=:tid
                        ";
        if ($action == 'insert') {
            $query .= ', owner_id = :owner_id';
        }
        if ($action == 'update') {
            $query .= ' WHERE owner_id = :owner_id AND task_id = :task_id';
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', $user_info->name);
        $stmt->bindValue(':tid', $user_info->id);
        $stmt->bindValue(':screen_name', $user_info->screen_name);
        $stmt->bindValue(':profile_image_url', $user_info->profile_image_url);
        $stmt->bindValue(':friends_count', $user_info->friends_count);
        $stmt->bindValue(':followers_count', $user_info->followers_count);
        $stmt->bindValue(':statuses_count', $user_info->statuses_count);
        $stmt->bindValue(':last_status_id', $user_info->status->id_str);
        $stmt->bindValue(':statuses_count', $user_info->statuses_count);
        $stmt->bindValue(':date_added', time());
        $stmt->bindValue(':owner_id', $owner_id);
        if ($task_id) {
            $stmt->bindValue(':task_id', $task_id);
        }
        $stmt->execute();
    }






    public function makeReplayUser($replay_message = 'أفضل استقدام من مكتب السلام للاستقدام', $owner_id, $task_id)
    {
        $redirectUrl = URL_ROOT . '/tweets/replay/add.php';
        $username = Helper::security($_POST['account_name']);

//        $countSelect = "SELECT id FROM replay_users WHERE screen_name = :screen_name";

        if ($username == '') {
            Session::flash('error', 'يجب كتابة اسم الحساب بشكل صحيح');
            header('Location: ' . $redirectUrl);
        } else {
//            $stmt = $this->db->prepare($countSelect);
//            $stmt->bindValue(':screen_name', $username);
//            if ($stmt->rowCount()) {
//                Session::flash('error', 'تم اضافة الحساب مسبقا');
//                header('Location: ' . $redirectUrl);
//            } else {

                $twitterApi = Helper::getTwInstance();
                $user_info  = $twitterApi->get('users/show', array('screen_name' => $username));

                if (isset($user_info->errors) && $user_info->errors) {
                    $error = 'حدث خطأ اثناء الاتصال بتويتر "'.$user_info->errors[0]->message.'" ';
                    Session::flash('error', $error);
                    header('Location: ' . $redirectUrl);
                } else {

                    $query = "INSERT INTO replay_users SET 
                        tid = :tid, 
                        name=:name,
                        screen_name = :screen_name, 
                        profile_image_url = :profile_image_url,
                        followers_count = :followers_count,
                        friends_count = :friends_count,
                        statuses_count = :statuses_count,
                        date_added = :date_added,
                        last_status_id = :last_status_id,
                        replay_message = :replay_message,
                        owner_id = :owner_id,
                        task_id = :task_id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindValue(':name', $user_info->name);
                    $stmt->bindValue(':tid', $user_info->id);
                    $stmt->bindValue(':screen_name', $user_info->screen_name);
                    $stmt->bindValue(':profile_image_url', $user_info->profile_image_url);
                    $stmt->bindValue(':friends_count', $user_info->friends_count);
                    $stmt->bindValue(':followers_count', $user_info->followers_count);
                    $stmt->bindValue(':statuses_count', $user_info->statuses_count);
                    $stmt->bindValue(':last_status_id', $user_info->status->id_str);
                    $stmt->bindValue(':statuses_count', $user_info->statuses_count);
                    $stmt->bindValue(':date_added', time());
                    $stmt->bindValue(':replay_message', $replay_message);
                    $stmt->bindValue(':owner_id', $owner_id);
                    $stmt->bindValue(':task_id', $task_id);
                    $stmt->execute();
                    return true;
                    Session::flash('success', 'تم اضافة الحساب بنجاح');
                    header('Location: ' . $redirectUrl);

                }

//            }
        }

    }


    public function create_task($data)
    {
        $query = "INSERT INTO tasks (task_name, target_twitter_id, task_time) VALUES (:task_name, :target_twitter_id, :task_time)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':task_name', $data['task_name']);
        $stmt->bindValue(':target_twitter_id', $data['target_twitter_id']);
        $stmt->bindValue(':task_time', $data['task_time']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function edit_task($data, $task_id)
    {
        $query = "UPDATE tasks SET task_name = :task_name, target_twitter_id = :target_twitter_id, task_time = :task_time
            WHERE tasks.id = :task_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':task_name', $data['task_name']);
        $stmt->bindValue(':target_twitter_id', $data['target_twitter_id']);
        $stmt->bindValue(':task_time', $data['task_time']);
        $stmt->bindValue(':task_id', $task_id);
        return $stmt->execute();
    }


    public function get_all_tasks()
    {
        $query = "SELECT * FROM tasks";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function get_task($task_id)
    {
        $query = "SELECT * FROM tasks WHERE id = :task_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':task_id', $task_id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }


    public function get_replay_retweet_task($task_id)
    {
        $q1 = "SELECT * FROM replay_users WHERE task_id = :task_id";
        $stmt = $this->db->prepare($q1);
        $stmt->bindValue(':task_id', $task_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && count($row)) {
            return $row;
        }
        else {
            $q2 = "SELECT * FROM retweets_users WHERE task_id = :task_id";
            $stmt = $this->db->prepare($q2);
            $stmt->bindValue(':task_id', $task_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        }
    }


    public function delete_owners_when_update($owners_id, $task_id, $table)
    {
        $owners_id = implode(',', $owners_id);
        $query = "DELETE FROM {$table} WHERE owner_id NOT IN ($owners_id) AND task_id = $task_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute();
    }

    public function check_owner_id_exist($owner_id, $task_id, $table)
    {
        $query = "SELECT * FROM {$table} WHERE owner_id = {$owner_id} AND task_id = $task_id";
        $stmt = $this->db->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && count($row)) {return $row;}
        return false;
    }

    public function edit_replay_user($replay_message = 'أفضل استقدام من مكتب السلام للاستقدام', $owner_id, $task_id)
    {
        $redirectUrl = URL_ROOT . '/tweets/replay/add.php';
        $username = Helper::security($_POST['account_name']);

//        $countSelect = "SELECT id FROM replay_users WHERE screen_name = :screen_name";

        if ($username == '') {
            Session::flash('error', 'يجب كتابة اسم الحساب بشكل صحيح');
            header('Location: ' . $redirectUrl);
            exit;
        } else {
//            $stmt = $this->db->prepare($countSelect);
//            $stmt->bindValue(':screen_name', $username);
//            if ($stmt->rowCount()) {
//                Session::flash('error', 'تم اضافة الحساب مسبقا');
//                header('Location: ' . $redirectUrl);
//            } else {

                $twitterApi = Helper::getTwInstance();
                $user_info  = $twitterApi->get('users/show', array('screen_name' => $username));

                if (isset($user_info->errors) && $user_info->errors) {
                    $error = 'حدث خطأ اثناء الاتصال بتويتر "'.$user_info->errors[0]->message.'" ';
                    Session::flash('error', $error);
                    header('Location: ' . $redirectUrl);
                } else {

                    $query = "UPDATE replay_users SET 
                        tid = :tid, 
                        name=:name,
                        screen_name = :screen_name, 
                        profile_image_url = :profile_image_url,
                        followers_count = :followers_count,
                        friends_count = :friends_count,
                        statuses_count = :statuses_count,
                        date_added = :date_added,
                        last_status_id = :last_status_id,
                        replay_message = :replay_message
                        WHERE 
                        task_id = :task_id
                        AND owner_id = :owner_id
                        ";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindValue(':name', $user_info->name);
                    $stmt->bindValue(':tid', $user_info->id);
                    $stmt->bindValue(':screen_name', $user_info->screen_name);
                    $stmt->bindValue(':profile_image_url', $user_info->profile_image_url);
                    $stmt->bindValue(':friends_count', $user_info->friends_count);
                    $stmt->bindValue(':followers_count', $user_info->followers_count);
                    $stmt->bindValue(':statuses_count', $user_info->statuses_count);
                    $stmt->bindValue(':last_status_id', $user_info->status->id_str);
                    $stmt->bindValue(':statuses_count', $user_info->statuses_count);
                    $stmt->bindValue(':date_added', time());
                    $stmt->bindValue(':replay_message', $replay_message);
                    $stmt->bindValue(':owner_id', $owner_id);
                    $stmt->bindValue(':task_id', $task_id);
                    $stmt->execute();

//                    Session::flash('success', 'Task Edit Successfully');
                    return true;
                }

//            }
        }

    }


    public function get_replay_messages($task_id)
    {
        $query = "SELECT replay_message FROM replay_users WHERE task_id = :task_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':task_id', $task_id);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $replayes = [];
        foreach ($rows as $row) {
            $replayes[] = $row['replay_message'];
        }
        return implode(', ', $replayes);
    }
}