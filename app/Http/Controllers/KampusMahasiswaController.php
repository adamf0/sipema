<?php

namespace App\Http\Controllers;

use App\KampusItemBayar;
use App\KampusMahasiswa;
use App\KampusMou;
use App\KampusProdi;
use App\KampusRencanaMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;

class KampusMahasiswaController extends Controller
{
    public $id_kampus = null;
    public function __construct()
    {
        // Auth::user()->id_kampus = auth()->user()->id_kampus;
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

        $mahasiswas = KampusMahasiswa::whereHas('prodi',function($query) use(&$user){
            $query->whereKampus(Session::get('id_kampus'));
        })->simplePaginate(5);

        return view('kampus.mahasiswa.index', ['mahasiswas' => $mahasiswas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $prodis = KampusProdi::all(['id', 'kode_prodi', 'nama']);
        $user = Auth::user();
        $user->load('user_kampus');
        
        $itemBayars = KampusItemBayar::with(['gelombang', 'item'])
            ->whereKampus(Session::get('id_kampus'))
            ->where('status', 1)
            ->get()
            ->filter(function ($item) {
                if (\Carbon\Carbon::parse(date('Y-m-d'))->between($item->gelombang->tanggal_mulai, $item->gelombang->tanggal_akhir)) {
                    return $item;
                }
            })
            ->values()
            ->groupBy(['id_item'])
            ->values();
        // dd($itemBayars);

        return view('kampus.mahasiswa.create', [
            'prodis' => $prodis,
            'itemBayars' => $itemBayars
        ]);
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
                'nim',
                'nama_lengkap',
                'tanggal_lahir',
                'jenis_kelamin',
                'prodi',
                'tanggal_pembayaran',
                'item_bayar_selected',
            ]),
            [
                'nim' => ['nullable', 'numeric', 'digits_between:9,10'],
                'nama_lengkap' => ['required'],
                'tanggal_lahir' => ['required', 'date'],
                'jenis_kelamin' => ['required', 'integer', Rule::in([1, 2])],
                'prodi' => ['required', 'exists:kampus_prodi,id'],
                'tanggal_pembayaran' => ['required', 'date'],
                'item_bayar_selected.*' => ['required'],
            ],
            [],
            [
                'nim' => 'NIM',
                'nama_lengkap' => 'Nama Lengkap',
                'tanggal_lahir' => 'Tanggal Lahir',
                'jenis_kelamin' => 'Jenis Kelamin',
                'prodi' => 'Prodi',
                'tanggal_pembayaran' => 'Tanggal Pembayaran',
                'item_bayar_selected.*' => 'Item Bayar' 
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

        $mahasiswa = new KampusMahasiswa();

        if ($request->nim) {
            $mahasiswa->nim = $request->nim;
        } else {
            $mahasiswa->nim_sementara = sprintf("%06d", mt_rand(1, 999999999999));

            $mahasiswaCount = KampusMahasiswa::where('nim_sementara', $mahasiswa->nim_sementara)->count();
            while ($mahasiswaCount >= 1) {
                $mahasiswa->nim_sementara = sprintf("%06d", mt_rand(1, 999999999999));

                $mahasiswaCount = KampusMahasiswa::where('nim_sementara', $mahasiswa->nim_sementara)->count();
            }
        }

        $data_kampus_rencana_mahasiswa = [];
        $user = Auth::user();
        $user->load('user_kampus');

        $mou = KampusMou::whereKampus(Session::get('id_kampus'))
            ->orderByDesc('tanggal_dibuat')
            ->first();

        $mahasiswa->nama_lengkap = $request->nama_lengkap;
        $mahasiswa->tanggal_lahir = $request->tanggal_lahir;
        $mahasiswa->jenis_kelamin = $request->jenis_kelamin;
        $mahasiswa->id_prodi = $request->prodi;
        $mahasiswa->tanggal_pembayaran = $request->tanggal_pembayaran;
        $mahasiswa->id_mou = $mou->id;
        $mahasiswa->item_bayar_selected = json_encode($request->item_bayar_selected);

        DB::transaction(function () use ($request, &$mahasiswa, &$data_kampus_rencana_mahasiswa) {
            $mahasiswa->save();
            $data_item_bayar_selected = collect($request->item_bayar_selected);

            foreach ($data_item_bayar_selected as $item_bayar_selected) {
                $kampus_item_bayar = KampusItemBayar::findOrFail($item_bayar_selected);
                $data_template_angsuran = collect(
                    json_decode($kampus_item_bayar->template_angsuran)
                );

                $prodi = KampusProdi::findOrFail($request->prodi);
                $banyak_bulan = 12 * $prodi->masa_studi;
                $banyak_semester = $banyak_bulan / 6;
                $tanggal = date('Y-m-d', strtotime($request->tanggal_pembayaran));

                for ($j = 0; $j < $banyak_semester; $j++) {
                    foreach ($data_template_angsuran as $template_angsuran) {
                        array_push($data_kampus_rencana_mahasiswa, [
                            "id_mahasiswa" => $mahasiswa->id,
                            "id_item_bayar" => $item_bayar_selected,
                            "id_biaya_potongan" => null,
                            "nama" => $template_angsuran->nama,
                            "biaya" => $template_angsuran->nominal,
                            "tanggal_bayar" => $tanggal,
                            "status" => 0,
                            "isDelete" => 0,
                            // "semester"=>$this->getSemester($j),
                        ]);
                        $tanggal = date('Y-m-d', strtotime("+1 month", strtotime($tanggal)));
                    }
                }
            }

            DB::table('kampus_rencana_mahasiswa')->insert($data_kampus_rencana_mahasiswa);
            $data_kampus_rencana_mahasiswa = collect($data_kampus_rencana_mahasiswa);
            // dd($data_kampus_rencana_mahasiswa,$mahasiswa);
        });

        return redirect(route('kampus.mahasiswa.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusMahasiswa  $kampusMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(KampusMahasiswa $kampusMahasiswa)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusMahasiswa  $kampusMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusMahasiswa $kampusMahasiswa)
    {
        $kampusMahasiswa->item_bayar_selected = array_map('intval',json_decode($kampusMahasiswa->item_bayar_selected));
        $prodis = KampusProdi::all(['id', 'kode_prodi', 'nama']);
        
        $user = Auth::user();
        $user->load('user_kampus');

        $itemBayars = KampusItemBayar::with(['gelombang', 'item'])
            ->whereKampus(Session::get('id_kampus'))
            ->where('status', 1)
            ->get()
            ->filter(function ($item) {
                if (\Carbon\Carbon::parse(date('Y-m-d'))->between($item->gelombang->tanggal_mulai, $item->gelombang->tanggal_akhir)) {
                    return $item;
                }
            })
            ->values()
            ->groupBy(['id_item'])
            ->values();
        // dd($itemBayars);

        return view('kampus.mahasiswa.edit', [
            'prodis' => $prodis,
            'mahasiswa' => $kampusMahasiswa,
            'itemBayars' => $itemBayars
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusMahasiswa  $kampusMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusMahasiswa $kampusMahasiswa)
    {
        $mahasiswa = KampusMahasiswa::findOrFail($kampusMahasiswa->id);

        $validator = Validator::make(
            $request->only([
                'nim',
                'nama_lengkap',
                'tanggal_lahir',
                'jenis_kelamin',
                'prodi',
                'tanggal_pembayaran',
                'item_bayar_selected',
            ]),
            [
                'nim' => ['nullable', 'numeric', 'digits_between:9,10'],
                'nama_lengkap' => ['required'],
                'tanggal_lahir' => ['required', 'date'],
                'jenis_kelamin' => ['required', 'integer', Rule::in([1, 2])],
                'prodi' => ['required', 'exists:kampus_prodi,id'],
                'tanggal_pembayaran' => ['required', 'date'],
                'item_bayar_selected.*' => ['required'],
            ],
            [],
            [
                'nim' => 'NIM',
                'nama_lengkap' => 'Nama Lengkap',
                'tanggal_lahir' => 'Tanggal Lahir',
                'jenis_kelamin' => 'Jenis Kelamin',
                'prodi' => 'Prodi',
                'tanggal_pembayaran' => 'Tanggal Pembayaran',
                'item_bayar_selected.*' => 'Item Bayar' 
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

        if ($request->nim) {
            $kampusMahasiswa->nim_sementara = null;
            $kampusMahasiswa->nim = $request->nim;
        }

        if (!$request->nim) {
            $kampusMahasiswa->nim = null;
            $kampusMahasiswa->nim_sementara = sprintf("%06d", mt_rand(1, 999999999999));

            $kampusMahasiswaCount = KampusMahasiswa::where('nim_sementara', $kampusMahasiswa->nim_sementara)->count();
            while ($kampusMahasiswaCount >= 1) {
                $kampusMahasiswa->nim_sementara = sprintf("%06d", mt_rand(1, 999999999999));

                $kampusMahasiswaCount = KampusMahasiswa::where('nim_sementara', $kampusMahasiswa->nim_sementara)->count();
            }
        }

        $kampusMahasiswa->nama_lengkap = $request->nama_lengkap;
        $kampusMahasiswa->tanggal_lahir = $request->tanggal_lahir;
        $kampusMahasiswa->jenis_kelamin = $request->jenis_kelamin;
        if ($request->has('prodi')) {
            $kampusMahasiswa->id_prodi = $request->prodi;
            $id_prodi = $request->prodi;
        } else {
            $id_prodi = $mahasiswa->id_prodi;
        }
        if ($request->has('tanggal_pembayaran')) {
            $kampusMahasiswa->tanggal_pembayaran = $request->tanggal_pembayaran;
            $tanggal_pembayaran = $request->tanggal_pembayaran;
        } else {
            $tanggal_pembayaran = $mahasiswa->tanggal_pembayaran;
        }
        if ($request->has('item_bayar_selected')) {
            $kampusMahasiswa->item_bayar_selected = json_encode($request->item_bayar_selected);
            $_item_bayar_selected = $request->item_bayar_selected;
        } else {
            $_item_bayar_selected = json_decode($mahasiswa->item_bayar_selected);
        }

        if (!$kampusMahasiswa->getDirty()) {
            return redirect()
                ->route('kampus.mahasiswa.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $data_kampus_rencana_mahasiswa = [];
        $d1 = array_diff($_item_bayar_selected, json_decode($mahasiswa->item_bayar_selected));

        DB::transaction(function () use (&$request, &$kampusMahasiswa, &$mahasiswa, &$id_prodi, &$tanggal_pembayaran, &$d1, &$_item_bayar_selected, &$data_kampus_rencana_mahasiswa) {
            $kampusMahasiswa->save();
            $data_item_bayar_selected = collect($request->item_bayar_selected);

            if ($id_prodi != $mahasiswa->id_prodi || $tanggal_pembayaran != $mahasiswa->tanggal_pembayaran || count($d1) > 0) {
                DB::table('kampus_rencana_mahasiswa')->where('id_mahasiswa', $mahasiswa->id)->delete();

                $data_item_bayar_selected = collect($_item_bayar_selected);
                foreach ($data_item_bayar_selected as $item_bayar_selected) {
                    $kampus_item_bayar = DB::table('kampus_item_bayar')
                        ->where('id', $item_bayar_selected)
                        ->first();

                    $data_template_angsuran = collect(
                        json_decode($kampus_item_bayar->template_angsuran)
                    );

                    $prodi = DB::table('kampus_prodi')->where('id', $id_prodi)->first();
                    $banyak_bulan = 12 * $prodi->masa_studi;
                    $banyak_semester = $banyak_bulan / 6;
                    $tanggal = date('Y-m-d', strtotime($tanggal_pembayaran));

                    for ($j = 0; $j < $banyak_semester; $j++) {
                        foreach ($data_template_angsuran as $template_angsuran) {
                            array_push($data_kampus_rencana_mahasiswa, [
                                "id_mahasiswa" => $mahasiswa->id,
                                "id_item_bayar" => $item_bayar_selected,
                                "id_biaya_potongan" => null,
                                "nama" => $template_angsuran->nama,
                                "biaya" => $template_angsuran->nominal,
                                "tanggal_bayar" => $tanggal,
                                "status" => 0,
                                "isDelete" => 0,
                            ]);
                            $tanggal = date('Y-m-d', strtotime("+1 month", strtotime($tanggal)));
                        }
                    }
                }
                DB::table('kampus_rencana_mahasiswa')->insert($data_kampus_rencana_mahasiswa);
            }
            // dd(
            //     $kampusMahasiswa,
            //     $mahasiswa,
            //     $id_prodi,
            //     $tanggal_pembayaran,
            //     $d1,
            //     $_item_bayar_selected,
            //     $data_kampus_rencana_mahasiswa
            // );
        });

        return redirect(route('kampus.mahasiswa.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusMahasiswa  $kampusMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusMahasiswa $kampusMahasiswa)
    {
        if (!$kampusMahasiswa->delete()) {
            return redirect(route('kampus.mahasiswa.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.mahasiswa.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}