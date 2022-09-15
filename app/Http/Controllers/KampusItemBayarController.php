<?php

namespace App\Http\Controllers;

use App\KampusGelombang;
use App\KampusItemBayar;
use App\MasterItem;
use App\Rules\AnggaranNotEmpty;
use App\Rules\AnggaranValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class KampusItemBayarController extends Controller
{
    public $id_kampus = null;
    public function __construct()
    {
        // Session::get('id_kampus') = auth()->user()->id_kampus;
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
        
        $kampusItemBayars = KampusItemBayar::whereKampus(Session::get('id_kampus'))
                            ->with(['item','gelombang'])
                            ->orderBy('tahun_akademik','ASC')
                            ->orderBy('id_item','ASC')
                            ->orderBy('id_data_gelombang','ASC')
                            ->get()
                            ->each(function($item_bayar,$index){
                                $item_bayar->template_angsuran = collect(json_decode($item_bayar->template_angsuran));
                                $item_bayar->total_bayar = $item_bayar->template_angsuran->sum('nominal');
                            });

        return view('kampus.item_bayar.index',['item_bayars'=>$kampusItemBayars]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = MasterItem::all();
        $gelombangs = KampusGelombang::whereKampus(Session::get('id_kampus'))->get();
        return view('kampus.item_bayar.create',["items"=>$items,'gelombangs'=>$gelombangs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        // $input = [
        //     // 'tahun_akademik',
        //     // 'id_gelombang',
        //     // 'id_item',
        //     'total_angsuran.*',
        // ];
        // $rule = [
        //     // 'tahun_akademik' => ['required'],
        //     // 'id_gelombang' => ['required'],
        //     // 'id_item' => ['required'],
        //     'total_angsuran.*' => ['required'],
        // ];
        // $output = [
        //     // 'tahun_akademik' => 'Nama Beasiswa',
        //     // 'id_gelombang' => 'Persentase Potongan',
        //     // 'id_item' => 'Item',
        //     'total_angsuran.*' => 'Angsuran',
        // ];
        // foreach($request->total_angsuran as $angsuran){
        //     for($i=0;$i<$angsuran;$i++){
        //         array_push($input,"anggaran[$angsuran][$i]");
        //         $rule["anggaran.$angsuran"] = function ($attribute, $value, $fail) {
        //             if (array_sum($value)!=1500000) {
        //                 $e = explode('.',$attribute);
        //                 $fail("Total nominal pada angsuran ke-$e[1] tidak mencapai 1500000");
        //             }
        //         };
        //         $rule["anggaran.$angsuran.$i"] = ["required"];
        //         $output["anggaran.$angsuran.$i"] = "Angsuran ke-$angsuran cicilan ke-".($i+1);
        //     }
        // }

        // $validator = Validator::make(
        //     $request->only($input),
        //     $rule,
        //     [],
        //     $output
        // );

        // if ($validator->fails()) {
        //     return redirect()
        //         ->back()
        //         ->with('flash_message', (object)[
        //             'type' => 'danger',
        //             'title' => 'Terjadi Kesalahan',
        //             'message' => 'Silahkan cek kembali Form'
        //         ])
        //         ->withErrors($validator)
        //         ->withInput();
        // }
        // dd(
        //     $request->total_angsuran,
        //     $request->anggaran,
        //     $validator->getMessageBag(),
        //     $validator->fails()
        // );
        
        DB::transaction(function () use (&$request) {
            $anggaran = [];            
            foreach($request->total_angsuran as $angsuran){
                $_anggaran = [];
                collect($request->anggaran[$angsuran])->each(function($a,$index) use(&$_anggaran){
                    $nominal = str_replace("Rp ","",$a);
                    $nominal = str_replace(".","",$nominal);
                    $nominal = str_replace(",00","",$nominal);
        
                    array_push($_anggaran,[
                        "nama"=> "cicilan ke ".($index+1),
                        "nominal"=>(int) $nominal 
                    ]);
                });
                array_push($anggaran,[
                    "angsuran"=>(int) $angsuran,
                    "data"=>$_anggaran
                ]);
                
                $kampusItemBayar = new KampusItemBayar();
                $kampusItemBayar->id_kampus = Session::get('id_kampus');
                $kampusItemBayar->id_item = $request->id_item;
                $kampusItemBayar->id_data_gelombang = $request->id_gelombang;
                $kampusItemBayar->tahun_akademik = $request->tahun_akademik;
                $kampusItemBayar->jumlah_angsuran = $angsuran;
                $kampusItemBayar->status = 1;
                $kampusItemBayar->template_angsuran = json_encode($_anggaran);
                $kampusItemBayar->save();
            }
            $template = [
                "id_item"=>(int) $request->id_item,
                "id_gelombang"=>(int) $request->id_gelombang,
                "template"=>$anggaran
            ];
            
            DB::table("kampus_template_item_bayar")->insert(["id_kampus"=>Session::get('id_kampus'),"template_item_bayar"=>json_encode($template)]);
        });

        return redirect(route('kampus.item-bayar.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KampusItemBayar  $kampusItemBayar
     * @return \Illuminate\Http\Response
     */
    public function show(KampusItemBayar $kampusItemBayar)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KampusItemBayar  $kampusItemBayar
     * @return \Illuminate\Http\Response
     */
    public function edit(KampusItemBayar $kampusItemBayar)
    {
        $items = MasterItem::all();
        $gelombangs = KampusGelombang::whereKampus(Session::get('id_kampus'))->get();
        $kampusItemBayar->with('item');
        $kampusItemBayar->template_angsuran = json_decode($kampusItemBayar->template_angsuran);

        return view('kampus.item_bayar.edit',["items"=>$items,'gelombangs'=>$gelombangs,'item_bayar'=>$kampusItemBayar]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KampusItemBayar  $kampusItemBayar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KampusItemBayar $kampusItemBayar)
    {
        DB::transaction(function () use (&$request,&$kampusItemBayar) {
            $_anggaran = [];
            collect($request->anggaran)->each(function($a,$index) use(&$_anggaran){
                $nominal = str_replace("Rp ","",$a);
                $nominal = str_replace(".","",$nominal);
                $nominal = str_replace(",00","",$nominal);
    
                array_push($_anggaran,[
                    "nama"=> "cicilan ke ".($index+1),
                    "nominal"=>(int) $nominal 
                ]);
            });

            $kampusItemBayar->id_kampus = Session::get('id_kampus');
            $kampusItemBayar->id_item = $request->id_item;
            $kampusItemBayar->id_data_gelombang = $request->id_gelombang;
            $kampusItemBayar->tahun_akademik = $request->tahun_akademik;
            $kampusItemBayar->template_angsuran = json_encode($_anggaran);
            
            $templateItemBayar = DB::table('kampus_template_item_bayar')
                                    ->where('template_item_bayar','like','%"id_item":'.$request->id_item.',"id_gelombang":'.$request->id_gelombang.'%')
                                    ->first();
            $templateItemBayar = collect(json_decode($templateItemBayar->template_item_bayar));
            collect($templateItemBayar['template'])->each(function($template,$index) use(&$kampusItemBayar,&$_anggaran){
                if(count($template->data)==$kampusItemBayar->jumlah_angsuran){
                    $template->data = $_anggaran;
                }
            });
            
            $kampusItemBayar->save();
            DB::table('kampus_template_item_bayar')
                ->where('template_item_bayar','like','%"id_item":'.$request->id_item.',"id_gelombang":'.$request->id_gelombang.'%')
                ->update([
                    "template_item_bayar"=>json_encode($templateItemBayar->toArray())
                ]);
        });

        return redirect(route('kampus.item-bayar.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KampusItemBayar  $kampusItemBayar
     * @return \Illuminate\Http\Response
     */
    public function destroy(KampusItemBayar $kampusItemBayar)
    {
        if (!$kampusItemBayar->delete()) {
            return redirect(route('kampus.item-bayar.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('kampus.item-bayar.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}
