<?php


namespace app\models;

use yii\db\ActiveRecord;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%blog_posts}}".
 *
 * @property int $id
 * @property string $topic
 * @property string|null $text
 * @property int|null $author_id
 *
 * @property User $author
 * @property Category $category
 */
class Post extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%blog_posts}}';
    }

    public function rules()
    {
        return [
            [['topic','text'], 'required'],
            [[ 'author','category'], 'integer'],
            [['topic', 'text'], 'string', 'max' => 255],
          //  [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'topic' => 'Тема',
            'text' => 'Текст',
            'author' => 'Автор',
            'category' => 'Категория'
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author']);
    }
}