<?php

namespace App\BO\Invoices\v100\Repositories;

use App\BO\Invoices\v100\Models\Invoices;
use App\BO\Invoices\v100\Repositories\Interfaces\InvoicesReporitoryInterface;

class InvoicesRepository implements InvoicesReporitoryInterface
{
    protected $model;

    public function __construct(Invoices $model)
    {
        $this->model = $model;
    }

    public function getInvoiceById($id)
    {
        try {
            $invoice = $this->model->find($id);
            return $invoice;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function generateInvoiceNumber()
    {
        try {
            $lastInvoice = $this->model->orderBy('id', 'desc')->first();
            $lastNumber = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, 4)) : 0;

            return 'INV-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function createInvoice(array $data)
    {
        try {
            $data['invoice_number'] = $this->generateInvoiceNumber();
            $invoice = $this->model->create($data);
            return $invoice;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getAllInvoices($perPage = 10)
    {
        try {
            $invoices = $this->model->orderBy('created_at', 'desc')->paginate($perPage);
            return $invoices;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}