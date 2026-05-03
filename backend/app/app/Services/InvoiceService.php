<?php

namespace App\Services;

use App\Models\TimeEntry;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function generate($userId, $from = null, $to = null)
    {
        $query = TimeEntry::with('project')
            ->where('user_id', $userId)
            ->whereNotNull('duration');

        if ($from && $to) {
            $query->whereBetween('start_time', [$from, $to]);
        }

        $entries = $query->get();

        $total = $entries->sum(function ($e) {
            return ($e->duration / 3600) * $e->project->hourly_rate;
        });

        return Pdf::loadView('invoice', [
            'entries' => $entries,
            'total' => $total,
        ]);
    }
}