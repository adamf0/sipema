<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class kampus_tagihan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kampus_tagihan:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'membuat tagihan transaksi mahasiswa dari data perencanaan mahasiswa';

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
        $data_group_mahasiwa = DB::table('kampus_rencana_mahasiswa as krm')
                            ->select(
                                DB::raw('concat(mk.kode_kampus,"'.rand(0,100).'",IF(km.nim!="", km.nim, km.nim_sementara)) as nomor_transaksi'),
                                'krm.id_mahasiswa',
                                'krm.tanggal_bayar as tanggal',
                                DB::raw('concat("0") as status'),
                                'krm.id_mahasiswa'
                            )
                            ->join('kampus_mahasiswa as km','krm.id_mahasiswa','=','km.id')
                            ->join('kampus_prodi as kp','km.id_prodi','=','kp.id')
                            ->join('master_kampus as mk','kp.id_kampus','=','mk.id')
                            ->where('krm.tanggal_bayar',date('Y-m-d')) //date('Y-m-d')
                            ->groupBy('krm.id_mahasiswa')
                            ->get();
        $data_mahasiwa = DB::table('kampus_rencana_mahasiswa as krm')
                    ->select('krm.id as id_tagihan_mahasiswa','krm.biaya')
                    ->join('kampus_mahasiswa as km','krm.id_mahasiswa','=','km.id')
                    ->join('kampus_prodi as kp','km.id_prodi','=','kp.id')
                    ->join('master_kampus as mk','kp.id_kampus','=','mk.id')
                    ->where('krm.tanggal_bayar',date('Y-m-d')) //date('Y-m-d')
                    ->get();
        
        DB::transaction(function () use (&$data_group_mahasiwa,&$data_mahasiwa) {
            foreach($data_group_mahasiwa as $dgm){
                if(DB::table('kampus_tagihan')->where('tanggal','like','%'.date('Y-m-d').'%')->count()==0){ //date('Y-m-d')
                    $id_transaksi = DB::table('kampus_tagihan')->insertGetId((array) $dgm);

                    foreach($data_mahasiwa as $dm){
                        DB::table('kampus_tagihan_detail')->insertGetId([
                            "id_transaksi"=>$id_transaksi,
                            "id_tagihan_mahasiswa"=>$dm->id_tagihan_mahasiswa,
                            "biaya"=>$dm->biaya
                        ]);
                    }
                }
            }
        });
    }
}
