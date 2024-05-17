<?php

namespace App\Http\Controllers\Invoices\v100;

use App\Base\Controller\BaseController;
use App\BO\Invoices\v100\Requests\InvoicesCreationRequest;
use App\BO\Invoices\v100\Requests\InvoiceUpdateRequest;
use App\BO\Invoices\v100\Services\InvoicesServices;
use Illuminate\Http\Request;

/**
 * Class InvoicesController
 *
 * This controller handles CRUD operations and status toggling for invoices.
 *
 * @package App\Http\Controllers\Invoices\v100
 */
class InvoicesController extends BaseController
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
            $perPage = $request->input('perPage', 10); // Default to 10 items per page
            $filters = [
                'customerName' => $request->customerName,
                'startDate' => $request->startDate ?? date('Y-m-d', 1970-01-01), // Default to 1970-01-01 if start date is null
                'endDate' => $request->endDate ?? date('Y-m-d'), // Default to today if end date is null
                'paidStatus' => $request->paidStatus ?? '',
            ];


            $invoices = $this->invoiceService->getInvoices($filters, $perPage);
            if (empty($invoices['data'])) {
                return response()->json([
                    'status' => 'Not Found',
                    'message' => 'No records available.'
                ], 200);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Invoices retreived successfully',
                'data' => $invoices
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
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
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invoice not found'
                ], 404);
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice retrieved successfully',
                'data' => $invoice
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
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

            // Return the new invoice as JSON for Vue.js to use and update the UI
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice created successfully',
                'data' => $invoice
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
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

            // Return the updated invoice as JSON for Vue.js to use and update the UI
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice updated successfully',
                'data' => $invoice
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the status of the specified invoice.
     *
     * @param string $invoiceNumber
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($invoiceNumber)
    {
        try {
            $invoice = $this->invoiceService->toggleStatus($invoiceNumber);

            // Return the updated invoice as JSON for Vue.js to use and update the UI
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice status updated successfully',
                'data' => $invoice
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified invoice from database.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
       try {
            $invoice = $this->invoiceService->deleteInvoice($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice deleted successfully',
                'data' => $invoice
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}