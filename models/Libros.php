<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "libros".
 *
 * @property int $id
 * @property string $titulo
 * @property string $autor
 * @property string|null $editorial
 * @property float $isbn
 * @property string|null $fecha_publicacion
 * @property string|null $fecha_1a_edicion
 * @property string|null $descripcion
 * @property float|null $n_paginas
 *
 * @property Comentarios[] $comentarios
 * @property Criticas[] $criticas
 * @property Usuarios[] $usuarios
 */
class Libros extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'libros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titulo', 'autor', 'isbn'], 'required'],
            [['isbn', 'n_paginas'], 'number'],
            [['fecha_publicacion', 'fecha_1a_edicion'], 'safe'],
            [['descripcion'], 'string'],
            [['titulo', 'editorial'], 'string', 'max' => 255],
            [['autor'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'autor' => 'Autor',
            'editorial' => 'Editorial',
            'isbn' => 'Isbn',
            'fecha_publicacion' => 'Fecha Publicacion',
            'fecha_1a_edicion' => 'Fecha 1a Edicion',
            'descripcion' => 'Descripcion',
            'n_paginas' => 'N Paginas',
        ];
    }

    /**
     * Gets query for [[Comentarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::class, ['libro_id' => 'id'])->inverseOf('libro');
    }

    /**
     * Gets query for [[Criticas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCriticas()
    {
        return $this->hasMany(Criticas::class, ['libro_id' => 'id'])->inverseOf('libro');
    }

    /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::class, ['id' => 'usuario_id'])->viaTable('criticas', ['libro_id' => 'id']);
    }
}
