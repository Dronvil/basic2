<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{%tbl_user}}".
 *
 * @property int $id
 * @property string $login
 * @property string $username
 * @property string $password_hash
 * @property string $email
 * @property string $auth_key
 * @property int $access_level
 *
 * @property Post[] $tblPosts
 *
 */
//
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * User status
     */

    /**
     * {@inheritdoc}
     */
    const BAN = 0;
    const USER = 1;
    const ADMIN = 2;
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'username', 'email'], 'required'],
            [['email'], 'email'],
            [['login', 'email'], 'unique'],
            [['login'], 'string', 'max' => 40],
            [['email'], 'string', 'max' => 100],
            [['username', 'password_hash', 'auth_key'], 'string', 'max' => 255],
            ['access_level', 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['author_id' => 'id']);
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'Пароль',
            'access_level' => 'Роль',
            'login' => 'Логин',
            'email' => 'Электронная почта',
            'username' => 'Имя',
            'password_hash' => 'Хэш пароля',
            'auth_key' => 'Ключ аутентификации',
        ];
    }

    /**
     * Gets query for [[TblPosts]].
     */
     // @return \yii\db\ActiveQuery

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($access_level, $type = null)
    {
        return static::findOne(['access_level' => $access_level]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login]);
    }
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    public function setPasswordHash($password)
    {
        return $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    public function generateAuthKey()
    {
        return $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
