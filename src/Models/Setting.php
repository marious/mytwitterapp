<?php

namespace MyApp\Models;


class Setting extends AbstractModel
{
    protected $table = 'app_settings';

    public function get($key = null)
    {
        $query = "SELECT * FROM {$this->table} LIMIT 1";
        if ($key) {
            $query = "SELECT * FROM {$this->table} WHERE id = :key LIMIT 1";
        }
        $stmt = $this->db->prepare($query);
        if ($key) {
            $stmt->bindValue(':key', $key);
        }
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && count($row)) {
            return $row;
        }
        return false;
    }


    public function create($data = array())
    {
        $query = "INSERT INTO {$this->table} (id, consumer_key, consumer_secret, oauth_callback)
                    VALUES(:id, :consumer_key, :consumer_secret, :oauth_callback)
            ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':consumer_key', $data['consumer_key']);
        $stmt->bindValue(':consumer_secret', $data['consumer_secret']);
        $stmt->bindValue(':oauth_callback', $data['oauth_callback']);
        $stmt->bindValue(':id', $data['id']);
        return $stmt->execute();
    }


    public function update($data = array())
    {
        $query = "UPDATE {$this->table}
                    SET consumer_key = :consumer_key,
                        consumer_secret = :consumer_secret,
                        oauth_callback = :oauth_callback
                    WHERE id = :id
                ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':consumer_key', $data['consumer_key']);
        $stmt->bindValue(':consumer_secret', $data['consumer_secret']);
        $stmt->bindValue(':oauth_callback', $data['oauth_callback']);
        $stmt->bindValue(':id', $data['id']);
        return $stmt->execute();
    }
}