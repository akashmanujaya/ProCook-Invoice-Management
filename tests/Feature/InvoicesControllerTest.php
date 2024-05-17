<?php

namespace Tests\Feature;

use App\BO\Invoices\v100\Models\Invoices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Create sample invoices
        Invoices::factory()->count(2)->create();

        $response = $this->getJson('invoices');

        dd($response);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'invoice_number',
                            'first_name',
                            'last_name',
                            'description',
                            'invoice_date',
                            'payment_term',
                            'total_amount',
                            'due_date',
                            'status'
                        ]
                    ],
                    'pagination' => [
                        'total',
                        'perPage',
                        'currentPage',
                        'lastPage',
                        'from',
                        'to'
                    ]
                ]
            ]);
    }

    public function testShow()
    {
        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001']);

        $response = $this->getJson('/api/invoices/INV-001');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Invoice retrieved successfully',
                'data' => [
                    'invoice_number' => 'INV-001',
                    'first_name' => $invoice->first_name,
                    'last_name' => $invoice->last_name,
                    'description' => $invoice->description,
                    'invoice_date' => $invoice->invoice_date->format('d-m-Y h:i A'),
                    'payment_term' => $invoice->payment_term,
                    'total_amount' => number_format($invoice->total_amount, 2, '.', ','),
                    'due_date' => $invoice->due_date->format('d-m-Y h:i A'),
                    'status' => $invoice->status ? 'Paid' : 'Pending'
                ]
            ]);
    }

    public function testStore()
    {
        $invoiceData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'description' => 'Test Invoice',
            'invoice_date' => now()->format('Y-m-d H:i:s'),
            'payment_term' => 30,
            'total_amount' => 1000.00,
            'due_date' => now()->addDays(30)->format('Y-m-d H:i:s'),
            'status' => 1,
        ];

        $response = $this->postJson('/api/invoices', $invoiceData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'invoice_number',
                    'first_name',
                    'last_name',
                    'description',
                    'invoice_date',
                    'payment_term',
                    'total_amount',
                    'due_date',
                    'status'
                ]
            ]);
    }

    public function testUpdate()
    {
        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001']);

        $updateData = [
            'description' => 'Updated Invoice Description',
            'total_amount' => 1200.00
        ];

        $response = $this->putJson('/api/invoices/INV-001', $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Invoice updated successfully',
                'data' => [
                    'invoice_number' => 'INV-001',
                    'description' => 'Updated Invoice Description',
                    'total_amount' => number_format(1200.00, 2, '.', ',')
                ]
            ]);
    }

    public function testToggleStatus()
    {
        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001', 'status' => 0]);

        $response = $this->patchJson('/api/invoices/INV-001/toggle-status');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Invoice status updated successfully',
                'data' => [
                    'invoice_number' => 'INV-001',
                    'status' => 'Paid'
                ]
            ]);

        // Toggle back to pending
        $response = $this->patchJson('/api/invoices/INV-001/toggle-status');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Invoice status updated successfully',
                'data' => [
                    'invoice_number' => 'INV-001',
                    'status' => 'Pending'
                ]
            ]);
    }

    public function testDestroy()
    {
        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001']);

        $response = $this->deleteJson('/api/invoices/INV-001');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Invoice deleted successfully'
            ]);

        $this->assertNull(Invoices::find($invoice->id));
    }
}
