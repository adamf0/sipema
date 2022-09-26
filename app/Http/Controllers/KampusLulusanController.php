<?php

namespace App\Http\Controllers;

use App\KampusLulusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KampusLulusanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kampus_lulusan = KampusLulusan::simplePaginate(5);

        return view('kampus.lulusan.index', [
            'kampusLulusan' => $kampus_lulusan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.lulusan.create');
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
                'nama' => 'Nama Lulusan'
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

        $kampus_lulusan = new KampusLulusan();
        $kampus_lulusan->id_kampus = Session::get('id_kampus');
        $kampus_lulusan->nama = $request->nama;
        $kampus_lulusan->save();

        return redirect(route('kampus.lulusan.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusLulusan  $kampus_lulusan
     * @return \Illuminate\Http\Response
     */
    public function show(KampusLulusan $kampus_lulusan)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusLulusan  $kampus_lulusan
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusLulusan $kampus_lulusan)
    {
        return view('kampus.lulusan.edit',['lulusan'=>$kampus_lulusan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusLulusan  $kampus_lulusan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusLulusan $kampus_lulusan)
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
                'nama' => 'Nama Lulusan'
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

        $kampus_lulusan->id_kampus = Session::get('id_kampus');
        $kampus_lulusan->nama = $request->nama;

        if (!$kampus_lulusan->getDirty()) {
            return redirect()
                ->route('kampus.lulusan.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $kampus_lulusan->save();

        return redirect(route('kampus.lulusan.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusLulusan  $kampus_lulusan
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusLulusan $kampus_lulusan)
    {
        if (!$kampus_lulusan->delete()) {
            return redirect(route('kampus.lulusan.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.lulusan.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}
