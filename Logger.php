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
    
    public function __construct(Handler $handler = null, $formatter = null)
    {
        $refl = new \ReflectionClass('Psr\log\LogLevel');
        $this->_levels = array_values($refl->getConstants());
        $this->_handler = $handler ? $handler : new FileHandler();
        $this->_formatter = $formatter ? $formatter : new CommonFormatter();
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
    
    public function log($level, $message, array $context = [])
    {
        $this->_validLevel($level);
        $context['level'] = $level;
        $this->_messages[$level][] = $this->_formatter->format($message, $context);
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
    
    public function EMERGENCY($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
        return $this;
    }
    
    public function WARNING($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
        return $this;
    }
    
    public function CRITICAL($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
        return $this;
    }
    
    public function DEBUG($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
        return $this;
    }
    
    public function NOTICE($message, array $context = array())
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
        $this->_handler->write($this->_messages);
        $this->_messages = [];
    }
    
    public function __destruct()
    {
        $this->flush();
    }
}