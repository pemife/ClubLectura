<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comentarios".
 *
 * @property int $id
 * @property string $created_at
 * @property string $texto
 * @property int|null $usuario_id
 * @property int $pelicula_id
 * @property int $libro_id
 *
 * @property Libros $libro
 * @property Peliculas $pelicula
 * @property Usuarios $usuario
 */
class Comentarios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comentarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['texto', 'pelicula_id', 'libro_id'], 'required'],
            [['texto'], 'string'],
            [['usuario_id', 'pelicula_id', 'libro_id'], 'default', 'value' => null],
            [['usuario_id', 'pelicula_id', 'libro_id'], 'integer'],
            [['libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Libros::class, 'targetAttribute' => ['libro_id' => 'id']],
            [['pelicula_id'], 'exist', 'skipOnError' => true, 'targetClass' => Peliculas::class, 'targetAttribute' => ['pelicula_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'texto' => 'Texto',
            'usuario_id' => 'Usuario ID',
            'pelicula_id' => 'Pelicula ID',
            'libro_id' => 'Libro ID',
        ];
    }

    /**
     * Gets query for [[Libro]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libros::class, ['id' => 'libro_id'])->inverseOf('comentarios');
    }

    /**
     * Gets query for [[Pelicula]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPelicula()
    {
        return $this->hasOne(Peliculas::class, ['id' => 'pelicula_id'])->inverseOf('comentarios');
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::class, ['id' => 'usuario_id'])->inverseOf('comentarios');
    }
}
