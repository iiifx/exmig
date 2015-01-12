<?php

class m141007_065601_CREATE_user extends \Exmig\Migration {

    public $tableName = 'user';

    public function safeUp () {
        $this->createEntityTable( [
            'email'                => "VARCHAR(128) NOT NULL DEFAULT ''",
            'password_hash'        => "VARCHAR(128) NOT NULL DEFAULT ''",
            'date_password_change' => "TIMESTAMP NOT NULL",
            'date_last_visit'      => "TIMESTAMP NOT NULL",
            'date_blocked_to'      => "TIMESTAMP NOT NULL",
            'is_active'            => "TINYINT(1) NOT NULL DEFAULT 1",
        ] );
        $this->createUniqueKey( 'email' );
        $this->createIndexKey( 'is_active' );
    }

    public function safeDown () {
        $this->dropCurrentTable();
    }

}