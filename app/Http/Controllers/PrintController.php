<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Order;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function print($id)
    {
        $order = Order::find($id);
        $company = Company::latest()->first();

        $pdf = app()->make(PDF::class);
        $pdf->setPaper([0, 0, 226.77, 1000], 'portrait');
        $pdf->loadView('print', [
            'order' => $order,
            'company' => $company
        ]);

        return $pdf->stream($id . '.pdf');

        // return view('print', compact('order', 'company'));
        // dd($company);
    }
}
