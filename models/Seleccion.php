<?php

namespace app\models;

use Yii;

/**
 * Esta es la clase modelo para la tabla seleccion
 *
 * @property float $orden
 * @property int $usuario_id
 * @property int $libro_id
 */
class Seleccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seleccion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orden', 'usuario_id', 'libro_id'], 'required'],
            [['orden'], 'number'],
            [['usuario_id', 'libro_id'], 'default', 'value' => null],
            [['usuario_id', 'libro_id'], 'integer'],
            [['usuario_id', 'libro_id'], 'unique', 'targetAttribute' => ['usuario_id', 'libro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'orden' => 'Orden',
            'usuario_id' => 'Usuario ID',
            'libro_id' => 'Libro ID',
        ];
    }

    /**
     * Gets query for [[Libros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libros::class, ['id' => 'libro_id']);
    }

    /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::class, ['id' => 'usuario_id']);
    }
}
