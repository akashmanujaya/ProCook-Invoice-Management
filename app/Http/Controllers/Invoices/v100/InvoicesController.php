<?php

namespace App\Http\Controllers\Invoices\v100;

use App\Base\Controller\BaseController;
use App\BO\Invoices\v100\Requests\InvoicesCreationRequest;
use App\BO\Invoices\v100\Services\InvoicesServices;
use Illuminate\Http\Request;

class InvoicesController extends BaseController
{
    protected $invoiceService;

    public function __construct(InvoicesServices $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

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

    public function update(Request $request, $id)
    {
        // Your code here
    }

    public function destroy($id)
    {
        // Your code here
    }
}