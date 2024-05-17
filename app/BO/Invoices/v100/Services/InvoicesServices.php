<?php

namespace App\BO\Invoices\v100\Services;

use App\Base\Exception\BaseException;
use App\BO\Invoices\v100\Repositories\InvoicesRepository;
use App\BO\Invoices\v100\Transformations\InvoiceTransformable;

/**
 * Class InvoicesServices
 *
 * This service handles business logic for invoice operations, including
 * creating, retrieving, updating, and deleting invoices, as well as toggling their status.
 *
 * @package App\BO\Invoices\v100\Services
 */
class InvoicesServices
{
    use InvoiceTransformable;

    /**
     * @var InvoicesRepository
     */
    protected $InvoiceRepo;

    /**
     * InvoicesServices constructor.
     *
     * @param InvoicesRepository $InvoiceRepo
     */
    public function __construct(InvoicesRepository $InvoiceRepo)
    {
        $this->InvoiceRepo = $InvoiceRepo;
    }

    /**
     * Create a new invoice.
     *
     * @param array $data The data for the new invoice.
     * @return array The transformed invoice data.
     * @throws BaseException If there is a business logic related exception.
     * @throws \Exception If there is a general exception.
     */
    public function createInvoice($data)
    {
        try {
            $invoice = $this->InvoiceRepo->createInvoice($data);
            return $this->transformInvoice($invoice);
        } catch (BaseException $e) {
            throw $e;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Retrieve a paginated list of invoices based on filters.
     *
     * @param array $filters The filters for retrieving invoices.
     * @param int $perPage The number of invoices per page.
     * @return array The transformed invoice data and pagination details.
     * @throws BaseException If there is a business logic related exception.
     * @throws \Exception If there is a general exception.
     */
    public function getInvoices($filters, $perPage = 10)
    {
        try {
            $invoices = $this->InvoiceRepo->getInvoices($filters, $perPage);
            return [
                'data' => $invoices->map([$this, 'transformInvoice'])->toArray(),
                'pagination' => [
                    'total' => $invoices->total(),
                    'perPage' => $invoices->perPage(),
                    'currentPage' => $invoices->currentPage(),
                    'lastPage' => $invoices->lastPage(),
                    'from' => $invoices->firstItem(),
                    'to' => $invoices->lastItem()
                ]
            ];
        } catch (BaseException $e) {
            throw $e;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Retrieve an invoice by its number.
     *
     * @param string $invoiceNumber The invoice number.
     * @return array|null The transformed invoice data or null if not found.
     */
    public function getInvoiceByNumber($invoiceNumber)
    {
        $invoice = $this->InvoiceRepo->findByNumber($invoiceNumber);
        if (!$invoice) {
            return null;
        }
        return $this->transformInvoice($invoice);
    }

    /**
     * Update an existing invoice.
     *
     * @param array $data The updated data for the invoice.
     * @param string $invoiceNumber The invoice number.
     * @return array The transformed updated invoice data.
     * @throws BaseException If there is a business logic related exception.
     * @throws \Exception If there is a general exception.
     */
    public function updateInvoice($data, $invoiceNumber)
    {
        try {
            $invoice = $this->InvoiceRepo->updateInvoice($data, $invoiceNumber);
            return $this->transformInvoice($invoice);
        } catch (BaseException $e) {
            throw $e;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Toggle the status of an invoice.
     *
     * @param string $invoiceNumber The invoice number.
     * @return array The transformed invoice data with updated status.
     * @throws BaseException If there is a business logic related exception.
     * @throws \Exception If there is a general exception.
     */
    public function toggleStatus($invoiceNumber)
    {
        try {
            $invoice = $this->InvoiceRepo->toggleStatus($invoiceNumber);
            return $this->transformInvoice($invoice);
        } catch (BaseException $e) {
            throw $e;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Delete an invoice by its number.
     *
     * @param string $invoiceNumber The invoice number.
     * @return array The transformed invoice data of the deleted invoice.
     * @throws BaseException If there is a business logic related exception.
     * @throws \Exception If there is a general exception.
     */
    public function deleteInvoice($invoiceNumber)
    {
        try {
            $invoice = $this->InvoiceRepo->deleteInvoice($invoiceNumber);
            return $this->transformInvoice($invoice);
        } catch (BaseException $e) {
            throw $e;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
