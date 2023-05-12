<?php

use app\models\Commentary;
use yii\helpers\Html;
use app\models\User;
use app\models\Post;
use yii\widgets\DetailView;
/** @var yii\web\View $this */
$post=$_GET['post'];
$post_model = Post::findOne($post);
$post_author = $post_model->author;
$this->title = 'Комментарии';

echo '<div class="post-comment">';

    echo '<h1>'.Html::encode($this->title).'</h1>';
    echo '<p>'.Html::a('Написать комментарий', ['commentcreate', 'post' => $post], ['class' => 'btn btn-primary']).'</p>';
    foreach (Commentary::find()
                           ->indexBy('id')->where(['post'=>$post]) ->select(['id',
                'com_text',
                'author'
            ])
                           ->asArray()
                           ->all() as $comment)
    {
                echo '<p>'.$comment['com_text'].'</p';
                echo '<br>';
                $user = User::find()->where(['id' => $comment['author']])->select(['login'])->asArray()->all();
                if ($user != null)
                {
                    echo'   <small><strong><sub>by '.$user[0]['login'].'</sub></strong></small>';
                }
                else
                {
                    echo '   <small><strong><sub>Guest</sub></strong></small>';
                }
                echo '<br>';
                if (!Yii::$app->user->isGuest){
                    if (Yii::$app->user->identity->id == $comment['author'] or Yii::$app->user->identity->id == $post_author or Yii::$app->user->identity->access_level == User::ADMIN){
                        echo Html::a('Удалить комментарий', ['deletecomment', 'id' => $comment['id']], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]);
                }}
                echo '<hr><br><br>';
    }
