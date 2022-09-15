<?php

namespace App\Http\Controllers;

use App\MasterTipeBiayaPotongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterTipeBiayaPotonganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $masterTipeBiayaPotongans = MasterTipeBiayaPotongan::simplePaginate(5);

        return view('master.tipe-biaya-potongan.index', [
            'masterTipeBiayaPotongans' => $masterTipeBiayaPotongans
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.tipe-biaya-potongan.create');
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
                'nama' => ['required'],
            ],
            [],
            [
                'nama' => 'Nama'
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

        $masterItem = new MasterTipeBiayaPotongan();
        $masterItem->nama = $request->nama;
        $masterItem->save();

        return redirect(route('master.tipe-biaya-potongan.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MasterTipeBiayaPotongan  $masterTipeBiayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function show(MasterTipeBiayaPotongan $masterTipeBiayaPotongan)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MasterTipeBiayaPotongan  $masterTipeBiayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterTipeBiayaPotongan $masterTipeBiayaPotongan)
    {
        return view('master.tipe-biaya-potongan.edit', [
            'masterTipeBiayaPotongan' => $masterTipeBiayaPotongan
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MasterTipeBiayaPotongan  $masterTipeBiayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterTipeBiayaPotongan $masterTipeBiayaPotongan)
    {
        $validator = Validator::make(
            $request->only([
                'nama'
            ]),
            [
                'nama' => ['required'],
            ],
            [],
            [
                'nama' => 'Nama'
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

        $masterTipeBiayaPotongan->nama = $request->nama;

        if (!$masterTipeBiayaPotongan->getDirty()) {
            return redirect()
                ->route('master.tipe-biaya-potongan.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $masterTipeBiayaPotongan->save();

        return redirect(route('master.tipe-biaya-potongan.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MasterTipeBiayaPotongan  $masterTipeBiayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterTipeBiayaPotongan $masterTipeBiayaPotongan)
    {
        if (!$masterTipeBiayaPotongan->delete()) {
            return redirect(route('master.tipe-biaya-potongan.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('master.tipe-biaya-potongan.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}