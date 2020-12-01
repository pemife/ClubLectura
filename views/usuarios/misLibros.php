<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis libros';
$this->params['breadcrumbs'][] = $this->title;

$totalLibros = $dataProvider->count;
$url = Url::to(['usuarios/ordenar-lista-libros']);
$url2 = Url::to(['usuarios/mis-libros']);
$uId = Yii::$app->user->identity->id;

$js = <<<SCRIPT

$(function() {
    $('#tablasOrden').hide();
    $('#botonGuardar').hide();

    $('#botonGuardar').click(function (e){
        e.preventDefault();
        if (nuevoOrden.length != $totalLibros) {
            alert('Primero ordena la lista completa!');
        } else {
            //AJAX con nuevo orden
            $.ajax({
                method: 'POST',
                url: '$url',
                data: {uId: $uId, nO: nuevoOrden},
                dataType: 'json',
                success: function(result){
                    console.log(result);
                    if (result) {
                        window.location = '$url2';
                    } else {
                        alert('No ha funcionado, intentalo de nuevo mas tarde');
                    }
                }
              });
        }
    });

    $('#listaLibros').sortable({
        update: function(e, ui){
            $('#botonGuardar').show();
            nuevoOrden = devolverNuevoOrden();
        }
    });
    
    $('#listaLibros').disableSelection();

    function devolverNuevoOrden(){
        var ordenLibros = new Array();
        var libros = $('.libro');

        for (var i = 0; i < libros.length; i++) {
            ordenLibros.push(libros[i].dataset.libroid);
        }

        return ordenLibros;
    }

});

SCRIPT;

$this->registerJS($js);
?>

<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" defer></script>

<div class="usuarios-mis-libros">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Puedes ordenar la lista arrastrando los libros</p>

    <?= Html::a('Guardar Orden', 'javascript:void(0)', ['class' => 'btn btn-success ml-2 mb-2', 'id' => 'botonGuardar', 'style' => 'display:none']) ?>

    <ul id="listaLibros">

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => function ($model, $key, $index, $widget) {
                ?>
                <li 
                class="row libro"
                data-libroId="<?= $model->libro->id ?>"
                data-libroOrden="<?= $model->orden?>"
                data-libroNombre="<?= $model->libro->titulo ?>"
                >
                    <div class="col-md-12">
                        <h3><?= Html::encode($model->libro->titulo) ?></h3>
                    </div>
                    <div class="row col-md-12">
                        <div class="col-md-2">
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
                </li>
                <?php
            },
        ]); ?>

        </ul>

</div>

