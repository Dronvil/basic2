<?php

/** @var yii\web\View $this */
/** @var app\models\Sort $model */
/** @var app\models\User $user */
/** @var yii\widgets\ActiveForm $form */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Post;
use app\models\Category;
?>
    <div class="row">
        <div class="col-lg-5">
            <?= Html::textInput('field') ?>
            <?= Html::dropDownList( 'type',[],['author'=>'Авторах', 'topic' => 'Темах', 'text'=>'Тексте', 'category' =>'Категориях'])?>
            <?= Html::dropDownList('sort', [],['author' => 'Автору', 'id' => 'Новизне', 'category' => 'Категории', 'topic' => 'Теме'])?>

            <div class="form-group">
                <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
            </div>

        </div>
    </div>