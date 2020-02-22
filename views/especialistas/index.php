<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EspecialistasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Especialistas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="especialistas-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Especialistas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nombre',
            'especialidad_id',
            'hora_minima',
            'hora_maxima',
            //'duracion',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
