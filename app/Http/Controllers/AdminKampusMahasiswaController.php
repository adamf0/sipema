<?php

namespace App\Http\Controllers;

use App\KampusMahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KampusItemBayar;
use App\KampusMou;
use App\KampusProdi;
use App\MasterKampus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminKampusMahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterKampus $kampus)
    {
        $mahasiswas = KampusMahasiswa::whereHas('prodi', function ($query) use ($kampus) {
            $query->whereKampus($kampus->id);
        })->simplePaginate(5);

        return view('detail-kampus.mahasiswa.index', [
            'kampus' => $kampus,
            'mahasiswas' => $mahasiswas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(MasterKampus $kampus)
    {
        $prodis = KampusProdi::all(['id', 'kode_prodi', 'nama']);
        $itemBayars = KampusItemBayar::with(['gelombang', 'item'])
            ->whereKampus($kampus->id)
            ->where('status', 1)
            ->get()
            ->filter(function ($item) {
                if (Carbon::parse(date('Y-m-d'))->between($item->gelombang->tanggal_mulai, $item->gelombang->tanggal_akhir)) {
                    return $item;
                }
            })
            ->values()
            ->groupBy(['id_item'])
            ->values();

        return view('detail-kampus.mahasiswa.create', [
            'kampus' => $kampus,
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
    public function store(MasterKampus $kampus, Request $request)
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
                'nim' => ['nullable', 'numeric'],
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

        $mou = KampusMou::whereKampus($kampus->id)
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

        return redirect()
            ->route('detail-kampus.mahasiswa.index', ['kampus' => $kampus])
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusMahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function show(KampusMahasiswa $mahasiswa)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusMahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterKampus $kampus, KampusMahasiswa $mahasiswa)
    {
        $mahasiswa->item_bayar_selected = array_map('intval', json_decode($mahasiswa->item_bayar_selected));
        $prodis = KampusProdi::all(['id', 'kode_prodi', 'nama']);

        $itemBayars = KampusItemBayar::with(['gelombang', 'item'])
            ->whereKampus($kampus->id)
            ->where('status', 1)
            ->get()
            ->filter(function ($item) {
                if (Carbon::parse(date('Y-m-d'))->between($item->gelombang->tanggal_mulai, $item->gelombang->tanggal_akhir)) {
                    return $item;
                }
            })
            ->values()
            ->groupBy(['id_item'])
            ->values();
        // dd($itemBayars);

        return view('detail-kampus.mahasiswa.edit', [
            'kampus' => $kampus,
            'prodis' => $prodis,
            'mahasiswa' => $mahasiswa,
            'itemBayars' => $itemBayars
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusMahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function update(MasterKampus $kampus, Request $request, KampusMahasiswa $mahasiswa)
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

        if ($request->nim) {
            $mahasiswa->nim_sementara = null;
            $mahasiswa->nim = $request->nim;
        }

        if (!$request->nim) {
            $mahasiswa->nim = null;
            $mahasiswa->nim_sementara = sprintf("%06d", mt_rand(1, 999999999999));

            $mahasiswaCount = KampusMahasiswa::where('nim_sementara', $mahasiswa->nim_sementara)->count();
            while ($mahasiswaCount >= 1) {
                $mahasiswa->nim_sementara = sprintf("%06d", mt_rand(1, 999999999999));

                $mahasiswaCount = KampusMahasiswa::where('nim_sementara', $mahasiswa->nim_sementara)->count();
            }
        }

        $mahasiswa->nama_lengkap = $request->nama_lengkap;
        $mahasiswa->tanggal_lahir = $request->tanggal_lahir;
        $mahasiswa->jenis_kelamin = $request->jenis_kelamin;
        if ($request->has('prodi')) {
            $mahasiswa->id_prodi = $request->prodi;
            $id_prodi = $request->prodi;
        } else {
            $id_prodi = $mahasiswa->id_prodi;
        }
        if ($request->has('tanggal_pembayaran')) {
            $mahasiswa->tanggal_pembayaran = $request->tanggal_pembayaran;
            $tanggal_pembayaran = $request->tanggal_pembayaran;
        } else {
            $tanggal_pembayaran = $mahasiswa->tanggal_pembayaran;
        }
        if ($request->has('item_bayar_selected')) {
            $mahasiswa->item_bayar_selected = json_encode($request->item_bayar_selected);
            $_item_bayar_selected = $request->item_bayar_selected;
        } else {
            $_item_bayar_selected = json_decode($mahasiswa->item_bayar_selected);
        }

        if (!$mahasiswa->getDirty()) {
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

        DB::transaction(function () use (&$request, &$mahasiswa, &$id_prodi, &$tanggal_pembayaran, &$d1, &$_item_bayar_selected, &$data_kampus_rencana_mahasiswa) {
            $mahasiswa->save();
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
            //     $mahasiswa,
            //     $id_prodi,
            //     $tanggal_pembayaran,
            //     $d1,
            //     $_item_bayar_selected,
            //     $data_kampus_rencana_mahasiswa
            // );
        });

        return redirect()
            ->route('detail-kampus.mahasiswa.index', [
                'kampus' => $kampus->id
            ])
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusMahasiswa  $mahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterKampus $kampus, KampusMahasiswa $mahasiswa)
    {
        if (!$mahasiswa->delete()) {
            return redirect()
                ->route('detail-kampus.mahasiswa.index', [
                    'kampus' => $kampus->id
                ])
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect()
            ->route('detail-kampus.mahasiswa.index', [
                'kampus' => $kampus->id
            ])
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}