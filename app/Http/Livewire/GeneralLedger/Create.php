<?php

namespace App\Http\Livewire\GeneralLedger;

use Livewire\Component;
use App\Models\CoaGroup;
use App\Models\Coa;
use App\Models\Journal;
use App\Models\GeneralLedger;

class Create extends Component
{
    public $coa,$year,$month;

    public $total_selected,$coa_group_id,$from_date,$to_date,$create_gl=false,$journal_id=[],$message_error="",$coas,$coa_group;

    public function render()
    {
        return view('livewire.general-ledger.create');
    }

    public function mount(CoaGroup $coa_group)
    {
        $this->coa_group = $coa_group;
        
        foreach(Journal::select('journals.*')->join('coas','coas.id','=','journals.coa_id')->whereNull('journals.general_ledger_id')->where(['coas.coa_group_id'=>$this->coa_group->id])->get() as $journal){
            $this->journal_id[$journal->id] = $journal->status_general_ledger ==1 ? 1 : 0; 
        }

        $this->total_selected = Journal::select('journals.*')->join('coas','coas.id','=','journals.coa_id')->where(['status_general_ledger'=>1,'coas.coa_group_id'=>$this->coa_group->id])->count();
    }
    
    public function updated()
    {
        if($this->from_date and $this->to_date) $this->coas = Journal::where(['coa_id'=>$this->coa->id])->whereNull('general_ledger_id')->whereBetween('date_journal',[$this->from_date,$this->to_date])->get();
    }

    public function select_all($coa_id)
    {
        $journal = Journal::where(['coa_id'=>$coa_id])->whereNull('general_ledger_id')->get();
        foreach($journal as $item){
            $item->status_general_ledger = 1;
            $item->save();
            $this->journal_id[$item->id] = 1;
        }

        $this->total_selected = Journal::join('coas','coas.id','=','journals.coa_id')->where(['status_general_ledger'=>1,'coas.coa_group_id'=>$this->coa_group->id])->count();
        $this->emit('added-journal', "Select All");
    }

    public function downloadReport()
    {
        $this->validate([
            'year' => 'required',
            'month' => 'required'
        ]);
        $this->message_error = "";
        $check = GeneralLedger::where(['year'=>$this->year,'month'=>$this->month])->first();
        if($check){
            set_time_limit(500); 
            $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Stalavista System")
                                        ->setLastModifiedBy("Stalavista System")
                                        ->setTitle("Office 2007 XLSX Product Database")
                                        ->setSubject("General Ledger")
                                        ->setDescription("General Ledger")
                                        ->setKeywords("office 2007 openxml php")
                                        ->setCategory("General Ledger");
        
            $num=5;
            $invalidCharacters = $objPHPExcel->getActiveSheet()->getInvalidCharacters();
            $title = str_replace($invalidCharacters, '', $this->coa->name);
            $title = substr($title, 0, 31);

            $objPHPExcel->setActiveSheetIndex(0)->setTitle($title);

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
                ->setCellValue('B2', 'BUKU BESAR '.strtoupper($this->coa->name))
                ->setCellValue('B3', date('F', mktime(0, 0, 0, $this->month, 10)).' '.$this->year);
            $objPHPExcel->getActiveSheet()->getStyle("B1")->applyFromArray(['font' => ['bold' => true]]);
            $objPHPExcel->getActiveSheet()->getStyle("B2")->applyFromArray(['font' => ['bold' => true]]);
            $objPHPExcel->getActiveSheet()->getStyle("B3")->applyFromArray(['font' => ['bold' => true]]);
            $objPHPExcel->getActiveSheet()->getStyle("B1")->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle("B2")->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle("B3")->getAlignment()->setHorizontal('center');
            
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
                ->setCellValue('A'.$num, $this->coa->code)
                ->setCellValue('B'.$num, $this->coa->name);
            $objPHPExcel->getActiveSheet()->getStyle("B{$num}")->applyFromArray(['font' => ['bold' => true]]);

            $num++;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$num, "Saldo Awal");
            $objPHPExcel->getActiveSheet()->getStyle("B{$num}")->applyFromArray(['font' => ['bold' => true]]);
        
            // Journal
            $num++;
            $total_debit=0;$total_kredit=0;$total_saldo=0;
            foreach(Journal::where(['general_ledger_id'=>$check->id])->get() as $journal){
                $objPHPExcel->setActiveSheetIndex(0)
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
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$num, "Total {$this->coa->name}")
                ->setCellValue('F'.$num, $total_debit)
                ->setCellValue('G'.$num, $total_kredit)
                ->setCellValue('H'.$num, $total_saldo);
            $objPHPExcel->getActiveSheet()->getStyle("B{$num}")->getAlignment()->setHorizontal('center');
            $objPHPExcel->getActiveSheet()->getStyle('D'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('F'.$num.':H'.$num)->getNumberFormat()->setFormatCode('#,##0');
            
            $num++;

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, '')
                ->setCellValue('B'.$num, '')
                ->setCellValue('C'.$num, '')
                ->setCellValue('D'.$num, '')
                ->setCellValue('E'.$num, '')
                ->setCellValue('F'.$num, '')
                ->setCellValue('G'.$num, '')
                ->setCellValue('H'.$num, '');
            $num++;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, '')
                ->setCellValue('B'.$num, '')
                ->setCellValue('C'.$num, '')
                ->setCellValue('D'.$num, '')
                ->setCellValue('E'.$num, '')
                ->setCellValue('F'.$num, '')
                ->setCellValue('G'.$num, '')
                ->setCellValue('H'.$num, '');
            $num++;
            
            
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
            },'general-ledger-' .date('d-M-Y') .'.xlsx');
            
        }else{
            $this->message_error = 'Report belum di buat.';
        }
    }

    public function set_status_general_ledger(Journal $selected)
    {
        if($selected->status_general_ledger==1){
            $this->emit('added-journal', "Remove {$selected->no_voucher}");
            $selected->status_general_ledger = 0;
        }else{
            $this->emit('added-journal', "Add {$selected->no_voucher}");
            $selected->status_general_ledger = 1;
        }

        $selected->save();

        $this->total_selected = Journal::join('coas','coas.id','=','journals.coa_id')
                                            ->where(['status_general_ledger'=>1,'coas.coa_group_id'=>$this->coa_group->id])->count();
    }

    public function clear_selected()
    {
        Journal::where(['status_general_ledger'=>1])->update(['status_general_ledger'=>0]);
        
        $this->total_selected = Journal::join('coas','coas.id','=','journals.coa_id')->where(['status_general_ledger'=>1,'coa.coa_group_id'=>$this->coa_group->id])->count();
        $this->emit('added-journal', "Clear selected data...");
    }
}
