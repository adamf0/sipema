<?php

namespace App\Http\Controllers;

use App\MasterKampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MasterKampusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $masterKampus = MasterKampus::simplePaginate(5);

        return view('master.kampus.index', [
            'masterKampuss' => $masterKampus
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.kampus.create');
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
                'nama_kampus',
                'singkatan',
                'tahun_kerjasama',
            ]),
            [
                'nama_kampus' => ['required'],
                'singkatan' => ['required'],
                'tahun_kerjasama' => ['required', 'date'],
            ],
            [],
            [
                'nama_kampus' => 'Nama Kampus',
                'singkatan' => 'Singkatan',
                'tahun_kerjasama' => 'Tahun Kerjasama',
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

        $masterKampus = new MasterKampus();

        $arrayNama = explode(" ", $request->nama_kampus);
        $akronim = '';

        foreach ($arrayNama as $value) {
            $akronim .= substr(ucfirst($value), 0, 1);
        }

        $akronimCount = MasterKampus::where('kode_kampus', $akronim)->count();
        while ($akronimCount >= 1) {
            $akronim = $akronim . rand(0, 9) . rand(0, 9) . rand(1, 9);

            $akronimCount = MasterKampus::where('kode_kampus', $akronim)->count();
        }

        $masterKampus->kode_kampus = $akronim;
        $masterKampus->nama_kampus = $request->nama_kampus;
        $masterKampus->singkatan = $request->singkatan;
        $masterKampus->tahun_kerjasama = $request->tahun_kerjasama;
        $masterKampus->save();

        return redirect(route('master.kampus.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MasterKampus  $masterKampus
     * @return \Illuminate\Http\Response
     */
    public function show(MasterKampus $masterKampus)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MasterKampus  $masterKampus
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterKampus $masterKampus)
    {
        return view('master.kampus.edit', [
            'masterKampus' => $masterKampus
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MasterKampus  $masterKampus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterKampus $masterKampus)
    {
        $validator = Validator::make(
            $request->only([
                'nama_kampus',
                'singkatan',
                'tahun_kerjasama',
            ]),
            [
                'nama_kampus' => ['required'],
                'singkatan' => ['required'],
                'tahun_kerjasama' => ['required', 'date'],
            ],
            [],
            [
                'nama_kampus' => 'Nama Kampus',
                'singkatan' => 'Singkatan',
                'tahun_kerjasama' => 'Tahun Kerjasama',
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

        $masterKampus->nama_kampus = $request->nama_kampus;
        $masterKampus->singkatan = $request->singkatan;
        $masterKampus->tahun_kerjasama = $request->tahun_kerjasama;
        $masterKampus->save();

        return redirect(route('master.kampus.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MasterKampus  $masterKampus
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterKampus $masterKampus)
    {
        if (!$masterKampus->delete()) {
            return redirect(route('master.kampus.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('master.kampus.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}