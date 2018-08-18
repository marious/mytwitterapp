<?php
namespace MyApp\Controllers;

use MyApp\Libs\Session;
use MyApp\Models\Setting;

class Settings extends AbstractController
{

    protected $settingModel;

    public function __construct()
    {
        parent::__construct();
        $this->settingModel = new Setting();
    }


    public function handleSettings()
    {
        // Validate user input
        $validator = new \Validator($this->request->getParams()->all());
        $rules = [
            'consumer_key'      => 'trim|required',
            'consumer_secret'   => 'trim|required',
            'oauth_callback'    => 'trim|required',
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
        $data['consumer_key']       = $this->setData('consumer_key', true);
        $data['consumer_secret']    = $this->setData('consumer_secret', true);
        $data['oauth_callback']     = $_POST['oauth_callback'];
        $data['id']                 = 'my_twitter_app';

        if ($this->settingModel->get('my_twitter_app') && count($this->settingModel->get('my_twitter_app')))
        {
            $this->settingModel->update($data);
            Session::flash('success', 'Consumer Key and Consumer Secret Updated Successfully');
            header('Location: ' . URL_ROOT .'settings.php');
//            exit;
        } else {
            $this->settingModel->create($data);
            Session::flash('success', 'Consumer Key and Consumer Secret Set Successfully');
            header('Location: ./settings.php');
        }

        return true;

    }


}