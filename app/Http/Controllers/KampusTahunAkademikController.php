<?php

namespace App\Http\Controllers;

use App\KampusTahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KampusTahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KampusTahunAkademik::whereKampus(Session::get('id_kampus'))->where('id', '!=', 1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $actionBtn = "<div class='d-flex gap-2'>
                                    <a href='" . route('kampus.tahun_akademik.edit', ['tahun_akademik' => $row->id]) . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <form action='" . route('kampus.tahun_akademik.destroy', ['tahun_akademik' => $row->id]) . "' method='post'>
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

        return view('kampus.tahun_akademik.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.tahun_akademik.create');
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
                'nama',
                'tanggal_ajaran_baru'
            ]),
            [
                'nama' => ['required'],
                'tanggal_ajaran_baru' => ['required']
            ],
            [],
            [
                'nama' => 'Nama Tahun Akademik',
                'tanggal_ajaran_baru' => 'Tanggal Ajaran Baru'
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

        $kampus_tahun_akademik = new KampusTahunAkademik();
        $kampus_tahun_akademik->id_kampus = Session::get('id_kampus');
        $kampus_tahun_akademik->nama = $request->nama;
        $kampus_tahun_akademik->tanggal_ajaran_baru = $request->tanggal_ajaran_baru;
        $kampus_tahun_akademik->save();

        return redirect(route('kampus.tahun_akademik.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusTahunAkademik  $kampus_tahun_akademik
     * @return \Illuminate\Http\Response
     */
    public function show(KampusTahunAkademik $kampus_tahun_akademik)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusTahunAkademik  $kampus_tahun_akademik
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusTahunAkademik $kampus_tahun_akademik)
    {
        return view('kampus.tahun_akademik.edit', ["tahun_akademik" => $kampus_tahun_akademik]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusTahunAkademik  $kampus_tahun_akademik
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusTahunAkademik $kampus_tahun_akademik)
    {
        $validator = Validator::make(
            $request->only([
                'nama',
                'tanggal_ajaran_baru'
            ]),
            [
                'nama' => ['required'],
                'tanggal_ajaran_baru' => ['required']
            ],
            [],
            [
                'nama' => 'Nama Tahun Akademik',
                'tanggal_ajaran_baru' => 'Tanggal Ajaran Baru'
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

        $kampus_tahun_akademik->id_kampus = Session::get('id_kampus');
        $kampus_tahun_akademik->nama = $request->nama;
        $kampus_tahun_akademik->tanggal_ajaran_baru = $request->tanggal_ajaran_baru;

        if (!$kampus_tahun_akademik->getDirty()) {
            return redirect()
                ->route('kampus.tahun_akademik.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }
        $kampus_tahun_akademik->save();

        return redirect(route('kampus.tahun_akademik.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusTahunAkademik  $kampus_tahun_akademik
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusTahunAkademik $kampus_tahun_akademik)
    {
        if (!$kampus_tahun_akademik->delete()) {
            return redirect(route('kampus.tahun_akademik.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.tahun_akademik.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}
