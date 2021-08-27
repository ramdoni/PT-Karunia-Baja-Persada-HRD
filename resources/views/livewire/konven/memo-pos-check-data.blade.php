<div class="body">
    <div class="row">
        <div class="px-2 pt-2">
            <h6>Double Data {{format_idr($data->total())}}</h6>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
            </div>
        </div>
        <div>
            <a href="javascript:void(0)" class="btn btn-info btn-sm" wire:click="replaceAll"><i class="fa fa-refresh"></i> Replace All</a>
            <a href="javascript:void(0)" class="btn btn-success btn-sm" wire:click="keepAll"><i class="fa fa-check"></i> Keep All</a>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm" wire:click="deleteAll"><i class="fa fa-trash"></i> Delete All</a>
            <div wire:loading>
                <i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="table-responsive pt-0">
        <table class="table m-b-0 table-hover c_list">
            <thead>
                <tr>
                    <th>No</th>
                    <th></th>
                    <th></th>
                    <th>Bulan</th>
                    <th>User</th>
                    <th>User Akseptasi</th>
                    <th>Berkas Akseptasi</th>
                    <th>Tgl_Pengajuan_Email</th>
                    <th>Tgl Produksi</th>
                    <th>No Reg</th>
                    <th>No Reg Sistem</th>
                    <th>No DN/CN</th>
                    <th>No DN/CN Sistem</th>
                    <th>Jenis POS</th>
                    <th>Status</th>
                    <th>Posting</th>
                    <th></th>
                    <th>Ket_Perubahan_1</th>
                    <th>Ket_Perubahan_2</th>
                    <th>No Polis</th>
                    <th>Pemegang Polis</th>
                    <th>Cabang</th>
                    <th>Produk</th>
                    <th>Alamat</th>
                    <th>UP_Tujuan_Surat</th>
                    <th>Tujuan_Pembayaran</th>
                    <th>Bank</th>
                    <th>No Rekening</th>
                    <th>Jumlah_Peserta Pending</th>
                    <th>Up_Peserta_Pending</th>
                    <th>Premi_Peserta_Pending</th>
                    <th>Peserta</th>
                    <th>No Peserta Awal</th>
                    <th>sd</th>
                    <th>No_Peserta_Akhir</th>
                    <th>No_Sertifikat_Awal</th>
                    <th>No_Sertifikat_Akhir</th>
                    <th>Periode_Awal</th>
                    <th>Periode_Akhir</th>
                    <th>Tgl Proses</th>
                    <th>Movement</th>
                    <th>Tgl Invoice</th>
                    <th>Tgl Invoice 2</th>
                    <th>No Kwitansi Finance</th>
                    <th>No Kwitansi Finance 2</th>
                    <th>Total Gross Kwitansi</th>
                    <th>Total Gross Kwitansi 2</th>
                    <th>Jumlah Peserta Update</th>
                    <th>UP Cancel</th>
                    <th>Premi Gross Cancel</th>
                    <th>Extra Premi</th>
                    <th>ExtXtra</th>
                    <th>RpXtra</th>
                    <th>Diskon Premi</th>
                    <th>Jml Diskon</th>
                    <th>RpDiskon</th>
                    <th>extDiskon</th>
                    <th>Fee</th>
                    <th>Jml Handling Fee</th>
                    <th>ExtFee</th>
                    <th>RpFee</th>
                    <th>TampilanFee</th>
                    <th>PPH</th>
                    <th>Jml PPH</th>
                    <th>ExtPPh</th>
                    <th>RpPPh</th>
                    <th>Ppn</th>
                    <th>Jml Ppn</th>
                    <th>ExtPPn</th>
                    <th>RpPPn</th>
                    <th>Biaya Sertifikat</th>
                    <th>ExtBiayaSertifikat</th>
                    <th>RpBiayaSertifikat</th>
                    <th>extPstSertifikat</th>
                    <th>Net Sblm Endors</th>
                    <th>Data_Stlh_Endors</th>
                    <th>UP_Stlh_Endors</th>
                    <th>Premi Gross Endors</th>
                    <th>Extra Premi</th>
                    <th>extEM</th>
                    <th>RpXtra</th>
                    <th>Discount</th>
                    <th>Jml Discount</th>
                    <th>ExtDiskon</th>
                    <th>RpDiskon</th>
                    <th>Handling fee</th>
                    <th>Jml Fee</th>
                    <th>ExtFee</th>
                    <th>RpFee</th>
                    <th>tampilanFee</th>
                    <th>Pph</th>
                    <th>Jml Pph</th>
                    <th>extPPH</th>
                    <th>RpPPH</th>
                    <th>Ppn</th>
                    <th>Jml Ppn</th>
                    <th>extPPN</th>
                    <th>RpPPn</th>
                    <th>Biaya Sertifikat</th>
                    <th>ExtBiayaSertifikat</th>
                    <th>RpBiayaSertifikat</th>
                    <th>extPstSertifikat</th>
                    <th>Net Stlh Endors</th>
                    <th>Refund</th>
                    <th>Terbilang</th>
                    <th>Ket Lampiran</th>
                    <th>Grace Periode</th>
                    <th>Grace Periode</th>
                    <th>Tgl Jatuh Tempo</th>
                    <th>Tgl Update Database</th>
                    <th>Tgl Update Sistem</th>
                    <th>No Berkas Sistem</th>
                    <th>Tgl Posting Sistem</th>
                    <th>No Debet Note Finance</th>
                    <th>Tgl Bayar</th>
                    <th>Ket</th>
                    <th>Tgl Output Email</th>
                    <th>No Berkas 2</th>
                </tr>
            </thead>
            <tbody>
                @php($num=$data->firstItem())
                @foreach($data as $item)
                <tr>
                    <td rowspan="2">{{$num}}</td>
                    <td>
                        <a href="javascript:;" wire:click="$emit('replace-confirm-memo-pos',{{$item->id}})" class="text-info"><i class="fa fa-refresh"></i></a>
                        <a href="javascript:;" wire:click="$emit('delete-confirm-memo-pos',{{$item->id}})" class="text-danger ml-1"><i class="fa fa-trash"></i></a>
                        <a href="javascript:;" wire:click="$emit('keep-confirm-memo-pos',{{$item->id}})" class="text-info ml-1" title="Keep Data"><i class="fa fa-check"></i></a>
                    </td>
                    <td>
                        <span class="badge badge-success">*New</span>
                    </td>
                    <td>{{$item->bulan}}</td>
                    <td>{{$item->user}}</td>
                    <td>{{$item->user_akseptasi}}</td>
                    <td>{{$item->berkas_akseptasi}}</td>
                    <td>{{$item->tgl_pengajuan_email}}</td>
                    <td>{{$item->tgl_produksi}}</td>
                    <td>{{$item->no_reg}}</td>
                    <td>{{$item->no_reg_sistem}}</td>
                    <td>{{$item->no_dn_cn}}</td>
                    <td>{{$item->no_dn_cn_sistem}}</td>
                    <td>{{$item->jenis_po}}</td>
                    <td>{{$item->status}}</td>
                    <td>{{$item->posting}}</td>
                    <td>{{$item->jenis_po_2}}</td>
                    <td>{{$item->ket_perubahan1}}</td>
                    <td>{{$item->ket_perubahan2}}</td>
                    <td>{{$item->no_polis}}</td>
                    <td>{{$item->pemegang_polis}}</td>
                    <td>{{$item->cabang}}</td>
                    <td>{{$item->produk}}</td>
                    <td>{{$item->alamat}}</td>
                    <td>{{$item->up_tujuan_surat}}</td>
                    <td>{{$item->tujuan_pembayaran}}</td>
                    <td>{{$item->bank}}</td>
                    <td>{{$item->no_rekening}}</td>
                    <td>{{$item->jumlah_peserta_pending}}</td>
                    <td>{{format_idr($item->up_peserta_pending)}}</td>
                    <td>{{format_idr($item->premi_peserta_pending)}}</td>
                    <td>{{$item->peserta}}</td>
                    <td>{{$item->no_peserta_awal}}</td>
                    <td>s/d</td>
                    <td>{{$item->no_peserta_akhir}}</td>
                    <td>{{$item->no_sertifikat_awal}}</td>
                    <td>{{$item->no_sertifikat_akhir}}</td>
                    <td>{{$item->periode_awal}}</td>
                    <td>{{$item->periode_akhir}}</td>
                    <td>{{$item->tgl_proses}}</td>
                    <td>{{$item->movement}}</td>
                    <td>{{$item->tgl_invoice}}</td>
                    <td>{{$item->tgl_invoice2}}</td>
                    <td>{{$item->no_kwitansi_finance}}</td>
                    <td>{{$item->no_kwitansi_finance2}}</td>
                    <td>{{format_idr($item->total_gross_kwitansi)}}</td>
                    <td>{{format_idr($item->total_gross_kwitansi2)}}</td>
                    <td>{{$item->jumlah_peserta_update}}</td>
                    <td>{{format_idr($item->up_cancel)}}</td>
                    <td>{{format_idr($item->premi_gross_cancel)}}</td>
                    <td>{{format_idr($item->extra_premi)}}</td>
                    <td>{{$item->extextra}}</td>
                    <td>{{$item->rpextra}}</td>
                    <td>{{format_idr($item->diskon_premi)}}</td>
                    <td>{{format_idr(abs($item->jml_diskon))}}</td>
                    <td>{{$item->rp_diskon}}</td>
                    <td>{{$item->extdiskon}}</td>
                    <td>{{format_idr($item->fee)}}</td>
                    <td>{{$item->handling_fee}}</td>
                    <td>{{format_idr($item->ext_fee)}}</td>
                    <td>{{$item->rp_fee}}</td>
                    <td>{{$item->tampilan_fee}}</td>
                    <td>{{$item->pph}}</td>
                    <td>{{($item->jml_pph)}}</td>
                    <td>{{($item->extpph)}}</td>
                    <td>{{($item->rppph)}}</td>
                    <td>{{($item->ppn)}}</td>
                    <td>{{($item->jml_ppn)}}</td>
                    <td>{{($item->extppn)}}</td>
                    <td>{{($item->rpppn)}}</td>
                    <td>{{format_idr($item->biaya_sertifikat)}}</td>
                    <td>{{format_idr($item->extbiayasertifikat)}}</td>
                    <td>{{format_idr($item->rpbiayasertifikat)}}</td>
                    <td>{{format_idr($item->extpstsertifikat)}}</td>
                    <td>{{format_idr($item->net_sblm_endors)}}</td>
                    <td>{{format_idr($item->data_stlh_endors)}}</td>
                    <td>{{format_idr($item->up_stlh_endors)}}</td>
                    <td>{{format_idr($item->premi_gross_endors)}}</td>
                    <td>{{format_idr($item->extra_premi2)}}</td>
                    <td>{{format_idr($item->extem)}}</td>
                    <td>{{format_idr($item->rpxtra)}}</td>
                    <td>{{format_idr($item->discount)}}</td>
                    <td>{{format_idr($item->jml_discount)}}</td>
                    <td>{{$item->ext_discount}}</td>
                    <td>{{$item->rpdiscount}}</td>
                    <td>{{$item->handling_fee}}</td>
                    <td>{{$item->jml_fee}}</td>
                    <td>{{$item->extfee}}</td>
                    <td>{{format_idr($item->rpfee)}}</td>
                    <td>{{$item->tampilanfee}}</td>
                    <td>{{$item->pph2}}</td>
                    <td>{{$item->jml_pph2}}</td>
                    <td>{{$item->extpph2}}</td>
                    <td>{{$item->rppph2}}</td>
                    <td>{{$item->ppn2}}</td>
                    <td>{{$item->jml_ppn2}}</td>
                    <td>{{$item->extppn2}}</td>
                    <td>{{$item->rpppn2}}</td>
                    <td>{{$item->biaya_sertifikat2}}</td>
                    <td>{{$item->extbiayasertifikat2}}</td>
                    <td>{{$item->rpbiayasertifikat2}}</td>
                    <td>{{$item->extpstsertifikat2}}</td>
                    <td>{{format_idr($item->net_stlh_endors)}}</td>
                    <td>{{format_idr($item->refund)}}</td>
                    <td>{{$item->terbilang}}</td>
                    <td>{{$item->ket_lampiran}}</td>
                    <td>{{$item->grace_periode}}</td>
                    <td>{{$item->grace_periode_nominal}}</td>
                    <td>{{$item->tgl_jatuh_tempo}}</td>
                    <td>{{$item->tgl_update_database}}</td>
                    <td>{{$item->tgl_update_sistem}}</td>
                    <td>{{$item->no_berkas_sistem}}</td>
                    <td>{{$item->tgl_posting_sistem}}</td>
                    <td>{{$item->no_debit_note_finance}}</td>
                    <td>{{$item->tgl_bayar}}</td>
                    <td>{{$item->ket}}</td>
                    <td>{{$item->tgl_output_email}}</td>
                    <td>{{$item->no_berkas2}}</td>
                </tr>
                @if($item->parent)
                <tr>
                    <td></td>
                    <td>
                        <span class="badge badge-warning">*Old</span>
                    </td>
                    <td>{{$item->parent->bulan}}</td>
                    <td>{{$item->parent->user}}</td>
                    <td>{{$item->parent->user_akseptasi}}</td>
                    <td>{{$item->parent->berkas_akseptasi}}</td>
                    <td>{{$item->parent->tgl_pengajuan_email}}</td>
                    <td>{{$item->parent->tgl_produksi}}</td>
                    <td>{{$item->parent->no_reg}}</td>
                    <td>{{$item->parent->no_reg_sistem}}</td>
                    <td>{{$item->parent->no_dn_cn}}</td>
                    <td>{{$item->parent->no_dn_cn_sistem}}</td>
                    <td>{{$item->parent->jenis_po}}</td>
                    <td>{{$item->parent->status}}</td>
                    <td>{{$item->parent->posting}}</td>
                    <td>{{$item->parent->jenis_po_2}}</td>
                    <td>{{$item->parent->ket_perubahan1}}</td>
                    <td>{{$item->parent->ket_perubahan2}}</td>
                    <td>{{$item->parent->no_polis}}</td>
                    <td>{{$item->parent->pemegang_polis}}</td>
                    <td>{{$item->parent->cabang}}</td>
                    <td>{{$item->parent->produk}}</td>
                    <td>{{$item->parent->alamat}}</td>
                    <td>{{$item->parent->up_tujuan_surat}}</td>
                    <td>{{$item->parent->tujuan_pembayaran}}</td>
                    <td>{{$item->parent->bank}}</td>
                    <td>{{$item->parent->no_rekening}}</td>
                    <td>{{$item->parent->jumlah_peserta_pending}}</td>
                    <td>{{format_idr($item->parent->up_peserta_pending)}}</td>
                    <td>{{format_idr($item->parent->premi_peserta_pending)}}</td>
                    <td>{{$item->parent->peserta}}</td>
                    <td>{{$item->parent->no_peserta_awal}}</td>
                    <td>s/d</td>
                    <td>{{$item->parent->no_peserta_akhir}}</td>
                    <td>{{$item->parent->no_sertifikat_awal}}</td>
                    <td>{{$item->parent->no_sertifikat_akhir}}</td>
                    <td>{{$item->parent->periode_awal}}</td>
                    <td>{{$item->parent->periode_akhir}}</td>
                    <td>{{$item->parent->tgl_proses}}</td>
                    <td>{{$item->parent->movement}}</td>
                    <td>{{$item->parent->tgl_invoice}}</td>
                    <td>{{$item->parent->tgl_invoice2}}</td>
                    <td>{{$item->parent->no_kwitansi_finance}}</td>
                    <td>{{$item->parent->no_kwitansi_finance2}}</td>
                    <td>{{format_idr($item->parent->total_gross_kwitansi)}}</td>
                    <td>{{format_idr($item->parent->total_gross_kwitansi2)}}</td>
                    <td>{{$item->parent->jumlah_peserta_update}}</td>
                    <td>{{format_idr($item->parent->up_cancel)}}</td>
                    <td>{{format_idr($item->parent->premi_gross_cancel)}}</td>
                    <td>{{format_idr($item->parent->extra_premi)}}</td>
                    <td>{{$item->parent->extextra}}</td>
                    <td>{{$item->parent->rpextra}}</td>
                    <td>{{format_idr($item->parent->diskon_premi)}}</td>
                    <td>{{format_idr(abs($item->parent->jml_diskon))}}</td>
                    <td>{{$item->parent->rp_diskon}}</td>
                    <td>{{$item->parent->extdiskon}}</td>
                    <td>{{format_idr($item->parent->fee)}}</td>
                    <td>{{$item->parent->handling_fee}}</td>
                    <td>{{format_idr($item->parent->ext_fee)}}</td>
                    <td>{{$item->parent->rp_fee}}</td>
                    <td>{{$item->parent->tampilan_fee}}</td>
                    <td>{{$item->parent->pph}}</td>
                    <td>{{($item->parent->jml_pph)}}</td>
                    <td>{{($item->parent->extpph)}}</td>
                    <td>{{($item->parent->rppph)}}</td>
                    <td>{{($item->parent->ppn)}}</td>
                    <td>{{($item->parent->jml_ppn)}}</td>
                    <td>{{($item->parent->extppn)}}</td>
                    <td>{{($item->parent->rpppn)}}</td>
                    <td>{{format_idr($item->parent->biaya_sertifikat)}}</td>
                    <td>{{format_idr($item->parent->extbiayasertifikat)}}</td>
                    <td>{{format_idr($item->parent->rpbiayasertifikat)}}</td>
                    <td>{{format_idr($item->parent->extpstsertifikat)}}</td>
                    <td>{{format_idr($item->parent->net_sblm_endors)}}</td>
                    <td>{{format_idr($item->parent->data_stlh_endors)}}</td>
                    <td>{{format_idr($item->parent->up_stlh_endors)}}</td>
                    <td>{{format_idr($item->parent->premi_gross_endors)}}</td>
                    <td>{{format_idr($item->parent->extra_premi2)}}</td>
                    <td>{{format_idr($item->parent->extem)}}</td>
                    <td>{{format_idr($item->parent->rpxtra)}}</td>
                    <td>{{format_idr($item->parent->discount)}}</td>
                    <td>{{format_idr($item->parent->jml_discount)}}</td>
                    <td>{{$item->parent->ext_discount}}</td>
                    <td>{{$item->parent->rpdiscount}}</td>
                    <td>{{$item->parent->handling_fee}}</td>
                    <td>{{$item->parent->jml_fee}}</td>
                    <td>{{$item->parent->extfee}}</td>
                    <td>{{format_idr($item->parent->rpfee)}}</td>
                    <td>{{$item->parent->tampilanfee}}</td>
                    <td>{{$item->parent->pph2}}</td>
                    <td>{{$item->parent->jml_pph2}}</td>
                    <td>{{$item->parent->extpph2}}</td>
                    <td>{{$item->parent->rppph2}}</td>
                    <td>{{$item->parent->ppn2}}</td>
                    <td>{{$item->parent->jml_ppn2}}</td>
                    <td>{{$item->parent->extppn2}}</td>
                    <td>{{$item->parent->rpppn2}}</td>
                    <td>{{$item->parent->biaya_sertifikat2}}</td>
                    <td>{{$item->parent->extbiayasertifikat2}}</td>
                    <td>{{$item->parent->rpbiayasertifikat2}}</td>
                    <td>{{$item->parent->extpstsertifikat2}}</td>
                    <td>{{format_idr($item->parent->net_stlh_endors)}}</td>
                    <td>{{format_idr($item->parent->refund)}}</td>
                    <td>{{$item->parent->terbilang}}</td>
                    <td>{{$item->parent->ket_lampiran}}</td>
                    <td>{{$item->parent->grace_periode}}</td>
                    <td>{{$item->parent->grace_periode_nominal}}</td>
                    <td>{{$item->parent->tgl_jatuh_tempo}}</td>
                    <td>{{$item->parent->tgl_update_database}}</td>
                    <td>{{$item->parent->tgl_update_sistem}}</td>
                    <td>{{$item->parent->no_berkas_sistem}}</td>
                    <td>{{$item->parent->tgl_posting_sistem}}</td>
                    <td>{{$item->parent->no_debit_note_finance}}</td>
                    <td>{{$item->parent->tgl_bayar}}</td>
                    <td>{{$item->parent->ket}}</td>
                    <td>{{$item->parent->tgl_output_email}}</td>
                    <td>{{$item->parent->no_berkas2}}</td>
                </tr>
                @endif
                <tr><td colspan="126" style="background: #eee;padding:0;"></td></tr>
                @php($num++)
                @endforeach
            </tbody>
        </table>
        <br />
        {{$data->links()}}
    </div>
</div>
@push('after-scripts')
<script>
Livewire.on('keep-confirm-memo-pos',(id)=>{ 
    if(confirm('Keep this data ?')){
        Livewire.emit('keep-memo-pos',id);
    }
});
Livewire.on('replace-confirm-memo-pos',(id)=>{
    if(confirm('Replace this data ?')){
        Livewire.emit('replace-memo-pos',id);
    }
});
Livewire.on('delete-confirm-memo-pos',(id)=>{
    if(confirm('Delete this data ?')){
        Livewire.emit('delete-memo-pos',id);
    }
});
</script>
@endpush