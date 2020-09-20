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

    <script>
        var arrayLibros = document.getElementsByClassName("libros");
        console.log(arrayLibros);

        function seleccionar() {
            var numero = Math.floor((Math.random() * arrayLibros.length));
            alert(arrayLibros[numero].innerHTML);
            // console.log(arrayLibros[numero].innerHTML);
        }

        // var x = window.setInterval(seleccionar, 100);
    </script>

    <div class="row mb-4">
        <div class="col text-center">
            <button onclick="seleccionar()" class="btn btn-success">Elegir</button>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-6">
            <h3 class="libros">1. Viajera</h3>
            <h3 class="libros">2. Mujercitas</h3>
            <h3 class="libros">3. Orgullo y prejuicio</h3>
            <h3 class="libros">4. Harry Potter</h3>
            <h3 class="libros">5. Frankenstein</h3>
            <h3 class="libros">6. La isla del tesoro</h3>
            <h3 class="libros">7. Viaje al centro de la Tierra</h3>
            <h3 class="libros">8. 1984</h3>
            <h3 class="libros">9. Desayuno en Tiffany's</h3>
            <h3 class="libros">10. Peter Pan</h3>
        </div>
    
        <div class="col-md-6">
            <h3 class="libros">16. Dune</h3>
            <h3 class="libros">17. El aliento de los dioses</h3>
            <h3 class="libros">18. En el nombre de la rosa</h3>
            <h3 class="libros">19. La mano izquierda de la oscuridad</h3>
            <h3 class="libros">20. Que difícil es ser dios</h3>
        </div>

    </div>

</div>