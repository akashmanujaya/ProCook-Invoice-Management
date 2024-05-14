<?php

namespace App\BO\Invoices\v100\Services;

use App\Base\Exception\BaseException;
use App\BO\Invoices\v100\Repositories\InvoicesRepository;

class InvoicesServices
{
   protected $repository;

    public function __construct(InvoicesRepository $repository)
    {
         $this->repository = $repository;
    }

    public function createInvoice($data)
    {
        try {
            $invoice = $this->repository->createInvoice($request);
            return $invoice;
        } catch (BaseException $e) {
            throw $e;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}