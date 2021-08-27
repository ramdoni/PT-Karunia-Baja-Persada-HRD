<?php

namespace App\Http\Livewire\CashFlow;

use Livewire\Component;

class InternalReport extends Component
{
    public $year,$month;
    public $total_januari=0,$total_februari=0,$total_maret=0,$total_april=0,$total_mei=0,$total_juni=0,$total_juli=0,$total_agustus=0,$total_september=0,$total_oktober=0,$total_november=0,$total_desember=0;
    public $sub_total_januari=0,$sub_total_februari=0,$sub_total_maret=0,$sub_total_april=0,$sub_total_mei=0,$sub_total_juni=0,$sub_total_juli=0,$sub_total_agustus=0,$sub_total_september=0,$sub_total_oktober=0,$sub_total_november=0,$sub_total_desember=0;
    public function render()
    {
        return view('livewire.cash-flow.internal-report');
    }

    public function mount()
    {
        $this->year = date('Y');
        \LogActivity::add("Cash Flow Internal Report");
    }

    public function downloadExcel()
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Stalavista System")
                                     ->setLastModifiedBy("Stalavista System")
                                     ->setTitle("Office 2007 XLSX Product Database")
                                     ->setSubject("Cash Flow Database")
                                     ->setDescription("Cash Flow Database.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Cashflow");

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Cash Flow '.($this->year?$this->year:date('Y')));
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(false);
        $objPHPExcel->getActiveSheet()->getStyle('A3:N3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A3:N3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(34);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
        $objPHPExcel->getActiveSheet()->freezePane('A4');

        // $objPHPExcel->getActiveSheet()->setAutoFilter('A3:H3');
        $num=3;
        $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('D'.$num,'Januari')
                  ->setCellValue('E'.$num,'Februari')
                  ->setCellValue('F'.$num,'Maret')
                  ->setCellValue('G'.$num,'April')
                  ->setCellValue('H'.$num,'Mei')
                  ->setCellValue('I'.$num,'Juni')
                  ->setCellValue('J'.$num,'Juli')
                  ->setCellValue('K'.$num,'Agustus')
                  ->setCellValue('L'.$num,'September')
                  ->setCellValue('M'.$num,'Oktober')
                  ->setCellValue('N'.$num,'November')
                  ->setCellValue('O'.$num,'Desember');
        $objPHPExcel->getActiveSheet()->getStyle('D3:O3')->getFont()->setBold(true);
        
        $num++;
        $alphabet = [1=>'A',2=>'B',3=>'C',4=>'D',5=>'E'];
        foreach(get_group_cashflow() as $group_id =>$group){
            $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$num,@$alphabet[$group_id])
                  ->setCellValue('B'.$num,$group);
            $objPHPExcel->getActiveSheet()->getStyle("B".$num)->getFont()->setBold(true);
            
            $num++;
            foreach(\App\Models\CodeCashflow::where('group',$group_id)->get() as $key => $item){
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num,($key+1))
                ->setCellValue('B'.$num,$item->name)
                ->setCellValue('C'.$num,$item->code)
                ->setCellValue('D'.$num,sum_journal_cashflow($this->year,1,$item->id))
                ->setCellValue('E'.$num,sum_journal_cashflow($this->year,2,$item->id))
                ->setCellValue('F'.$num,sum_journal_cashflow($this->year,3,$item->id))
                ->setCellValue('G'.$num,sum_journal_cashflow($this->year,4,$item->id))
                ->setCellValue('H'.$num,sum_journal_cashflow($this->year,5,$item->id))
                ->setCellValue('I'.$num,sum_journal_cashflow($this->year,6,$item->id))
                ->setCellValue('J'.$num,sum_journal_cashflow($this->year,7,$item->id))
                ->setCellValue('K'.$num,sum_journal_cashflow($this->year,8,$item->id))
                ->setCellValue('L'.$num,sum_journal_cashflow($this->year,9,$item->id))
                ->setCellValue('M'.$num,sum_journal_cashflow($this->year,10,$item->id))
                ->setCellValue('N'.$num,sum_journal_cashflow($this->year,11,$item->id))
                ->setCellValue('O'.$num,sum_journal_cashflow($this->year,12,$item->id));

                $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":O".$num)->getNumberFormat()->setFormatCode('#,##0');

                $num++;
            }

            $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('B'.$num,'Cash From '.$group)
                  ->setCellValue('D'.$num,sum_journal_cashflow_by_group($this->year,1,$group_id))
                  ->setCellValue('E'.$num,sum_journal_cashflow_by_group($this->year,2,$group_id))
                  ->setCellValue('F'.$num,sum_journal_cashflow_by_group($this->year,3,$group_id))
                  ->setCellValue('G'.$num,sum_journal_cashflow_by_group($this->year,4,$group_id))
                  ->setCellValue('H'.$num,sum_journal_cashflow_by_group($this->year,5,$group_id))
                  ->setCellValue('I'.$num,sum_journal_cashflow_by_group($this->year,6,$group_id))
                  ->setCellValue('J'.$num,sum_journal_cashflow_by_group($this->year,7,$group_id))
                  ->setCellValue('K'.$num,sum_journal_cashflow_by_group($this->year,8,$group_id))
                  ->setCellValue('L'.$num,sum_journal_cashflow_by_group($this->year,9,$group_id))
                  ->setCellValue('M'.$num,sum_journal_cashflow_by_group($this->year,10,$group_id))
                  ->setCellValue('N'.$num,sum_journal_cashflow_by_group($this->year,11,$group_id))
                  ->setCellValue('O'.$num,sum_journal_cashflow_by_group($this->year,12,$group_id));
            $objPHPExcel->getActiveSheet()->getStyle('D'.$num.":O".$num)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('B'.$num.":O".$num)->getFont()->setBold(true);
            $num=$num+2;
        }
    
        // Rename worksheet
        //$objPHPExcel->getActiveSheet()->setTitle('Iuran-'. date('d-M-Y'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Cash-flow-' .date('d-M-Y') .'.xlsx"');
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
        },'cash-flow-' .date('d-M-Y') .'.xlsx');
        //return response()->download($writer->save('php://output'));
    }

    
}
