<?php
namespace LoggerOne\Formatter;

interface Formatter
{
    public function format($message, array $context = []);
}
