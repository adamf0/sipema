<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KampusGelombang;
use App\KampusItemBayar;
use App\KampusMahasiswa;
use App\KampusMou;
use App\KampusProdi;
use App\KampusTagihan;
use App\MasterKampus;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DetailKampusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MasterKampus $kampus, Request $request)
    {
        if ($request->filter_tanggal) {
            $validator = Validator::make(
                $request->only('filter_tanggal'),
                [
                    'filter_tanggal' => [
                        'date',
                        'date_format:Y-m-d',
                        'before_or_equal:now'
                    ]
                ],
                [
                    'filter_tanggal.date' => ':attribute yang di inputkan harus berisi tanggal yang valid.',
                    'filter_tanggal.date_format' => ':attribute tidak cocok dengan format yang telah ditentukan.',
                    'filter_tanggal.before_or_equal' => ':attribute harus berisi tanggal sebelum atau sama dengan tanggal sekarang.'
                ],
                [
                    'filter_tanggal' => 'Tanggal'
                ]
            );

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->with('flash_message', (object)[
                        'type' => 'danger',
                        'title' => 'Terjadi Kesalahan',
                        'message' => 'Silahkan cek kembali Filter Tanggal'
                    ])
                    ->withErrors($validator)
                    ->withInput();
            }

            $filter_tanggal = $request->filter_tanggal;
        } else {
            $filter_tanggal = now()->format('Y-m-d');
        }

        $menunggu = KampusTagihan::with(['mahasiswa', 'tagihan_detail'])
            ->whereHas('mahasiswa.prodi', function ($query) use ($kampus) {
                $query->whereKampus($kampus->id);
            })
            ->where('tanggal', $filter_tanggal)
            ->waiting()
            ->get()
            ->each(function ($item, $index) {
                $item->total = 0;
                $item->tagihan_detail->each(function ($td, $index_) use ($item) {
                    $item->total += $td->biaya;
                });
            });
        $selesai = KampusTagihan::with(['mahasiswa', 'tagihan_detail'])
            ->whereHas('mahasiswa.prodi', function ($query) use ($kampus) {
                $query->whereKampus($kampus->id);
            })
            ->where('tanggal', $filter_tanggal)
            ->settlement()
            ->get()
            ->each(function ($item, $index) {
                $item->total = 0;
                $item->tagihan_detail->each(function ($td, $index_) use ($item) {
                    $item->total += $td->biaya;
                });
            });

        $jadwal_ulang = KampusTagihan::with(['mahasiswa', 'tagihan_detail'])
            ->whereHas('mahasiswa.prodi', function ($query) use ($kampus) {
                $query->whereKampus($kampus->id);
            })
            ->where('tanggal', $filter_tanggal)
            ->reschedule()
            ->get()
            ->each(function ($item, $index) {
                $item->total = 0;
                $item->tagihan_detail->each(function ($td, $index_) use ($item) {
                    $item->total += $td->biaya;
                });
            });

        $belum_selesai = KampusTagihan::with(['mahasiswa', 'tagihan_detail'])
            ->whereHas('mahasiswa.prodi', function ($query) use ($kampus) {
                $query->whereKampus($kampus->id);
            })
            ->where('tanggal', $filter_tanggal)
            ->waiting()
            ->get()
            ->each(function ($item, $index) {
                $item->total = 0;
                $item->tagihan_detail->each(function ($td, $index_) use ($item) {
                    $item->total += $td->biaya;
                });
            });

        $data = [
            "filter_tanggal" => $filter_tanggal,
            "kampus" => $kampus,
            "mou" => KampusMou::whereKampus($kampus->id)->orderBy('tanggal_dibuat', 'DESC')->first(),
            "prodi" => KampusProdi::whereKampus($kampus->id)->get(),
            "gelombang" => KampusGelombang::whereKampus($kampus->id)->get(),
            "item_bayar" => KampusItemBayar::whereKampus($kampus->id)->get(),
            "mahasiswa" => KampusMahasiswa::whereHas('prodi', function ($query) use ($kampus) {
                $query->whereKampus($kampus->id);
            })->get(),
            "tagihan" => (object) [
                "menunggu" => $menunggu,
                "selesai" => $selesai,
                "jadwal_ulang" => $jadwal_ulang,
                "belum_selesai" => $belum_selesai,
            ]
        ];

        return view('detail-kampus.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}