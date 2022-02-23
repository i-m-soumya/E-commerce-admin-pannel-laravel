<?php

namespace App\Http\Controllers;

use PDF;

class PDFController extends Controller
{
    public function generatepdf()
    {
        $name = "Debanjan";
        $pdf = PDF::loadView('pdf.dummy_pdf', ['name' => $name]);
        return $pdf->stream('pdf_file.pdf');
    }
    public function generate_order_pdf()
    {
        $name = "Debanjan";
        $pdf = PDF::loadView('pdf.order_invoice_pdf', ['name' => $name]);
        return $pdf->stream('pdf_file.pdf');
    }
}
