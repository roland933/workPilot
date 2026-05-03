<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TimeEntry;
use App\Services\InvoiceService;


class InvoiceController
{
    public function download()
        {
          $invoice = app(InvoiceService::class)->generate(auth()->id())
                    ->download('invoice.pdf');

          return $invoice;
        }

    public function preview(Request $request)
      {
            $invoice = app(InvoiceService::class)->generate(auth()->id())
                    ->stream();

            return $invoice;
      }    
}
