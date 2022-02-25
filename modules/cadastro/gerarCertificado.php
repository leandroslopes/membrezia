<?php

setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/libs/fpdf/alphapdf.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MembroDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";

$membroDAO = new MembroDAO();
$membro  = $membroDAO->getMembro($_REQUEST["idMembro"]);

$nome = $membro->getNome();
$dataBatismo = $membro->getDataBatismo();

$pdf = new AlphaPDF();

$pdf->AddPage('L');

$pdf->SetLineWidth(1.5);

$pdf->Image('certificado.jpg',0,0,295);

$pdf->SetAlpha(1);

$pdf->SetFont('Arial', 'B', 12); 
$pdf->SetXY(54, 78); 
$pdf->MultiCell(107, 10, $nome, 0, 'C', FALSE); 

$pdf->SetFont('Arial', 'B', 12); 
$pdf->SetXY(27, 97); 
$pdf->MultiCell(94, 10, $dataBatismo, 0, 'C', FALSE); 

$pdf->SetFont('Arial', '', 16); 
$pdf->SetXY(95, 110 ); 
$pdf->MultiCell(110, 10, utf8_decode(DataUtil::imprimirDataAtual()), 0, 'C', FALSE);

$pdf->Output();