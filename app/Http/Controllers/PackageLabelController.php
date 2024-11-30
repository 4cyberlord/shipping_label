<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Barryvdh\DomPDF\Facade\Pdf;

class PackageLabelController extends Controller
{
    public function show(Package $package)
    {
        $pdf = PDF::loadView('packages.shipping-label', compact('package'));

        // Set paper size to 4x6 inches
        $pdf->setPaper([0, 0, 288, 432], 'portrait'); // 4x6 inches in points (72 points per inch)

        // Disable default margins
        $pdf->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0);

        return $pdf->stream('shipping-label.pdf');
    }
}
