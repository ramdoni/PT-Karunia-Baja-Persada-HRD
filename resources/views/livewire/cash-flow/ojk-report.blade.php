<div>
    <div class="px-0 pt-0 header row">
        <div class="col-md-2">
            <select class="form-control" wire:model="year">
                <option value=""> -- Year -- </option>
                @foreach(\App\Models\Journal::select( DB::raw( 'YEAR(date_journal) AS year' ))->groupBy('year')->get() as $i)
                <option>{{$i->year}}</option>
                @endforeach
            </select>
        </div>
        <div class="px-0 col-md-4">
            <a href="javascript:void(0)" class="btn btn-info" wire:click="downloadExcel"><i class="fa fa-download"></i> Download Excel</a>
        </div>
    </div>
    <div class="px-0 table-responsive">
        <table class="table table-hover table-striped m-b-0 c_list table-bordered table-style1">
            <tr>
                <td></td>
                @foreach(month() as $month)
                    <th>{{$month}}</th>
                @endforeach
            </tr>
            <tr>
                <th>SALDO AWAL KAS DAN BANK</th>
                @foreach(month() as $month)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>ARUS KAS DARI AKTIVITAS OPERASI</th>
                @foreach(month() as $month)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>Arus Kas Masuk</th>
                @foreach(month() as $month)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>a. Premi</td>
                @foreach(month() as $key_month => $month)
                    <td>{{format_idr(sum_journal_cashflow($year,$key_month,1))}}</td>
                @endforeach
            </tr>
            <tr>
                <td>b. Klaim Koasuransi</td>
                @foreach(month() as $month)
                    <td></td>
                @endforeach
            </tr>
            <tr>
                <td>c. Klaim Reasuransi</td>
                @foreach(month() as $key_month => $month)
                    <td>{{format_idr(sum_journal_cashflow($year,$key_month,2))}}</td>
                @endforeach
            </tr>
            <tr>
                <td>d. Komisi</td>
                @foreach(month() as $month)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>e. Piutang</td>
                @foreach(month() as $month)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>f. Lain-lain</td>
                @foreach(month() as $key_month => $month)
                    <td>{{format_idr(sum_journal_cashflow($year,$key_month,3))}}</td>
                @endforeach
            </tr>
            <tr>
                <th>Jumlah Arus Kas Masuk</td>
                @foreach(month() as $key_month => $month)
                    <th>
                        {{
                            format_idr(
                                sum_journal_cashflow($year,$key_month,1)+
                                sum_journal_cashflow($year,$key_month,2)+
                                sum_journal_cashflow($year,$key_month,3)
                                )
                        }}
                    </th>
                @endforeach
            </tr>
            <tr>
                <th>Arus Kas Keluar</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>a. Premi Reasuransi</td>
                @foreach(month() as $key_month => $month)
                    <td>{{format_idr(sum_journal_cashflow($year,$key_month,8))}}</td>
                @endforeach
            </tr>
            <tr>
                <td>b. Klaim</td>
                @foreach(month() as $key_month => $month)
                    <td>{{format_idr(sum_journal_cashflow($year,$key_month,9))}}</td>
                @endforeach
            </tr>
            <tr>
                <td>c. Komisi</td>
                @foreach(month() as $key_month => $month)
                    <td>{{format_idr(sum_journal_cashflow($year,$key_month,5))}}</td>
                @endforeach
            </tr>
            <tr>
                <td>d. Biaya-biaya</td>
                @foreach(month() as $key_month => $month)
                    <td>{{format_idr(sum_journal_cashflow($year,$key_month,4)+sum_journal_cashflow($year,$key_month,6)+sum_journal_cashflow($year,$key_month,7))}}</td>
                    <!--Marketing Expenses--->
                    <!--Taxation--->
                    <!--General & Administration Expenses--->
                @endforeach
            </tr>
            <tr>
                <td>e. Lain-lain</td>
                @foreach(month() as $m)
                <td>{{format_idr(\App\Models\Journal::whereYear('date_journal',$year)->whereMonth('date_journal',$month)->where('code_cashflow_id',3)->sum('saldo'))}}</td>
                @endforeach
            </tr>
            <tr>
                <th>Jumlah Arus Kas Keluar</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>JUMLAH ARUS KAS DARI AKTIVITAS OPERASI</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>ARUS KAS DARI AKTIVITAS INVESTASI</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>Arus Kas Masuk</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>a. Penerimaan Hasil Investasi</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>b. Pencairan Investasi</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>c. Penjualan Aset Tetap</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>d. Lain-lain</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>Jumlah Arus Kas Keluar</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>JUMLAH ARUS KAS DARI AKTIVITAS INVESTASI</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>ARUS KAS DARI AKTIVITAS PENDANAAN</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>Arus Kas Masuk</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>Arus Kas Masuk</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>a. Pinjaman Subordinasi</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>b. Setoran Modal</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>c. Lain-lain</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>Jumlah Arus Kas Masuk</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>a. Pembayaran Dividen</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>b. Pembayaran Pinjaman Subordinasi</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>c. Lain-lain</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>Jumlah Arus Kas Keluar</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>JUMLAH ARUS KAS DARI AKTIVITAS PENDANAAN</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <th>SALDO AKHIR KAS DAN BANK</th>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
            <tr>
                <td>CEK</td>
                @foreach(month() as $m)
                    <th></th>
                @endforeach
            </tr>
        </table>
    </div>
</div>