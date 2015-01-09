<?php

namespace ExMig;

use Yii;
use yii\base\ErrorException;

class Seeder extends migration\BaseMigration {

    public function init () {
        /*
         * Пока не использовать, до реализации
         */
        throw new ErrorException( 'Is not implemented' );
    }

    /*
     * В данный момент функционал не реализован
     *
     * @TODO Реализовать функционал для упрощенного наполнения\удаления данных с таблиц
     * @TODO Закрыть доступ ко всем методам миграций, которые могут повлиять на структуру
     */

}