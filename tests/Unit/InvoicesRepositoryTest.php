<?php

namespace Tests\Unit;

use App\BO\Invoices\v100\Models\Invoices;
use App\BO\Invoices\v100\Repositories\InvoicesRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicesRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Ensure using SQLite in-memory database
        $this->app->make('config')->set('database.default', 'sqlite');
        $this->app->make('config')->set('database.connections.sqlite.database', ':memory:');


    }

    public function testFindByNumber()
    {
        // Create a sample invoice using the factory
        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001']);

        $repository = new InvoicesRepository(new Invoices());
        $foundInvoice = $repository->findByNumber('INV-001');

        $this->assertNotNull($foundInvoice);
        $this->assertEquals('INV-001', $foundInvoice->invoice_number);
        $this->assertIsString($foundInvoice->first_name);
        $this->assertIsString($foundInvoice->last_name);
    }

    public function testGenerateInvoiceNumber()
    {
        // Create a sample invoice using the factory
        $lastInvoice = Invoices::factory()->create(['invoice_number' => 'INV-001']);

        $repository = new InvoicesRepository(new Invoices());
        $newInvoiceNumber = $repository->generateInvoiceNumber();

        $this->assertEquals('INV-002', $newInvoiceNumber);

        // Create another invoice and check the number
        Invoices::factory()->create(['invoice_number' => $newInvoiceNumber]);

        $newInvoiceNumber2 = $repository->generateInvoiceNumber();
        $this->assertEquals('INV-003', $newInvoiceNumber2);
    }

    public function testGetInvoices()
    {
        // Create sample invoices using the factory
        Invoices::factory()->create([
            'invoice_number' => 'INV-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'description' => 'Test Invoice',
            'invoice_date' => now(),
            'payment_term' => 30,
            'total_amount' => 1000.00,
            'due_date' => now()->addDays(30),
            'status' => 1,
        ]);

        Invoices::factory()->create([
            'invoice_number' => 'INV-002',
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'description' => 'Test Invoice 2',
            'invoice_date' => now(),
            'payment_term' => 15,
            'total_amount' => 2000.00,
            'due_date' => now()->addDays(15),
            'status' => 0,
        ]);

        $repository = new InvoicesRepository(new Invoices());
        $filters = [
            'customerName' => 'Doe',
            'startDate' => now()->subDays(1)->format('Y-m-d'),
            'endDate' => now()->addDays(1)->format('Y-m-d'),
            'paidStatus' => '',
        ];
        $perPage = 10;
        $invoices = $repository->getInvoices($filters, $perPage);

        $this->assertCount(2, $invoices);
        $this->assertEquals('INV-001', $invoices[0]->invoice_number);
        $this->assertEquals('INV-002', $invoices[1]->invoice_number);
        $this->assertArrayHasKey('total', $invoices->toArray());
        $this->assertArrayHasKey('per_page', $invoices->toArray());
    }

    public function testCreateInvoice()
    {
        $repository = new InvoicesRepository(new Invoices());
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

        $createdInvoice = $repository->createInvoice($invoiceData);

        $this->assertNotNull($createdInvoice);
        $this->assertEquals('INV-001', $createdInvoice->invoice_number);
        $this->assertEquals('John', $createdInvoice->first_name);
        $this->assertEquals('Doe', $createdInvoice->last_name);
        $this->assertEquals('Test Invoice', $createdInvoice->description);
        $this->assertInstanceOf(Invoices::class, $createdInvoice);
    }

    public function testUpdateInvoice()
    {
        // Create a sample invoice using the factory
        $invoice = Invoices::factory()->create([
            'invoice_number' => 'INV-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'description' => 'Test Invoice',
            'invoice_date' => now(),
            'payment_term' => 30,
            'total_amount' => 1000.00,
            'due_date' => now()->addDays(30),
            'status' => 1,
        ]);

        $repository = new InvoicesRepository(new Invoices());
        $updateData = [
            'description' => 'Updated Invoice Description',
            'total_amount' => 1200.00
        ];
        $updatedInvoice = $repository->updateInvoice($updateData, 'INV-001');

        $this->assertEquals('Updated Invoice Description', $updatedInvoice->description);
        $this->assertEquals(1200.00, $updatedInvoice->total_amount);
        $this->assertInstanceOf(Invoices::class, $updatedInvoice);
    }

    public function testToggleStatus()
    {
        // Create a sample invoice using the factory
        $invoice = Invoices::factory()->create([
            'invoice_number' => 'INV-001',
            'status' => 0,
        ]);

        $repository = new InvoicesRepository(new Invoices());
        $toggledInvoice = $repository->toggleStatus('INV-001');

        $this->assertEquals(1, $toggledInvoice->status);

        // Toggle again to check if it reverts back
        $toggledInvoice = $repository->toggleStatus('INV-001');
        $this->assertEquals(0, $toggledInvoice->status);
        $this->assertInstanceOf(Invoices::class, $toggledInvoice);
    }

    public function testDeleteInvoice()
    {
        // Create a sample invoice using the factory
        $invoice = Invoices::factory()->create(['invoice_number' => 'INV-001']);

        $repository = new InvoicesRepository(new Invoices());
        $deletedInvoice = $repository->deleteInvoice('INV-001');

        $this->assertNull(Invoices::find($deletedInvoice->id));
        $this->assertEquals('INV-001', $deletedInvoice->invoice_number);
        $this->assertInstanceOf(Invoices::class, $deletedInvoice);
    }
}
