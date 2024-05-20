<?php

use yii\db\Schema;
use yii\db\Migration;

class m240510_112700_log_asignaciones_permiso extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%log_asignaciones_permiso}}',
            [
                'id'=> $this->primaryKey(11),
                'fecha_hora'=> $this->datetime()->notNull(),
                'usuario_accion'=> $this->integer(10)->unsigned()->notNull(),
                'item_name_accion_permiso'=> $this->string(64)->notNull(),
                'tipo_accion_permiso'=> $this->string(45)->notNull(),
                'item_name_modificado'=> $this->string(45)->notNull(),
            ],$tableOptions
        );
        $this->createIndex('log_asignaciones_permiso_FK','{{%log_asignaciones_permiso}}',['usuario_accion'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('log_asignaciones_permiso_FK', '{{%log_asignaciones_permiso}}');
        $this->dropTable('{{%log_asignaciones_permiso}}');
    }
}