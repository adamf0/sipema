<?php

namespace App\Http\Controllers;

use App\KampusProdi;
use App\MasterJenjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KampusProdiController extends Controller
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
            $data = KampusProdi::with('jenjang')->whereKampus(Session::get('id_kampus'))->where('id', '!=', 1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $actionBtn = "<div class='d-flex gap-2'>
                                    <a href='" . route('kampus.kelas.edit', ['kampus_kelas' => $row->id]) . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <form action='" . route('kampus.kelas.destroy', ['kampus_kelas' => $row->id]) . "' method='post'>
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

        return view('kampus.prodi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.prodi.create', ["jenjangs" => MasterJenjang::all()]);
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

        $kampusProdi = new KampusProdi();
        $kampusProdi->id_kampus = Session::get('id_kampus');
        $kampusProdi->kode_prodi = $request->kode_prodi;
        $kampusProdi->nama = $request->nama;
        $kampusProdi->id_jenjang = $request->jenjang;
        $kampusProdi->masa_studi = $request->masa_studi;
        $kampusProdi->save();

        return redirect(route('kampus.prodi.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusProdi  $kampusProdi
     * @return \Illuminate\Http\Response
     */
    public function show(KampusProdi $kampusProdi)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusProdi  $kampusProdi
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusProdi $kampusProdi)
    {
        return view('kampus.prodi.edit', [
            'prodi' => $kampusProdi,
            'jenjangs' => MasterJenjang::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusProdi  $kampusProdi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusProdi $kampusProdi)
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

        $kampusProdi->id_kampus = Session::get('id_kampus');
        $kampusProdi->kode_prodi = $request->kode_prodi;
        $kampusProdi->nama = $request->nama;
        $kampusProdi->id_jenjang = $request->jenjang;
        $kampusProdi->masa_studi = $request->masa_studi;

        if (!$kampusProdi->getDirty()) {
            return redirect()
                ->route('kampus.prodi.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $kampusProdi->save();

        return redirect(route('kampus.prodi.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusProdi  $kampusProdi
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusProdi $kampusProdi)
    {
        if (!$kampusProdi->delete()) {
            return redirect(route('kampus.prodi.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.prodi.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}
