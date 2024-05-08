<?php

namespace mdm\admin\models;


use Yii;

/**
 * This is the model class for table "log_asignaciones_permiso".
 *
 * @property int $id
 * @property string $fecha_hora
 * @property int $usuario_accion
 * @property string $item_name
 * @property string $tipo_accion_permiso
 */
class LogAsignacionesPermiso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_asignaciones_permiso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'usuario_accion', 'item_name_accion_permiso','item_name_modificado', 'tipo_accion_permiso'], 'required'],
            [['usuario_accion'], 'integer'],
            [['fecha_hora'], 'safe'],
            [['item_name_accion_permiso','item_name_modificado'], 'string', 'max' => 64],
            [['tipo_accion_permiso'], 'string', 'max' => 45],
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
            'item_name_accion_permiso' => 'Item Name',
            'tipo_accion_permiso' => 'Tipo Accion Permiso',
        ];
    }
}
