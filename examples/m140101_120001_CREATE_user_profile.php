<?php

class m140101_120001_CREATE_user_profile extends \iiifx\Exmig\Migration {

    # Название таблицы связанной сущности UserProfile
    public $tableName = 'user_profile';

    public function safeUp () {
        # Создаем таблицу для сущности UserProfile
        $this->createEntityTable( [
            # Используется сандартный функционал yii\db\Migration
            'user_id'    => "INT(11) NULL DEFAULT NULL",
            'first_name' => "VARCHAR(128) NOT NULL DEFAULT ''",
            'last_name'  => "VARCHAR(128) NOT NULL DEFAULT ''",
            'date_birth' => "DATE NOT NULL",
        ] );
        # Создаем FK индекс для поля user_id, который ссылается на id таблицы user
        $this->createForeignKey( 'user', 'user_id' );
    }

    public function safeDown () {
        # При откате уничтожаем текущую таблицу
        $this->dropCurrentTable();
    }

}