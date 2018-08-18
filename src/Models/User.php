<?php

namespace MyApp\Models;


class User extends AbstractModel
{

    public function getById($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && count($row)) {
            return $row;
        }
        return false;
    }


    public function getAll()
    {
        $query = "SELECT * FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ($result && count($result)) {
            return $result;
        }
        return false;
    }



    public function create($data = [])
    {
        $query = "INSERT INTO users (id, oauth_token, oauth_token_secret,
                    profile_image_url, screen_name, followers_count, friends_count, 
                    statuses_count, created_at, favourites_count, oauth_verifier, name)
                        VALUES (:id, :oauth_token, :oauth_token_secret,
                        :profile_image_url, :screen_name, :followers_count, :friends_count, :statuses_count, :created_at,
                        :favourites_count, :oauth_verifier, :name)
                ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $data['id']);
        $stmt->bindValue(':oauth_token', $data['oauth_token']);
        $stmt->bindValue(':oauth_token_secret', $data['oauth_token_secret']);
        $stmt->bindValue(':profile_image_url', $data['profile_image_url']);
        $stmt->bindValue(':screen_name', $data['screen_name']);
        $stmt->bindValue(':followers_count', $data['followers_count']);
        $stmt->bindValue(':friends_count', $data['friends_count']);
        $stmt->bindValue(':statuses_count', $data['statuses_count']);
        $stmt->bindValue(':created_at', $data['created_at']);
        $stmt->bindValue(':favourites_count', $data['favourites_count']);
        $stmt->bindValue(':oauth_verifier', $data['oauth_verifier']);
        $stmt->bindValue(':name', $data['name']);
        return $stmt->execute();
    }


    public function update($data = [])
    {
        $query = "UPDATE users SET oauth_token = :oauth_token, oauth_token_secret = :oauth_token_secret,
                    profile_image_url = :profile_image_url, screen_name = :screen_name, 
                    followers_count = :followers_count, friends_count = :friends_count, oauth_verifier = :oauth_verifier,
                    statuses_count = :statuses_count, favourites_count = :favourites_count, name = :name
                    WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $data['id']);
        $stmt->bindValue(':oauth_token', $data['oauth_token']);
        $stmt->bindValue(':oauth_token_secret', $data['oauth_token_secret']);
        $stmt->bindValue(':profile_image_url', $data['profile_image_url']);
        $stmt->bindValue(':screen_name', $data['screen_name']);
        $stmt->bindValue(':followers_count', $data['followers_count']);
        $stmt->bindValue(':friends_count', $data['friends_count']);
        $stmt->bindValue(':statuses_count', $data['statuses_count']);
        $stmt->bindValue(':favourites_count', $data['favourites_count']);
        $stmt->bindValue(':oauth_verifier', $data['oauth_verifier']);
        $stmt->bindValue(':name', $data['name']);
        return $stmt->execute();
    }


    public function create_regular_user($data = [])
    {
        $query = "INSERT INTO regular_user(username, password, email)
                  VALUES (:username, :password, :email) 
                  ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':username', $data['username']);
        $stmt->bindValue(':password', $data['password']);
        $stmt->bindValue(':email', $data['email']);
        return $stmt->execute();
    }


    public function check_regular_user_login($data)
    {
        $username = $data['username'];
        $password = $data['password'];

        $query = "SELECT * FROM regular_user WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && count($row))
        {
            $check_password = password_verify($password, $row['password']);
            if ($check_password)
            {
                return $row;
            }
        }
        return false;

    }


    public function update_regular_user_twitter_id($user_id, $twitter_id)
    {
        $query = "SELECT * FROM regular_user WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && count($row))
        {
            $query = "UPDATE regular_user SET twitter_id = :twitter_id WHERE id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->bindValue(':twitter_id', $twitter_id);
            return $stmt->execute();
        }
        return false;
    }

    public function get_regular_user($user_id)
    {
        $query = "SELECT * FROM regular_user WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && count($row)) {
            return $row;
        }
        return false;
    }


    public function get_twitter_user_data($twitter_id)
    {
        $query = "SELECT * FROM users WHERE id = :twitter_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':twitter_id', $twitter_id);
        $stmt->execute();
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($row && count($row)) {
            return $row;
        }
        return false;
    }

}