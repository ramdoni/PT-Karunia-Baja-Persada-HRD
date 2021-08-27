<?php

namespace App\Http\Livewire\IncomePremiumReceivable;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $keyword,$unit,$status,$payment_date_from,$payment_date_to,$voucher_date;
    protected $paginationTheme = 'bootstrap',$export_data,$queryString = ['page'];
    public function render()
    {
        $data = \App\Models\Income::orderBy('id','desc')->where('reference_type','Premium Receivable');
        
        if($this->keyword) $data = $data->where('description','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                        ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
                                        ->orWhere('client','LIKE',"%{$this->keyword}%");
        if($this->unit) $data = $data->where('type',$this->unit);
        if($this->status) $data = $data->where('status',$this->status);
        if($this->payment_date_from and $this->payment_date_to) $data = $data->whereBetween('payment_date',[$this->payment_date_from,$this->payment_date_to]);
        if($this->voucher_date) $data = $data->whereDate('created_at',$this->voucher_date);
        
        $received = clone $data;
        $outstanding = clone $data;

        return view('livewire.income-premium-receivable.index')->with([
            'data'=>$data->paginate(100),
            'total'=>$received->where('is_auto',0)->sum('nominal'),
            'received'=>$received->where('is_auto',0)->where('status',2)->sum('payment_amount'),
            'outstanding'=>$outstanding->where('is_auto',0)->where('status',3)->sum('outstanding_balance')
        ]);
    }

    public function mount()
    {
        if(isset($_GET['keyword'])) $this->keyword = $_GET['keyword'];
        if(isset($_GET['unit'])) $this->unit = $_GET['unit'];
        if(isset($_GET['status'])) $this->status = $_GET['status'];
        if(isset($_GET['payment_date_from'])) $this->status = $_GET['payment_date_from'];
        if(isset($_GET['payment_date_to'])) $this->status = $_GET['payment_date_to'];

        \LogActivity::add('Income - Premium Receivable');
    }

    public function updated($propertyName="")
    {
        $query['keyword'] = $this->keyword;
        $query['unit'] = $this->unit;
        $query['status'] = $this->status;
        $query['payment_date_from'] = $this->payment_date_from;
        $query['payment_date_to'] = $this->payment_date_to;
        
        $query[$propertyName] = $this->$propertyName;
        $query['page'] = $this->page;

        session(['url_back'=>route('income.premium-receivable',$query)]);
        
        $this->emit('update-url',route('income.premium-receivable',$query));
    }

    public function downloadExcel()
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Stalavista System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Premium Receivable")
                                    ->setDescription("Premium Receivable.")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Income");

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Premium Receivable');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(false);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'Status')
                    ->setCellValue('C3', 'No Voucher')
                    ->setCellValue('D3', 'Payment Date')
                    ->setCellValue('E3', 'Voucher Date')
                    ->setCellValue('F3', 'Reference Date')
                    ->setCellValue('G3', 'Aging')
                    ->setCellValue('H3', 'Due Date')
                    ->setCellValue('I3', 'Debit Note / Kwitansi')
                    ->setCellValue('J3', 'Policy Number / Policy Holder')
                    ->setCellValue('K3', 'Total')
                    ->setCellValue('L3', 'Cancelation')
                    ->setCellValue('M3', 'Endorsement')
                    ->setCellValue('N3', 'From Bank Account')
                    ->setCellValue('O3', 'To Bank Account')
                    ->setCellValue('P3', 'Outstanding Balance')
                    ->setCellValue('Q3', 'Bank Charges')
                    ->setCellValue('R3', 'Payment Amount')
                    ;

        $objPHPExcel->getActiveSheet()->getStyle('A3:R3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:R3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->freezePane('A4');
        $objPHPExcel->getActiveSheet()->setAutoFilter('B3:R3');
        $num=4;
        $data = \App\Models\Income::orderBy('id','desc')->where('reference_type','Premium Receivable');
        $received = clone $data;
        $outstanding = clone $data;
        if($this->keyword) $data = $data->where('description','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                        ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
                                        ->orWhere('client','LIKE',"%{$this->keyword}%");
        if($this->unit) $data = $data->where('type',$this->unit);
        if($this->status) $data = $data->where('status',$this->status);
        if($this->payment_date_from and $this->payment_date_to) $data = $data->whereBetween('payment_date',[$this->payment_date_from,$this->payment_date_to]);
        if($this->voucher_date) $data = $data->whereDate('created_at',$this->voucher_date);
        foreach($data->get() as $k => $i){
            $cancelation = 0;
            $endorsement = 0;
            if($i->type ==1)
                $cancelation = isset($i->cancelation_konven)?$i->cancelation_konven->sum('nominal'):0;
            else
                $cancelation = isset($i->cancelation_syariah)?$i->cancelation_syariah->sum('nominal'):0;

            if($i->type ==1)
                $cancelation = isset($i->endorsemement_konven)?$i->endorsemement_konven->sum('nominal'):0;
            else
                $cancelation = isset($i->endorsemement_syariah)?$i->endorsemement_syariah->sum('nominal'):0;

            
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, ($k+1))
                ->setCellValue('B'.$num, strip_tags(status_income($i->status)))
                ->setCellValue('C'.$num,$i->no_voucher .' ['.($i->type==1?'K':'S').']')
                ->setCellValue('D'.$num,$i->payment_date)
                ->setCellValue('E'.$num,$i->created_at)
                ->setCellValue('F'.$num,$i->reference_date)
                ->setCellValue('G'.$num,calculate_aging($i->reference_date))
                ->setCellValue('H'.$num,$i->due_date?date('d M Y',strtotime($i->due_date)):'')
                ->setCellValue('I'.$num,$i->reference_no)
                ->setCellValue('J'.$num,$i->client)
                ->setCellValue('K'.$num,$cancelation)
                ->setCellValue('L'.$num,$endorsement)
                ->setCellValue('M'.$num,$i->nominal)
                ->setCellValue('N'.$num,isset($i->from_bank_account->no_rekening) ? $i->from_bank_account->no_rekening .'- '.$i->from_bank_account->bank.' an '. $i->from_bank_account->owner : '-')
                ->setCellValue('O'.$num,isset($i->bank_account->no_rekening) ? $i->bank_account->no_rekening .'- '.$i->bank_account->bank.' an '. $i->bank_account->owner : '-')
                ->setCellValue('P'.$num,$i->outstanding_balance)
                ->setCellValue('Q'.$num,$i->bank_charges)
                ->setCellValue('R'.$num,$i->payment_amount)
                ;
            $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('P'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('Q'.$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('R'.$num)->getNumberFormat()->setFormatCode('#,##0');
            
            $num++;
        }

        // Rename worksheet
        //$objPHPExcel->getActiveSheet()->setTitle('Iuran-'. date('d-M-Y'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="premium-receivable-' .date('d-M-Y') .'.xlsx"');
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
        },'premium-reveivable-' .date('d-M-Y') .'.xlsx');
    }
}