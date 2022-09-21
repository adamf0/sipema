<?php

namespace App\Http\Controllers;

use App\KampusGelombang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KampusGelombangController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gelombangs = KampusGelombang::whereKampus(Session::get('id_kampus'))->simplePaginate(5);

        return view('kampus.gelombang.index', ['gelombangs' => $gelombangs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.gelombang.create');
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
                'nama_gelombang',
                'tanggal_mulai',
                'tanggal_akhir'
            ]),
            [
                'nama_gelombang' => ['required'],
                'tanggal_mulai' => ['required', 'date'],
                'tanggal_akhir' => ['required', 'date'],
            ],
            [],
            [
                'nama_gelombang' => 'Nama Gelombang',
                'tanggal_mulai' => 'Tanggal Mulai',
                'tanggal_akhir' => 'Tanggal Akhir',
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

        $kampusGelombang = new KampusGelombang();
        $kampusGelombang->id_kampus = Session::get('id_kampus');
        $kampusGelombang->nama_gelombang = $request->nama_gelombang;
        $kampusGelombang->tanggal_mulai = $request->tanggal_mulai;
        $kampusGelombang->tanggal_akhir = $request->tanggal_akhir;
        $kampusGelombang->save();

        return redirect(route('kampus.gelombang.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusGelombang  $kampusGelombang
     * @return \Illuminate\Http\Response
     */
    public function show(KampusGelombang $kampusGelombang)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusGelombang  $kampusGelombang
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusGelombang $kampusGelombang)
    {
        return view('kampus.gelombang.edit', [
            'gelombang' => $kampusGelombang
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusGelombang  $kampusGelombang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusGelombang $kampusGelombang)
    {
        $validator = Validator::make(
            $request->only([
                'nama_gelombang',
                'tanggal_mulai',
                'tanggal_akhir'
            ]),
            [
                'nama_gelombang' => ['required'],
                'tanggal_mulai' => ['required', 'date'],
                'tanggal_akhir' => ['required', 'date'],
            ],
            [],
            [
                'nama_gelombang' => 'Nama Gelombang',
                'tanggal_mulai' => 'Tanggal Mulai',
                'tanggal_akhir' => 'Tanggal Akhir',
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

        $kampusGelombang->id_kampus = Session::get('id_kampus');
        $kampusGelombang->nama_gelombang = $request->nama_gelombang;
        $kampusGelombang->tanggal_mulai = $request->tanggal_mulai;
        $kampusGelombang->tanggal_akhir = $request->tanggal_akhir;

        if (!$kampusGelombang->getDirty()) {
            return redirect()
                ->route('kampus.gelombang.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $kampusGelombang->save();

        return redirect(route('kampus.gelombang.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusGelombang  $kampusGelombang
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusGelombang $kampusGelombang)
    {
        if (!$kampusGelombang->delete()) {
            return redirect(route('kampus.gelombang.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.gelombang.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}