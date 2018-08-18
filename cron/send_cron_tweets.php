<?php
if (count($tweet)) {
    $tweetContent = $tweet['tweet_content'];
    if (!empty($tweetContent)) {
        if ($tweet['tweet_media'] != '0') {
            $media = explode('-', $tweet['tweet_media']);
            for ($i = 0; $i <= (TWITTER_UPLOADS_POST_MAX_IMG - 1); $i++) {
                if (isset($media[$i])) {
                    $this_media = '';
                    $this_media = $connection->upload('media/upload', [
                        'media' => URL_ROOT . 'media/' . $media[$i],
                    ]);
                    if (isset($this_media->media_id) && $this_media->media_id != '') {
                        $media_ids[] = $this_media->media_id;
                    }
                }
            }
        }

        $parameters = [];
        if (isset($media_ids) && count($media_ids) > 0) {
            $parameters = [
                'status' => $tweetContent,
                'media_ids' => implode(',', $media_ids),
            ];
        } else {
            $parameters = ['status' => $tweetContent];
        }

        $content = $connection->post('statuses/update', $parameters);
        if ($connection->getLastHttpCode() == 200) {
            echo 'success';
            $q2 = "DELETE FROM scheduled_tweets WHERE owner_id = :owner_id 
                  AND id = $schedule_tweet_id";
            $stmt2 = $db->prepare($q2);
            $stmt2->bindValue(':owner_id', $u_data['id']);
            $stmt2->execute();
        } else {
            echo 'error';
        }
        var_dump($content);

    }
}