<?php

namespace App\Http\Controllers;

use App\KampusMetodeBelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KampusMetodeBelajarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KampusMetodeBelajar::where('id', '!=', 1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $actionBtn = "<div class='d-flex gap-2'>
                                    <a href='" . route('kampus.metode_belajar.edit', ['metode_belajar' => $row->id]) . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <form action='" . route('kampus.metode_belajar.destroy', ['metode_belajar' => $row->id]) . "' method='post'>
                                        <input type='hidden' name='_token' value='" . csrf_token() . "'>
                                        <input type='hidden' name='_method' value='DELETE'>
                                        <button type='submit' class='btn btn-danger btn-sm'>Hapus</button>
                                    </form>
                                </div>";
                    return $actionBtn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('kampus.metode_belajar.index');
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
        return view('kampus.metode_belajar.edit', ['metode_belajar' => $kampus_metode_belajar]);
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
