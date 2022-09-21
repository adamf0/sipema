<?php

namespace App\Http\Controllers;

use App\KampusJadwalUlang;
use App\KampusMahasiswa;
use App\KampusMou;
use App\KampusTagihan;
use App\KampusTagihanDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KampusJadwalUlangTagihan extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $data = KampusJadwalUlang::with(['mahasiswa','mahasiswa.prodi'])
                    ->whereHas('mahasiswa.prodi.kampus',function($q){
                        return $q->where('id',Session::get('id_kampus'));
                    })
                    ->get()
                    ->each(function($item,$index){
                        $item->tagihan = KampusTagihan::whereIn('id',json_decode($item->item_tagihan_selected))->get();
                    });
        
        return view('kampus.jadwal_ulang.index',[
            "jadwal_ulangs"=>$data
        ]);
    }

    public function create()
    {
        $mahasiswas = KampusMahasiswa::with(['prodi'])->whereHas('prodi.kampus',function($q){
            return $q->where('id',Session::get('id_kampus'));
        })->get();
        $data_tagihan = KampusTagihan::with('tagihan_detail','tagihan_detail.rencana.item_bayar.item')->where('id_mahasiswa',76)->get();
        return view('kampus.jadwal_ulang.create',["mahasiswas"=>$mahasiswas,'tagihans'=>$data_tagihan]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->only([
                'id_mahasiswa',
                'dokumen',
                'tanggal_diundur',
            ]),
            [
                'id_mahasiswa' => ['required'],
                'dokumen' => ['required', 'mimes:jpeg,png,jpg', 'max:2048'],
                'tanggal_diundur' => ['required'],
            ],
            [],
            [
                'id_mahasiswa' => 'Mahasiswa',
                'dokumen' => 'Dokumen',
                'tanggal_diundur' => 'Tanggal Diundur',
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
        // dd($request->all());

        $image_file = $request->file('dokumen');
        $image_name = 'sipema' . '-' . date('dmY') . '-' . time() . '.' . $image_file->getClientOriginalExtension();
        $uploadImage = $image_file->storePubliclyAs('images/master/dokumen', $image_name, 'public');
        // dd($uploadImage);

        if (!Storage::disk('public')->exists('/' . $uploadImage)) {
            return redirect()
                ->back()
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan Saat Upload Logo',
                    'message' => 'Silahkan Upload kembali Logo'
                ])
                ->withErrors($validator)
                ->withInput();
        }

        $cek_data = KampusJadwalUlang::where('id_mahasiswa',$request->id_mahasiswa)->count();
        $mou = KampusMou::where('id_kampus',Session::get('id_kampus'))->orderByDesc('tanggal_dibuat')->first();
        if($cek_data>=$mou->max_reschedule){
            return redirect(route('kampus.jadwal_ulang.index'))
                    ->with('flash_message', (object)[
                        'type' => 'danger',
                        'title' => 'Danger',
                        'message' => 'Gagal Menambah Data'
                    ]);
        }

        try {
            $jadwal_ulang = new KampusJadwalUlang();
            $jadwal_ulang->id_mahasiswa = $request->id_mahasiswa;
            $jadwal_ulang->dokumen = $image_name;
            $jadwal_ulang->item_tagihan_selected = json_encode($request->id_tagihan);
            $jadwal_ulang->tanggal_diundur = $request->tanggal_diundur;
            $jadwal_ulang->status = 1;

            if($jadwal_ulang->save() && KampusTagihan::whereIn('id',$request->id_tagihan)->update(['status'=>'-1'])){
                return redirect(route('kampus.jadwal_ulang.index'))
                    ->with('flash_message', (object)[
                        'type' => 'success',
                        'title' => 'Sukses',
                        'message' => 'Berhasil Menambah Data'
                    ]);
            }
            else{ 
                return redirect(route('kampus.jadwal_ulang.index'))
                            ->with('flash_message', (object)[
                                'type' => 'danger',
                                'title' => 'Danger',
                                'message' => 'Gagal Menambah Data'
                            ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 200,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function show(KampusJadwalUlang $jadwal_ulang)
    {
        abort(404);
    }

    public function edit(KampusJadwalUlang $jadwal_ulang)
    {
        $jadwal_ulang->item_tagihan_selected = json_decode($jadwal_ulang->item_tagihan_selected);
        $mahasiswas = KampusMahasiswa::with(['prodi'])->whereHas('prodi.kampus',function($q){
            return $q->where('id',Session::get('id_kampus'));
        })->get();
        $data_tagihan = KampusTagihan::with('tagihan_detail','tagihan_detail.rencana.item_bayar.item')->where('id_mahasiswa',76)->get();

        return view('kampus.jadwal_ulang.edit',["mahasiswas"=>$mahasiswas,'jadwal_ulang'=>$jadwal_ulang,'tagihans'=>$data_tagihan]);
    }

    public function update(Request $request, KampusJadwalUlang $jadwal_ulang)
    {
        $old_select = json_decode($jadwal_ulang->item_tagihan_selected);
        $validator = Validator::make(
            $request->only([
                'id_mahasiswa',
                'dokumen',
                'tanggal_diundur',
            ]),
            [
                'id_mahasiswa' => ['required'],
                'dokumen' => ['null', 'mimes:jpeg,png,jpg', 'max:2048'],
                'tanggal_diundur' => ['required'],
            ],
            [],
            [
                'id_mahasiswa' => 'Mahasiswa',
                'dokumen' => 'Dokumen',
                'tanggal_diundur' => 'Tanggal Diundur',
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

        if ($request->file('dokumen')) {
            $image_file = $request->file('deokumen');
            $image_name = 'sipema' . '-' . date('dmY') . '-' . time() . '.' . $image_file->getClientOriginalExtension();

            $uploadImage = $image_file->storePubliclyAs('images/master/dokumen', $image_name, 'public');

            if (!Storage::disk('public')->exists($uploadImage)) {
                return redirect()
                    ->back()
                    ->with('flash_message', (object)[
                        'type' => 'danger',
                        'title' => 'Terjadi Kesalahan Saat Upload Dokumen',
                        'message' => 'Silahkan Upload kembali Dokumen'
                    ])
                    ->withErrors($validator)
                    ->withInput();
            }

            if (!empty($jadwal_ulang->deokumen) && Storage::disk('public')->exists('images/master/dokumen/' . $jadwal_ulang->dokumen)) {
                Storage::disk('public')->delete('images/master/dokumen/' . $jadwal_ulang->dokumen);
            }
        }
        $jadwal_ulang->dokumen = $image_name ?? $jadwal_ulang->dokumen;

        $cek_data = KampusJadwalUlang::where('id_mahasiswa',$request->id_mahasiswa)->count();
        $mou = KampusMou::where('id_kampus',Session::get('id_kampus'))->orderByDesc('tanggal_dibuat')->first();
        if($cek_data>=$mou->max_reschedule){
            return redirect(route('kampus.jadwal_ulang.index'))
                    ->with('flash_message', (object)[
                        'type' => 'danger',
                        'title' => 'Danger',
                        'message' => 'Gagal Mengubah Data'
                    ]);
        }

        try {
            $jadwal_ulang->id_mahasiswa = $request->id_mahasiswa;
            $jadwal_ulang->item_tagihan_selected = $request->has('id_tagihan')? json_encode($request->id_tagihan):"[]";
            $jadwal_ulang->tanggal_diundur = $request->tanggal_diundur;
            // dd($jadwal_ulang);

            if($jadwal_ulang->save()){
                KampusTagihan::whereIn('id',$old_select)->update(['status'=>'0']);
                KampusTagihan::whereIn('id',json_decode($jadwal_ulang->item_tagihan_selected))->update(['status'=>'-1']);
                return redirect(route('kampus.jadwal_ulang.index'))
                    ->with('flash_message', (object)[
                        'type' => 'success',
                        'title' => 'Sukses',
                        'message' => 'Berhasil Mengubah Data'
                    ]);
            }
            else{ 
                return redirect(route('kampus.jadwal_ulang.index'))
                            ->with('flash_message', (object)[
                                'type' => 'danger',
                                'title' => 'Danger',
                                'message' => 'Gagal Mengubah Data'
                            ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 200,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    public function destroy(KampusJadwalUlang $jadwal_ulang)
    {
        try {
            DB::transaction(function() use(&$jadwal_ulang){
                $item_tagihan_selected = json_decode($jadwal_ulang->item_tagihan_selected);
                KampusTagihan::whereIn('id',$item_tagihan_selected)->update(["status"=>0]);
                $jadwal_ulang->delete();
            });
                           
            return redirect(route('kampus.jadwal_ulang.index'))
                    ->with('flash_message', (object)[
                        'type' => 'success',
                        'title' => 'Sukses',
                        'message' => 'Berhasil Menghapus Data'
                    ]);
        } catch (\Throwable $e) {
            return redirect(route('kampus.jadwal_ulang.index'))
                            ->with('flash_message', (object)[
                                'type' => 'danger',
                                'title' => 'Danger',
                                'message' => 'Gagal Menghapus Data'
                            ]);
        }
    }
}
