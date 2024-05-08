<?php

namespace mdm\admin\models;


use Yii;

/**
 * This is the model class for table "log_asignaciones".
 *
 * @property int $id
 * @property string|null $fecha_hora
 * @property int|null $usuario_accion
 * @property int|null $usuario_asignado
 * @property string $item_name
 * @property int|null $tipo_accion_permiso
 */
class LogAsignacionesUsuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_asignaciones_usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora'], 'safe'],
            [['usuario_accion', 'usuario_asignado'], 'integer'],
            [['item_name'], 'required'],
            [['tipo_accion_permiso'], 'string', 'max' => 45],
            [['item_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha_hora' => 'Fecha Hora',
            'usuario_accion' => 'Usuario Accion',
            'usuario_asignado' => 'Usuario Asignado',
            'item_name' => 'Item Name',
            'tipo_accion_permiso' => 'Tipo Accion Permiso',
        ];
    }
}
