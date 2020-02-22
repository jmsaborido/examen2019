<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "citas".
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $especialista_id
 * @property string $instante
 *
 * @property Especialistas $especialista
 * @property Usuarios $usuario
 */
class Citas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'citas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'especialista_id', 'instante'], 'required'],
            [['usuario_id', 'especialista_id'], 'default', 'value' => null],
            [['usuario_id', 'especialista_id'], 'integer'],
            [['instante'], 'safe'],
            [['especialista_id'], 'exist', 'skipOnError' => true, 'targetClass' => Especialistas::className(), 'targetAttribute' => ['especialista_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_id' => 'Usuario ID',
            'especialista_id' => 'Especialista ID',
            'instante' => 'Instante',
        ];
    }
    public function attributes()
    {
        return array_merge(parent::attributes(), ['especialista.nombre']);
    }

    /**
     * Gets query for [[Especialista]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEspecialista()
    {
        return $this->hasOne(Especialistas::className(), ['id' => 'especialista_id'])->inverseOf('citas');
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('citas');
    }
}
