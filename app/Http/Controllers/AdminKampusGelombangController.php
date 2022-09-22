<?php

namespace App\Http\Controllers;

use App\KampusGelombang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KampusItemBayar;
use App\MasterKampus;
use Illuminate\Support\Facades\Validator;

class AdminKampusGelombangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterKampus $kampus)
    {
        $gelombangs = KampusGelombang::whereKampus($kampus->id)->simplePaginate(5);

        return view('detail-kampus.gelombang.index', [
            'kampus' => $kampus,
            'gelombangs' => $gelombangs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(MasterKampus $kampus)
    {
        return view('detail-kampus.gelombang.create', [
            'kampus' => $kampus
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

        $gelombang = new KampusGelombang();
        $gelombang->id_kampus = $kampus->id;
        $gelombang->nama_gelombang = $request->nama_gelombang;
        $gelombang->tanggal_mulai = $request->tanggal_mulai;
        $gelombang->tanggal_akhir = $request->tanggal_akhir;
        $gelombang->save();

        return redirect()
            ->route('detail-kampus.gelombang.index', [
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
     * @param  \App\KampusGelombang  $gelombang
     * @return \Illuminate\Http\Response
     */
    public function show(KampusGelombang $gelombang)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusGelombang  $gelombang
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterKampus $kampus, KampusGelombang $gelombang)
    {
        return view('detail-kampus.gelombang.edit', [
            'kampus' => $kampus,
            'gelombang' => $gelombang
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusGelombang  $gelombang
     * @return \Illuminate\Http\Response
     */
    public function update(MasterKampus $kampus, Request $request, KampusGelombang $gelombang)
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

        $gelombang->id_kampus = $kampus->id;
        $gelombang->nama_gelombang = $request->nama_gelombang;
        $gelombang->tanggal_mulai = $request->tanggal_mulai;
        $gelombang->tanggal_akhir = $request->tanggal_akhir;

        if (!$gelombang->getDirty()) {
            return redirect()
                ->route('detail-kampus.gelombang.index', [
                    'kampus' => $kampus->id
                ])
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $gelombang->save();

        return redirect()
            ->route('detail-kampus.gelombang.index', [
                'kampus' => $kampus->id
            ])
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusGelombang  $gelombang
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterKampus $kampus, KampusGelombang $gelombang)
    {
        if (!$gelombang->delete()) {
            return redirect()
                ->route('detail-kampus.gelombang.index', [
                    'kampus' => $kampus->id
                ])
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect()
            ->route('detail-kampus.gelombang.index', [
                'kampus' => $kampus->id
            ])
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}