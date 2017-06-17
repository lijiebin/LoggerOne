<?php
namespace LoggerOne\Handler;

interface Handler
{
    public function __construct(string $destination, object $operator = null);
    
    public function write(array $messages);
}