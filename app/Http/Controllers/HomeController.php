<?php

namespace App\Http\Controllers;

use App\KampusGelombang;
use App\KampusItemBayar;
use App\KampusMahasiswa;
use App\KampusMou;
use App\KampusProdi;
use App\KampusTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $data = [];
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

        if (Auth::user()->hasRole('User')) {
            $menunggu = KampusTagihan::with(['mahasiswa', 'tagihan_detail'])
                ->whereHas('mahasiswa.prodi', function ($query) {
                    $query->whereKampus(Session::get('id_kampus'));
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
                ->whereHas('mahasiswa.prodi', function ($query) {
                    $query->whereKampus(Session::get('id_kampus'));
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
                ->whereHas('mahasiswa.prodi', function ($query) {
                    $query->whereKampus(Session::get('id_kampus'));
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
                ->whereHas('mahasiswa.prodi', function ($query) {
                    $query->whereKampus(Session::get('id_kampus'));
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
                "mou" => KampusMou::whereKampus(Session::get('id_kampus'))->orderBy('tanggal_dibuat', 'DESC')->first(),
                "prodi" => KampusProdi::whereKampus(Session::get('id_kampus'))->get(),
                "gelombang" => KampusGelombang::whereKampus(Session::get('id_kampus'))->get(),
                "item_bayar" => KampusItemBayar::whereKampus(Session::get('id_kampus'))->get(),
                "mahasiswa" => KampusMahasiswa::whereHas('prodi', function ($query) {
                    $query->whereKampus(Session::get('id_kampus'));
                })->get(),
                "tagihan" => (object) [
                    "menunggu" => $menunggu,
                    "selesai" => $selesai,
                    "jadwal_ulang" => $jadwal_ulang,
                    "belum_selesai" => $belum_selesai,
                ]
            ];
        }

        return view('home', $data);
    }
}