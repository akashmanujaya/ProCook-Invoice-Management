<?php

namespace App\BO\Invoices\v100\Repositories\Interfaces;

/**
 * Interface InvoicesRepositoryInterface
 *
 * This interface defines the methods that the InvoicesRepository class must implement.
 *
 * @package App\BO\Invoices\v100\Repositories\Interfaces
 */
interface InvoicesReporitoryInterface
{
    /**
     * Create a new invoice.
     *
     * @param array $data The data for the new invoice.
     * @return \App\BO\Invoices\v100\Models\Invoices The created invoice.
     */
    public function createInvoice(array $data);

    /**
     * Generate a new unique invoice number.
     *
     * @return string The generated invoice number.
     */
    public function generateInvoiceNumber();

    /**
     * Find an invoice by its number.
     *
     * @param string $invoiceNumber The invoice number.
     * @return \App\BO\Invoices\v100\Models\Invoices|null The found invoice or null if not found.
     */
    public function findByNumber($invoiceNumber);
}