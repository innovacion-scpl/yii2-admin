<?php

use yii\db\Schema;
use yii\db\Migration;

class m240510_112947_log_asignaciones_usuario extends Migration
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
            '{{%log_asignaciones_usuario}}',
            [
                'id'=> $this->primaryKey(11),
                'fecha_hora'=> $this->datetime()->notNull(),
                'usuario_accion'=> $this->integer(10)->unsigned()->notNull(),
                'usuario_asignado'=> $this->integer(10)->unsigned()->notNull(),
                'item_name'=> $this->string(64)->notNull(),
                'tipo_accion_permiso'=> $this->string(45)->notNull(),
            ],$tableOptions
        );
        $this->createIndex('log_asignaciones_usuario_FK','{{%log_asignaciones_usuario}}',['usuario_accion'],false);
        $this->createIndex('log_asignaciones_usuario_FK_1','{{%log_asignaciones_usuario}}',['usuario_asignado'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('log_asignaciones_usuario_FK', '{{%log_asignaciones_usuario}}');
        $this->dropIndex('log_asignaciones_usuario_FK_1', '{{%log_asignaciones_usuario}}');
        $this->dropTable('{{%log_asignaciones_usuario}}');
    }
}