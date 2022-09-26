<?php

namespace App\Http\Controllers;

use App\KampusPembayaran;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MasterChannelPembayaran;
use App\MasterKampus;
use Illuminate\Support\Facades\Validator;

class AdminKampusPembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterKampus $kampus)
    {
        $kampusPembayaran = KampusPembayaran::with('chanel_pembayaran')
            ->whereKampus($kampus->id)
            ->simplePaginate(5);

        return view('detail-kampus.pembayaran.index', [
            'kampus' => $kampus,
            'kampusPembayarans' => $kampusPembayaran
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(MasterKampus $kampus)
    {
        // dd($kampus->metodePembayaran->pluck('id_chanel_pembayaran')->toArray());
        // dd(MasterChannelPembayaran::whereNotIn('id', $kampus->metodePembayaran->pluck('id_chanel_pembayaran')->toArray())->get());
        $chanel_pembayarans = MasterChannelPembayaran::whereNotIn('id', $kampus->metodePembayaran->pluck('id_chanel_pembayaran')->toArray())->get();

        if ($chanel_pembayarans->count() >= 1) {
            return view('detail-kampus.pembayaran.create', [
                'kampus' => $kampus,
                'chanel_pembayarans' => $chanel_pembayarans
            ]);
        }

        return redirect()
            ->route('detail-kampus.pembayaran.index', [
                'kampus' => $kampus->id
            ])
            ->with('flash_message', (object)[
                'type' => 'warning',
                'title' => 'Peringatan',
                'message' => 'Seluruh Metode Pembayaran yang tersedia telah ada.'
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MasterKampus $kampus, Request $request)
    {
        $validator = Validator::make(
            $request->only([
                'id_chanel_pembayaran'
            ]),
            [
                'id_chanel_pembayaran' => ['required']
            ],
            [],
            [
                'id_chanel_pembayaran' => 'Channel Pembayaran'
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan cek kembali Form'
                ])
                ->withErrors($validator)
                ->withInput();
        }

        $kampusPembayaran = new KampusPembayaran();
        $kampusPembayaran->id_kampus = $kampus->id;
        $kampusPembayaran->id_chanel_pembayaran = $request->id_chanel_pembayaran;
        $kampusPembayaran->status = 1;
        $kampusPembayaran->save();

        return redirect()
            ->route('detail-kampus.pembayaran.index', [
                'kampus' => $kampus->id
            ])
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusPembayaran  $kampusPembayaran
     * @return \Illuminate\Http\Response
     */
    public function show(KampusPembayaran $kampusPembayaran)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusPembayaran  $kampusPembayaran
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusPembayaran $kampusPembayaran)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusPembayaran  $kampusPembayaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusPembayaran $kampusPembayaran)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusPembayaran  $pembayaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterKampus $kampus, KampusPembayaran $pembayaran)
    {
        if (!$pembayaran->delete()) {
            return redirect()
                ->route('detail-kampus.pembayaran.index', ['kampus' => $kampus->id])
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect()
            ->route('detail-kampus.pembayaran.index', ['kampus' => $kampus->id])
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}