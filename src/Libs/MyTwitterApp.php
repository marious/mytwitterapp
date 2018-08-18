<?php

namespace MyApp\Libs;


use Abraham\TwitterOAuth\TwitterOAuth;
use Codebird\Codebird;
use MyApp\Models\Setting;

class MyTwitterApp
{
    protected $twitter;
    protected $cb;      // represent codbird instance

    public function __construct()
    {
        $settingsModel = new Setting();
        $settings = $settingsModel->get('my_twitter_app');
        $this->twitter = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret']);

        Codebird::setConsumerKey($settings['consumer_key'], $settings['consumer_secret']);
        $this->cb = Codebird::getInstance();
        $this->cb->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
//        $this->cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    }


    public function send($msg)
    {
        return $this->cb->statuses_update('status=Whohoo, I just Tweeted!');
    }


    public function getTwitterInstance()
    {
        return $this->twitter;
    }



    public function getCodeBirdInstance()
    {
        return $this->cb;
    }

}