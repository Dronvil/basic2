<?php

/** @var yii\web\View $this */
/** @var app\models\Post $model */
/** @var app\models\User $user */
/** @var yii\widgets\ActiveForm $form */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Post;
use app\models\Category;

$this->title = 'Создание поста';
$this->params['breadcrumbs'][] = $this->title; ?>


<div class="blog-post-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::a('Создать категорию', ['categorycreate'], ['class' => 'btn btn-primary'])?></p>
    <p>Пожалуйста, заполните следующие поля для создания поста:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'topic') ?>
            <?= $form->field($model, 'text')->textarea() ?>
            <?= $form->field($model, 'category')->dropDownList(ArrayHelper::map(Category::find()->all(),'id','category_name'))?>

            <div class="form-group">
                <?= Html::submitButton('Создать пост', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>