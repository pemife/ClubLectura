<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nombre
 * @property string $password
 * @property string $auth_key
 * @property string $telefono
 * @property string $poblacion
 * @property string $created_at
 * @property string|null $requested_at
 * @property string|null $token
 * @property string $email
 * @property string|null $biografia
 * @property string|null $fechanac
 *
 * @property Comentarios[] $comentarios
 * @property Criticas[] $criticas
 * @property Libros[] $libros
 * @property Peliculas[] $peliculas
 */
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'password', 'email'], 'required'],
            [['nombre', 'auth_key', 'telefono', 'poblacion'], 'string', 'max' => 255],
            [['created_at', 'requested_at', 'fechanac'], 'safe'],
            [['biografia'], 'string'],
            [['nombre', 'token'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 60],
            [['password', 'password_repeat'], 'required', 'on' => [self::SCENARIO_CREATE]],
            [['password'], 'compare', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['nombre'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'password' => 'Password',
            'created_at' => 'Created At',
            'requested_at' => 'Requested At',
            'token' => 'Token',
            'email' => 'Email',
            'biografia' => 'Biografia',
            'fechanac' => 'Fechanac',
        ];
    }

    /**
     * Gets query for [[Comentarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComentarios()
    {
        return $this->hasMany(Comentarios::class, ['usuario_id' => 'id'])->inverseOf('usuario');
    }

    /**
     * Gets query for [[Criticas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCriticas()
    {
        return $this->hasMany(Criticas::class, ['usuario_id' => 'id'])->inverseOf('usuario');
    }

    /**
     * Gets query for [[Libros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLibros()
    {
        return $this->hasMany(Libros::class, ['id' => 'libro_id'])->viaTable('seleccion', ['usuario_id' => 'id']);
    }

    /**
     * Gets query for [[Peliculas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeliculas()
    {
        return $this->hasMany(Peliculas::class, ['id' => 'pelicula_id'])->viaTable('criticas', ['usuario_id' => 'id']);
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @param null|mixed $type
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
    }

    /**
     * Validates password.
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert) {
            if ($this->scenario === self::SCENARIO_CREATE) {
                goto salto;
            }
        } elseif ($this->scenario === self::SCENARIO_UPDATE) {
            if ($this->password === '') {
                $this->password = $this->getOldAttribute('password');
            } else {
                salto:
                $this->password = Yii::$app->security
                    ->generatePasswordHash($this->password);
            }
        }
        return true;
    }

    /**
     * Esta función dirá si el usuario participa o no en la selección de libro
     *
     * @return bool
     */
    public function participaEnSeleccion()
    {
        return $this->libros->count == 5;
    }

    public function participantesId()
    {
        $pIds = [];
        
        foreach ($usuario in $this->findAll()) {
            
        }
    }
}
