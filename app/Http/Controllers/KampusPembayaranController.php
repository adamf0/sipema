<?php

namespace App\Http\Controllers;

use App\KampusPembayaran;
use App\MasterChannelPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class KampusPembayaranController extends Controller
{
    public $id_kampus = null;
    public function __construct()
    {
        // Auth::user()->id_kampus = auth()->user()->id_kampus;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kampusPembayaran = KampusPembayaran::with('chanel_pembayaran')
                                ->whereKampus(Session::get('id_kampus'))
                                ->simplePaginate(5);

        return view('kampus.pembayaran.index', [
            'kampusPembayarans' => $kampusPembayaran
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $chanel_pembayarans = MasterChannelPembayaran::all();

        return view('kampus.pembayaran.create', [
            'chanel_pembayarans' => $chanel_pembayarans
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
        $validator = Validator::make(
            $request->only([
                'id_chanel_pembayaran'
            ]),
            [
                'id_chanel_pembayaran' => ['required']
            ],
            [],
            [
                'id_chanel_pembayaran' => 'Canel Pembayaran'
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
        $kampusPembayaran->id_kampus = Session::get('id_kampus');
        $kampusPembayaran->id_chanel_pembayaran = $request->id_chanel_pembayaran;
        $kampusPembayaran->status = 1;
        $kampusPembayaran->save();

        return redirect(route('kampus.pembayaran.index'))
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
    public function edit(KampusPembayaran $pembayaran)
    {
        $chanel_pembayarans = MasterChannelPembayaran::all();
        $pembayaran->load('chanel_pembayaran');

        return view('kampus.pembayaran.edit', [
            'chanel_pembayarans' => $chanel_pembayarans,
            'kampusPembayaran'=> $pembayaran
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusPembayaran  $kampusPembayaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusPembayaran $pembayaran)
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
                'id_chanel_pembayaran' => 'Canel Pembayaran'
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

        $pembayaran->id_kampus = Session::get('id_kampus');
        $pembayaran->id_chanel_pembayaran = $request->id_chanel_pembayaran;
        $pembayaran->status = 1;
        
        if (!$pembayaran->getDirty()) {
            return redirect()
                ->route('kampus.pembayaran.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $pembayaran->save();

        return redirect(route('kampus.pembayaran.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusPembayaran  $kampusPembayaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusPembayaran $pembayaran)
    {
        if (!$pembayaran->delete()) {
            return redirect(route('kampus.pembayaran.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.pembayaran.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}
