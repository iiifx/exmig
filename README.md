# Exmig - Yii2 Extended Migration

## Расширение базовой миграции Yii2 для упрощения работы с миграциями.

Миграции в Exmig построены по принципу: 1 миграция = 1 таблица. Не допускается использование нескольких таблиц в одной миграции, из-за структуры приложения.

Рекомендуется отделить сидинг - заполнение и модификацию данных в таблице, от манипуляции со структурой таблицы.

Рекомендации по именованию миграций:
------------------------------------

```
m000000_000000_CREATE_table_name.php - Создание структуры таблицы
m000000_000000_ALTER_table_name.php - Изменение структуры таблицы
m000000_000000_INSERT_table_name.php - Сидинг, заполнение таблицы данными
m000000_000000_UPDATE_table_name.php - Сидинг, изменения данных таблицы
```

где

```
m000000_000000 - временная метка миграции
table_name - название таблицы
```

Пример миграции:
----------------

```php
class m141007_065000_CREATE_language extends Exmig\Migration {

    public $tableName = 'language'; # Название таблицы, с которой работает миграция

    public function up () {
        $this->createEntityTable( [ # Создаем таблицу сущности
            'title'      => "VARCHAR(255) NOT NULL DEFAULT ''",
            'code'       => "VARCHAR(5) NOT NULL DEFAULT ''",
            'segment'    => "VARCHAR(24) NOT NULL DEFAULT ''",
            'is_default' => "INT(1) NOT NULL DEFAULT 0",
            'is_active'  => "INT(1) NOT NULL DEFAULT 1",
        ] );
        $this->createIndexKey( 'is_active' ); # Создаем индекс для is_active
        $this->createIndexKey( 'is_default' ); # Создаем индекс для is_default
    }

    public function down () {
        $this->dropCurrentTable(); # Уничтожаем текущую таблицу
    }

}
```


Пример и описание методов:
--------------------------

##### Exmig\Migration::createEntityTable ( $customFieldList = [] )

Создание таблицы сущности, в которой автоматически создается PK и TIMESTAMPS.

К примеру, при использовании:
```php
$this->createEntityTable( [
    'string' => "VARCHAR(255) NOT NULL DEFAULT ''",
] );
```

будет создана таблица с такой структурой:
```sql
CREATE TABLE `fd_language` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `string` VARCHAR(255) NOT NULL DEFAULT '',
    `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_edited` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1;
```

* Использование двух TIMESTAMP в одной таблице требует версию MySQL не ниже 5.6
* Миграция также использует системные настройки, потому имя таблицы было создано как "fd_language" с использованием префикса для имен таблиц "fd_"


##### Exmig\Migration::dropCurrentTable ()

Быстрое удаление текущей таблицы

```php
$this->dropCurrentTable();
```

##### Exmig\Migration::createIndexKey ( $fieldName )

Быстрое создание индекса для поля текущей таблицы. Название идекса формируется автоматически в формате "K_fieldName"

```php
$this->createIndexKey( 'is_active' );
```

##### Exmig\Migration::createUniqueKey ( $fieldName )

Быстрое создание уникального индекса для поля текущей таблицы. Название идекса формируется автоматически в формате "U_fieldName"

```php
$this->createUniqueKey( 'post_id' );
```

##### Exmig\Migration::createForeignKey ( $referenceTable, $currentField, $referenceField = 'id', $deleteType = 'CASCADE', $updateType = 'CASCADE' )

Быстрое создание внешнего ключа для текущей таблицы. Название идекса формируется автоматически в формате "FK_tableName_2_referenceTable__fieldName".

Пример быстрого использования:
```php
$this->createForeignKey( 'user', 'user_id' );
```

Пример использования с изменением параметров:
```php
$this->createForeignKey( 'category', 'category_id', 'cat_id', 'SET NULL', 'CASCADE' );
```

Общие методы вывода в консоль:
------------------------

##### ::output ( $string = NULL )

Производит вывод строки в консоль. Строка автоматически дополняется переходом на новую

```php
$this->output( 'Message...' );
```

##### ::stdout ( $string )

Производит вывод строки в консоль

```php
$this->stdout( 'Hello' );
$this->stdout( ' ' );
$this->stdout( 'world' );
```
