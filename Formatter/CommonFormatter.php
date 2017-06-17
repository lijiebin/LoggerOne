<?php
namespace LoggerOne\Formatter;

use LoggerOne\Formatter\Formatter;

class CommonFormatter implements Formatter
{
    public function format($message, array $context = [])
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if ( ! is_array($val) && ( ! is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }
}