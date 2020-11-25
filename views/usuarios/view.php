<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$esAdmin = $tienePermisos = false;

if (!Yii::$app->user->isGuest) {
    
    $esAdmin = Yii::$app->user->identity->id === 1;
    
    $tienePermisos = $esAdmin || (Yii::$app->user->identity->id === $model->id);
}
?>
<div class="usuarios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($tienePermisos) : ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nombre',
            'created_at',
            'email:email',
            'biografia:ntext',
            'fechanac',
        ],
    ]) ?>

</div>
