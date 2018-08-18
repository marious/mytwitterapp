<?php

namespace MyApp\Models;


class ScheduledTweet extends AbstractModel
{
    protected $table = 'scheduled_tweets';

    public function create($data = [])
    {
        $query = "INSERT INTO {$this->table}(owner_id, tweet_content, tweet_media, time_to_post)
                  VALUES(:owner_id, :tweet_content, :tweet_media, :time_to_post)
            ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':owner_id', $data['owner_id']);
        $stmt->bindValue(':tweet_content', $data['tweet_content']);
        $stmt->bindValue(':tweet_media', $data['tweet_media']);
        $stmt->bindValue(':time_to_post', $data['time_to_post']);
        return $stmt->execute();
    }


    public function update($data = [])
    {
        $query = "UPDATE {$this->table} SET tweet_content = :tweet_content, 
                 tweet_media = :tweet_media, time_to_post = :time_to_post
                 WHERE id =:tweet_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':tweet_content', $data['tweet_content']);
        $stmt->bindValue(':tweet_media', $data['tweet_media']);
        $stmt->bindValue(':time_to_post', $data['time_to_post']);
        $stmt->bindValue(':tweet_id', $data['tweet_id']);
        return $stmt->execute();
    }
}