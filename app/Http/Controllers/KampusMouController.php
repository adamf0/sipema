<?php

namespace App\Http\Controllers;

use App\KampusMou;
use App\MasterKampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class KampusMouController extends Controller
{
    public $id_kampus = null;
    public $kampus = [];
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
        $kampusMous = KampusMou::whereKampus(Session::get('id_kampus'))->simplePaginate(5);

        return view('kampus.mou.index', [
            'kampusMous' => $kampusMous
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $masterKampus = MasterKampus::all(['id', 'kode_kampus', 'nama_kampus']);

        return view('kampus.mou.create', ['masterKampuss' => $masterKampus]);
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
                'no_mou',
                // 'kampus',
                'max_reschedule',
                'status_gelombang'
            ]),
            [
                'no_mou' => ['required'],
                // 'kampus' => ['required'],
                'max_reschedule' => ['required', 'min:0', 'gte:0'],
                'status_gelombang' => ['nullable', 'in:1'],
            ],
            [],
            [
                'no_mou' => 'No. MOU',
                // 'kampus' => 'Kampus',
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
        $kampusMou->id_kampus = Session::get('id_kampus');
        $kampusMou->max_reschedule = $request->max_reschedule;
        $kampusMou->status_gelombang = $request->status_gelombang ?? 0;
        $kampusMou->tanggal_dibuat = now()->format('Y-m-d');
        $kampusMou->save();

        return redirect(route('kampus.mou.index'))
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
    public function show(KampusMou $kampusMou)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusMou  $kampusMou
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusMou $kampusMou)
    {
        $masterKampus = MasterKampus::all(['id', 'kode_kampus', 'nama_kampus']);

        return view('kampus.mou.edit', [
            'masterKampuss' => $masterKampus,
            'kampusMou' => $kampusMou
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusMou  $kampusMou
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusMou $kampusMou)
    {
        $validator = Validator::make(
            $request->only([
                'no_mou',
                // 'kampus',
                'max_reschedule',
                'status_gelombang'
            ]),
            [
                'no_mou' => ['required'],
                // 'kampus' => ['required'],
                'max_reschedule' => ['required', 'min:0', 'gte:0'],
                'status_gelombang' => ['nullable', 'in:1'],
            ],
            [],
            [
                'no_mou' => 'No. MOU',
                // 'kampus' => 'Kampus',
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

        $kampusMou->no_mou = $request->no_mou;
        $kampusMou->id_kampus = Session::get('id_kampus');
        $kampusMou->max_reschedule = $request->max_reschedule;
        $kampusMou->status_gelombang = $request->status_gelombang ?? 0;
        $kampusMou->tanggal_dibuat = now()->format('Y-m-d');

        if (!$kampusMou->getDirty()) {
            return redirect()
                ->route('kampus.mou.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $kampusMou->save();

        return redirect(route('kampus.mou.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusMou  $kampusMou
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusMou $kampusMou)
    {
        if (!$kampusMou->delete()) {
            return redirect(route('kampus.mou.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.mou.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}