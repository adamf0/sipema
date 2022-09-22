<?php

namespace App\Http\Controllers;

use App\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KampusItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $masterItems = MasterItem::simplePaginate(5);

        return view('kampus.item.index', [
            'masterItems' => $masterItems
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.item.create');
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
            return redirect(route('kampus.item.create'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan cek kembali Form'
                ])
                ->withErrors($validator)
                ->withInput();
        }

        $masterItem = new MasterItem();
        $masterItem->nama = $request->nama;
        $masterItem->save();

        return redirect(route('kampus.item.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MasterItem  $masterItem
     * @return \Illuminate\Http\Response
     */
    public function show(MasterItem $masterItem)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MasterItem  $masterItem
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterItem $masterItem)
    {
        return view('kampus.item.edit', [
            'masterItem' => $masterItem
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MasterItem  $masterItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterItem $masterItem)
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

        $masterItem->nama = $request->nama;

        if (!$masterItem->getDirty()) {
            return redirect()
                ->route('kampus.item.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $masterItem->save();

        return redirect(route('kampus.item.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MasterItem  $masterItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterItem $masterItem)
    {
        if (!$masterItem->delete()) {
            return redirect(route('kampus.item.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.item.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}