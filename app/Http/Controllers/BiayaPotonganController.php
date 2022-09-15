<?php

namespace App\Http\Controllers;

use App\BiayaPotongan;
use App\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BiayaPotonganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $biaya_potongans = BiayaPotongan::with(['master_item'])->simplePaginate(5);
        // dd($biaya_potongans);
        return view('etc.biaya_potong.index', ['biaya_potongs' => $biaya_potongans]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = MasterItem::all();        
        return view('etc.biaya_potong.create', ['items' => $items]);
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
                'nama_beasiswa',
                'persentase_potongan',
                'id_item',
                'tanggal_berlaku',
                'tanggal_berakhir'
            ]),
            [
                'nama_beasiswa' => ['required'],
                'persentase_potongan' => ['required'],
                'id_item' => ['required'],
                'tanggal_berlaku' => ['required', 'date'],
                'tanggal_berakhir' => ['required', 'date','after_or_equal:start_date'],
            ],
            [],
            [
                'nama_beasiswa' => 'Nama Beasiswa',
                'persentase_potongan' => 'Persentase Potongan',
                'id_item' => 'Item',
                'tanggal_berlaku' => 'Tanggal Berlaku',
                'tanggal_berakhir' => 'Tanggal Berakhir',
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

        $biayaPotongan = new BiayaPotongan();
        $biayaPotongan->nama_beasiswa = $request->nama_beasiswa;
        $biayaPotongan->persentase_potongan = ($request->persentase_potongan)/100;
        $biayaPotongan->id_item = $request->id_item;
        $biayaPotongan->tanggal_berlaku = $request->tanggal_berlaku;
        $biayaPotongan->tanggal_berakhir = $request->tanggal_berakhir;
        $biayaPotongan->save();

        return redirect(route('biaya-potongan.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BiayaPotongan  $biayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function show(BiayaPotongan $biayaPotongan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BiayaPotongan  $biayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function edit(BiayaPotongan $biayaPotongan)
    {
        $items = MasterItem::all();        
        return view('etc.biaya_potong.edit', ['items' => $items,'biaya_potong'=>$biayaPotongan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BiayaPotongan  $biayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BiayaPotongan $biayaPotongan)
    {
        $validator = Validator::make(
            $request->only([
                'nama_beasiswa',
                'persentase_potongan',
                'id_item',
                'tanggal_berlaku',
                'tanggal_berakhir'
            ]),
            [
                'nama_beasiswa' => ['required'],
                'persentase_potongan' => ['required'],
                'id_item' => ['required'],
                'tanggal_berlaku' => ['required', 'date'],
                'tanggal_berakhir' => ['required', 'date','after_or_equal:start_date'],
            ],
            [],
            [
                'nama_beasiswa' => 'Nama Beasiswa',
                'persentase_potongan' => 'Persentase Potongan',
                'id_item' => 'Item',
                'tanggal_berlaku' => 'Tanggal Berlaku',
                'tanggal_berakhir' => 'Tanggal Berakhir',
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

        $biayaPotongan->nama_beasiswa = $request->nama_beasiswa;
        $biayaPotongan->persentase_potongan = ($request->persentase_potongan)/100;
        $biayaPotongan->id_item = $request->id_item;
        $biayaPotongan->tanggal_berlaku = $request->tanggal_berlaku;
        $biayaPotongan->tanggal_berakhir = $request->tanggal_berakhir;
        $biayaPotongan->save();

        return redirect(route('biaya-potongan.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BiayaPotongan  $biayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function destroy(BiayaPotongan $biayaPotongan)
    {
        if (!$biayaPotongan->delete()) {
            return redirect(route('biaya-potongan.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('biaya-potongan.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}
