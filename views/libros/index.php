<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Libros';
$this->params['breadcrumbs'][] = $this->title;

//Yii::debug($selecsId);

$url = Url::to(['usuarios/anadir-libro']);

$js = <<<SCRIPT
$("[name='botonAnadir']").click(anadirLista);

function anadirLista(e){
    e.preventDefault();
    $.ajax({
        method: 'GET',
        url: '$url',
        data: {l: this.dataset.libroid},
        success: function (result) {
            if (result) {
                alert(result);
            } else {
                alert('Ha ocurrido un error al añadir a la lista');
            }
        }, 
    });
    
}
SCRIPT;

$this->registerJS($js);

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
                        if (Yii::$app->user->isGuest) return '';

                        return Html::a(
                            '',
                            'javascript:void(0)',
                            [
                                'data' => [
                                    'libroid' => $model->id,
                                ],
                                'class' => 'fas fa-plus',
                                'title' => 'Añadir libro a tu lista',
                                'name' => 'botonAnadir',
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
