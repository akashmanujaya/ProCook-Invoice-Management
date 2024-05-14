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

    public function index()
    {
        // Your code here
    }

    public function show($id)
    {
        // Your code here
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