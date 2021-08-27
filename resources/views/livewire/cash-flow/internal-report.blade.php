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
        <table class="table table-striped m-b-0 c_list table-bordered table-style1 table-hover">
            <tbody>
                @php($alphabet=[1=>'A',2=>'B',3=>'D',4=>'E'])
                @foreach(get_group_cashflow() as $k =>$i)
                    <tr>
                        <th>{{@$alphabet[$k]}}</th>
                        <th>{{$i}}</th>
                        <th></th>
                        @foreach(month() as $m)
                            <th>{{$k==1? $m : ''}}</th>
                        @endforeach
                    </tr>
                    @php($sub_total=0)
                    @foreach(\App\Models\CodeCashflow::where('group',$k)->get() as $key => $item)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$item->name}}</td>
                        <td style="width: 50px;">{{$item->code}}</td>
                        <td>
                            @php($sub_total_januari=sum_journal_cashflow($year,1,$item->id))
                            {{format_idr($sub_total_januari)}}
                        </td>
                        <td>
                            @php($sub_total_februari=sum_journal_cashflow($year,2,$item->id))
                            {{format_idr($sub_total_februari)}}
                        </td>
                        <td>
                            @php($sub_total_maret=sum_journal_cashflow($year,3,$item->id))
                            {{format_idr($sub_total_maret)}}
                        </td>
                        <td>
                            @php($sub_total_april=sum_journal_cashflow($year,4,$item->id))
                            {{format_idr($sub_total_april)}}
                        </td>
                        <td>
                            @php($sub_total_mei=sum_journal_cashflow($year,5,$item->id))
                            {{format_idr($sub_total_mei)}}
                        </td>
                        <td>
                            @php($sub_total_juni=sum_journal_cashflow($year,6,$item->id))
                            {{format_idr($sub_total_juni)}}
                        </td>
                        <td>
                            @php($sub_total_juli=sum_journal_cashflow($year,7,$item->id))
                            {{format_idr($sub_total_juli)}}
                        </td>
                        <td>
                            @php($sub_total_agustus=sum_journal_cashflow($year,8,$item->id))
                            {{format_idr($sub_total_agustus)}}
                        </td>
                        <td>
                            @php($sub_total_september=sum_journal_cashflow($year,9,$item->id))
                            {{format_idr($sub_total_september)}}
                        </td>
                        <td>
                            @php($sub_total_oktober=sum_journal_cashflow($year,10,$item->id))
                            {{format_idr($sub_total_oktober)}}
                        </td>
                        <td>
                            @php($sub_total_november=sum_journal_cashflow($year,11,$item->id))
                            {{format_idr($sub_total_november)}}
                        </td>
                        <td>
                            @php($sub_total_desember=sum_journal_cashflow($year,12,$item->id))
                            {{format_idr($sub_total_desember)}}
                        </td>
                    </tr>
                    @php($total_januari += $sub_total_januari)
                    @php($total_februari += $sub_total_februari)
                    @php($total_maret += $sub_total_maret)
                    @php($total_april += $sub_total_april)
                    @php($total_mei += $sub_total_mei)
                    @php($total_juni += $sub_total_juni)
                    @php($total_juli += $sub_total_juli)
                    @php($total_agustus += $sub_total_agustus)
                    @php($total_september += $sub_total_september)
                    @php($total_oktober += $sub_total_oktober)
                    @php($total_november += $sub_total_november)
                    @php($total_desember += $sub_total_desember)
                    @endforeach
                    
                    <!--Total-->
                    <tr>
                        <td></td>
                        <th>Cash From {{$i}}</th>
                        <th></th>
                        <th>{{format_idr($sub_total_januari)}}</th>
                        <th>{{format_idr($sub_total_februari)}}</th>
                        <th>{{format_idr($sub_total_maret)}}</th>
                        <th>{{format_idr($sub_total_april)}}</th>
                        <th>{{format_idr($sub_total_mei)}}</th>
                        <th>{{format_idr($sub_total_juni)}}</th>
                        <th>{{format_idr($sub_total_juli)}}</th>
                        <th>{{format_idr($sub_total_agustus)}}</th>
                        <th>{{format_idr($sub_total_september)}}</th>
                        <th>{{format_idr($sub_total_oktober)}}</th>
                        <th>{{format_idr($sub_total_november)}}</th>
                        <th>{{format_idr($sub_total_desember)}}</th>
                    </tr>
                    <tr><td colspan="15"></td></tr>
                @endforeach
                <tr>
                    <th></th>
                    <th>Increase/ Decrease Cash and Equivalent Cash</th>
                    <th></th>
                    <th>{{format_idr($total_januari)}}</th>
                    <th>{{format_idr($total_februari)}}</th>
                    <th>{{format_idr($total_maret)}}</th>
                    <th>{{format_idr($total_april)}}</th>
                    <th>{{format_idr($total_mei)}}</th>
                    <th>{{format_idr($total_juni)}}</th>
                    <th>{{format_idr($total_juli)}}</th>
                    <th>{{format_idr($total_agustus)}}</th>
                    <th>{{format_idr($total_september)}}</th>
                    <th>{{format_idr($total_oktober)}}</th>
                    <th>{{format_idr($total_november)}}</th>
                    <th>{{format_idr($total_desember)}}</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Cash and Equivalent Cash Beginning</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <th>Cash And Equivalent Cash End Periode</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <th>CEK</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>