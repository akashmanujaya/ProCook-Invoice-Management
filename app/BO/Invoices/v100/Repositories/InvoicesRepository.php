<?php

namespace App\BO\Invoices\v100\Repositories;

use App\BO\Invoices\v100\Models\Invoices;

class InvoicesRepository
{
    protected $model;

    public function __construct(Invoices $model)
    {
        $this->model = $model;
    }

    
}