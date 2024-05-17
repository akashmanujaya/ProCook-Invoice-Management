<?php

namespace App\BO\Invoices\v100\Exceptions;

use App\Base\Exception\BaseException;

class InvoiceNotFoundException extends BaseException
{
    public function __construct()
    {
        $this->http_status_code = 404;
        $this->code = 2006;
    }
}
