<?php

namespace App\Http\Controllers;

use App\KampusTagihan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KampusTagihanDetail;

class KampusPelaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pelaporans = KampusTagihan::with('mahasiswa')->simplePaginate(5);
        // dd($pelaporans);
        return view('kampus.pelaporan.index', [
            'pelaporans' => $pelaporans
        ]);
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
    public function show(KampusTagihan $pelaporan)
    {
        $detail_tagihan = $pelaporan->tagihan_detail()->simplePaginate(5);
        return view('kampus.pelaporan.show', ['pelaporan' => $pelaporan, 'detail_tagihans' => $detail_tagihan]);
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