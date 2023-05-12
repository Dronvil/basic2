<?php
/** @var yii\web\View $this */
/** @var app\models\Commentary $model */
/** @var app\models\User $user */
/** @var yii\widgets\ActiveForm $form */
use app\models\Commentary;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Post;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
/** @var yii\web\View $this */
$post=$_GET['post'];
$this->title = 'Создание комментария';
?>
<div class="blog-post-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'com_text')->textarea() ?>

            <div class="form-group">
                <?= Html::submitButton('Опубликовать', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
