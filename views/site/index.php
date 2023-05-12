<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Post;
use app\models\User;
use app\models\Category;
use app\models\Sortirovka;
use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;
/** @var yii\web\View $this */
/** @var Post $posts */
$this->title = 'Все посты';

    echo '<h1>'.Html::encode($this->title).'</h1>';
    if (Yii::$app->user->isGuest or Yii::$app->user->identity->access_level == User::BAN){
        echo '';
    }
    else {
        echo Html::a('Создать пост', ['create'], ['class' => 'btn btn-primary']).' ';
    } ?>
    <?php Pjax::begin() ?>
    <div class="col-md-12">

    <?php $form = ActiveForm::begin(['layout' => 'horizontal']) ?>

        Искать<?= $form->field($model, 'field') ?>
        в<?= $form->field($model, 'type')->dropDownList(['author'=>'Автор','topic'=>'Тема','text'=>'Текст','category'=>'Категория']) ?>
        Сортировать по<?= $form->field($model, 'sort')->dropDownList(['id'=>'Новизне','author'=>'Автору','category'=>'Категории']) ?>

        <div class="form-group">
            <?= Html::submitButton('Поиск', ['class' => 'btn btn-default', 'name'=>'submit1']) ?>
        </div>


    <?php ActiveForm::end() ?>
    <?php Pjax::end() ?>
</div>
    <?php

    if (isset($_POST['submit1']))
    {
        $search = $_POST['Sortirovka'];
        $field = $search['field'];
        $type = $search['type'];
        $sort = $search['sort'];
        if ($type == 'topic' or $type == 'text')
        {
            foreach (Post::find()->filterWhere(['like', $type, $field] )->orderBy($sort)->
            select(['id',
            'topic',
            'text',
            'category',
            'author',
            ])->asArray()->all() as $post)
            {
            $category = Category::findOne($post['category']);
            $user = User::findOne($post['author']);
            echo '<h2>'.$post['topic'].'</h2>';
            echo '<h4>'.$post['text'].'</h4>';
            echo '<small> Категория: '.$category['category_name'].'<br>           by '.$user['username'].'</small><br>';
            echo Html::a('Комментарии', ['comment', 'post' => $post['id']], ['class' => 'btn btn-primary']);
            if (!Yii::$app->user->isGuest and (Yii::$app->user->identity->id == $post['author'] or Yii::$app->user->identity->access_level == User::ADMIN))
            {
                echo Html::a('Удалить', ['deletepost', 'id' => $post['id']], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]);
            }
            echo '<br><br><hr>';
            }
        }
        if ($type == 'author' or $type == 'category')
        {
            foreach (Post::find()->orderBy($sort)->
            select(['id',
                'topic',
                'text',
                'category',
                'author',
            ])->asArray()->all() as $post)
            {
                $category = Category::findOne($post['category']);
                $user = User::findOne($post['author']);
                if (($type == 'author' and str_contains($user['username'],$field))or($type == 'category' and str_contains($category['category_name'],$field))){
                echo '<h2>'.$post['topic'].'</h2>';
                echo '<h4>'.$post['text'].'</h4>';
                echo '<small> Категория: '.$category['category_name'].'<br>           by '.$user['username'].'</small><br>';
                echo Html::a('Комментарии', ['comment', 'post' => $post['id']], ['class' => 'btn btn-primary']);
                if (!Yii::$app->user->isGuest and (Yii::$app->user->identity->id == $post['author'] or Yii::$app->user->identity->access_level == User::ADMIN))
                {
                    echo Html::a('Удалить', ['deletepost', 'id' => $post['id']], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]);
                }
                echo '<br><br><hr>';}
            }
        }
    }
    else{

    echo '<hr>';

    foreach (Post::find()->indexBy('id')->
        select(['id',
                'topic',
                'text',
                'category',
                'author',
                ])->asArray()->all() as $post)
    {
        $category = Category::findOne($post['category']);
        $user = User::findOne($post['author']);
        echo '<h2>'.$post['topic'].'</h2>';
        echo '<h4>'.$post['text'].'</h4>';
        echo '<small> Категория: '.$category['category_name'].'<br>           by '.$user['username'].'</small><br>';
        echo Html::a('Комментарии', ['comment', 'post' => $post['id']], ['class' => 'btn btn-primary']);
        if (!Yii::$app->user->isGuest and (Yii::$app->user->identity->id == $post['author'] or Yii::$app->user->identity->access_level == User::ADMIN))
        {
            echo Html::a('Удалить', ['deletepost', 'id' => $post['id']], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        }
        echo '<br><br><hr>';
    }}

