<?php

namespace App\Http\Livewire\GeneralLedger;

use Livewire\Component;
use App\Models\GeneralLedger;
use App\Models\Journal;

class KonvenDetail extends Component
{
    public $coas,$coa_group,$gl,$coa_group_id;

    public function render()
    {
        return view('livewire.general-ledger.konven-detail');
    }

    public function mount(GeneralLedger $gl)
    {
        $this->gl = $gl;

        $this->coas  = Journal::select('coas.*')->where('general_ledger_id',$this->gl->id)
                            ->join('coas','coas.id','=','journals.coa_id')
                            ->groupBy('journals.coa_id')->get();

        $this->coa_group = Journal::select('coa_groups.*')
                            ->where('general_ledger_id',$this->gl->id)
                            ->join('coas','coas.id','=','journals.coa_id')
                            ->join('coa_groups','coa_groups.id','=','coas.coa_group_id')    
                            ->groupBy('coa_groups.id')->get();        
    }

    public function updated($propertyName)
    {
        $this->coas  = Journal::select('coas.*')->where('general_ledger_id',$this->gl->id)->join('coas','coas.id','=','journals.coa_id');

        if($propertyName=='coa_group_id' and !empty($this->coa_group_id)){
            $this->coas = $this->coas->where('coas.coa_group_id',$this->coa_group_id);
        }
        $this->coas = $this->coas->groupBy('journals.coa_id')->get();

        $this->coa_group = Journal::select('coa_groups.*')
                            ->where('general_ledger_id',$this->gl->id)
                            ->join('coas','coas.id','=','journals.coa_id')
                            ->join('coa_groups','coa_groups.id','=','coas.coa_group_id')    
                            ->groupBy('coa_groups.id')->get();    
    }

    public function downloadReport()
    {
        set_time_limit(500); 
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Stalavista System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Cancelation")
                                    ->setDescription("Cancelation")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Expense");
        
        $coa_groups = Journal::select('coa_groups.*')
                            ->where('general_ledger_id',$this->gl->id)
                            ->join('coas','coas.id','=','journals.coa_id')
                            ->join('coa_groups','coa_groups.id','=','coas.coa_group_id')    
                            ->groupBy('coa_groups.id');
        
        if($this->coa_group_id) $coa_groups->where('coa_groups.id',$this->coa_group_id);

        foreach($coa_groups->get() as $index => $group){
            $num=5;
            $invalidCharacters = $objPHPExcel->getActiveSheet()->getInvalidCharacters();
            $title = str_replace($invalidCharacters, '', $group->name);
            $title = substr($title, 0, 31);

            if($index!=0){
                $objPHPExcel->createSheet();
                $objPHPExcel->setActiveSheetIndex($index)->setTitle($title);
            } else $objPHPExcel->setActiveSheetIndex($index)->setTitle($title);

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

            // Bold
            $objPHPExcel->getActiveSheet()->mergeCells('B1:H1');
            $objPHPExcel->getActiveSheet()->mergeCells('B2:H2');
            $objPHPExcel->getActiveSheet()->mergeCells('B3:H3');
            $objPHPExcel->getActiveSheet()
                ->setCellValue('B1', 'PT ASURANSI JIWA RELIANCE INDONESIA')
                ->setCellValue('B2', 'BUKU BESAR '.strtoupper($group->name))
                ->setCellValue('B3', date('d F Y',strtotime($this->gl->submit_date)));
            $objPHPExcel->getActiveSheet()->getStyle("B1")->applyFromArray(['font' => ['bold' => true]]);
            $objPHPExcel->getActiveSheet()->getStyle("B2")->applyFromArray(['font' => ['bold' => true]]);
            $objPHPExcel->getActiveSheet()->getStyle("B3")->applyFromArray(['font' => ['bold' => true]]);
            $objPHPExcel->getActiveSheet()->getStyle("B1")->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle("B2")->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle("B3")->getAlignment()->setHorizontal('center');
            
            $coas  = Journal::select('coas.*')->where('general_ledger_id',$this->gl->id)
                                ->join('coas','coas.id','=','journals.coa_id')
                                ->where('coas.coa_group_id',$group->id)
                                ->groupBy('journals.coa_id')->get();
            
            foreach($coas as $k => $coa){
                // Header
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('A'.$num, '')
                    ->setCellValue('B'.$num, 'No Voucher')
                    ->setCellValue('C'.$num, 'Date')
                    ->setCellValue('D'.$num, 'Account')
                    ->setCellValue('E'.$num, 'Description')
                    ->setCellValue('F'.$num, 'Debit')
                    ->setCellValue('G'.$num, 'Kredit')
                    ->setCellValue('H'.$num, 'Saldo');

                $objPHPExcel->getActiveSheet()->getStyle("B{$num}:H{$num}")->getAlignment()->setHorizontal('center');
                $objPHPExcel->getActiveSheet()->getStyle("B{$num}:H{$num}")
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('EEEEEE');
                // Bold
                $objPHPExcel->getActiveSheet()->getStyle("A{$num}:H{$num}")->applyFromArray(['font' => ['bold' => true]]);

                $num++;
                $objPHPExcel->getActiveSheet()
                    ->setCellValue('A'.$num, $coa->code)
                    ->setCellValue('B'.$num, $coa->name);
                $objPHPExcel->getActiveSheet()->getStyle("B{$num}")->applyFromArray(['font' => ['bold' => true]]);

                $num++;
                $objPHPExcel->setActiveSheetIndex($index)
                    ->setCellValue('B'.$num, "Saldo Awal");
                $objPHPExcel->getActiveSheet()->getStyle("B{$num}")->applyFromArray(['font' => ['bold' => true]]);
            
                // Journal
                $num++;
                $total_debit=0;$total_kredit=0;$total_saldo=0;
                foreach(Journal::where(['coa_id'=>$coa->id,'general_ledger_id'=>$this->gl->id])->get() as $journal){
                    $objPHPExcel->setActiveSheetIndex($index)
                        ->setCellValue('B'.$num,$journal->no_voucher)
                        ->setCellValue('C'.$num,date('d-M-Y',strtotime($journal->date_journal)))
                        ->setCellValue('D'.$num,isset($journal->coa->name) ? $journal->coa->name : '')
                        ->setCellValue('E'.$num,$journal->description)
                        ->setCellValue('F'.$num,$journal->debit)
                        ->setCellValue('G'.$num,$journal->kredit)
                        ->setCellValue('H'.$num,$journal->saldo);
                        $total_debit += $journal->debit;$total_kredit += $journal->kredit;$total_saldo += $journal->saldo;
                    
                    $objPHPExcel->getActiveSheet()->getStyle('F'.$num)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$num)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getNumberFormat()->setFormatCode('#,##0');
                    $objPHPExcel->getActiveSheet()->getStyle("B{$num}:C{$num}")->getAlignment()->setHorizontal('center');

                    $num++;
                }

                // Total
                $objPHPExcel->getActiveSheet()->getStyle("B{$num}:H{$num}")->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('EEEEEE');
                $objPHPExcel->getActiveSheet()->getStyle("B{$num}:H{$num}")->applyFromArray(['font' => ['bold' => true]]);
                $objPHPExcel->getActiveSheet()->mergeCells("B{$num}:E{$num}");
                $objPHPExcel->setActiveSheetIndex($index)
                    ->setCellValue('B'.$num, "Total {$coa->name}")
                    ->setCellValue('F'.$num, $total_debit)
                    ->setCellValue('G'.$num, $total_kredit)
                    ->setCellValue('H'.$num, $total_saldo);
                $objPHPExcel->getActiveSheet()->getStyle("B{$num}")->getAlignment()->setHorizontal('center');
                $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getNumberFormat()->setFormatCode('#,##0');
                $objPHPExcel->getActiveSheet()->getStyle('F'.$num.':H'.$num)->getNumberFormat()->setFormatCode('#,##0');
                
                $num++;

                $objPHPExcel->setActiveSheetIndex($index)
                    ->setCellValue('A'.$num, '')
                    ->setCellValue('B'.$num, '')
                    ->setCellValue('C'.$num, '')
                    ->setCellValue('D'.$num, '')
                    ->setCellValue('E'.$num, '')
                    ->setCellValue('F'.$num, '')
                    ->setCellValue('G'.$num, '')
                    ->setCellValue('H'.$num, '');
                $num++;
                $objPHPExcel->setActiveSheetIndex($index)
                    ->setCellValue('A'.$num, '')
                    ->setCellValue('B'.$num, '')
                    ->setCellValue('C'.$num, '')
                    ->setCellValue('D'.$num, '')
                    ->setCellValue('E'.$num, '')
                    ->setCellValue('F'.$num, '')
                    ->setCellValue('G'.$num, '')
                    ->setCellValue('H'.$num, '');
                $num++;
            }
        }
        // Rename worksheet
        //$objPHPExcel->getActiveSheet()->setTitle('Iuran-'. date('d-M-Y'));
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="general-ledger-' .date('d-M-Y') .'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        //header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        return response()->streamDownload(function() use($writer){
            $writer->save('php://output');
        },'general-ledger-' .date('d-M-Y',strtotime($this->gl->submit_date)) .'.xlsx');
    }
}
