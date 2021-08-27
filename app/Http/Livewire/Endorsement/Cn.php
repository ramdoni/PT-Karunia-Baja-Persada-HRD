<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;
use Livewire\WithPagination;

class Cn extends Component
{
    use WithPagination;
    public $keyword,$reas,$status,$type;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = \App\Models\Expenses::orderBy('id','desc');

        if(!$this->reas){
            $data = $data->where(function($table){
                $table->where('reference_type','Endorsement CN')
                        ->orWhere('reference_type','Endorsement CN Reas');
                });
        }else{
            if($this->reas=='Reas') $data = $data->where('reference_type','Endorsement CN Reas');
            if($this->reas=='Ajri') $data = $data->where('reference_type','Endorsement CN');
        }
        if($this->keyword) $data = $data->where('description','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                        ->orWhere('reference_no','LIKE',"%{$this->keyword}%");
        if($this->status) $data = $data->where('status',$this->status);
        if($this->type) $data = $data->where('type',$this->type);
        
        $total = clone $data;

        return view('livewire.endorsement.cn')->with(['data'=>$data->paginate(100),'total_payment_amount'=>format_idr($total->sum('payment_amount'))]);
    }

    public function mount()
    {
        \LogActivity::add("Endorsement Credit Note");
    }
    public function delete($id)
    {
        \App\Models\Expenses::find($id)->delete();
    }
    public function downloadExcel()
    {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Stalavista System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Endorsement")
                                    ->setDescription("Endorsement")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Expense");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Endorsement Credit Note');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(false);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'Type')
                    ->setCellValue('C3', 'Status')
                    ->setCellValue('D3', 'No Voucher')
                    ->setCellValue('E3', 'Payment Date')
                    ->setCellValue('F3', 'Voucher Date')
                    ->setCellValue('G3', 'Debit Note / Kwitansi')
                    ->setCellValue('H3', 'Policy Number / Policy Holder')
                    ->setCellValue('I3', 'From Bank Account')
                    ->setCellValue('J3', 'To Bank Account')
                    ->setCellValue('K3', 'Bank Charges')
                    ->setCellValue('L3', 'Payment Amount');
        $objPHPExcel->getActiveSheet()->getStyle('A3:L3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:L3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
        $objPHPExcel->getActiveSheet()->freezePane('A4');
        $objPHPExcel->getActiveSheet()->setAutoFilter('B3:L3');
        $num=4;
        $data = \App\Models\Expenses::orderBy('id','desc');

        if(!$this->reas){
            $data = $data->where(function($table){
                $table->where('reference_type','Endorsement CN')
                        ->orWhere('reference_type','Endorsement CN Reas');
                });
        }else{
            if($this->reas=='Reas') $data = $data->where('reference_type','Endorsement CN Reas');
            if($this->reas=='Ajri') $data = $data->where('reference_type','Endorsement CN');
        }
        if($this->keyword) $data = $data->where('description','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                        ->orWhere('reference_no','LIKE',"%{$this->keyword}%");
        if($this->status) $data = $data->where('status',$this->status);
        if($this->type) $data = $data->where('type',$this->type);
        foreach($data->get() as $k => $i){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, ($k+1))
                ->setCellValue('B'.$num, ($i->reference_type=='Endorsement DN Reas'?'Reas':'Ajri'))
                ->setCellValue('C'.$num, strip_tags(status_income($i->status)))
                ->setCellValue('D'.$num,$i->no_voucher .' ['.($i->type==1?'K':'S').']')
                ->setCellValue('E'.$num,$i->payment_date)
                ->setCellValue('F'.$num,$i->created_at)
                ->setCellValue('G'.$num,$i->reference_no)
                ->setCellValue('H'.$num,$i->client)
                ->setCellValue('I'.$num,isset($i->from_bank_account->no_rekening) ? $i->from_bank_account->no_rekening .'- '.$i->from_bank_account->bank.' an '. $i->from_bank_account->owner : '-')
                ->setCellValue('J'.$num,isset($i->bank_account->no_rekening) ? $i->bank_account->no_rekening .'- '.$i->bank_account->bank.' an '. $i->bank_account->owner : '-')
                ->setCellValue('K'.$num,$i->bank_charges)
                ->setCellValue('L'.$num,$i->payment_amount);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('K'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode('#,##0');
            
            $num++;
        }
        // Rename worksheet
        //$objPHPExcel->getActiveSheet()->setTitle('Iuran-'. date('d-M-Y'));
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="claim-' .date('d-M-Y') .'.xlsx"');
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
        },'endorsement-credit-note-' .date('d-M-Y') .'.xlsx');
    }
}