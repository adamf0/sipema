<?php

namespace App\Http\Controllers;

use App\KampusProdi;
use App\MasterKampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminKampusProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterKampus $kampus)
    {
        $prodis = KampusProdi::where('id_kampus', $kampus->id)->simplePaginate(5);

        return view('detail-kampus.prodi.index', [
            'kampus' => $kampus,
            'prodis' => $prodis
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(MasterKampus $kampus)
    {
        return view('detail-kampus.prodi.create', [
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
                'kode_prodi',
                'nama',
                'jenjang',
                'masa_studi'
            ]),
            [
                'kode_prodi' => ['required', 'numeric'],
                'nama' => ['required'],
                'jenjang' => ['required'],
                'masa_studi' => ['required', 'min:1'],
            ],
            [],
            [
                'kode_prodi' => 'Kode Prodi',
                'nama' => 'Nama Prodi',
                'jenjang' => 'Jenjang',
                'masa_studi' => 'Masa Studi',
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

        $prodi = new KampusProdi();
        $prodi->id_kampus = $kampus->id;
        $prodi->kode_prodi = $request->kode_prodi;
        $prodi->nama = $request->nama;
        $prodi->jenjang = $request->jenjang;
        $prodi->masa_studi = $request->masa_studi;
        $prodi->save();

        return redirect()
            ->route('detail-kampus.prodi.index', [
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
     * @param  \App\KampusProdi  $prodi
     * @return \Illuminate\Http\Response
     */
    public function show(KampusProdi $prodi)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusProdi  $prodi
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterKampus $kampus, KampusProdi $prodi)
    {
        return view('detail-kampus.prodi.edit', [
            'kampus' => $kampus,
            'prodi' => $prodi
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusProdi  $prodi
     * @return \Illuminate\Http\Response
     */
    public function update(MasterKampus $kampus, Request $request, KampusProdi $prodi)
    {
        $validator = Validator::make(
            $request->only([
                'kode_prodi',
                'nama',
                'jenjang',
                'masa_studi'
            ]),
            [
                'kode_prodi' => ['required', 'numeric'],
                'nama' => ['required'],
                'jenjang' => ['required'],
                'masa_studi' => ['required', 'min:1'],
            ],
            [],
            [
                'kode_prodi' => 'Kode Prodi',
                'nama' => 'Nama Prodi',
                'jenjang' => 'Jenjang',
                'masa_studi' => 'Masa Studi',
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

        $prodi->id_kampus = $kampus->id;
        $prodi->kode_prodi = $request->kode_prodi;
        $prodi->nama = $request->nama;
        $prodi->jenjang = $request->jenjang;
        $prodi->masa_studi = $request->masa_studi;

        if (!$prodi->getDirty()) {
            return redirect()
                ->route('detail-kampus.prodi.index', [
                    'kampus' => $kampus->id
                ])
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $prodi->save();

        return redirect()
            ->route('detail-kampus.prodi.index', [
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
     * @param  \App\KampusProdi  $prodi
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterKampus $kampus, KampusProdi $prodi)
    {
        if (!$prodi->delete()) {
            return redirect()
                ->route('detail-kampus.prodi.index', [
                    'kampus' => $kampus
                ])
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect()
            ->route('detail-kampus.prodi.index', [
                'kampus' => $kampus
            ])
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}