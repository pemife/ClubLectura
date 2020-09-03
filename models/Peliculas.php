<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "peliculas".
 *
 * @property int $id
 * @property string $titulo
 * @property string $director
 * @property string $guionistas
 * @property string|null $productores
 * @property string|null $principales_actores
 * @property string|null $descripcion
 *
 * @property Comentarios[] $comentarios
 * @property Criticas[] $criticas
 * @property Usuarios[] $usuarios
 */
class Peliculas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peliculas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titulo', 'director', 'guionistas'], 'required'],
            [['principales_actores', 'descripcion'], 'string'],
            [['titulo', 'director', 'guionistas', 'productores'], 'string', 'max' => 255],
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
            'director' => 'Director',
            'guionistas' => 'Guionistas',
            'productores' => 'Productores',
            'principales_actores' => 'Principales Actores',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * Gets query for [[Comentarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::className(), ['pelicula_id' => 'id'])->inverseOf('pelicula');
    }

    /**
     * Gets query for [[Criticas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCriticas()
    {
        return $this->hasMany(Criticas::className(), ['pelicula_id' => 'id'])->inverseOf('pelicula');
    }

    /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuarios::className(), ['id' => 'usuario_id'])->viaTable('criticas', ['pelicula_id' => 'id']);
    }
}
