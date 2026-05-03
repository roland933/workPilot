<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TimeEntry;


class InvoiceController
{
    public function downloadInvoice()
        {
            $entries = TimeEntry::with('project')
                ->where('user_id', auth()->id())
                ->whereNotNull('duration')
                ->get();

            $total = $entries->sum(function ($e) {
                return ($e->duration / 3600) * $e->project->hourly_rate;
            });

            $pdf = Pdf::loadView('invoice', [
                'entries' => $entries,
                'total' => $total,
            ]);

            return $pdf->download('invoice.pdf');
        }
}
