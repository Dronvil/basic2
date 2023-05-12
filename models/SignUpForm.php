<?php


namespace app\models;

use app\controllers\SiteController;
use Yii;
use yii\base\Model;
use yii\base\BaseObject;

class SignUpForm extends User
{
    public $login;
    public $username;
    public $email;
    public $password;
    public $access_level =1;

    //public $tblPosts;


    public function rules()
    {
        return [
            ['login', 'filter', 'filter' => 'trim'],
            ['login', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['login', 'unique', 'targetClass' => User::className(), 'message' => 'Этот логин уже занят'],
            ['login', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['username', 'filter', 'filter' => 'trim'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['access_level', 'integer']
        ];
    }

    public function signUp()
    {
        $user = new User();
        if ($this->validate()) {
            $user->login = $this->login;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->password_hash = $user->setPasswordHash($this->password);
            $user->auth_key = $user->generateAuthKey();
            $user->access_level = $this->access_level;
            if ($user->save()) {
                return $user;
            }
        }
        return null;

    }

}