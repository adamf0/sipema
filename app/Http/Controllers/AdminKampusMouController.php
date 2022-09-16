<?php

namespace App\Http\Controllers;

use App\KampusMou;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MasterKampus;
use Illuminate\Support\Facades\Validator;

class AdminKampusMouController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterKampus $kampus)
    {
        $kampusMous = KampusMou::where('id_kampus', $kampus->id)->simplePaginate(5);

        return view('detail-kampus.mou.index', [
            'kampus' => $kampus,
            'kampusMous' => $kampusMous
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(MasterKampus $kampus)
    {
        return view('detail-kampus.mou.create', [
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
                'no_mou',
                'max_reschedule',
                'status_gelombang'
            ]),
            [
                'no_mou' => ['required'],
                'max_reschedule' => ['required', 'min:0', 'gte:0'],
                'status_gelombang' => ['nullable', 'in:1'],
            ],
            [],
            [
                'no_mou' => 'No. MOU',
                'max_reschedule' => 'Max Reschedule',
                'status_gelombang' => 'Status Gelombang',
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

        $kampusMou = new KampusMou();
        $kampusMou->no_mou = $request->no_mou;
        $kampusMou->id_kampus = $kampus->id;
        $kampusMou->max_reschedule = $request->max_reschedule;
        $kampusMou->status_gelombang = $request->status_gelombang ?? 0;
        $kampusMou->tanggal_dibuat = now()->format('Y-m-d');
        $kampusMou->save();

        return redirect(route('detail-kampus.mou.index', ['kampus' => $kampus->id]))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusMou  $kampusMou
     * @return \Illuminate\Http\Response
     */
    public function show(MasterKampus $kampus, KampusMou $mou)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusMou  $mou
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterKampus $kampus, KampusMou $mou)
    {
        return view('detail-kampus.mou.edit', [
            'kampus' => $kampus,
            'mou' => $mou
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusMou  $mou
     * @return \Illuminate\Http\Response
     */
    public function update(MasterKampus $kampus, Request $request, KampusMou $mou)
    {
        $validator = Validator::make(
            $request->only([
                'no_mou',
                'max_reschedule',
                'status_gelombang'
            ]),
            [
                'no_mou' => ['required'],
                'max_reschedule' => ['required', 'min:0', 'gte:0'],
                'status_gelombang' => ['nullable', 'in:1'],
            ],
            [],
            [
                'no_mou' => 'No. MOU',
                'max_reschedule' => 'Max Reschedule',
                'status_gelombang' => 'Status Gelombang',
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

        $mou->no_mou = $request->no_mou;
        $mou->id_kampus = $kampus->id;
        $mou->max_reschedule = $request->max_reschedule;
        $mou->status_gelombang = $request->status_gelombang ?? 0;
        $mou->tanggal_dibuat = now()->format('Y-m-d');

        if (!$mou->getDirty()) {
            return redirect()
                ->route('detail-kampus.mou.index', [
                    'kampus' => $kampus->id
                ])
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $mou->save();

        return redirect()
            ->route('detail-kampus.mou.index', [
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
     * @param  \App\KampusMou  $mou
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterKampus $kampus, KampusMou $mou)
    {
        if (!$mou->delete()) {
            return redirect()
                ->route('detail-kampus.mou.index', [
                    'kampus' => $kampus->id
                ])
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect()
            ->route('detail-kampus.mou.index', [
                'kampus' => $kampus->id
            ])
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}