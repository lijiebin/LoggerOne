<?php
namespace LoggerOne;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use LoggerOne\Handler\Handler;
use LoggerOne\Handler\FileHandler;
use LoggerOne\Formatter\CommonFormatter;
use LoggerOne\Formatter\Formatter;

class Logger extends AbstractLogger
{
    private $_levels = [];
   
    private $_messages = [];
    
    private $_handler;
    
    private $_formatter;
    
    static private $_instance;
    
    public function __construct(Handler $handler = null, $formatter = null)
    {
        $refl = new \ReflectionClass('Psr\Log\LogLevel');
        $this->_levels = array_values($refl->getConstants());
        $this->_handler = $handler ? $handler : new FileHandler();
        $this->_formatter = $formatter ? $formatter : new CommonFormatter();
    }
    
    static public function getInstance()
    {
        if ( ! self::$_instance) {
            self::$_instance = new self;
        }
       
        return self::$_instance;
    }
    
    private function _validLevel($level = 0)
    {
        if ( ! in_array($level, $this->_levels) ) {
            throw new LoggerOneException("Unkonw log level given: {$level}");
        }
    }
    
    public function setHandler(Handler $handler)
    {
        $this->_handler = $handler;
        return $this;
    }
    
    public function setFormatter(Formatter $formatter)
    {
        $this->_formatter = $handler;
        return $this;
    }
    
    public function getHandler()
    {
        return $this->_handler;
        return $this;
    }
    
    public function getFormatter()
    {
        return $this->_formatter;
        return $this;
    }
    
    public function log($level, $message, array $context = [])
    {
        $this->_validLevel($level);
        $context['level'] = $level;
        $this->_messages[] = $this->_formatter->format($message, $context);
        return $this;
    }
    
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
        return $this;
    }
    
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
        return $this;
    }
    
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
        return $this;
    }
    
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
        return $this;
    }
    
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
        return $this;
    }
    
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
        return $this;
    }
    
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
        return $this;
    }
    
    public function getLevels()
    {
        return $this->_levels;
    }
    
    public function getAllMessages()
    {
        return $this->_messages;
    }
    
    public function getMessages($level = 0)
    {
        return $this->_messages[$level];
    }
    
    public function flush()
    {
        if ( ! $this->_messages) return;
        $this->_handler->write($this->_messages);
        $this->_messages = [];
    }
    
    public function __destruct()
    {
        $this->flush();
    }
    
}