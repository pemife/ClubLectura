<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis libros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuarios-mis-libros">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            ?>
            <div 
            class="row libro"
            data-libroId="<?= $model->libro->id ?>"
            data-libroOrden="<?= $model->orden?>"
            data-libroNombre="<?= $model->libro->titulo ?>"
            >
                <div class="col-md-12">
                    <h3><?= Html::encode($model->libro->titulo) ?></h3>
                </div>
                <div class="row col-md-12">
                    <div class="col-md-1">
                        <div><span class="fas fa-bars"></span></div>
                        <div><h1><?= Html::encode($model->orden) ?></h1></div>
                        <div><span class="fas fa-bars"></span></div>
                    </div>
                    <div class="col-md-3">
                        <img src="" width="150" height="125">
                    </div>
                    <div class="col-md-7">
                        
                    </div>
                </div>
                <div class="col-md-12"><hr></div>
            </div>
            <?php
        }
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            
            //'img',
            'libro.titulo',
            'libro.autor',
            'orden',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        if (Yii::$app->user->isGuest || Yii::$app->user->identity->id != $model->usuario->id) {
                            return '';
                        }
                        return Html::a('', ['libros/view', 'id' => $model->libro->id], [
                            'class' => 'glyphicon glyphicon-eye-open',
                            'title' => 'Ver libro',
                            'style' => [
                                'color' => 'limegreen',
                            ]
                        ]);
                    },
                    'update' =>  function ($url, $model, $key) {
                        return '';
                    },
                    'delete' => function ($url, $model, $key){
                        if (Yii::$app->user->isGuest || Yii::$app->user->identity->id != $model->usuario->id) {
                            return '';
                        }
                        return Html::a('', ['usuarios/borrar-libro', 'l' => $model->libro->id], [
                            'class' => 'glyphicon glyphicon-trash',
                            'title' => 'Ver libro',
                            'style' => [
                                'color' => 'red',
                            ]
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>


</div>
