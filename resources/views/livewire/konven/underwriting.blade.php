@section('title', 'Underwriting')
@section('parentPageTitle', 'Home')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#additional">Additional</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#memo_pos">Memo POS</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_komisi">Komisi</a></li>
                </ul>
                <div class="px-0 tab-content">
                    <div class="tab-pane show active" id="additional">
                        <div class="mt-2" id="keydown">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..."/>
                                </div>
                                <div class="px-0 col-md-1">
                                    <select class="form-control" wire:model="status">
                                        <option value=""> --- Status --- </option>
                                        <option value="1">Draft</option>
                                        <option value="2">Sync</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <a href="javascript:void(0)" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modal_upload_teknis_conven" class="mb-2 btn btn-info btn-sm" style="width:150px;"><i class="fa fa-upload"></i> Upload</a>
                                    @if($total_sync>0)
                                    <a href="javascript:void(0)" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modal_confirm_sync" class="mb-2 btn btn-warning btn-sm"><i class="fa fa-refresh"></i> Sync {{$total_sync?"(".$total_sync.")" : "(0)"}}</a>
                                    @endif
                                </div>
                                <div class="col-md-4 text-right">
                                    <h6>Sync : <span class="text-info">{{format_idr(\App\Models\KonvenUnderwriting::where('status',2)->count())}}</span>, Draft : <span class="text-warning">{{format_idr(\App\Models\KonvenUnderwriting::where('status',1)->count())}}</span>, Total : <span class="text-success">{{format_idr($data->total())}}</span></h6>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped m-b-0 table-hover c_list">
                                    <thead>
                                        <tr>
                                            <th>No</th>
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
                                            <td>{{$num}}</td>
                                            <td>
                                                @if($item->status==1)
                                                <span class="badge text-warning">Draft</span>
                                                @endif
                                                @if($item->status==2)
                                                <span class="badge text-success">Sync</span>
                                                @endif
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
                                        @php($num++)
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <br />
                            {{$data->links()}}
                        </div>
                    </div>
                    <div class="tab-pane" id="memo_pos">
                        <livewire:konven.memo-pos />
                    </div>
                    <div class="tab-pane" id="tab_komisi">
                        <livewire:konven.komisi />
                    </div>
                </div>
                <div wire:ignore.self class="modal fade" id="modal_upload_teknis_conven" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <livewire:konven.upload-underwriting>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal_confirm_sync" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <livewire:konven.underwriting-sync>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal_check_data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" style="max-width:90%;" role="document">
                        <div class="modal-content">
                            <livewire:konven.underwriting-check-data>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @push('after-scripts')
    <script>
    Livewire.on('emit-check-data',()=>{
        $("#modal_upload_teknis_conven").modal("hide");
        setTimeout(function(){
            $("#modal_check_data").modal(
                {
                    backdrop: 'static',
                    keyboard: false
                });
        },1000);
    });
    </script>
    @endpush
</div>
