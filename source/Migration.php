<?php

namespace ExMig;

use Yii;

class Migration extends migration\BaseMigration {

    /**
     * Название таблицы, без префикса. Указывается в миграции
     *
     * @var string
     */
    protected $tableName;

    /**
     * Префикс таблиц, с конфига приложения. Заполняется автоматически
     *
     * @var string
     */
    protected $tablePrefix;

    /**
     * Полное название таблицы, с префиксом. Заполняется автоматически
     *
     * @var string
     */
    protected $tableNameFull;

    /**
     * Манипуляции с данными перед стартом миграции
     *
     * @throws \Exception
     */
    public function init () {
        parent::init();
        $this->tablePrefix = ( Yii::$app->db->tablePrefix ) ? Yii::$app->db->tablePrefix : '';
        $this->tableNameFull = "{$this->tablePrefix}{$this->tableName}";
    }

    /**
     * Хелпер для создания названия FK индекса
     *
     * @param string $referenceTable
     *
     * @return string
     */
    public function buildForeignKeyName ( $referenceTable ) {
        return "FK_{$this->tableNameFull}_2_{$this->tablePrefix}{$referenceTable}";
    }

    /**
     * Хелпер для создания расширенного названия FK индекса, с участием названия поля
     *
     * @param string $referenceTable
     * @param string $fieldName
     *
     * @return string
     */
    public function buildForeignKeyExtraName ( $referenceTable, $fieldName ) {
        # Формируем название внешнего ключа
        $name = "FK_{$this->tableNameFull}_2_{$this->tablePrefix}{$referenceTable}__{$fieldName}";
        # Проверяем длину
        if ( strlen( $name ) <= 64 ) {
            return $name;
        }
        # Генерируем короткий вариант
        $nameHashTail = "FK_{$this->tableNameFull}_2_{$this->tablePrefix}{$referenceTable}__" . hash( 'crc32', $fieldName );
        # Проверяем длину
        if ( strlen( $nameHashTail ) <= 64 ) {
            return $nameHashTail;
        }
        # Оба варианта слишком длинные, делам самый короткий вариант
        return 'FK_' . hash( 'crc32', "{$this->tableNameFull}_2_{$this->tablePrefix}{$referenceTable}__{$fieldName}" );
    }

    /**
     * Хелпер для быстрого создания FK индекса
     *
     * @param string $referenceTable
     * @param string $currentField
     * @param string $referenceField
     * @param string $deleteType
     * @param string $updateType
     *
     * @internal param string $type
     */
    public function createForeignKey ( $referenceTable, $currentField, $referenceField = 'id', $deleteType = 'CASCADE', $updateType = 'CASCADE' ) {
        $this->addForeignKey(
            $this->buildForeignKeyExtraName( $referenceTable, $currentField ),
            "{{%{$this->tableName}}}", $currentField,
            "{{%{$referenceTable}}}", $referenceField,
            $deleteType, $updateType
        );
    }

    /**
     * Хелпер для быстрого создания KEYиндекса
     *
     * @param string|array $fieldName
     */
    public function createIndexKey ( $fieldName ) {
        if ( is_array( $fieldName ) ) {
            $indexName = implode( '_', $fieldName );
        } else {
            $indexName = $fieldName;
        }
        $this->createIndex( "KEY_{$indexName}", "{{%{$this->tableName}}}", $fieldName, FALSE );
    }

    /**
     * Хелпер для быстрого создания UNIQUE индекса
     *
     * @param string $fieldName
     */
    public function createUniqueKey ( $fieldName ) {
        $this->createIndex( "UNIQUE_{$fieldName}", "{{%{$this->tableName}}}", $fieldName, TRUE );
    }

    /**
     * Расширенный вариант создания таблицы. Сам добавляет ID и DATE_*, а также utf8_general_ci и InnoDB
     *
     * @param array $fieldList
     */
    public function createEntityTable ( $fieldList ) {
        $fieldListFull = array (
            'id' => 'pk'
        );
        foreach ( $fieldList as $fieldName => $fieldParams ) {
            $fieldListFull[ $fieldName ] = $fieldParams;
        }
        $fieldListFull[ 'date_created' ] = "TIMESTAMP NOT NULL DEFAULT NOW()";
        $fieldListFull[ 'date_edited' ] = "TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE NOW()";
        $this->createTable( "{{%{$this->tableName}}}", $fieldListFull, "
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB"
        );
    }

    /**
     * Быстрый вариант дропа таблицы
     */
    public function dropCurrentTable () {
        $this->dropTable( "{{%{$this->tableName}}}" );
    }

}