<?php

namespace App\Http\Controllers;

use App\KampusGelombang;
use App\KampusItemBayar;
use App\KampusMahasiswa;
use App\KampusMou;
use App\KampusProdi;
use App\KampusTagihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [];
        if(Auth::user()->hasRole('User')){
            $menunggu = KampusTagihan::with(['mahasiswa','tagihan_detail'])
                            ->whereHas('mahasiswa.prodi',function($query){
                                $query->whereKampus(Session::get('id_kampus'));
                            })
                            ->now()
                            ->waiting()
                            ->get()
                            ->each(function($item,$index){
                                $item->total = 0;
                                $item->tagihan_detail->each(function($td,$index_) use($item){
                                    $item->total += $td->biaya;
                                });
                            });
            $selesai = KampusTagihan::with(['mahasiswa','tagihan_detail'])
                            ->whereHas('mahasiswa.prodi',function($query){
                                $query->whereKampus(Session::get('id_kampus'));
                            })
                            ->now()
                            ->settlement()
                            ->get()
                            ->each(function($item,$index){
                                $item->total = 0;
                                $item->tagihan_detail->each(function($td,$index_) use($item){
                                    $item->total += $td->biaya;
                                });
                            });
            $jadwal_ulang = KampusTagihan::with(['mahasiswa','tagihan_detail'])
                                ->whereHas('mahasiswa.prodi',function($query){
                                    $query->whereKampus(Session::get('id_kampus'));
                                })
                                ->now()
                                ->reschedule()
                                ->get()
                                ->each(function($item,$index){
                                    $item->total = 0;
                                    $item->tagihan_detail->each(function($td,$index_) use($item){
                                        $item->total += $td->biaya;
                                    });
                                });

            $belum_selesai = KampusTagihan::with(['mahasiswa','tagihan_detail'])
                                ->whereHas('mahasiswa.prodi',function($query){
                                    $query->whereKampus(Session::get('id_kampus'));
                                })
                                ->old()
                                ->waiting()
                                ->get()
                                ->each(function($item,$index){
                                    $item->total = 0;
                                    $item->tagihan_detail->each(function($td,$index_) use($item){
                                        $item->total += $td->biaya;
                                    });
                                });
            
            $data = [
                "mou"=>KampusMou::whereKampus(Session::get('id_kampus'))->orderBy('tanggal_dibuat','DESC')->first(),
                "prodi"=>KampusProdi::whereKampus(Session::get('id_kampus'))->get(),
                "gelombang"=>KampusGelombang::whereKampus(Session::get('id_kampus'))->get(),
                "item_bayar"=>KampusItemBayar::whereKampus(Session::get('id_kampus'))->get(),
                "mahasiswa"=>KampusMahasiswa::whereHas('prodi',function($query){
                    $query->whereKampus(Session::get('id_kampus'));
                })->get(),
                "tagihan"=>(object) [
                    "menunggu"=>$menunggu,
                    "selesai"=>$selesai,
                    "jadwal_ulang"=>$jadwal_ulang,
                    "belum_selesai"=>$belum_selesai,
                ]
            ];
        }

        return view('home',$data);
    }
}
