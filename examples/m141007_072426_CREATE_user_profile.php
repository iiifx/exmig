<?php

class m141007_072426_CREATE_user_profile extends \Exmig\Migration {

    public $tableName = 'user_profile';

    public function safeUp () {
        $this->createEntityTable( array (
            'user_id'      => "INT(11) NULL DEFAULT NULL",
            'first_name'   => "VARCHAR(128) NOT NULL DEFAULT ''",
            'last_name'    => "VARCHAR(128) NOT NULL DEFAULT ''",
            'date_birth'   => "DATE NOT NULL",
        ) );
        $this->createForeignKey( 'user', 'user_id' );
    }

    public function safeDown () {
        $this->dropCurrentTable();
    }

}
