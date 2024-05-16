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

    public function findByNumber($invoiceNumber)
    {
        try {
            $invoice = $this->model->where('invoice_number', $invoiceNumber)->first();
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

    public function getInvoices($filters, $perPage = 10)
    {
        try {
            $query = $this->model->newQuery();

            // Filter by customer name using first_name and last_name
            if (!empty($filters['customerName'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('first_name', 'like', '%' . $filters['customerName'] . '%')
                    ->orWhere('last_name', 'like', '%' . $filters['customerName'] . '%');
                });
            }

            // Filter by start date
            if (!empty($filters['startDate'])) {
                $query->whereDate('invoice_date', '>=', $filters['startDate']);
            }

            // Filter by end date
            if (!empty($filters['endDate'])) {
                $query->whereDate('invoice_date', '<=', $filters['endDate']);
            }

            // Filter by payment status
            if (!empty($filters['paidStatus'])) {
                $query->where('status', $filters['paidStatus']);
            }

            // Order by most recent and paginate results
            return $query->orderBy('created_at', 'desc')->paginate($perPage);
        } catch (\Exception $e) {
            // Handle any errors that occur during the query execution
            report($e);
            return response()->json(['error' => 'Failed to retrieve filtered invoices'], 500);
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

    public function updateInvoice(array $data, $invoiceNumber)
    {
        try {
            $invoice = $this->model->where('invoice_number', $invoiceNumber)->first();
            $invoice->update($data);
            return $invoice;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function toggleStatus($invoiceNumber)
    {
        try {
            $invoice = $this->model->where('invoice_number', $invoiceNumber)->first();
            $invoice->status = !$invoice->status;
            $invoice->save();
            return $invoice;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function deleteInvoice($invoiceNumber)
    {
        try {
            $invoice = $this->model->where('invoice_number', $invoiceNumber)->first();
            $invoice->delete();
            return $invoice;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}