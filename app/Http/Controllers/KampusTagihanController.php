<?php

namespace App\Http\Controllers;

use App\KampusProdi;
use App\KampusTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KampusTagihanController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $kampusTagihans = KampusTagihan::whereHas(
            'mahasiswa',function($q) use($request){
                if($request->has('mahasiswa')) $q->where('nama_lengkap','like',"%$request->mahasiswa%");
                if($request->has('prodi')) $q->where('id_prodi',$request->prodi);
            
                return $q;
            })
            ->whereHas('mahasiswa.prodi',function($q){
                return $q->whereKampus(Session::get('id_kampus'));
            })
            ->whereHas('tagihan_detail.rencana.item_bayar.item');
        if($request->has('tanggal')) $kampusTagihans->where('tanggal',$request->tanggal);
        if($request->has('status')) $kampusTagihans->where('status',$request->status);
        $kampusTagihans = $kampusTagihans->simplePaginate(5);
        
        // dd($kampusTagihans);
        return view('kampus.tagihan.index', [
            'kampusTagihans' => $kampusTagihans,
            'prodis'=>KampusProdi::whereKampus(Session::get('id_kampus'))->get(),
        ])->with($request->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusTagihan  $kampusTagihan
     * @return \Illuminate\Http\Response
     */
    public function show(KampusTagihan $kampusTagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusTagihan  $kampusTagihan
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusTagihan $kampusTagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusTagihan  $kampusTagihan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusTagihan $kampusTagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusTagihan  $kampusTagihan
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusTagihan $kampusTagihan)
    {
        //
    }
}
