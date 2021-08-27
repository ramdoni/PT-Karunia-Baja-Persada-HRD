<?php

namespace App\Http\Livewire\ExpenseCommisionPayable;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expenses;
use App\Models\ExpensePayment;
use LogActivity;

class Index extends Component
{
    use WithPagination;
    public $keyword,$unit,$status,$paging_total_=1;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = Expenses::orderBy('id','desc')->where('reference_type','Komisi');
        if($this->keyword) $data = $data->where(function($table){
                                            $table->where('description','LIKE', "%{$this->keyword}%")
                                            ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                            ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
                                            ->orWhere('recipient','LIKE',"%{$this->keyword}%");
                                        });
        if($this->status) $data = $data->where('status',$this->status);
        if($this->unit) $data = $data->where('type',$this->unit);
        
        $total = clone $data;
        $fee_base = 0;$maintenance=0;$admin_agency=0;$agen_penutup=0;$operasional_agency=0;$handling_fee_broker=0;$referal_fee=0;
        foreach($total->get() as $item){
            $fee_base += $item->payment_fee_base ? $item->payment_fee_base->payment_amount:0;
            $maintenance += $item->payment_maintenance ? $item->payment_maintenance->payment_amount:0;
            $admin_agency += $item->payment_admin_agency ? $item->payment_admin_agency->payment_amount:0;
            $agen_penutup += $item->payment_agen_penutups ? $item->payment_agen_penutup->payment_amount:0;
            $operasional_agency += $item->payment_operasional_agency ? $item->payment_operasional_agency->payment_amount:0;
            $handling_fee_broker += $item->payment_handling_fee_broker ? $item->payment_handling_fee_broker->payment_amount:0;
            $referal_fee += $item->payment_referal_fee ? $item->payment_referal_fee->payment_amount:0;
        }
        
        return view('livewire.expense-commision-payable.index')->with([
            'fee_base'=>$fee_base,
            'maintenance'=>$maintenance,
            'admin_agency'=>$admin_agency,
            'agen_penutup'=>$agen_penutup,
            'operasional_agency'=>$operasional_agency,
            'handling_fee_broker'=>$handling_fee_broker,
            'referal_fee'=>$referal_fee,
            'data'=>$data->paginate(100),'total'=>0,'received'=>0,'outstanding'=>0]);
    }
    public function delete($id)
    {
        Expenses::find($id)->delete();
        ExpensePayment::where('expense_id',$id)->delete();
        LogActivity::add("Expense - Commision Payable Delete {$id}");
    }
    public function mount()
    {
        LogActivity::add('Expense - Commision Payable');
    }
    public function downloadExcel()
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Stalavista System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Commision Payable")
                                    ->setDescription("Commision Payable.")
                                    ->setKeywords("office 2007 openxml php")
                                    ->setCategory("Income");

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Commision Payable');
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(false);
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'Status')
                    ->setCellValue('C3', 'No Voucher')
                    ->setCellValue('D3', 'Voucher Date')
                    ->setCellValue('E3', 'Debit Note / Kwitansi')
                    ->setCellValue('F3', 'Policy Number / Policy Holder')
                    ->setCellValue('G3', 'Fee Base')
                    ->setCellValue('K3', 'Maintenance')
                    ->setCellValue('O3', 'Admin Agency')
                    ->setCellValue('S3', 'Agen Penutup')
                    ->setCellValue('W3', 'Operasional Agency')
                    ->setCellValue('AA3', 'Handling Fee Broker')
                    ->setCellValue('AE3', 'Referal Fee');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:A4'); // No
        $objPHPExcel->getActiveSheet()->mergeCells('B3:B4'); // Status
        $objPHPExcel->getActiveSheet()->mergeCells('C3:C4'); // No Voucher
        $objPHPExcel->getActiveSheet()->mergeCells('D3:D4'); // Voucher Date
        $objPHPExcel->getActiveSheet()->mergeCells('E3:E4'); // Debit Note / Kwitansi
        $objPHPExcel->getActiveSheet()->mergeCells('F3:F4'); // Policy Number / Policy Holder
        $objPHPExcel->getActiveSheet()->mergeCells('G3:J3'); // Fee Base
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('G4', 'Biaya')
                    ->setCellValue('H4', 'Nama Penerima')
                    ->setCellValue('I4', 'Bank Penerima')
                    ->setCellValue('J4', 'Rekening Penerima');
        
        $objPHPExcel->getActiveSheet()->mergeCells('K3:N3'); // Maintenance
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('K4', 'Biaya')
                    ->setCellValue('L4', 'Nama Penerima')
                    ->setCellValue('M4', 'Bank Penerima')
                    ->setCellValue('N4', 'Rekening Penerima');
        
        $objPHPExcel->getActiveSheet()->mergeCells('O3:R3'); // Admnin Agency
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('O4', 'Biaya')
                    ->setCellValue('P4', 'Nama Penerima')
                    ->setCellValue('Q4', 'Bank Penerima')
                    ->setCellValue('R4', 'Rekening Penerima');
        
        $objPHPExcel->getActiveSheet()->mergeCells('S3:V3'); // Admnin Penutup
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('S4', 'Biaya')
                    ->setCellValue('T4', 'Nama Penerima')
                    ->setCellValue('U4', 'Bank Penerima')
                    ->setCellValue('V4', 'Rekening Penerima');

        $objPHPExcel->getActiveSheet()->mergeCells('W3:Z3'); // Operasional Agency
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('W4', 'Biaya')
                    ->setCellValue('X4', 'Nama Penerima')
                    ->setCellValue('Y4', 'Bank Penerima')
                    ->setCellValue('Z4', 'Rekening Penerima');
        
        $objPHPExcel->getActiveSheet()->mergeCells('AA3:AD3'); // Handling Fee Broker
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('AA4', 'Biaya')
                    ->setCellValue('AB4', 'Nama Penerima')
                    ->setCellValue('AC4', 'Bank Penerima')
                    ->setCellValue('AD4', 'Rekening Penerima');
        
        $objPHPExcel->getActiveSheet()->mergeCells('AE3:AH3'); // Referal Fee
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('AE4', 'Biaya')
                    ->setCellValue('AF4', 'Nama Penerima')
                    ->setCellValue('AG4', 'Bank Penerima')
                    ->setCellValue('AH4', 'Rekening Penerima');
                    
        $objPHPExcel->getActiveSheet()->getStyle('A3:AH3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:AH3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A4:AH4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A4:AH4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        //$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()
                            //->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            //->getStartColor()->setRGB('e2efd9')
                        // ;
        // $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AK')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->freezePane('A5');
        // $objPHPExcel->getActiveSheet()->setAutoFilter('B3:N3');
        $num=5;
        $data = Expenses::orderBy('id','desc')->where('reference_type','Komisi');
        if($this->keyword) $data = $data->where(function($table){
                                            $table->where('description','LIKE', "%{$this->keyword}%")
                                            ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                            ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
                                            ->orWhere('recipient','LIKE',"%{$this->keyword}%");
                                        });
        if($this->status) $data = $data->where('status',$this->status);
        if($this->unit) $data = $data->where('type',$this->unit);
        foreach($data->get() as $k => $item){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, ($k+1))
                ->setCellValue('B'.$num, strip_tags(status_income($item->status)))
                ->setCellValue('C'.$num,$item->no_voucher .' ['.($item->type==1?'K':'S').']')
                ->setCellValue('D'.$num,date('d M Y', strtotime($item->created_at)))
                ->setCellValue('E'.$num,$item->reference_no)
                ->setCellValue('F'.$num,$item->recipient)
                ->setCellValue('G'.$num,isset($item->payment_fee_base->payment_amount) ? $item->payment_fee_base->payment_amount : '-')
                ->setCellValue('H'.$num,isset($item->payment_fee_base->to_bank_account->owner) ? $item->payment_fee_base->to_bank_account->owner : '-')
                ->setCellValue('I'.$num,isset($item->payment_fee_base->to_bank_account->bank) ? $item->payment_fee_base->to_bank_account->bank : '-')
                ->setCellValue('J'.$num,isset($item->payment_fee_base->to_bank_account->no_rekening) ? $item->payment_fee_base->to_bank_account->no_rekening : '-')

                ->setCellValue('K'.$num,isset($item->payment_maintenance->payment_amount) ? $item->payment_maintenance->payment_amount : '-')
                ->setCellValue('L'.$num,isset($item->payment_maintenance->to_bank_account->owner) ? $item->payment_maintenance->to_bank_account->owner : '-')
                ->setCellValue('M'.$num,isset($item->payment_maintenance->to_bank_account->bank) ? $item->payment_maintenance->to_bank_account->bank : '-')
                ->setCellValue('N'.$num,isset($item->payment_maintenance->to_bank_account->no_rekening) ? $item->payment_maintenance->to_bank_account->no_rekening : '-')
                
                ->setCellValue('O'.$num,isset($item->payment_admin_agency->payment_amount) ? $item->payment_admin_agency->payment_amount : '-')
                ->setCellValue('P'.$num,isset($item->payment_admin_agency->to_bank_account->owner) ? $item->payment_admin_agency->to_bank_account->owner : '-')
                ->setCellValue('Q'.$num,isset($item->payment_admin_agency->to_bank_account->bank) ? $item->payment_admin_agency->to_bank_account->bank : '-')
                ->setCellValue('R'.$num,isset($item->payment_admin_agency->to_bank_account->no_rekening) ? $item->payment_admin_agency->to_bank_account->no_rekening : '-')
                
                ->setCellValue('S'.$num,isset($item->payment_agen_penutup->payment_amount) ? $item->payment_agen_penutup->payment_amount : '-')
                ->setCellValue('T'.$num,isset($item->payment_agen_penutup->to_bank_account->owner) ? $item->payment_agen_penutup->to_bank_account->owner : '-')
                ->setCellValue('U'.$num,isset($item->payment_agen_penutup->to_bank_account->bank) ? $item->payment_agen_penutup->to_bank_account->bank : '-')
                ->setCellValue('V'.$num,isset($item->payment_agen_penutup->to_bank_account->no_rekening) ? $item->payment_agen_penutup->to_bank_account->no_rekening : '-')
                
                ->setCellValue('W'.$num,isset($item->payment_operasional_agency->payment_amount) ? $item->payment_operasional_agency->payment_amount : '-')
                ->setCellValue('X'.$num,isset($item->payment_operasional_agency->to_bank_account->owner) ? $item->payment_operasional_agency->to_bank_account->owner : '-')
                ->setCellValue('Y'.$num,isset($item->payment_operasional_agency->to_bank_account->bank) ? $item->payment_operasional_agency->to_bank_account->bank : '-')
                ->setCellValue('Z'.$num,isset($item->payment_operasional_agency->to_bank_account->no_rekening) ? $item->payment_operasional_agency->to_bank_account->no_rekening : '-')
                
                ->setCellValue('AA'.$num,isset($item->payment_handling_fee_broker->payment_amount) ? $item->payment_handling_fee_broker->payment_amount : '-')
                ->setCellValue('AB'.$num,isset($item->payment_handling_fee_broker->to_bank_account->owner) ? $item->payment_handling_fee_broker->to_bank_account->owner : '-')
                ->setCellValue('AC'.$num,isset($item->payment_handling_fee_broker->to_bank_account->bank) ? $item->payment_handling_fee_broker->to_bank_account->bank : '-')
                ->setCellValue('AD'.$num,isset($item->payment_handling_fee_broker->to_bank_account->no_rekening) ? $item->payment_handling_fee_broker->to_bank_account->no_rekening : '-')
                
                ->setCellValue('AE'.$num,isset($item->payment_referal_fee->payment_amount) ? $item->payment_referal_fee->payment_amount : '-')
                ->setCellValue('AF'.$num,isset($item->payment_referal_fee->to_bank_account->owner) ? $item->payment_referal_fee->to_bank_account->owner : '-')
                ->setCellValue('AG'.$num,isset($item->payment_referal_fee->to_bank_account->bank) ? $item->payment_referal_fee->to_bank_account->bank : '-')
                ->setCellValue('AH'.$num,isset($item->payment_referal_fee->to_bank_account->no_rekening) ? $item->payment_referal_fee->to_bank_account->no_rekening : '-')    
                ;
                
            // $objPHPExcel->getActiveSheet()->getStyle('I'.$num)->getNumberFormat()->setFormatCode('#,##0');
            // $objPHPExcel->getActiveSheet()->getStyle('L'.$num)->getNumberFormat()->setFormatCode('#,##0');
            // $objPHPExcel->getActiveSheet()->getStyle('M'.$num)->getNumberFormat()->setFormatCode('#,##0');
            // $objPHPExcel->getActiveSheet()->getStyle('N'.$num)->getNumberFormat()->setFormatCode('#,##0');
            
            $num++;
        }

        // Rename worksheet
        //$objPHPExcel->getActiveSheet()->setTitle('Iuran-'. date('d-M-Y'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="commision-payable' .date('d-M-Y') .'.xlsx"');
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
        },'commision-payable' .date('d-M-Y') .'.xlsx');
    }
}