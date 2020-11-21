<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Libros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="libros-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Añadir Libros', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'titulo',
            'autor',
            'editorial',
            'isbn',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {anadir}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        if (Yii::$app->user->isGuest || Yii::$app->user->identity->id != 1) {
                            return '';
                        }
                    },
                    'delete' => function ($url, $model, $key) {
                        if (Yii::$app->user->isGuest || Yii::$app->user->identity->id != 1) {
                            return '';
                        }
                        return Html::a(
                            '',
                            ['update', 'id' => $model->id],
                            [
                                'class' => 'fas fa-edit'
                            ]
                        );
                    },
                    'anadir' => function ($url, $model, $key) {
                        if (Yii::$app->user->isGuest) {
                            return '';
                        }
                        return Html::a(
                            '',
                            [
                                '/usuarios/anadir-libro',
                                'l' => $model->id
                            ],
                            [
                                'class' => 'fas fa-plus',
                                'title' => 'Añadir libro a tu lista',
                                'style' => [
                                    'color' => 'LimeGreen',
                                ],
                            ]
                        );
                    },
                ]
            ],
        ]
    ]); ?>


</div>
