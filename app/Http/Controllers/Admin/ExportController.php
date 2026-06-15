<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LostItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LostItemsExport;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $filename = 'lost_items_' . $request->start_date . '_to_' . $request->end_date . '.xlsx';

        return Excel::download(
            new LostItemsExport($request->start_date, $request->end_date),
            $filename
        );
    }
}
