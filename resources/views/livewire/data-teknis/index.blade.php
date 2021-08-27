@section('title', 'Data Teknis')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="mr-3">
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_upload_teknis_sariah" class="btn btn-primary"><i class="fa fa-upload"></i> Upload Syariah</a>
                </div>
                <div>
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_upload_teknis_conven" class="btn btn-success"><i class="fa fa-upload"></i> Upload Conven</a>
                </div>
                <div>
                    <a href="javascript:void(0)" wire:click="clear_db" class="ml-3 btn btn-danger"><i class="fa fa-trash"></i> Clear</a>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-striped m-b-0 table-hover c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bulan</th>                                    
                                <th>No Debit Note</th>                                    
                                <th>No Polis</th>                                    
                                <th>Pemegang Polis</th>                                    
                                {{-- <th>Alamat</th>                                    
                                <th>Produk</th> --}}
                                <th>Jumlah Kepesertaan</th>
                                <th>Nilai Manfaat</th>
                                <th>Premi / Kontribusi</th>
                                <th>Diskon</th>
                                <th>Jumlah Diskon</th>
                                <th>Premi Netto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($num=$data->firstItem())
                            @foreach($data as $k => $item)
                            <tr>
                                <td style="width: 50px;">{{$num}}</td>
                                <td>{{$item->bulan}}</td>
                                <td>{{$item->no_debit_note}}</td>
                                <td>{{$item->no_polis}}</td>
                                <td>{{$item->pemegang_polis}}</td>
                                {{-- <td>{{$item->alamat}}</td>
                                <td>{{$item->jenis_produk}}</td> --}}
                                <td>{{$item->jml_kepesertaan}}</td>
                                <td>{{format_idr($item->nilai_manfaat)}}</td>
                                <td>{{format_idr($item->kontribusi)}}</td>
                                <td>{{format_idr($item->pot_langsung)}}</td>
                                <td>{{format_idr($item->jumlah_diskon)}}</td>
                                <td>{{format_idr($item->net_kontribusi)}}</td>
                            </tr>
                            @php($num++)
                            @endforeach
                            @if(count($data)==0)
                            <tr>
                                <td colspan="8" class="text-center"><i>Empty</i></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <br />
                {{$data->links()}}
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="modal_upload_teknis_sariah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <livewire:data-teknis.upload-sariah>
        </div>
    </div>
</div>

@section('page-script')

Livewire.on('hideModal', () =>{
    $('#modal_upload_teknis_sariah').modal('hide')
    $('.page-loader-wrapper').hide();
});
@endsection