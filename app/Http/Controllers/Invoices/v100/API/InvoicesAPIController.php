<?php

namespace App\Http\Controllers\Invoices\v100\API;

use App\Base\Controller\BaseController;
use Illuminate\Http\Request;
use App\BO\Invoices\v100\Requests\InvoicesCreationRequest;
use App\BO\Invoices\v100\Requests\InvoiceUpdateRequest;
use App\BO\Invoices\v100\Services\InvoicesServices;
use App\Helpers\ApiResponse;

/**
 * Class InvoicesController
 *
 * This controller handles CRUD operations and status toggling for invoices.
 *
 * @package App\Http\Controllers\Invoices\v100
 */
class InvoicesAPIController extends BaseController
{
    /**
     * @var InvoicesServices
     */
    protected $invoiceService;

    /**
     * InvoicesController constructor.
     *
     * @param InvoicesServices $invoiceService
     */
    public function __construct(InvoicesServices $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the invoices.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('perPage', 10);
            $filters = [
                'customerName' => $request->customerName,
                'startDate' => $request->startDate ?? date('Y-m-d', 1970-01-01),
                'endDate' => $request->endDate ?? date('Y-m-d'),
                'paidStatus' => $request->paidStatus ?? '',
            ];

            $invoices = $this->invoiceService->getInvoices($filters, $perPage);
            if (empty($invoices['data'])) {
                return ApiResponse::error('No records available', 200);
            }
            return ApiResponse::send($invoices['data'], 'Invoices retrieved successfully', true, 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to retrieve invoices: ' . $e->getMessage(), 500);
        }
    }

     /**
     * Display the specified invoice.
     *
     * @param string $invoiceNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($invoiceNumber)
    {
        try {
            $invoice = $this->invoiceService->getInvoiceByNumber($invoiceNumber);
            if (!$invoice) {
                return ApiResponse::error('Invoice not found', 404);
            }
            return ApiResponse::send($invoice, 'Invoice retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Error retrieving invoice: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created invoice in storage.
     *
     * @param InvoicesCreationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(InvoicesCreationRequest $request)
    {
        try {
            $invoice = $this->invoiceService->createInvoice($request->validated());
            return ApiResponse::send($invoice, 'Invoice created successfully', true, 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Error creating invoice: ' . $e->getMessage(), 500);
        }
    }


    /**
     * Update the specified invoice in storage.
     *
     * @param InvoiceUpdateRequest $request
     * @param string $invoiceNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(InvoiceUpdateRequest $request, $invoiceNumber)
    {
        try {
            $invoice = $this->invoiceService->updateInvoice($request->validated(), $invoiceNumber);
            return ApiResponse::send($invoice, 'Invoice updated successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Error updating invoice: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle the status of the specified invoice.
     *
     * @param string $invoiceNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->invoiceService->deleteInvoice($id);
            return ApiResponse::send(null, 'Invoice deleted successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Error deleting invoice: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified invoice from database.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($invoiceNumber)
    {
        try {
            $invoice = $this->invoiceService->toggleStatus($invoiceNumber);
            return ApiResponse::send($invoice, 'Invoice status updated successfully');
        } catch (\Exception $e) {
            return ApiResponse::error('Error updating invoice status: ' . $e->getMessage(), 500);
        }
    }


}