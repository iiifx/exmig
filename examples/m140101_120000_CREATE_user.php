<?php

class m140101_120000_CREATE_user extends \iiifx\Exmig\Migration {

    # Название таблицы сущности User
    public $tableName = 'user';

    public function safeUp () {
        # Создаем таблицу для сущности User
        $this->createEntityTable( [
            # Используется сандартный функционал yii\db\Migration
            'email'                => "VARCHAR(128) NOT NULL DEFAULT ''",
            'password_hash'        => "VARCHAR(128) NOT NULL DEFAULT ''",
            'date_password_change' => "TIMESTAMP NOT NULL",
            'date_last_visit'      => "TIMESTAMP NOT NULL",
            'date_blocked_to'      => "TIMESTAMP NOT NULL",
            'is_active'            => "TINYINT(1) NOT NULL DEFAULT 1",
        ] );
        # Создаем UNIQUE индекс для поля email
        $this->createUniqueKey( 'email' );
        # Или комплексный UNIQUE индекс
        //$this->createUniqueKey( [ 'field_1', 'field_2' ] );
        # Создаем KEY индекс для поля is_active
        $this->createIndexKey( 'is_active' );
        # Или комплексный KEY индекс
        //$this->createIndexKey( [ 'field_1', 'field_2' ] );
    }

    public function safeDown () {
        # При откате уничтожаем текущую таблицу
        $this->dropCurrentTable();
    }

}