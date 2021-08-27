<?php

namespace App\Http\Livewire\ExpenseReinsurance;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $keyword,$status,$unit,$received,$outstanding;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = \App\Models\Expenses::orderBy('id','desc')->where('reference_type','Reinsurance Premium');
        if($this->keyword) $data = $data->where(function($table){
                                        $table->where('description','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                        ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
                                        ->orWhere('recipient','LIKE',"%{$this->keyword}%")
                                        ;
                                    });
        if($this->status) $data = $data->where('status',$this->status);
        if($this->unit) $data = $data->where('type',$this->unit);
        
        $this->received = clone $data;$this->received = $this->received->where('status',2)->sum('payment_amount');
        $this->outstanding = clone $data;$this->outstanding = $this->outstanding->sum('outstanding_balance');
                
        return view('livewire.expense-reinsurance.index')->with(['data'=>$data->paginate(100),'total'=>$data->sum('nominal')]);
    }
    public function mount()
    {
        \LogActivity::add('Expense - Reinsurance Premium');
    }
    public function downloadExcel()
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Stalavista System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Reinsurance Premium")
                                    ->setDescription("Reinsurance Premium.")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Income");

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Reinsurance Premium');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(false);
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'Status')
                    ->setCellValue('C3', 'No Voucher')
                    ->setCellValue('D3', 'Payment Date')
                    ->setCellValue('E3', 'Voucher Date')
                    ->setCellValue('F3', 'Reference Date')
                    ->setCellValue('G3', 'Debit Note / Kwitansi')
                    ->setCellValue('H3', 'Policy Number / Policy Holder')
                    ->setCellValue('I3', 'Total')
                    ->setCellValue('J3', 'From Bank Account')
                    ->setCellValue('K3', 'To Bank Account')
                    ->setCellValue('L3', 'Bank Charges')
                    ->setCellValue('M3', 'Outstanding Balance')
                    ->setCellValue('N3', 'Payment Amount')
                    ;

        $objPHPExcel->getActiveSheet()->getStyle('A3:N3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:N3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        //$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()
                            //->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            //->getStartColor()->setRGB('e2efd9')
                        // ;
        $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(34);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->freezePane('A4');
        $objPHPExcel->getActiveSheet()->setAutoFilter('B3:N3');
        $num=4;
        $data = \App\Models\Expenses::orderBy('id','desc')->where('reference_type','Reinsurance Premium');
        if($this->keyword) $data = $data->where(function($table){
                                        $table->where('description','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                        ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
                                        ->orWhere('recipient','LIKE',"%{$this->keyword}%")
                                        ;
                                    });
        if($this->status) $data = $data->where('status',$this->status);
        if($this->unit) $data = $data->where('type',$this->unit);
        foreach($data->get() as $k => $i){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, ($k+1))
                ->setCellValue('B'.$num, strip_tags(status_income($i->status)))
                ->setCellValue('C'.$num,$i->no_voucher .' ['.($i->type==1?'K':'S').']')
                ->setCellValue('D'.$num,$i->payment_date)
                ->setCellValue('E'.$num,$i->created_at)
                ->setCellValue('F'.$num,$i->reference_date)
                ->setCellValue('G'.$num,$i->reference_no)
                ->setCellValue('H'.$num,$i->recipient)
                ->setCellValue('I'.$num,$i->nominal)
                ->setCellValue('J'.$num,isset($i->from_bank_account->no_rekening) ? $i->from_bank_account->no_rekening .'- '.$i->from_bank_account->bank.' an '. $i->from_bank_account->owner : '-')
                ->setCellValue('K'.$num,isset($i->bank_account->no_rekening) ? $i->bank_account->no_rekening .'- '.$i->bank_account->bank.' an '. $i->bank_account->owner : '-')
                ->setCellValue('L'.$num,$i->outstanding_balance)
                ->setCellValue('M'.$num,$i->bank_charges)
                ->setCellValue('N'.$num,$i->payment_amount)
                ;
            $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode('#,##0');
            
            $num++;
        }

        // Rename worksheet
        //$objPHPExcel->getActiveSheet()->setTitle('Iuran-'. date('d-M-Y'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="reinsurance-premium' .date('d-M-Y') .'.xlsx"');
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
        },'reinsurance-premium' .date('d-M-Y') .'.xlsx');
    }
}