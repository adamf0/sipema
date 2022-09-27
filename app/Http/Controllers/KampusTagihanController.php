<?php

namespace App\Http\Controllers;

use App\KampusProdi;
use App\KampusTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KampusTagihanController extends Controller
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
        if ($request) {
            $validator = Validator::make(
                $request->only('tanggal', 'mahasiswa', 'prodi', 'status'),
                [
                    'tanggal' => [
                        'sometimes',
                        'date',
                        'date_format:Y-m-d',
                        'before_or_equal:now'
                    ],
                    'mahasiswa' => ['sometimes', 'string'],
                    'prodi' => [
                        'sometimes',
                        // 'exists:kampus_prodi,id'
                        // Bug
                        Rule::exists('kampus_prodi', 'id')->where(function ($query) use ($request) {
                            return $query->where('id_kampus', Session::get('id_kampus'));
                        }),
                    ],
                    'status' => ['sometimes', 'in:0,1'],
                ],
                [
                    'tanggal.date' => ':attribute yang di inputkan harus berisi tanggal yang valid.',
                    'tanggal.date_format' => ':attribute tidak cocok dengan format yang telah ditentukan.',
                    'tanggal.before_or_equal' => ':attribute harus berisi tanggal sebelum atau sama dengan tanggal sekarang.'
                ],
                [
                    'tanggal' => 'Tanggal',
                    'mahasiswa' => 'Nama Mahasiswa',
                    'prodi' => 'Prodi',
                    'status' => 'Tanggal'
                ]
            );

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->with('flash_message', (object)[
                        'type' => 'danger',
                        'title' => 'Terjadi Kesalahan',
                        'message' => 'Silahkan cek kembali Filter yang tersedia'
                    ])
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $kampusTagihans = KampusTagihan::whereHas('mahasiswa', function ($q) use ($request) {
            if ($request->has('mahasiswa')) $q->where('nama_lengkap', 'like', "%$request->mahasiswa%");
            if ($request->has('prodi')) $q->where('id_prodi', $request->prodi);

            return $q;
        })
            ->whereHas('mahasiswa.prodi', function ($q) {
                return $q->whereKampus(Session::get('id_kampus'));
            })
            ->whereHas('tagihan_detail.rencana.item_bayar.item');

        if ($request->has('tanggal')) $kampusTagihans->where('tanggal', $request->tanggal);
        if ($request->has('status')) $kampusTagihans->where('status', $request->status);

        $kampusTagihans = $kampusTagihans->simplePaginate(5);

        // dd($kampusTagihans);
        return view('kampus.tagihan.index', [
            'kampusTagihans' => $kampusTagihans,
            'prodis' => KampusProdi::whereKampus(Session::get('id_kampus'))->get(),
        ])->with($request->all());
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
     * @param  \App\KampusTagihan  $kampusTagihan
     * @return \Illuminate\Http\Response
     */
    public function show(KampusTagihan $kampusTagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusTagihan  $kampusTagihan
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusTagihan $kampusTagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusTagihan  $kampusTagihan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusTagihan $kampusTagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusTagihan  $kampusTagihan
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusTagihan $kampusTagihan)
    {
        //
    }
}