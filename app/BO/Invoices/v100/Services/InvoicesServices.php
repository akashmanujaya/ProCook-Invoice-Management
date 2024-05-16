<?php

namespace App\BO\Invoices\v100\Services;

use App\Base\Exception\BaseException;
use App\BO\Invoices\v100\Repositories\InvoicesRepository;
use App\BO\Invoices\v100\Transformations\InvoiceTransformable;

class InvoicesServices
{
    use InvoiceTransformable;

    protected $InvoiceRepo;

    public function __construct(InvoicesRepository $InvoiceRepo)
    {
         $this->InvoiceRepo = $InvoiceRepo;
    }

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


    public function getInvoiceByNumber($invoiceNumber)
    {
        $invoice =  $this->InvoiceRepo->findByNumber($invoiceNumber);
        return $this->transformInvoice($invoice);
    }
}