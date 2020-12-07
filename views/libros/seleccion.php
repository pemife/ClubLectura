<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lista de libros propuestos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="libros-seleccion">

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'itemView' => function ($model, $key, $index, $widget) { ?>
            
        <?php }
    ]); ?>

</div>