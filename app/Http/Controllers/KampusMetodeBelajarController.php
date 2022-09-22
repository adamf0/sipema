<?php

namespace App\Http\Controllers;

use App\KampusMetodeBelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KampusMetodeBelajarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kampusMetodes = KampusMetodeBelajar::simplePaginate(5);

        return view('kampus.metode_belajar.index', [
            'kampusMetodes' => $kampusMetodes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.metode_belajar.create');
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
                'nama'
            ]),
            [
                'nama' => ['required']
            ],
            [],
            [
                'nama' => 'Nama Metode Belajar'
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

        $kampus_metode_belajar = new KampusMetodeBelajar();
        $kampus_metode_belajar->id_kampus = Session::get('id_kampus');
        $kampus_metode_belajar->nama = $request->nama;
        $kampus_metode_belajar->save();

        return redirect(route('kampus.metode_belajar.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusMetodeBelajar  $kampus_metode_belajar
     * @return \Illuminate\Http\Response
     */
    public function show(KampusMetodeBelajar $kampus_metode_belajar)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusMetodeBelajar  $kampus_metode_belajar
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusMetodeBelajar $kampus_metode_belajar)
    {
        return view('kampus.metode_belajar.edit',['metode_belajar'=>$kampus_metode_belajar]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusMetodeBelajar  $kampus_metode_belajar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusMetodeBelajar $kampus_metode_belajar)
    {
        $validator = Validator::make(
            $request->only([
                'nama'
            ]),
            [
                'nama' => ['required']
            ],
            [],
            [
                'nama' => 'Nama Metode Belajar'
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

        $kampus_metode_belajar->id_kampus = Session::get('id_kampus');
        $kampus_metode_belajar->nama = $request->nama;

        if (!$kampus_metode_belajar->getDirty()) {
            return redirect()
                ->route('kampus.metode_belajar.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $kampus_metode_belajar->save();

        return redirect(route('kampus.metode_belajar.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusMetodeBelajar  $kampus_metode_belajar
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusMetodeBelajar $kampus_metode_belajar)
    {
        if (!$kampus_metode_belajar->delete()) {
            return redirect(route('kampus.metode_belajar.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.metode_belajar.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}
