<?php

namespace App\BO\Invoices\v100\Repositories\Interfaces;

interface InvoicesReporitoryInterface
{
    public function createInvoice(array $data);
    public function generateInvoiceNumber();
    public function findByNumber($invoiceNumber);
}