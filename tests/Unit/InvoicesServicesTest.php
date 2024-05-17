<?php

namespace Tests\Unit;

use App\BO\Invoices\v100\Models\Invoices;
use App\BO\Invoices\v100\Repositories\InvoicesRepository;
use App\BO\Invoices\v100\Services\InvoicesServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class InvoicesServicesTest extends TestCase
{
    use RefreshDatabase;

    protected $invoiceRepo;
    protected $invoiceService;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure using SQLite in-memory database
        $this->app->make('config')->set('database.default', 'sqlite');
        $this->app->make('config')->set('database.connections.sqlite.database', ':memory:');



        $this->invoiceRepo = Mockery::mock(InvoicesRepository::class);
        $this->invoiceService = new InvoicesServices($this->invoiceRepo);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateInvoice()
    {
        $invoiceData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'description' => 'Test Invoice',
            'invoice_date' => now(),
            'payment_term' => 30,
            'total_amount' => 1000.00,
            'due_date' => now()->addDays(30),
            'status' => 1,
        ];

        $invoice = Invoices::factory()->make($invoiceData);

        $this->invoiceRepo->shouldReceive('createInvoice')
            ->with($invoiceData)
            ->andReturn($invoice);

        $transformedInvoice = $this->invoiceService->createInvoice($invoiceData);

        $this->assertNotNull($transformedInvoice);
        $this->assertEquals('John', $transformedInvoice['first_name']);
        $this->assertEquals('Doe', $transformedInvoice['last_name']);
        $this->assertIsString($transformedInvoice['invoice_number']);
    }

    public function testGetInvoices()
    {
        $invoices = Invoices::factory()->count(2)->make();

        $filters = [
            'customerName' => 'Doe',
            'startDate' => now()->subDays(1)->format('Y-m-d'),
            'endDate' => now()->addDays(1)->format('Y-m-d'),
            'paidStatus' => '',
        ];
        $perPage = 10;

        $paginatedInvoices = new \Illuminate\Pagination\LengthAwarePaginator(
            $invoices, 
            2, 
            $perPage, 
            1, 
            ['path' => 'http://localhost']
        );

        $this->invoiceRepo->shouldReceive('getInvoices')
            ->with($filters, $perPage)
            ->andReturn($paginatedInvoices);

        $response = $this->invoiceService->getInvoices($filters, $perPage);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('data', $response);
        $this->assertCount(2, $response['data']);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertEquals(2, $response['pagination']['total']);
    }

    public function testGetInvoiceByNumber()
    {
        $invoice = Invoices::factory()->make(['invoice_number' => 'INV-001']);

        $this->invoiceRepo->shouldReceive('findByNumber')
            ->with('INV-001')
            ->andReturn($invoice);

        $transformedInvoice = $this->invoiceService->getInvoiceByNumber('INV-001');

        $this->assertNotNull($transformedInvoice);
        $this->assertEquals('INV-001', $transformedInvoice['invoice_number']);
        $this->assertIsString($transformedInvoice['first_name']);
        $this->assertIsString($transformedInvoice['last_name']);
    }

    public function testUpdateInvoice()
    {
        // Create and persist a sample invoice using the factory
        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001']);

        $updateData = [
            'description' => 'Updated Invoice Description',
            'total_amount' => 1200.00
        ];

        // Mock the repository method to update the invoice
        $this->invoiceRepo->shouldReceive('updateInvoice')
            ->with($updateData, 'INV-001')
            ->andReturn($invoice->fill($updateData)); // Update the mock to return the updated invoice

        // Call the service method
        $transformedInvoice = $this->invoiceService->updateInvoice($updateData, 'INV-001');

        // Assertions
        $this->assertEquals('Updated Invoice Description', $transformedInvoice['description']);
        $this->assertEquals('1,200.00', $transformedInvoice['total_amount']);
        $this->assertIsString($transformedInvoice['invoice_number']);
    }


    public function testToggleStatus()
    {
        $invoice = Invoices::factory()->make(['invoice_number' => 'INV-001', 'status' => 0]);

        $this->invoiceRepo->shouldReceive('toggleStatus')
            ->with('INV-001')
            ->andReturn($invoice);

        $transformedInvoice = $this->invoiceService->toggleStatus('INV-001');

        $this->assertEquals('Pending', $transformedInvoice['status']);
        $this->assertIsString($transformedInvoice['invoice_number']);
        $this->assertEquals('INV-001', $transformedInvoice['invoice_number']);
    }

    public function testDeleteInvoice()
    {
        $invoice = Invoices::factory()->make(['invoice_number' => 'INV-001']);

        $this->invoiceRepo->shouldReceive('deleteInvoice')
            ->with('INV-001')
            ->andReturn($invoice);

        $transformedInvoice = $this->invoiceService->deleteInvoice('INV-001');

        $this->assertNotNull($transformedInvoice);
        $this->assertEquals('INV-001', $transformedInvoice['invoice_number']);
        $this->assertIsString($transformedInvoice['first_name']);
        $this->assertIsString($transformedInvoice['last_name']);
    }
}
