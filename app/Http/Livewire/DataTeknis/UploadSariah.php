<?php

namespace App\Http\Livewire\DataTeknis;

use Livewire\Component;
use Livewire\WithFileUploads;

class UploadSariah extends Component
{
    use WithFileUploads;

    public $file;
    public function render()
    {
        return view('livewire.data-teknis.upload-sariah');
    }

    public function save()
    {
        $this->emitTo('data-teknis.index','listenUploaded');
        
        $this->validate([
            'file'=>'required|mimes:xls,xlsx'
        ]);
        
        $path = $this->file->getRealPath();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $data = $reader->load($path);
        $sheetData = $data->getActiveSheet()->toArray();
        if(count($sheetData) > 0){
            $countLimit = 1;
            foreach($sheetData as $key => $i){
                if($key<2) continue; // skip header
                
                foreach($i as $k=>$a){ $i[$k] = trim($a); }

                $bulan = $i[1];
                $user_memo = $i[2];
                $user_akseptasi = $i[3];
                $transaksi_id = $i[4];
                $berkas_akseptasi = $i[5];
                $tanggal_pengajuan_email = date('Y-m-d',strtotime($i[6]));
                $tanggal_produksi = date('Y-m-d',strtotime($i[7]));
                $tanggal_akrual = date('Y-m-d',strtotime($i[8]));
                $bordero = $i[9];
                $no_memo = $i[10];
                $no_debit_note = $i[11];
                $no_polis = $i[12];
                $pemegang_polis = $i[13];
                $alamat = $i[14];
                $cabang = $i[15];
                $jenis_produk = $i[16];
                $jml_kepesertaan_tertunda = $i[17];
                $manfaat_kepesertaan_tertunda = replace_idr($i[18]);
                $kontribusi_kepesertaan_tertunda = replace_idr($i[19]);
                $jml_kepesertaan = replace_idr($i[20]);
                $no_kepesertaan_awal = $i[21];
                $s_d = $i[22];
                $no_kepesertaan_akhir = $i[23];
                $masa_awal_asuransi = date('Y-m-d',strtotime($i[24]));
                $masa_akhir_asuransi = date('Y-m-d',strtotime($i[25]));
                $nilai_manfaat = replace_idr($i[26]);
                $dana_tabbaru = replace_idr($i[27]);
                $dana_ujrah = replace_idr($i[28]);
                $kontribusi = replace_idr($i[29]);
                $ektra_kontribusi = replace_idr($i[30]);
                $total_kontribusi = replace_idr($i[31]);
                $pot_langsung = replace_idr($i[32]);
                $jumlah_diskon = replace_idr($i[33]);
                $status_potongan = $i[34];
                $handling_fee = replace_idr($i[35]);
                $jumlah_fee = replace_idr($i[36]);
                $pph = replace_idr($i[37]);
                $jumlah_pph = replace_idr($i[38]);
                $ppn = replace_idr($i[39]);
                $jumlah_ppn = replace_idr($i[40]);
                $biaya_polis = replace_idr($i[41]);
                $biaya_sertifikat = replace_idr($i[42]);
                $extpst = replace_idr($i[43]);
                $net_kontribusi = replace_idr($i[44]);
                $terbilang = $i[45];
                $tgl_update_database = date('Y-m-d',strtotime($i[46]));
                $tgl_update_sistem = date('Y-m-d',strtotime($i[47]));
                $no_berkas_sistem = replace_idr($i[48]);
                $tgl_posting_sistem = date('Y-m-d',strtotime($i[49]));
                $ket_posting = $i[50];
                $grace_periode = $i[51];
                $grace_periode_number = replace_idr($i[52]);
                $tgl_jatuh_tempo = date('Y-m-d',strtotime($i[53]));
                $tgl_lunas = date('Y-m-d',strtotime($i[54]));
                $pembayaran = replace_idr($i[55]);
                $piutang = replace_idr($i[56]);
                $total_peserta = replace_idr($i[57]);
                $outstanding_peserta = replace_idr($i[58]);
                $produksi_cash_basis = $i[59];
                $ket_lampiran = $i[60];
                $masa_asuransi = replace_idr($i[61]);
                $kontribusi_netto_u_biaya_penutupan = replace_idr($i[62]);
                $perkalian_biaya_penutupan = $i[63];
                $bp = $i[64];
                $total_biaya_penutupan = replace_idr($i[65]);
                $ket_penutupan = $i[66];
                $tahun = trim($i[67]);
                $peserta_reas = replace_idr($i[68]);
                $nilai_manfaat_or = replace_idr($i[69]);
                $nilai_manfaat_reas = replace_idr($i[70]);
                $kontribusi_gross_reas = replace_idr($i[71]);
                $ujroh = replace_idr($i[72]);
                $extra_mortalita = replace_idr($i[73]);
                $total_kontribusi_reas = replace_idr($i[74]);
                $kontribusi_netto_reas = replace_idr($i[75]);
                $produksi_reas_akrual = replace_idr($i[76]);
                $internal_memo_reas = $i[77];
                $invoice_reas = $i[78];
                $tgl_pembayaran_kontribusi_reas = date('Y-m-d',strtotime($i[79]));
                $produksi_reas_cash_basis = $i[80];
                $ket_reas = $i[81];
                $reasuradur = $i[82];
                $channel = $i[83];
                $gross_ajri_peserta_yang_direaskan = $i[84];

                // cek no polis
                $polis = \App\Models\Policy::where('no_polis',$no_polis)->first();
                if(!$polis){
                    $polis = new \App\Models\Policy();
                    $polis->no_polis = $no_polis;
                    $polis->pemegang_polis = $pemegang_polis;
                    $polis->alamat = $alamat;
                    $polis->cabang = $cabang;
                    $polis->produk = $jenis_produk;
                    $polis->save();
                }

                $data = new \App\Models\Teknis();
                $data->user_id = \Auth::user()->id;
                $data->bulan = $bulan;
                $data->user_memo = $user_memo;
                $data->user_akseptasi = $user_akseptasi;
                $data->transaksi_id = $transaksi_id;
                $data->berkas_akseptasi = $berkas_akseptasi;
                $data->tanggal_pengajuan_email = $tanggal_pengajuan_email;
                $data->tanggal_produksi = $tanggal_produksi;
                $data->tanggal_akrual = $tanggal_akrual;
                $data->bordero = $bordero;
                $data->no_memo = $no_memo;
                $data->no_debit_note = $no_debit_note;
                $data->no_polis = $no_polis;
                $data->pemegang_polis = $pemegang_polis;
                $data->alamat = $alamat;
                $data->cabang = $cabang;
                $data->jenis_produk = $jenis_produk;
                $data->jml_kepesertaan_tertunda = $jml_kepesertaan_tertunda;
                $data->manfaat_kepesertaan_tertunda = $manfaat_kepesertaan_tertunda;
                $data->kontribusi_kepesertaan_tertunda = $kontribusi_kepesertaan_tertunda;
                $data->jml_kepesertaan = $jml_kepesertaan;
                $data->no_kepesertaan_awal = $no_kepesertaan_awal;
                $data->no_kepesertaan_akhir = $no_kepesertaan_akhir;
                $data->masa_awal_asuransi = $masa_awal_asuransi;
                $data->masa_akhir_asuransi = $masa_akhir_asuransi;
                $data->nilai_manfaat = $nilai_manfaat;
                $data->dana_tabbaru = $dana_tabbaru;
                $data->dana_ujrah = $dana_ujrah;
                $data->kontribusi = $kontribusi;
                $data->ektra_kontribusi = $ektra_kontribusi;
                $data->total_kontribusi = $total_kontribusi;
                $data->pot_langsung = $pot_langsung;
                $data->jumlah_diskon = $jumlah_diskon;
                $data->status_potongan = $status_potongan;
                $data->handling_fee = $handling_fee;
                $data->jumlah_fee = $jumlah_fee;
                $data->pph = $pph;
                $data->jumlah_pph = $jumlah_pph;
                $data->ppn = $ppn;
                $data->jumlah_ppn = $jumlah_ppn;
                $data->biaya_polis = $biaya_polis;
                $data->biaya_sertifikat = $biaya_sertifikat;
                $data->extpst = $extpst;
                $data->net_kontribusi = $net_kontribusi;
                $data->terbilang = $terbilang;
                $data->tgl_update_database = $tgl_update_database;
                $data->tgl_update_sistem = $tgl_update_sistem;
                $data->no_berkas_sistem = $no_berkas_sistem;
                $data->tgl_posting_sistem = $tgl_posting_sistem;
                $data->ket_posting = $ket_posting;
                $data->grace_periode = $grace_periode;
                $data->grace_periode_number = $grace_periode_number;
                $data->tgl_jatuh_tempo = $tgl_jatuh_tempo;
                $data->tgl_lunas = $tgl_lunas;
                $data->pembayaran = $pembayaran;
                $data->piutang = $piutang;
                $data->total_peserta = replace_idr($total_peserta);
                $data->outstanding_peserta = replace_idr($outstanding_peserta);
                $data->produksi_cash_basis = replace_idr($produksi_cash_basis);
                $data->ket_lampiran = $ket_lampiran;
                $data->masa_asuransi = $masa_asuransi;
                $data->kontribusi_netto_u_biaya_penutupan = $kontribusi_netto_u_biaya_penutupan;
                $data->perkalian_biaya_penutupan = $perkalian_biaya_penutupan;
                $data->bp = $bp;
                $data->total_biaya_penutupan = $total_biaya_penutupan;
                $data->ket_penutupan = $ket_penutupan;
                $data->tahun = $tahun;
                $data->peserta_reas = $peserta_reas;
                $data->nilai_manfaat_or = $nilai_manfaat_or;
                $data->kontribusi_gross_reas = $kontribusi_gross_reas;
                $data->ujroh = $ujroh;
                $data->extra_mortalita = $extra_mortalita;
                $data->total_kontribusi_reas = $total_kontribusi_reas;
                $data->kontribusi_netto_reas = $kontribusi_netto_reas;
                $data->produksi_reas_akrual = $produksi_reas_akrual;
                $data->internal_memo_reas = $internal_memo_reas;
                $data->invoice_reas = $invoice_reas;
                $data->tgl_pembayaran_kontribusi_reas = $tgl_pembayaran_kontribusi_reas;
                $data->produksi_reas_cash_basis = $produksi_reas_cash_basis;
                $data->ket_reas = $ket_reas;
                $data->reasuradur = $reasuradur;
                $data->channel = $channel;
                $data->gross_ajri_peserta_yang_direaskan = $gross_ajri_peserta_yang_direaskan;
                $data->gross_ajri_peserta_yang_direaskan = $gross_ajri_peserta_yang_direaskan;
                $data->save();

                // kredit
                if(!empty($net_kontribusi)){
                    $new = new \App\Models\Income();
                    $new->teknis_id = $data->id;
                    $new->debit_note = $no_debit_note;
                    $new->nominal = $net_kontribusi;
                    $new->policy_id = $polis->id;
                    $new->save();
                }
                // debit
                if(!empty($jml_discount)){
                    $new = new \App\Models\Expenses();
                    $new->nominal = $jml_discount;
                    $new->recipient = $pemegang_polis;
                    $new->reference_type = 'Diskon';
                    $new->reference_no = $no_debit_note;
                    $new->reference_date = $tanggal_produksi;
                    $new->policy_id = $polis->id;
                    $new->save();
                }
                // debit
                if(!empty($kontribusi_netto_reas)){
                    $new = new \App\Models\Expenses();
                    $new->nominal = $kontribusi_netto_reas;
                    $new->recipient = $pemegang_polis;
                    $new->reference_type = 'Premi Reas';
                    $new->reference_no = $no_debit_note;
                    $new->reference_date = $tanggal_produksi;
                    $new->policy_id = $polis->id;
                    $new->save();
                }
                // debit
                if(!empty($kontribusi_netto_reas)){
                    $new = new \App\Models\Expenses();
                    $new->nominal = $kontribusi_netto_reas;
                    $new->recipient = $pemegang_polis;
                    $new->reference_type = 'Premi Reas';
                    $new->reference_no = $no_debit_note;
                    $new->reference_date = $tanggal_produksi;
                    $new->policy_id = $polis->id;
                    $new->save();
                }
            }
            session()->flash('message-success','Upload success !');
            
            return redirect()->to('data-teknis');
        }
    }
}
