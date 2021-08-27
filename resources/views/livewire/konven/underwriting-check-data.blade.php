<div class="body">
    <div class="row">
        <div class="px-2 pt-2">
            <h6>Double Data : {{format_idr($data->total())}}</h6>
        </div>
        <div class="px-2 col-md-3">
            <div class="form-group">
                <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
            </div>
        </div>
        <div class="px-2">
            <a href="javascript:void(0)" class="btn btn-info btn-sm" wire:click="$emit('confirm-replace-all')"><i class="fa fa-check"></i> Replace All</a>
            <a href="javascript:void(0)" class="btn btn-success btn-sm" wire:click="$emit('confirm-keep-all')"><i class="fa fa-check"></i> Keep All</a>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm" wire:click="$emit('confirm-delete-all')"><i class="fa fa-trash"></i> Delete All</a>
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
                    <th>Status</th>                                    
                    <th>Bulan</th>                                    
                    <th>User Memo</th>                                    
                    <th>User Akseptasi</th>                                    
                    <th>Transaksi ID</th>     
                    <th>Berkas Akseptasi</th>
                    <th>Tgl Pengajuan Email</th>
                    <th>Tgl Produksi</th>
                    <th>No Reg</th>
                    <th>No Polis</th>
                    <th>No Polis Sistem</th>
                    <th>Pemegang Polis</th>
                    <th>Alamat</th>
                    <th>Cabang</th>
                    <th>Produk</th>
                    <th>Jml Peserta Pending</th>
                    <th>UP Peserta Pending</th>
                    <th>Premi Peserta Pending</th>
                    <th>Jml Peserta</th>
                    <th>No Peserta Awal</th>
                    <th>No Peserta Akhir</th>
                    <th>Periode Awal</th>
                    <th>Periode Akhir</th>
                    <th>UP</th>
                    <th>Premi Gross</th>
                    <th>Extra Premi</th>
                    <th>Discount</th>
                    <th>Jml Discount</th>
                    <th>Jml Cad Klaim</th>
                    <th>ExtDiskon</th>
                    <th>Cad Klaim</th>
                    <th>Handling Fee</th>
                    <th>Jml Fee</th>
                    <th>PPh</th>
                    <th>Jml PPh</th>
                    <th>PPN</th>
                    <th>Jml PPN</th>
                    <th>Biaya Polis</th>
                    <th>Biaya Sertifikat</th>
                    <th>Ext Sertifikat</th>
                    <th>Premi Netto</th>
                    <th>Terbilang</th>
                    <th>Tgl Update Database</th>
                    <th>Tgl Update Sistem</th>
                    <th>No Berkas Sistem</th>
                    <th>Tgl Posting Sistem</th>
                    <th>Ket Posting</th>
                    <th>Tgl Invoice</th>
                    <th>No Kwitansi / Debit Note</th>
                    <th>Total Gross Kwitansi</th>
                    <th>Grace Periode Terbilang</th>
                    <th>Grace Periode</th>
                    <th>Tgl Jatuh Tempo</th>
                    <th>Extend Tgl Jatuh Tempo</th>
                    <th>Tgl Lunas</th>
                    <th>Ket Lampiran</th>
                    <th>Line Bussines</th>
                </tr>
            </thead>
            <tbody>
                @php($num=$data->firstItem())
                @foreach($data as $item)
                    <tr>
                        <td rowspan="2">{{$num}}</td>
                        <td>
                            <a href="javascript:;" wire:click="$emit('replace-confirm',{{$item->id}})" class="text-info"><i class="fa fa-refresh"></i></a>
                            <a href="javascript:;" wire:click="$emit('delete-confirm',{{$item->id}})" class="text-danger ml-1"><i class="fa fa-trash"></i></a>
                            <a href="javascript:;" wire:click="$emit('keep-confirm',{{$item->id}})" class="text-info ml-1" title="Keep Data"><i class="fa fa-check"></i></a>
                        </td>
                        <td>
                            <span class="badge badge-success">*New</span>
                        </td>
                        <td>{{$item->bulan}}</td>
                        <td>{{$item->user_memo}}</td>
                        <td>{{$item->user_akseptasi}}</td>
                        <td>{{$item->transaksi_id}}</td>
                        <td>{{$item->berkas_akseptasi}}</td>
                        <td>{{$item->tanggal_pengajuan_email}}</td>
                        <td>{{$item->tanggal_produksi}}</td>
                        <td>{{$item->no_reg}}</td>
                        <td>{{$item->no_polis}}</td>
                        <td>{{$item->no_polis_sistem}}</td>
                        <td>{{$item->pemegang_polis}}</td>
                        <td>{{$item->alamat}}</td>
                        <td>{{$item->cabang}}</td>
                        <td>{{$item->produk}}</td>
                        <td>{{$item->jumlah_peserta_pending}}</td>
                        <td>{{format_idr($item->up_peserta_pending)}}</td>
                        <td>{{format_idr($item->premi_peserta_pending)}}</td>
                        <td>{{$item->jumlah_peserta}}</td>
                        <td>{{$item->nomor_peserta_awal}}</td>
                        <td>{{$item->nomor_peserta_akhir}}</td>
                        <td>{{$item->periode_awal}}</td>
                        <td>{{$item->periode_akhir}}</td>
                        <td>{{format_idr($item->up)}}</td>
                        <td>{{format_idr($item->premi_gross)}}</td>
                        <td>{{format_idr($item->extra_premi)}}</td>
                        <td>{{$item->discount}}</td>
                        <td>{{format_idr($item->jumlah_discount)}}</td>
                        <td>{{format_idr($item->jumlah_cad_klaim)}}</td>
                        <td>{{$item->ext_diskon}}</td>
                        <td>{{$item->cad_klaim}}</td>
                        <td>{{format_idr($item->handling_fee)}}</td>
                        <td>{{format_idr($item->jumlah_fee)}}</td>
                        <td>{{$item->pph}}%</td>
                        <td>{{format_idr($item->jumlah_pph)}}</td>
                        <td>{{$item->ppn}}%</td>
                        <td>{{format_idr($item->jumlah_ppn)}}</td>
                        <td>{{format_idr($item->biaya_polis)}}</td>
                        <td>{{format_idr($item->biaya_sertifikat)}}</td>
                        <td>{{format_idr($item->extsertifikat)}}</td>
                        <td>{{format_idr($item->premi_netto)}}</td>
                        <td>{{$item->terbilang}}</td>
                        <td>{{$item->tgl_update_database}}</td>
                        <td>{{$item->tgl_update_sistem}}</td>
                        <td>{{$item->no_berkas_sistem}}</td>
                        <td>{{$item->tgl_postingan_sistem}}</td>
                        <td>{{$item->ket_postingan}}</td>
                        <td>{{$item->tgl_invoice}}</td>
                        <td>{{$item->no_kwitansi_debit_note}}</td>
                        <td>{{format_idr($item->total_gross_kwitansi)}}</td>
                        <td>{{$item->grace_periode_terbilang}}</td>
                        <td>{{format_idr($item->grace_periode)}}</td>
                        <td>{{$item->tgl_jatuh_tempo}}</td>
                        <td>{{$item->extend_tgl_jatuh_tempo}}</td>
                        <td>{{$item->tgl_lunas}}</td>
                        <td>{{$item->ket_lampiran}}</td>
                        <td>{{$item->line_bussines}}</td>
                    </tr>
                    @if($item->parent)
                    <tr>
                        <td></td>
                        <td><span class="badge badge-warning">*Old</span></td>
                        <td>{{$item->parent->bulan}}</td>
                        <td>{{$item->parent->user_memo}}</td>
                        <td>{{$item->parent->user_akseptasi}}</td>
                        <td>{{$item->parent->transaksi_id}}</td>
                        <td>{{$item->parent->berkas_akseptasi}}</td>
                        <td>{{$item->parent->tanggal_pengajuan_email}}</td>
                        <td>{{$item->parent->tanggal_produksi}}</td>
                        <td>{{$item->parent->no_reg}}</td>
                        <td>{{$item->parent->no_polis}}</td>
                        <td>{{$item->parent->no_polis_sistem}}</td>
                        <td>{{$item->parent->pemegang_polis}}</td>
                        <td>{{$item->parent->alamat}}</td>
                        <td>{{$item->parent->cabang}}</td>
                        <td>{{$item->parent->produk}}</td>
                        <td>{{$item->parent->jumlah_peserta_pending}}</td>
                        <td>{{format_idr($item->parent->up_peserta_pending)}}</td>
                        <td>{{format_idr($item->parent->premi_peserta_pending)}}</td>
                        <td>{{$item->parent->jumlah_peserta}}</td>
                        <td>{{$item->parent->nomor_peserta_awal}}</td>
                        <td>{{$item->parent->nomor_peserta_akhir}}</td>
                        <td>{{$item->parent->periode_awal}}</td>
                        <td>{{$item->parent->periode_akhir}}</td>
                        <td>{{format_idr($item->parent->up)}}</td>
                        <td>{{format_idr($item->parent->premi_gross)}}</td>
                        <td>{{format_idr($item->parent->extra_premi)}}</td>
                        <td>{{$item->parent->discount}}</td>
                        <td>{{format_idr($item->parent->jumlah_discount)}}</td>
                        <td>{{format_idr($item->parent->jumlah_cad_klaim)}}</td>
                        <td>{{$item->parent->ext_diskon}}</td>
                        <td>{{$item->parent->cad_klaim}}</td>
                        <td>{{format_idr($item->parent->handling_fee)}}</td>
                        <td>{{format_idr($item->parent->jumlah_fee)}}</td>
                        <td>{{$item->parent->pph}}%</td>
                        <td>{{format_idr($item->parent->jumlah_pph)}}</td>
                        <td>{{$item->parent->ppn}}%</td>
                        <td>{{format_idr($item->parent->jumlah_ppn)}}</td>
                        <td>{{format_idr($item->parent->biaya_polis)}}</td>
                        <td>{{format_idr($item->parent->biaya_sertifikat)}}</td>
                        <td>{{format_idr($item->parent->extsertifikat)}}</td>
                        <td>{{format_idr($item->parent->premi_netto)}}</td>
                        <td>{{$item->parent->terbilang}}</td>
                        <td>{{$item->parent->tgl_update_database}}</td>
                        <td>{{$item->parent->tgl_update_sistem}}</td>
                        <td>{{$item->parent->no_berkas_sistem}}</td>
                        <td>{{$item->parent->tgl_postingan_sistem}}</td>
                        <td>{{$item->parent->ket_postingan}}</td>
                        <td>{{$item->parent->tgl_invoice}}</td>
                        <td>{{$item->parent->no_kwitansi_debit_note}}</td>
                        <td>{{format_idr($item->parent->total_gross_kwitansi)}}</td>
                        <td>{{$item->parent->grace_periode_terbilang}}</td>
                        <td>{{format_idr($item->parent->grace_periode)}}</td>
                        <td>{{$item->parent->tgl_jatuh_tempo}}</td>
                        <td>{{$item->parent->extend_tgl_jatuh_tempo}}</td>
                        <td>{{$item->parent->tgl_lunas}}</td>
                        <td>{{$item->parent->ket_lampiran}}</td>
                        <td>{{$item->parent->line_bussines}}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="59" style="background:#eee;padding:0;"></td>
                    </tr>
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
Livewire.on('confirm-replace-all',()=>{
    if(confirm('Replace all data ?')){
        Livewire.emit('replace-all');
    }
});
Livewire.on('confirm-delete-all',()=>{
    if(confirm('Delete all data ?')){
        Livewire.emit('delete-all');
    }
});
Livewire.on('confirm-keep-all',()=>{
    if(confirm('Keep all data ?')){
        Livewire.emit('keep-all');
    }
});
Livewire.on('keep-confirm',(id)=>{
    if(confirm('Keep this data ?')){
        Livewire.emit('keep-new',id);
    }
});
Livewire.on('delete-confirm',(id)=>{
    if(confirm('Delete this data ?')){
        Livewire.emit('delete-new',id);
    }
});
Livewire.on('replace-confirm',(id)=>{
    if(confirm('Replace this data ?')){
        Livewire.emit('replace-old',id);
    }
});
</script>
@endpush