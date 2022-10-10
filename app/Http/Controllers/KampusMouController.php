<?php

namespace App\Http\Controllers;

use App\KampusMou;
use App\MasterKampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KampusMou::whereKampus(Session::get('id_kampus'))->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $actionBtn = "<div class='d-flex gap-2'>
                                    <a href='" . route('kampus.mou.edit', ['kampus_mou' => $row->id]) . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <form action='" . route('kampus.mou.destroy', ['kampus_mou' => $row->id]) . "' method='post'>
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
        return view('kampus.mou.index');
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
                'sharing_fee'
            ]),
            [
                'no_mou' => ['required'],
                'sharing_fee' => ['required', 'min:0.01'],
            ],
            [],
            [
                'no_mou' => 'No. MOU',
                'sharing_fee' => 'Sharing Fee',
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
        $kampusMou->sharing_fee = $request->sharing_fee;
        $kampusMou->tanggal_dibuat = now()->format('Y-m-d');
        $kampusMou->status = 0;
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
                'sharing_fee'
            ]),
            [
                'no_mou' => ['required'],
                'sharing_fee' => ['required', 'min:0.01'],
            ],
            [],
            [
                'no_mou' => 'No. MOU',
                'sharing_fee' => 'Sharing Fee',
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
        $kampusMou->sharing_fee = $request->sharing_fee;
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

    public function change($id)
    {
        $kampusMou = KampusMou::findOrFail($id);
        $kampusMou->status = 1;

        if (KampusMou::where('id', '!=', $kampusMou->id)->update(["status" => 0]) && $kampusMou->save()) {
            return redirect(route('kampus.mou.index'))
                ->with('flash_message', (object)[
                    'type' => 'success',
                    'title' => 'Sukses',
                    'message' => 'Berhasil Merubah Status'
                ]);
        } else {
            return redirect(route('kampus.mou.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Danger',
                    'message' => 'Gagal Merubah Status'
                ]);
        }
    }
}
