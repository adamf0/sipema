<?php

namespace App\Console\Commands;

use App\KampusTagihan;
use App\KampusTagihanDetail;
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
                    ->select('krm.id as id_tagihan_mahasiswa','krm.biaya','krm.id_mahasiswa')
                    ->join('kampus_mahasiswa as km','krm.id_mahasiswa','=','km.id')
                    ->join('kampus_prodi as kp','km.id_prodi','=','kp.id')
                    ->join('master_kampus as mk','kp.id_kampus','=','mk.id')
                    ->where('krm.tanggal_bayar',date('Y-m-d')) //date('Y-m-d')
                    ->get();

        DB::transaction(function () use (&$data_group_mahasiwa,&$data_mahasiwa) {
            foreach($data_group_mahasiwa as $dgm){
                if(KampusTagihan::where('tanggal','like','%'.date('Y-m-d').'%')->where('id_mahasiswa',$dgm->id_mahasiswa)->count()==0){
                    $tagihan = new KampusTagihan();
                    $tagihan->nomor_transaksi = $dgm->nomor_transaksi;
                    $tagihan->tanggal = $dgm->tanggal;
                    $tagihan->status = $dgm->status;
                    $tagihan->id_mahasiswa = $dgm->id_mahasiswa;
                    $tagihan->save();

                    foreach($data_mahasiwa as $dm){
                        if($dgm->id_mahasiswa == $dm->id_mahasiswa){
                            $tagihan_detail = new KampusTagihanDetail();
                            $tagihan_detail-> id_transaksi = $tagihan->id;
                            $tagihan_detail->id_tagihan_mahasiswa = $dm->id_tagihan_mahasiswa;
                            $tagihan_detail->biaya = $dm->biaya;
                            $tagihan_detail->save();
                        }
                    }
                }
            }
        });
    }
}
