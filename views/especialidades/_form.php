<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Especialidades */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="especialidades-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'especialidad')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
