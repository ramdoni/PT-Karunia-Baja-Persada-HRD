<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Income;

class CheckAging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:aging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = Income::orderBy('id','desc')->where(['reference_type'=>'Premium Receivable','status'=>2])->get();
        
        foreach($data as $item){
            $aging = calculate_aging($item->due_date);
            if($aging !=0){
                echo "Outstanding : {$item->no_voucher}\n";
                $item->outstanding_balance = $item->nominal;
                $item->status = 3;
                $item->save();
            }
        }

        return 0;
    }
}
