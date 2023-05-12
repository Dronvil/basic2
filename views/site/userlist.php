<?php

use yii\helpers\Html;
use app\models\User;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);





    echo '<h1>'.Html::encode($this->title).'</h1>';

    foreach (User::find()
                       ->indexBy('id')->select(['id',
                'login',
                'email',
                'username',
                'password_hash',
                'access_level',
                'auth_key',])
                       ->asArray()
                       ->all() as $user) {


        echo DetailView::widget([
            'model' => $user,
            'attributes' => [
                'id',
                'login',
                'email',
                'username',
                'password_hash',
                'access_level',
                'auth_key',
            ],
        ]);
        echo Html::a('Удалить полтзователя', ['deleteuser', 'id' => $user['id']], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]);
    }