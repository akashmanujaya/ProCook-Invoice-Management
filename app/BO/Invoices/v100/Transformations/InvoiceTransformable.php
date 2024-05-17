<?php

namespace App\BO\Invoices\v100\Transformations;

/**
 * Trait InvoiceTransformable
 *
 * This trait provides methods to transform invoice data into a consistent format.
 *
 * @package App\BO\Invoices\v100\Transformations
 */
trait InvoiceTransformable
{
    /**
     * Transform the given invoice data.
     *
     * @param \App\BO\Invoices\v100\Models\Invoices $invoice The invoice model instance.
     * @return array The transformed invoice data.
     */
    public function transformInvoice($invoice)
    {
        return [
            'invoice_number' => $invoice->invoice_number,
            'first_name' => $invoice->first_name,
            'last_name' => $invoice->last_name,
            'description' => $invoice->description ?: 'N/A',
            'invoice_date' => $invoice->invoice_date->format('d-m-Y h:i A'),
            'payment_term' => $invoice->payment_term,
            'total_amount' => number_format($invoice->total_amount, 2, '.', ','),
            'due_date' => $invoice->due_date->format('d-m-Y h:i A'),
            'status' => $invoice->status ? 'Paid' : 'Pending'
        ];
    }
}
