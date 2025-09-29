<?php

namespace App\Controllers;

use Spipu\Html2Pdf\Html2Pdf;

class ReporteController extends BaseController
{
    public function pdf()
    {
        $html2pdf = new Html2Pdf();
        $html2pdf->writeHTML("<h1>Hola Mundo</h1><p>Este es un PDF generado con Html2Pdf en CodeIgniter 4</p>");
        $html2pdf->output("reporte.pdf");
    }
}

