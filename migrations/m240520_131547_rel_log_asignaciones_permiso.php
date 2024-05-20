<?php

use yii\db\Schema;
use yii\db\Migration;

class m240520_131547_Relations extends Migration
{

    public function init()
    {
       $this->db = 'db';
       parent::init();
    }

    public function safeUp()
    {
        $this->addForeignKey('fk_log_asignaciones_permiso_usuario_accion',
            '{{%log_asignaciones_permiso}}','usuario_accion',
            '{{%user}}','id',
            'RESTRICT','RESTRICT'
         );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_log_asignaciones_permiso_usuario_accion', '{{%log_asignaciones_permiso}}');
    }
}