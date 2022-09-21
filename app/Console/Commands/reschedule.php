<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class reschedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reschedule:active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'membuat tagihan transaksi mahasiswa yg kena reschedule menjadi aktif kembali';

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
     * @return mixed
     */
    public function handle()
    {
        DB::transaction(function () {
            $jadwal_ulang_tagihan = DB::table('kampus_jadwal_ulang_tagihan')
                                        ->select('item_tagihan_selected')
                                        ->where('status',1)
                                        ->where('tanggal_diundur',date('Y-m-d')) //date('Y-m-d')
                                        ->get(); 
            $jadwal_ulang_tagihan = collect($jadwal_ulang_tagihan)->each(function($item,$index){ $item->item_tagihan_selected = json_decode($item->item_tagihan_selected); })
                                        ->pluck('item_tagihan_selected')
                                        ->flatten()
                                        ->unique(); 
            foreach($jadwal_ulang_tagihan as $key => $jut){
                if(DB::table('kampus_tagihan')->where('id',$jut)->count()==0){
                    unset($jadwal_ulang_tagihan[$key]);
                }
            }
            DB::table('kampus_tagihan')
                ->whereIn('id',$jadwal_ulang_tagihan)
                ->where('status',-1)
                ->update(["status"=>0]);
        });
    }
}
