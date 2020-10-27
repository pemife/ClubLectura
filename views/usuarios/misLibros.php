<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis libros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuarios-mis-libros">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            
            //'img',
            'titulo',
            'autor',
            'orden',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
