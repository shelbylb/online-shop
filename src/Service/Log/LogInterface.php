<?php

namespace Service\Log;

interface LogInterface
{
    public function log($exception, $data = null);

}