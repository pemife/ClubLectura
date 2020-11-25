<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Criticas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="criticas-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Criticas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'created_at',
            'texto:ntext',
            'usuario_id',
            'libro_id',
            //'pelicula_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
