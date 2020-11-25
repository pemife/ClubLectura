<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;

$this->title = 'My Yii Application';
$css = <<<CSS
    .portada {
        width: 30%;
        height: 30%;
    }
CSS;
$this->registerCSS($css);
?>
<div class="site-index">

    <div class="jumbotron">
        <img class="portada" src="https://i.ibb.co/HgmxpMB/aurin.png" alt="aurin">
        <p class="lead mt-2 font-weight-bold">Bienvenido a nuestro club de lectura</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-md-12 text-center">
                <?= Html::a('Lista de libros propuestos', ['/libros/seleccion'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>

    </div>
</div>
