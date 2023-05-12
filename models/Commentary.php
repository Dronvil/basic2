<?php


namespace app\models;

use app\models\User;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%commentary}}".
 *
 * @property int $id
 * @property string $com_text
 *
 * @property User $author
 * @property Post $post
 */
class Commentary extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%commentary}}';
    }

    public function rules()
    {
        return [
            [['com_text'], 'required'],
            [[ 'author','post'], 'integer'],
            [['com_text'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'com_text' => 'Текст комментария',
            'post' => 'Пост',
            'author' => 'Автор',
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author']);
    }
}