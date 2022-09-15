<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KampusJadwalUlangTagihan extends Controller
{
    public function __construct()
    {
        
    }
    public function index(){
        return response()->json([
            'status' => 200,
            'message' => 'berhasil simpan jadwal ulang',
            'data' => DB::table('kampus_jadwal_ulang_tagihan as kjut')
                        ->select('kjut.*','km.nim','km.nim_sementara','km.nama_lengkap','kp.nama','kp.jenjang')
                        ->join('kampus_mahasiswa as km','kjut.id_mahasiswa','=','km.id')
                        ->join('kampus_prodi as kp','km.id_prodi','=','kp.id')
                        ->get()
        ]);
    }
    public function add(){

    }
    public function edit(){

    }
    public function create(Request $req){
        try {
            $data = [
                "id_mahasiswa" => $req->id_mahasiswa,
                "dokumen" => $req->dokumen,
                "item_tagihan_selected" => $req->item_tagihan_selected,
                "tanggal_diundur"=> $req->tanggal_diundur,
                "tanggal_dibuat"=> $req->tanggal_dibuat,
                "status"=>0
            ];
    
            $kampus_jadwal_ulang_tagihan = DB::table('kampus_jadwal_ulang_tagihan')->where("id_mahasiswa",$req->id_mahasiswa)->count();
            if($kampus_jadwal_ulang_tagihan<3){
                DB::table('kampus_jadwal_ulang_tagihan')->insert($data);

                return response()->json([
                    'status' => 200,
                    'message' => 'berhasil simpan jadwal ulang',
                    'data' => $data
                ]);
            }
            else{ 
                return response()->json([
                    'status' => 200,
                    'message' => "pengajuan reschedule hanya boleh 3x saja",
                    'data' => []
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
    public function update($id,Request $req){
        try {
            $jadwal_ulang_tagihan = DB::table('kampus_jadwal_ulang_tagihan')->where('id',$id);
            $item_tagihan_selected = json_decode($jadwal_ulang_tagihan->first()->item_tagihan_selected);
                
            if(!$jadwal_ulang_tagihan->update(["status"=>1])){
                return response()->json([
                    'status' => 200,
                    'message' => "gagal update jadwal ulang",
                    'data' => []
                ]);
            }
            if(!DB::table('kampus_tagihan')->whereIn('id',$item_tagihan_selected)->update(["status"=>-1])){
                return response()->json([
                    'status' => 200,
                    'message' => "gagal update jadwal ulang karena data tidak ditemukan",
                    'data' => []
                ]);
            }
            
            return response()->json([
                'status' => 200,
                'message' => 'berhasil update jadwal ulang',
                'data' => []
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 200,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }
    public function delete($id){
        try {
            $jadwal_ulang_tagihan = DB::table('kampus_jadwal_ulang_tagihan')->where('id',$id);
            // $tanggal_diundur = $jadwal_ulang_tagihan->first()->tanggal_diundur;
            $item_tagihan_selected = json_decode($jadwal_ulang_tagihan->first()->item_tagihan_selected);
                
            if(!$jadwal_ulang_tagihan->delete()){
                return response()->json([
                    'status' => 200,
                    'message' => 'gagal hapus jadwal ulang',
                    'data' => []
                ]);
            }
            if(!DB::table('kampus_tagihan')->whereIn('id',$item_tagihan_selected)->update(["status"=>0])){
                return response()->json([
                    'status' => 200,
                    'message' => 'gagal hapus jadwal ulang karena data tidak ditemukan',
                    'data' => []
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'berhasil hapus jadwal ulang',
                'data' => []
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 200,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }
}
