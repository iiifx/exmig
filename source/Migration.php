<?php

namespace iiifx\Exmig;

use Yii;
use yii\base\ErrorException;

class Migration extends migration\BaseMigration {

    /**
     * Название таблицы, должно быть указано в миграции
     *
     * @var string
     */
    public $tableName;

    /**
     * Шаблон определения названия таблицы с установленным шаблоном префикса
     *
     * @var string
     */
    public $tableNamePattern = '/{{%[a-zA-Z0-9-_]{1,64}}}/';

    /*
     *** Внутренние хелперы ***
     */

    /**
     * Получить название таблицы без шаблона префикса
     *
     * @return string
     * @throws ErrorException
     */
    protected function getTableName () {
        if ( is_string( $this->tableName ) && $this->tableName ) {
            return $this->tableName;
        }
        throw new ErrorException( '{$this->tableName} must contain the name of the table' );
    }

    /**
     * Получить название таблицы с шаблоном префикса
     *
     * @return string
     * @throws ErrorException
     */
    protected function getTableNameWithPrefix () {
        return $this->buildTableNameWithPrefix( $this->getTableName() );
    }

    /**
     * Создать название таблицы с шаблоном префикса
     *
     * @param string $tableName
     *
     * @return string
     * @throws ErrorException
     */
    protected function buildTableNameWithPrefix ( $tableName = '' ) {
        if ( is_string( $tableName ) && $tableName ) {
            $matches = preg_match( $this->tableNamePattern, $tableName );
            if ( $matches === 0 ) {
                return "{{%{$tableName}}}";
            } elseif ( $matches === 1 ) {
                return $tableName;
            }
            throw new ErrorException( 'Incorrect template: {$this->tableNamePattern}' );
        }
        throw new ErrorException( 'The table name must be a string and must not be empty' );
    }

    /*
     *** Работа с индексами ***
     */

    /**
     * Создать индекс
     *
     * @param string|array $fieldNames
     */
    public function createIndexKey ( $fieldNames ) {
        $fieldNames = (array) $fieldNames;
        $indexName = 'K_' . implode( '_', $fieldNames );
        $this->createIndex( $indexName, $this->getTableNameWithPrefix(), $fieldNames, FALSE );
    }

    /**
     * Создать уникальный индекс
     *
     * @param string|string $fieldNames
     */
    public function createUniqueKey ( $fieldNames ) {
        $fieldNames = (array) $fieldNames;
        $indexName = 'U_' . implode( '_', $fieldNames );
        $this->createIndex( $indexName, $this->getTableNameWithPrefix(), $fieldNames, TRUE );
    }

    /**
     * Создать FK индекс, внешний ключ
     *
     * @param string $referenceTable
     * @param string $currentField
     * @param string $referenceField
     * @param string $deleteType
     * @param string $updateType
     *
     * @throws ErrorException
     */
    public function createForeignKey ( $referenceTable, $currentField, $referenceField = 'id', $deleteType = 'CASCADE', $updateType = 'CASCADE' ) {
        $this->addForeignKey(
            $this->buildForeignKeyName( $referenceTable, $currentField ),
            $this->getTableNameWithPrefix(), $currentField,
            $this->buildTableNameWithPrefix( $referenceTable ), $referenceField,
            $deleteType, $updateType
        );
    }

    /*
     *** Работа с названиями ***
     */

    /**
     * Создать имя FK ключа
     *
     * @param string $referenceTable
     * @param string $fieldName
     *
     * @return string
     */
    protected function buildForeignKeyName ( $referenceTable, $fieldName ) {
        # Формируем название индекса
        $name = "FK_{$this->getTableName()}_2_{$referenceTable}__{$fieldName}";
        # Проверяем длину, она не должна быть больше 64 символов
        if ( strlen( $name ) <= 64 ) {
            return $name;
        }
        # Генерируем короткий вариант, с хэшем вместо полного названия таблиц
        return 'FK_' . hash( 'crc32', "FK_{$this->getTableName()}_2_{$referenceTable}__{$fieldName}" );
    }

    /*
     *** Работа с таблицами ***
     */

    /**
     * Создать таблицу сущности
     *
     * @param array $customFieldList
     * @param bool  $addSortingPosition
     */
    public function createEntityTable ( $customFieldList = [], $addSortingPosition = FALSE ) {
        $fieldList = [ 'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY' ];
        $fieldList = array_merge( $fieldList, $customFieldList );
        if ( $addSortingPosition ) {
            $fieldList[ 'sorting_position' ] = 'INT(11) UNSIGNED NOT NULL DEFAULT 0';
        }
        $fieldList[ 'date_created' ] = 'TIMESTAMP NOT NULL DEFAULT NOW()';
        $fieldList[ 'date_edited' ] = "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE NOW()";
        $this->createTable( $this->getTableNameWithPrefix(), $fieldList, "COLLATE='utf8_general_ci' ENGINE=InnoDB" );
        $this->createIndexKey( [ 'date_created', 'date_edited' ] );
    }

    /**
     * Уничтожить текущую таблицу
     */
    public function dropCurrentTable () {
        $this->dropTable( $this->getTableNameWithPrefix() );
    }

}