<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'BookFinder';
$this->params['breadcrumbs'][] = $this->title;

$js = <<<SCRIPT
// ESTO ES LO QUE QUIERO HACER PERO NO FUNCIONA CON AJAX, A VER SI PUEDO CON PHP

// https://stackoverflow.com/questions/18349130/how-to-parse-html-in-php

// $('#textoBusqueda').keyup(function(){
//     var textoInput = this.value;
//     if (textoInput.length < 3) {
//         console.log('No es mayor de 3 caracteres');
//     } else {
//         // TODO: Esperar 2/3 segundos para evitar spam de eventos keyUp

//         // var textoFormateado = textoInput.replace(' ', '+');
//         recogerAjax(textoInput);
//     }
// });

// function recogerAjax(texto){
//     $.ajax({
//         method: 'GET',
//         url: 'https://isbndb.com/search/books/' + texto,
//         success: function(result){
//             if (result) {
//                 $('#contenidoRespuesta').html(result);
//             } else {
//                 alert('lo kavenio, avenio vasío oye');
//             }
//         },
//         error: function() {alert('a fallao')}
//     });
// }
SCRIPT;

$this->registerJs($js);
?>

<div class="libros-finder">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::input('text', 'libroTexto', null, [
        'class' => 'mt-2',
        'placeholder' => 'Escribe ISBN/Título/Autor',
        'id' => 'textoBusqueda',
    ]) ?>

    <div class="col-md-12 text-center" id="contenidoRespuesta">
    
        <p>Escribe al menos 3 caracteres</p>
    
    </div>

</div>