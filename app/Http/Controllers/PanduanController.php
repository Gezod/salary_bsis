<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PanduanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the panduan page with PDF viewer
     */
    public function index()
    {
        return view('panduan.index');
    }

    /**
     * Stream the PDF file
     */
    public function viewPdf()
    {
        $pdfPath = public_path('assets/documents/Buku-Panduan-BSIS-ABSENSI.pdf');

        if (!file_exists($pdfPath)) {
            abort(404, 'File panduan tidak ditemukan');
        }

        return Response::file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Buku-Panduan-BSIS-ABSENSI.pdf"'
        ]);
    }

    /**
     * Download the PDF file
     */
    public function downloadPdf()
    {
        $pdfPath = public_path('assets/documents/Buku-Panduan-BSIS-ABSENSI.pdf');

        if (!file_exists($pdfPath)) {
            abort(404, 'File panduan tidak ditemukan');
        }

        return Response::download($pdfPath, 'Buku-Panduan-BSIS-ABSENSI.pdf');
    }
}
