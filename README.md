# LoggerOne
One Efficient & Light & Simple high performance PHP log implemention of PSR-3

## 特性/Features
Inherently cached message record using PHP object attribute.

## 安装&使用/Istall&Usage
### Install

```shell
$ composer require logger-one/logger-one
```

### Invoke by default mode 
#### Will using `FileHandler` create a log file named for `%Y%m%d` and appendix `.log` at `LoggerOne` root folder, ervery message as line with the original

```php
$logger = LoggerOne\Logger::getInstance();  // Strongly recommend

$message = 'test log message {placeholder1} {placeholder2}';

$replacements = ['placeholder1' => 'Foo', 'placeholder2' => 'bar'];

$logger->info($message, $replacements);
```
#### Change `FileHandler` default path

```php
$logFile = './tmp/loggerOne.log'; // Your path where you want

$handler = new LoggerOne\Handler\FileHandler($logFile);

$logger->setHandler($handler);

$logger->info($message);
```

### Specific Handler & Formatter

```php

$handler = new LoggerOne\Handler\FooHandler();

$handler = new LoggerOne\Formatter\BarFormatter();

$logger->setHandler($handler)->setFormatter($formatter);

$logger->info('some test log message');
```

### Flush log message immediately
#### Once calling `flush` method will write all previous message by handler

```php
...
$logger->info('some test log message')->flush();
...
```

## 定制&扩展/Customization&Extending

### Handler Extension Simple
#### Put your own `MySQLHandler.php` in `LoggerOne\Handler` folder 

```php
<?php
namespace LoggerOne\Handler;

class MySQLHandler implements Handler
{
    protected $db;
    
    protected $table_name;
    
    
    public function __construct(string $table_name, object $dbHandle = null)
    {
        $this->table_name = $table_name;
        $this->db = $dbHandle;
    }
    
    public function write($messages)
    {
        $batchData = [];
        foreach ($messages as $level => $message) {
            $batchData[] = ['level' => $level, 'message' => $message, 'created' => time()];
        }
        $this->db->insertBatch($this->table_name, $batchData);
    }
}
```

#### Use `MySQLHandler.php`

```php
$logger = new LoggerOne\Logger();

$handler = new LoggerOne\Handler\MysqlHandler($yourLogTableName, $yourDbHandle);

$logger->setHandler($handler);

$logger->info('some test log message');
```

### Formatter Extension Simple
#### The way just like Handler extending

## 注意/Notice
- If you have not using composer, should remember load all dependency class in your way.
- Try to keep your handler write in batches for performace
