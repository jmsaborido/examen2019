<?php

use kartik\datecontrol\DateControl;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var $this yii\web\View */
/** @var $model app\models\Citas */

$this->title = 'Create Citas';
$this->params['breadcrumbs'][] = ['label' => 'Citas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['citas/especialistas']);
$urlHueco = Url::to(['citas/hueco']);
$js = <<<EOT
$('#citas-especialidad_id').on('change', function (ev) {
    var el = $(this);
    var especialidad_id = el.val();
    if (especialidad_id === '') {
        $('#citas-especialista_id').empty();
        $('#citas-especialista_id').append('<option value=" "></option>');
        $('#citas-instante').val('');
        $('#citas-instante-oculto').val('');
        return;
    }
    $.ajax({
        method: 'GET',
        url: '$url',
        data: {
            especialidad_id: especialidad_id
        },
        success: function (data, code, jqXHR) {
            var sel = $('#citas-especialista_id');
            sel.empty();
            for (var i in data) {
                sel.append(`<option value="\${i}">\${data[i]}</option>`);
            }
            $('#citas-especialista_id').trigger('change');
        }
    });
});
$('#citas-especialista_id').on('change', function (ev) {
    var el = $(this);
    var especialista_id = el.val();
    $.ajax({
        method: 'GET',
        url: '$urlHueco',
        data: {
            especialista_id: especialista_id
        },
        success: function (data, code, jqXHR) {
            $('#citas-instante').val(data.formateado);
            $('#citas-instante-oculto').val(data.valor);
            $('#citas-create').yiiActiveForm('validate');
            $('#citas-create').yiiActiveForm('validateAttribute', 'citas-especialista_id');
            $('#citas-create').yiiActiveForm('validateAttribute', 'citas-instante');
        }
    });
});
EOT;
$this->registerJs($js);
?>
<div class="citas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="citas-form">

        <?php $form = ActiveForm::begin([
            'id' => 'citas-create',
            'enableAjaxValidation' => true,
        ]); ?>

        <?= $form->field($model, 'especialidad_id')->dropDownList($especialidades) ?>
        <?= $form->field($model, 'especialista_id')->dropDownList($especialistas) ?>
        <?= Html::activeHiddenInput($model, 'instante', ['id' => 'citas-instante-oculto']) ?>
        <?= $form->field($model, 'instante')->textInput(['readonly' => true, 'name' => '']) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>