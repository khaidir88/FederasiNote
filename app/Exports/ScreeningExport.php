<?php

namespace App\Exports;

use App\Models\ScreeningResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class ScreeningExport implements FromView
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function view(): View
    {
        $results = ScreeningResult::with('user', 'category')
            ->where('user_id', $this->userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.excel', [
            'results' => $results
        ]);
    }
}
