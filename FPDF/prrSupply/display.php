<?php
    $pdf = new PDF('P','pt','A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();

    $pdf->SetFont('Arial','',10);;
    $pdf->tablewidths = array(15,20,400,45,22,15,19) ;
    $data1[] = array("RANK","ID #","NAME","POSITION","STATUS","DAYS","MINUTES");
    $pdf->morepagestable($data1);
    $data2[] = array("RANK","ID #"," THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG
                                    THE QUICK BROWN FOX JUMPS OVER THE LAZY DOG\nRUSEL TAYONG
    ","POSITION","STATUS","DAYS","MINUTES");
    $pdf->morepagestable($data2);

/*    $pdf->SetWidths(array(90));
    $pdf->Row(array("ASHDASLDHASLDSDJKFJDSLF;JSD;FJSD;LFJSDF;LKSDJFLSD;KFJS!"));*/



$pdf->Output();