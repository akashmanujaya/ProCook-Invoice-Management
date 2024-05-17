<?php

namespace Tests\Feature;

use App\BO\Invoices\v100\Models\Invoices;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoicesControllerTest extends FeatureTestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');

        // Create user
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        // Authenticate the user using the API login route
        $response = $this->withHeaders([
            'x-api-key' => env('API_KEY')
        ])->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $this->token = $response->json('data.token');
    }

    public function testIndex()
    {
        // Create sample invoices
        Invoices::factory()->count(2)->create();

        $response = $this->withHeaders([
            'x-api-key' => env('API_KEY'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/invoices');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'status_code',
                'message',
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
                ]
            ]);

    }

    public function testShow()
    {
        $this->actingAs($this->user);

        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001']);

        $response = $this->withHeaders([
            'x-api-key' => env('API_KEY'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/invoices/INV-001');

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
        $this->actingAs($this->user);

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

        $response = $this->withHeaders([
            'x-api-key' => env('API_KEY'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/invoices', $invoiceData);

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
        $this->actingAs($this->user);

        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001']);

        $updateData = [
            'first_name' => 'Updated First Name',
            'last_name' => 'Updated Last Name',
            'description' => 'Updated Invoice Description',
            'invoice_date' => now()->format('Y-m-d H:i:s'),
            'payment_term' => 30,
            'total_amount' => 1200.00,
            'due_date' => now()->addDays(30)->format('Y-m-d H:i:s'),
            'status' => 'Pending'
        ];

        // dd($invoice->invoice_number);

        $response = $this->withHeaders([
            'x-api-key' => env('API_KEY'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('api/invoices/' . $invoice->invoice_number, $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Invoice updated successfully',
                'data' => [
                    'first_name' => 'Updated First Name',
                    'last_name' => 'Updated Last Name',
                    'invoice_number' => 'INV-001',
                    'description' => 'Updated Invoice Description',
                    "payment_term" => 30,
                    'total_amount' => number_format(1200.00, 2, '.', ',')
                ]
            ]);
    }

    public function testToggleStatus()
    {
        $this->actingAs($this->user);

        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001', 'status' => 0]);

        $response = $this->withHeaders([
            'x-api-key' => env('API_KEY'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/invoices/toggle-status/INV-001');

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
        $response = $this->withHeaders([
            'x-api-key' => env('API_KEY'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/invoices/toggle-status/INV-001');

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
        $this->actingAs($this->user);

        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001']);

        $response = $this->withHeaders([
            'x-api-key' => env('API_KEY'),
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/invoices/' . $invoice->invoice_number);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Invoice deleted successfully'
            ]);

        $this->assertNull(Invoices::find($invoice->invoice_number));
    }
}
