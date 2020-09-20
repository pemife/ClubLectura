<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lista de libros propuestos';
$this->params['breadcrumbs'][] = $this->title;
// TODO
?>
<div class="libros-seleccion">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'orden',
            'libro.titulo',
            'libro.autor',
            // 'editorial',
            // 'isbn',
            //'fecha_publicacion',
            //'fecha_1a_edicion',
            //'descripcion:ntext',
            //'n_paginas',
        ],
    ]); ?>


</div>