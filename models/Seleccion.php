<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seleccion".
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
}
