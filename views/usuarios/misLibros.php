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

    <?= Yii::debug($dataProvider) ?>

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
