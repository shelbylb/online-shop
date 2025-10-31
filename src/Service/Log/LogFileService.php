<?php

namespace Service\Log;

class LogFileService implements LogInterface
{
        public function log($exception, $data = null)
    {
            $filename = '../Storage/Log/errors.txt';
            date_default_timezone_set('Etc/GMT-8');
            $datetime = date('d.m.y H:i');

            file_put_contents($filename,PHP_EOL . 'Время: '. $datetime . PHP_EOL, FILE_APPEND);
            file_put_contents($filename,$data . $exception->getMessage(). PHP_EOL, FILE_APPEND);
            file_put_contents($filename, 'Файл: '. $exception->getFile(). PHP_EOL, FILE_APPEND);
            file_put_contents($filename, 'Строка: '. $exception->getLine(). PHP_EOL, FILE_APPEND);


    }

}