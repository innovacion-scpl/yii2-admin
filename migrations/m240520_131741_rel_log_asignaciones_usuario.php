<?php

use yii\db\Schema;
use yii\db\Migration;

class m240520_131741_rel_log_asignaciones_usuario extends Migration
{

    public function init()
    {
       $this->db = 'db';
       parent::init();
    }

    public function safeUp()
    {
        $this->addForeignKey('fk_log_asignaciones_usuario_usuario_accion',
            '{{%log_asignaciones_usuario}}','usuario_accion',
            '{{%user}}','id',
            'RESTRICT','RESTRICT'
         );
        $this->addForeignKey('fk_log_asignaciones_usuario_usuario_asignado',
            '{{%log_asignaciones_usuario}}','usuario_asignado',
            '{{%user}}','id',
            'RESTRICT','RESTRICT'
         );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_log_asignaciones_usuario_usuario_accion', '{{%log_asignaciones_usuario}}');
        $this->dropForeignKey('fk_log_asignaciones_usuario_usuario_asignado', '{{%log_asignaciones_usuario}}');
    }
}