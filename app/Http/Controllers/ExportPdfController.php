<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExportPdfController extends Controller
{
    public function exportCustomerReport(Request $request)
    {
        dd($request->all());
        $pdf = PDF::loadView('pdf.table', compact('data'));
        return $pdf->download('table-data.pdf');
    }
}
