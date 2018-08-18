<?php

namespace MyApp\Libs;


use MyApp\Controllers\Tweets;
use PDO;

class Cron
{
    private $owner_id;
    private $first_pass_done;
    private $throttle_api_time_fw;
    private $throttle_api_time_fr;
    private $fr_fw_issue;
    private $db;
    public $last_error;


    public function __construct()
    {
        $this->first_pass_done = 0;
        $this->throttle_api_time_fr = 0;
        $this->throttle_api_time_fw = 0;
        $this->db = \DB::connect();
    }

    public function set_user_id($tw_id)
    {
        $this->owner_id = $tw_id;
    }

    public function set_throttle_time($throt, $type)
    {
        if ($type == 'fr') {
            $this->throttle_api_time_fr = $throt;
        } else if ($type == 'fw') {
            $this->throttle_api_time_fw = $throt;
        }
    }


    public function set_first_pass_done($fp)
    {
        $this->first_pass_done = $fp;
    }


    public function get_first_pass_done()
    {
        return $this->first_pass_done;
    }

    public function set_fr_fw_issue($si)
    {
        $this->fr_fw_issue = $si;
    }

    public function get_fr_fw_issue()
    {
        return $this->fr_fw_issue;
    }


    public function get_cron_state($cron_type)
    {
        $query = "SELECT cron_state FROM cron_status WHERE cron_name = :cron_type";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':cron_type', $cron_type);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cron_state'];
    }


    public function set_cron_state($cron_type, $cron_state)
    {
        $query = "UPDATE cron_status SET cron_state = :cron_state 
                  last_update = NOW() WHERE cron_name = :cron_type";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':cron_state', $cron_state);
        $stmt->bindValue(':cron_type', $cron_type);
        return $stmt->execute();
    }


    public function clear_table_flags()
    {
        $this->db->query("UPDATE fw_{$this->owner_id} SET stp = 0, 
            ntp = 0, otp = 0 WHERE 1");

        $this->db->query("UPDATE fr_{$this->owner_id} SET stp = 0, 
            ntp = 0, otp = 0 WHERE 1");
    }



    public function store_fw_fr_list($fr_or_fw)
    {
        $connection = Helper::getTwInstance();

        if ($fr_or_fw == 'fw') {
            $twit_op = 'followers/ids';
        }
        if ($fr_or_fw == 'fr') {
            $twit_op = 'friends/ids';
        }

        $this_cursor = "-1";
        $count_check = 0;

        while ($this_cursor != 0) {
            $content = $connection->get($twit_op, array(
                'user_id' => $this->owner_id,
                'cursor' => $this_cursor,
                'stringify_ids' => true,
                'count' => TWITTER_API_LIST_FW,
            ));

            if ( (!is_object($content)) || ($connection->getLastHttpCode() != 200) ) {
                for ($i = 1; $i <= TWITTER_API_MAX_RETRIES; $i++) {
                    $content = $connection->get($twit_op, array(
                        'user_id' => $this->owner_id,
                        'cursor' => $this_cursor,
                        'stringify_ids' => 'true',
                        'count' => TWITTER_API_LIST_FW
                    ));
                    sleep($i);
                    if ((is_object($content)) and ($connection->getLastHttpCode() == 200)) {
                        break;
                    }
                }
            }

            if ((!is_object($content)) or ($connection->getLastHttpCode() != 200)) {
                $this->set_fr_fw_issue(1);
                $this->last_error = ' (' . $content->errors[0]->code . ' - ' .
                    $content->errors[0]->message . ')';
                $this_cursor      = "0";
            } else {
                foreach ($content->ids as $this_id) {
                    $this->store_fr_fw_id($fr_or_fw, $this_id);
                    $count_check++;
                }
                $this_cursor = $content->next_cursor_str;
                $var_name    = 'throttle_api_time_ ' . $fr_or_fw;
                if ($this->$var_name > 0) {
                    sleep($this->$var_name);
                }
            }

        }

        return $count_check;
    }

    public function store_fr_fw_id($fr_or_fw, $save_id)
    {
        $table_name = $fr_or_fw . '_' . $this->owner_id;
        if ($this->first_pass_done == 0) {
            $query = "INSERT INTO {$table_name}(twitter_id) VALUES(:twitter_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':twitter_id', $save_id);
            $stmt->execute();
        } else {
            $qcheck = "SELECT twitter_id FROM {$table_name} WHERE twitter_id = :twitter_id";
            $stmt = $this->db->prepare($qcheck);
            $stmt->bindValue(':twitter_id', $save_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (count($row)) {
                $query = "UPDATE {$table_name} SET stp=1 WHERE twitter_id = :twitter_id";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':twitter_id', $save_id);
                $stmt->execute();
            } else {
                $query = "INSERT INTO {$table_name}(twitter_id, stp, ntp) VALUES(:twitter_id, 1, 1)";
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':twitter_id', $save_id);
                $stmt->execute();
            }
        }
    }



    public function get_id_changes()
    {
        $return_array = array();
        $types_tb     = array(
            'fw',
            'fr'
        );
        $types_id     = array(
            'new' => '1',
            'gone' => '0'
        );
        foreach ($types_tb as $this_tb) {
            foreach ($types_id as $ids => $idv) {
                $table_name = $this_tb . '_' . $this->owner_id;
                $qcheck = "SELECT twitter_id FROM {$table_name} 
                    WHERE stp = " . (int) $idv . " AND ntp= " . (int) $idv;
                $stmt = $this->db->prepare($qcheck);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $return_array[$this_tb . '_' . $ids][] = $row['twitter_id'];
                }
            }
        }

        return $return_array;
    }


    public function get_id_exclusions($this_type)
    {
        $return_array = [];
        $qcheck = "SELECT twitter_id FROM follow_exclusions 
                WHERE type = " . (int) $this_type . " AND owner_id = :owner_id";
        $stmt = $this->db->prepare($qcheck);
        $stmt->bindValue(':owner_id', $this->owner_id);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $return_array[] = $row['twitter_id'];
        }
        return $return_array;
    }

    public function delete_unseen_ids()
    {
        $this->db->query("DELETE FROM fw_" . $this->owner_id . " WHERE stp = 0");
        $this->db->query("DELETE FROM fr_" . $this->owner_id . " WHERE stp = 0");
        $this->db->query("OPTIMIZE TABLE fw_" . $this->owner_id);
        $this->db->query("OPTIMIZE TABLE fr_" . $this->owner_id);
    }


    public function set_first_pass_done_db()
    {

    }


    public function get_uncached_users($type_flag)
    {
        $return_array = [];

        if ($type_flag == 1) {
            $qcheck = "SELECT twitter_id FROM user_cache WHERE screen_name = '' 
              AND is_suspended = '0' ORDER BY last_update DESC";
        } else if ($type_flag == 2) {
            $qcheck = "SELECT twitter_id FROM user_cache WHERE is_suspended = '0' 
                      AND last_update <'" .  date("Y-m-d H:i:s", strtotime('-' . TWITTER_API_DAYS_BEFORE_RECACHE . ' days')) . "' ORDER BY last_updated ASC";
        } else if ($type_flag == 3) {
            $qcheck = "SELECT twitter_id FROM " . DB_PREFIX . "user_cache WHERE screen_name = '' AND                            is_suspended = '0' ORDER BY last_updated ASC";
        }  else if ($type_flag == 4) {
                $qcheck = "SELECT twitter_id FROM " . DB_PREFIX . "user_cache WHERE last_updated < '" . date("Y-m-d H:i:s", strtotime('-' . TWITTER_API_DAYS_BEFORE_RECACHE . ' days')) . "' ORDER BY is_suspended ASC, last_updated ASC";
        }

        if (isset($qcheck)) {
            $stmt = $this->db->prepare($qcheck);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $return_array[] = $row['twitter_id'];
            }
        }

        return $return_array;
    }


    public function get_remaining_hits()
    {
        $connection = Helper::getTwInstance();
        $return_array                 = array();
        $rate_con                     = $connection->get('application/rate_limit_status', array(
            "resources" => 'followers,friends,users'
        ));
        $return_array['fw_remaining'] = $rate_con->resources->followers->{'/followers/ids'}->remaining;
        $return_array['fw_limit']     = $rate_con->resources->followers->{'/followers/ids'}->limit;
        $return_array['fw_reset']     = $rate_con->resources->followers->{'/followers/ids'}->reset - time();
        $return_array['fr_remaining'] = $rate_con->resources->friends->{'/friends/ids'}->remaining;
        $return_array['fr_limit']     = $rate_con->resources->friends->{'/friends/ids'}->limit;
        $return_array['fr_reset']     = $rate_con->resources->friends->{'/friends/ids'}->reset - time();
        $return_array['us_remaining'] = $rate_con->resources->users->{'/users/show/:id'}->remaining;
        $return_array['us_limit']     = $rate_con->resources->users->{'/users/show/:id'}->limit;
        $return_array['us_reset']     = $rate_con->resources->users->{'/users/show/:id'}->reset - time();
        $return_array['ul_remaining'] = $rate_con->resources->users->{'/users/lookup'}->remaining;
        $return_array['ul_limit']     = $rate_con->resources->users->{'/users/lookup'}->limit;
        $return_array['ul_reset']     = $rate_con->resources->users->{'/users/lookup'}->reset - time();
        return $return_array;
    }



    public function create_cron_tables($tw_user_id)
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `fw_" . $tw_user_id . "` (
  `twitter_id` varchar(48) NOT NULL,
  `stp` tinyint(1) NOT NULL default '0',
  `ntp` tinyint(1) NOT NULL default '0',
  `otp` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`twitter_id`),
  KEY `stp` (`stp`),
  KEY `ntp` (`ntp`),
  KEY `otp` (`otp`)
  );");
        $this->db->query("CREATE TABLE IF NOT EXISTS `fr_" . $tw_user_id . "` (
  `twitter_id` varchar(48) NOT NULL,
  `stp` tinyint(1) NOT NULL default '0',
  `ntp` tinyint(1) NOT NULL default '0',
  `otp` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`twitter_id`),
  KEY `stp` (`stp`),
  KEY `ntp` (`ntp`),
  KEY `otp` (`otp`)
  );");
    }


}