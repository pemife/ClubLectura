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

    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table table-striped mt-4">
    
        <tr>
            <th>Libros</th>
        </tr>
    
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'itemView' => function ($model, $key, $index, $widget) { ?>
                <tr>
                    <td>
                        <p><?= $model->libro->titulo ?></p>
                    </td>
                </tr>
            <?php }
        ]); ?>

    </table>

</div>