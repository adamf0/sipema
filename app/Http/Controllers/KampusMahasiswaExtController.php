<?php

namespace App\Http\Controllers;

use App\KampusItemBayar;
use App\KampusMahasiswa;
use App\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Debugbar;

class KampusMahasiswaExtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.mahasiswa.item_bayar.create',[
            'mahasiswas'=>KampusMahasiswa::whereKampus(Session::get('id_kampus'))->get(),
        ]);
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
     * @param  \App\KampusMahasiswa  $kampusMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(KampusMahasiswa $kampusMahasiswa)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusMahasiswa  $kampusMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusMahasiswa $kampusMahasiswa)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusMahasiswa  $kampusMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusMahasiswa $kampusMahasiswa)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusMahasiswa  $kampusMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusMahasiswa $kampusMahasiswa)
    {
        //
    }

    public function getData(Request $request){
        Debugbar::disable();

        // dd($request->all());
        if($request->has('id_mahasiswa')){
            $mahasiswa = KampusMahasiswa::findOrFail($request->id_mahasiswa);
            $itemSelected = KampusItemBayar::whereIn('id',json_decode($mahasiswa->item_bayar_selected))->get()->pluck('id_item');
            $group_item_bayar = KampusItemBayar::whereKampus($request->id_kampus)->where('status',1)->groupBy('id_item')->get()->pluck('id_item');
            $available_item_bayar = KampusItemBayar::whereIn('id_item',$group_item_bayar)->whereNotIn('id_item',$itemSelected)->get();
            
            return response()->json([
                "status"=>200,
                "data"=>$available_item_bayar,
                "error"=>""
            ]);
        }
        else{
            return response()->json([
                "status"=>500,
                "data"=>$request->all(),
                "error"=>"data yang dikirim tidak lengkap"
            ]);
        }
    }
}
