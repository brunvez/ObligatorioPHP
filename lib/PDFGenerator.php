<?php
require 'FPDF/fpdf.php';

class PDFGenerator {

    private $property;


    /**
     * PDFGenerator constructor.
     * @param $property \Models\Property
     */
    function __construct($property) {
        $this->property = $property;
    }

    public function generate() {
        $cell_width = 35;

        $pdf        = new FPDF();
        $pdf->AddPage();
        $pdf->SetTitle('Property Information');

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(20, 10, 'Title: ');
        $pdf->Write(10, $this->encode_to_pdf($this->property->title));
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell($cell_width, 10, 'Type: ');
        $pdf->Write(10, $this->property->type());
        $pdf->Ln();
        $pdf->Cell($cell_width, 10, 'Operation: ');
        $pdf->Write(10, $this->property->operation());
        $pdf->Ln();
        $pdf->Cell($cell_width, 10, 'Price: ');
        $pdf->Write(10, '$ ' . $this->property->price);
        $pdf->Ln();
        $pdf->Cell($cell_width, 10, 'Square Meters: ');
        $pdf->Write(10, $this->property->square_meters);
        $pdf->Ln();
        $pdf->Cell($cell_width, 10, 'Rooms: ');
        $pdf->Write(10, $this->property->rooms);
        $pdf->Ln();
        $pdf->Cell($cell_width, 10, 'Garage: ');
        $pdf->Write(10, $this->property->garage ? 'Yes' : 'No');
        $pdf->Ln();
        $pdf->Write(10, 'Description:');
        $pdf->Ln();
        $pdf->Write(6, $this->encode_to_pdf($this->property->description));
        $pdf->Ln();
        $pdf->Output();
        foreach ($this->property->images() as $image) {
            $pdf->Image($image->url);
        }
    }

    private function encode_to_pdf($text) {
        return utf8_decode($text);
    }
}