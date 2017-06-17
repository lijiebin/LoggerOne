<?php
namespace LoggerOne\Handler;

use LoggerOne\LoggerOneException;

class FileHandler implements Handler
{
    private $_logFullName;
    
    public function __construct(string $logFullName = null, object $operator = null)
    {
        if ( ! $logFullName) {
            $path = dirname(dirname(__FILE__));
            $name = date('Ymd') . '.log';
        }
        $this->_logFullName = $path . DIRECTORY_SEPARATOR . $name;
    }
    
    public function write(array $messages = [])
    {
        $contents = '';
        foreach ($messages as $level => $message) {
            $contents .= implode("\r\n", $message) . "\r\n";
        }
        file_put_contents($this->_logFullName, $contents, FILE_APPEND);
    }
}