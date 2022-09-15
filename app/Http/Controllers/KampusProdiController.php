<?php

namespace App\Http\Controllers;

use App\KampusProdi;
use App\MasterKampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class KampusProdiController extends Controller
{
    public $id_kampus = null;
    public function __construct()
    {
        // $this->id_kampus = Session::get('id_kampus');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        // $user->load('user_kampus');
        
        $prodis = KampusProdi::whereKampus(Session::get('id_kampus'))->simplePaginate(5);

        return view('kampus.prodi.index', ['prodis' => $prodis]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.prodi.create');
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
        $kampusProdi->jenjang = $request->jenjang;
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
            'prodi' => $kampusProdi
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
        $kampusProdi->jenjang = $request->jenjang;
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