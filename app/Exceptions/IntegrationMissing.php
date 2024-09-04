<?php

namespace App\Exceptions;

use Exception;

class IntegrationMissing extends Exception
{
    public string $integrationName;

    public function __construct($integrationName, $message = "")
    {
        $this->integrationName = $integrationName;
        parent::__construct($message);
    }
}
