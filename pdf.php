<?php

/**
 * This script generates a PDF File using an opensource PHP Library
 *
 * @see http://www.fpdf.org
 * @package php-beyond-the-web
 * @author Raheel <raheelwp@gmail.com>
 */
require "fpdf.php";

$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('http://static.php.net/www.php.net/images/php.gif', 10);
$pdf->SetFont('Arial', 'B', 24);
$pdf->Cell(0, 30, 'An Important Report', 'B', 1, 'C');
$pdf->SetFont('Arial', null, 10);
$pdf->Cell(0, 12, 'Lots of really important text goes here. Etc.', null, 1);
$filename = tempnam(__DIR__, "rep").'.pdf';
$pdf->Output($filename, 'F');
