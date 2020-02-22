<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Especialistas */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="especialistas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'especialidad_id')->textInput() ?>

    <?= $form->field($model, 'hora_minima')->textInput() ?>

    <?= $form->field($model, 'hora_maxima')->textInput() ?>

    <?= $form->field($model, 'duracion')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
