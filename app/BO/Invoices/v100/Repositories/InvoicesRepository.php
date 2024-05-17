<?php

namespace App\BO\Invoices\v100\Repositories;

use App\BO\Invoices\v100\Exceptions\InvoiceNotFoundException;
use App\BO\Invoices\v100\Models\Invoices;
use App\BO\Invoices\v100\Repositories\Interfaces\InvoicesReporitoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use function App\Helpers\calculateDueDate;
use function App\Helpers\sanitizeInput;

/**
 * Class InvoicesRepository
 *
 * This repository handles data access logic for invoices, including
 * creating, retrieving, updating, and deleting invoices, as well as generating invoice numbers.
 *
 * @package App\BO\Invoices\v100\Repositories
 */
class InvoicesRepository implements InvoicesReporitoryInterface
{
    /**
     * @var Invoices
     */
    protected $model;

    /**
     * InvoicesRepository constructor.
     *
     * @param Invoices $model The invoice model.
     */
    public function __construct(Invoices $model)
    {
        $this->model = $model;
    }

    /**
     * Find an invoice by its number.
     *
     * @param string $invoiceNumber The invoice number.
     * @return Invoices|null The found invoice or null if not found.
     */
    public function findByNumber($invoiceNumber)
    {
        try {
            $invoice = $this->model->where('invoice_number', $invoiceNumber)->first();
            return $invoice;
        } catch (InvoiceNotFoundException $e) {
            Log::error($e->getMessage());
        }
        catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Generate a new unique invoice number.
     *
     * @return string The generated invoice number.
     */
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

    /**
     * Retrieve a paginated list of invoices based on filters.
     *
     * @param array $filters The filters for retrieving invoices.
     * @param int $perPage The number of invoices per page.
     * @return \Illuminate\Pagination\LengthAwarePaginator The paginated list of invoices.
     */
    public function getInvoices($filters, $perPage = 10)
    {
        $cacheKey = "invoices_" . md5(json_encode($filters)) . "_$perPage";
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters, $perPage) {
            Log::info("Cache miss for invoices with filters: " . json_encode($filters));
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
                if ($filters['paidStatus'] !== '') {
                    $query->where('status', $filters['paidStatus']);
                }

                // Order by most recent and paginate results
                return $query->orderBy('created_at', 'desc')->paginate($perPage);
            } catch (\Exception $e) {
                // Handle any errors that occur during the query execution
                report($e);
                return response()->json(['error' => 'Failed to retrieve filtered invoices'], 500);
            }
        });
    }

    /**
     * Create a new invoice.
     *
     * @param array $data The data for the new invoice.
     * @return Invoices The created invoice.
     */
    public function createInvoice(array $data)
    {
        try {
            $data  = sanitizeInput($data);
            $data = calculateDueDate($data);    
            $data['invoice_number'] = $this->generateInvoiceNumber();
            $invoice = $this->model->create($data);

            Cache::flush();

            return $invoice;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Update an existing invoice.
     *
     * @param array $data The updated data for the invoice.
     * @param string $invoiceNumber The invoice number.
     * @return Invoices The updated invoice.
     */
    public function updateInvoice(array $data, $invoiceNumber)
    {
        try {
            $data  = sanitizeInput($data);
            $data = calculateDueDate($data);   
            $invoice = $this->model->where('invoice_number', $invoiceNumber)->first();

            if (!$invoice) {
                return null;
            }
            $invoice->update($data);
            return $invoice;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Toggle the status of an invoice.
     *
     * @param string $invoiceNumber The invoice number.
     * @return Invoices The invoice with the updated status.
     */
    public function toggleStatus($invoiceNumber)
    {
        try {
            $invoice = $this->model->where('invoice_number', $invoiceNumber)->first();
            if (!$invoice) {
                return null;
            }
            $invoice->status = !$invoice->status;
            $invoice->save();
            return $invoice;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Delete an invoice by its number.
     *
     * @param string $invoiceNumber The invoice number.
     * @return Invoices The deleted invoice.
     */
    public function deleteInvoice($invoiceNumber)
    {
        try {
            $invoice = $this->model->where('invoice_number', $invoiceNumber)->first();
            if (!$invoice) {
                return null;
            }
            $invoice->delete();
            return $invoice;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
