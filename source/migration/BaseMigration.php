<?php

namespace iiifx\Exmig\migration;

use yii\db\Migration;
use yii\helpers\Console;

class BaseMigration extends Migration {

    /**
     * Вывод строки в консоль
     *
     * @param string|null $string
     *
     * @return bool|int
     */
    public function output ( $string = NULL ) {
        return Console::output( $string );
    }

    /**
     * Вывод в консоль
     *
     * @param string $string
     *
     * @return bool|int
     */
    public function stdout ( $string ) {
        return Console::stdout( $string );
    }

}