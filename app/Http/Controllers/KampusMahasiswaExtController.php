<?php

namespace App\Http\Controllers;

use App\KampusItemBayar;
use App\KampusMahasiswa;
use App\KampusRencanaMahasiswa;
use App\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Debugbar;
use Illuminate\Support\Facades\DB;

class KampusMahasiswaExtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.mahasiswa.item_bayar.create',[
            'mahasiswas'=>KampusMahasiswa::whereHas('prodi',function($query){
                return $query->whereKampus(Session::get('id_kampus'));
            })->get(),
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
        $kampusMahasiswa = KampusMahasiswa::findOrFail($request->id_mahasiswa);
        $kampus_item_bayar = KampusItemBayar::findOrFail($request->item_bayars);
        $data_kampus_rencana_mahasiswa = [];
        
        if($kampus_item_bayar->jenis!="bulanan"){
            if($kampus_item_bayar->jenis!="open"){
                $data_template_angsuran = collect(
                    json_decode($kampus_item_bayar->template_angsuran)
                );
                foreach($data_template_angsuran as $template_angsuran){
                    array_push($data_kampus_rencana_mahasiswa, [
                        "id_mahasiswa" => $kampusMahasiswa->id,
                        "id_item_bayar" => $request->item_bayars,
                        "id_biaya_potongan" => null,
                        "nama" => "cicilan ke-$template_angsuran->nama",
                        "biaya" => $template_angsuran->nominal,
                        "tanggal_bayar" => null,
                        "jenis" => $kampus_item_bayar->jenis,
                        "status" => 0,
                        "isDelete" => 0,
                    ]);
                }
            }
            else{
                array_push($data_kampus_rencana_mahasiswa, [
                    "id_mahasiswa" => $kampusMahasiswa->id,
                    "id_item_bayar" => $request->item_bayars,
                    "id_biaya_potongan" => null,
                    "nama" => "",
                    "biaya" => $kampus_item_bayar->nominal,
                    "tanggal_bayar" => null,
                    "jenis" => $kampus_item_bayar->jenis,
                    "status" => 0,
                    "isDelete" => 0,
                ]);
            } 
        }
        else{
            return redirect(route('kampus.mahasiswa.index'))
                        ->with('flash_message', (object)[
                            'type' => 'danger',
                            'title' => 'Danger',
                            'message' => 'fitur masih maintenance'
                        ]);

            $data_template_angsuran = collect(
                json_decode($kampus_item_bayar->template_angsuran)
            );
            foreach ($data_template_angsuran as $template_angsuran) {
                array_push($data_kampus_rencana_mahasiswa, [
                    "id_mahasiswa" => $kampusMahasiswa->id,
                    "id_item_bayar" => $request->item_bayars,
                    "id_biaya_potongan" => null,
                    "nama" => "cicilan ke-$template_angsuran->nama",
                    "biaya" => $template_angsuran->nominal,
                    "tanggal_bayar" => "0000-00-00",
                    "jenis" => $kampus_item_bayar->jenis,
                    "status" => 0,
                    "isDelete" => 0,
                ]);
            }   
        }

        $new_item_bayar_selected = json_decode($kampusMahasiswa->item_bayar_selected);
        array_push($new_item_bayar_selected, (int) $request->item_bayars);

        $kampusMahasiswa->item_bayar_selected = json_encode($new_item_bayar_selected);

        // dd($kampusMahasiswa,$data_kampus_rencana_mahasiswa);
        DB::transaction(function () use (&$kampusMahasiswa, &$data_kampus_rencana_mahasiswa) {
            $kampusMahasiswa->save();
            KampusRencanaMahasiswa::insert($data_kampus_rencana_mahasiswa);
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
        abort(404);
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
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusMahasiswa  $kampusMahasiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $kampusRencanaMahasiswa = KampusRencanaMahasiswa::where('id_mahasiswa',$request->id_mahasiswa)
            ->whereIn('id',$request->id_rencana_mahasiswa)
            ->delete();
        
        if($kampusRencanaMahasiswa){
            return redirect(route('kampus.mahasiswa.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
        }
        else{
            return redirect(route('kampus.mahasiswa.index'))
            ->with('flash_message', (object)[
                'type' => 'danger',
                'title' => 'Danger',
                'message' => 'Gagal Menghapus Data'
            ]);
        }
    }

    public function getData(Request $request){
        Debugbar::disable();

        // dd($request->all());
        if($request->has('id_mahasiswa')){
            $mahasiswa = KampusMahasiswa::findOrFail($request->id_mahasiswa);
            $available_item_bayar = KampusItemBayar::select('id','id_item','jumlah_angsuran')
                                        ->with('item')
                                        ->whereIn('id_item',function($query) use($request){
                                            $query->select('id_item')
                                                ->from(with(new KampusItemBayar)->getTable())
                                                ->where('id_kampus',$request->id_kampus)
                                                ->where('status',1)
                                                ->groupBy('id_item');
                                        })
                                        ->whereNotIn('id_item',function($query) use($mahasiswa){
                                            $query->select('id_item')
                                                ->from(with(new KampusItemBayar)->getTable())
                                                ->whereIn('id',json_decode($mahasiswa->item_bayar_selected));
                                        })
                                        ->get()
                                        ->each(function($item,$index){
                                            $item->text = $item->item->nama;
                                            if($item->jenis!="open" && $item->jumlah_angsuran>0){
                                                $item->text .= " ( ".$item->jumlah_angsuran."x )";
                                            }
                                            unset($item['item'],$item['jumlah_angsuran']);
                                        });

            
            return response()->json([
                "status"=>200,
                "data"=>$available_item_bayar,
                "error"=>""
            ]);
        }
        else{
            return response()->json([
                "status"=>500,
                "data"=>$request->all(),
                "error"=>"data yang dikirim tidak lengkap"
            ]);
        }
    }
}
