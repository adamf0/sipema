<?php

namespace App\Http\Controllers;

use App\KampusKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KampusKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kampus_kelas = kampusKelas::simplePaginate(5);

        return view('kampus.kelas.index', [
            'kampusKelas' => $kampus_kelas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.kelas.create');
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
                'nama' => 'Nama Kelas'
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

        $kampus_kelas = new KampusKelas();
        $kampus_kelas->id_kampus = Session::get('id_kampus');
        $kampus_kelas->nama = $request->nama;
        $kampus_kelas->save();

        return redirect(route('kampus.kelas.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusKelas  $kampus_kelas
     * @return \Illuminate\Http\Response
     */
    public function show(KampusKelas $kampus_kelas)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusKelas  $kampus_kelas
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusKelas $kampus_kelas)
    {
        return view('kampus.kelas.edit',['kelas'=>$kampus_kelas]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusKelas  $kampus_kelas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusKelas $kampus_kelas)
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
                'nama' => 'Nama Kelas'
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

        $kampus_kelas->id_kampus = Session::get('id_kampus');
        $kampus_kelas->nama = $request->nama;

        if (!$kampus_kelas->getDirty()) {
            return redirect()
                ->route('kampus.kelas.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $kampus_kelas->save();

        return redirect(route('kampus.kelas.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusKelas  $kampus_kelas
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusKelas $kampus_kelas)
    {
        if (!$kampus_kelas->delete()) {
            return redirect(route('kampus.kelas.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.kelas.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}
