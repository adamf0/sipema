<?php

namespace App\Http\Controllers;

use App\KampusGelombang;
use App\KampusTahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KampusGelombangController extends Controller
{
    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KampusGelombang::with('tahun_akademik')->whereKampus(Session::get('id_kampus'))->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $actionBtn = "<div class='d-flex gap-2'>
                                    <a href='" . route('kampus.gelombang.edit', ['kampus_gelombang' => $row->id]) . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <form action='" . route('kampus.gelombang.destroy', ['kampus_gelombang' => $row->id]) . "' method='post'>
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

        return view('kampus.gelombang.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.gelombang.create', ["tahun_akademiks" => KampusTahunAkademik::whereKampus(Session::get('id_kampus'))->get()]);
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
                'nama_gelombang',
                'tahun_akademik',
                'tanggal_mulai',
                'tanggal_akhir'
            ]),
            [
                'nama_gelombang' => ['required', 'unique:kampus_data_gelombang,nama_gelombang'],
                'tahun_akademik' => ['required'],
                'tanggal_mulai' => ['required', 'date'],
                'tanggal_akhir' => ['required', 'date'],
            ],
            [],
            [
                'nama_gelombang' => 'Nama Gelombang',
                'tahun_akademik' => 'Tahun Akademik',
                'tanggal_mulai' => 'Tanggal Mulai',
                'tanggal_akhir' => 'Tanggal Akhir',
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

        $kampusGelombang = new KampusGelombang();
        $kampusGelombang->id_kampus = Session::get('id_kampus');
        $kampusGelombang->id_tahun_akademik = $request->tahun_akademik;
        $kampusGelombang->nama_gelombang = $request->nama_gelombang;
        $kampusGelombang->tanggal_mulai = $request->tanggal_mulai;
        $kampusGelombang->tanggal_akhir = $request->tanggal_akhir;
        $kampusGelombang->save();

        return redirect(route('kampus.gelombang.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusGelombang  $kampusGelombang
     * @return \Illuminate\Http\Response
     */
    public function show(KampusGelombang $kampusGelombang)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusGelombang  $kampusGelombang
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusGelombang $kampusGelombang)
    {
        return view('kampus.gelombang.edit', [
            'gelombang' => $kampusGelombang,
            'tahun_akademiks' => KampusTahunAkademik::whereKampus(Session::get('id_kampus'))->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusGelombang  $kampusGelombang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusGelombang $kampusGelombang)
    {
        $validator = Validator::make(
            $request->only([
                'nama_gelombang',
                'tahun_akademik',
                'tanggal_mulai',
                'tanggal_akhir'
            ]),
            [
                'nama_gelombang' => ['required', 'unique:kampus_data_gelombang,nama_gelombang'],
                'tahun_akademik' => ['required'],
                'tanggal_mulai' => ['required', 'date'],
                'tanggal_akhir' => ['required', 'date'],
            ],
            [],
            [
                'nama_gelombang' => 'Nama Gelombang',
                'tahun_akademik' => 'Tahun Akademik',
                'tanggal_mulai' => 'Tanggal Mulai',
                'tanggal_akhir' => 'Tanggal Akhir',
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

        $kampusGelombang->id_kampus = Session::get('id_kampus');
        $kampusGelombang->id_tahun_akademik = $request->tahun_akademik;
        $kampusGelombang->nama_gelombang = $request->nama_gelombang;
        $kampusGelombang->tanggal_mulai = $request->tanggal_mulai;
        $kampusGelombang->tanggal_akhir = $request->tanggal_akhir;

        if (!$kampusGelombang->getDirty()) {
            return redirect()
                ->route('kampus.gelombang.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $kampusGelombang->save();

        return redirect(route('kampus.gelombang.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusGelombang  $kampusGelombang
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusGelombang $kampusGelombang)
    {
        if (!$kampusGelombang->delete()) {
            return redirect(route('kampus.gelombang.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.gelombang.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}
