<?php

# Предполагается использование сущности User
use common\models\entities\User;

class m150101_130000_INSERT_user extends \iiifx\Exmig\Seeder {

    # Список данных, на основе которых производится наполнение
    public $dataList = [
        [ 'admin@admin.com', 'adminpassword', 1 ],
        [ 'user@user.com', 'userpassword', 1 ],
        # ...
    ];

    public function safeUp () {
        # Поочередно заполняем данными
        foreach ( $this->dataList as $entityData ) {
            list( $email, $password, $isActive ) = $entityData;
            $user = new User();
            $user->email = $email;
            $user->setPassword( $password );
            $user->is_active = $isActive;
            $this->showResult( $user->save() );
        }
        $this->output();
    }

    public function safeDown () {
        # Удаляем все данные при откате
        User::deleteAll();
    }

}