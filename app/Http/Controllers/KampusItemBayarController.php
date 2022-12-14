<?php

namespace App\Http\Controllers;

use App\KampusGelombang;
use App\KampusItemBayar;
use App\KampusKelas;
use App\KampusLulusan;
use App\KampusMetodeBelajar;
use App\KampusProdi;
use App\KampusTahunAkademik;
use App\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class KampusItemBayarController extends Controller
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
        // $kampusItemBayars = KampusItemBayar::whereKampus(Session::get('id_kampus'))
        //                     ->with(['item','gelombang','gelombang.tahun_akademik','kelas','metode_belajar','lulusan','prodi','prodi.kampus'])
        //                     ->orderBy('tahun_akademik','ASC')
        //                     ->orderBy('id_item','ASC')
        //                     ->orderBy('id_data_gelombang','ASC')
        //                     ->get()
                            // ->each(function($item_bayar,$index){
                            //     $item_bayar->template_angsuran = collect(json_decode($item_bayar->template_angsuran));
                            //     $item_bayar->total_bayar = $item_bayar->template_angsuran->sum('nominal');
                            // });

        if ($request->ajax()) {
            $id_kampus = $request->id_kampus;
            $data = KampusItemBayar::whereKampus($id_kampus)
                                ->orderBy('tahun_akademik','ASC')
                                ->orderBy('id_item','ASC')
                                ->orderBy('id_data_gelombang','ASC')
                                ->groupBy([
                                    'tahun_akademik',
                                    'id_data_gelombang',
                                    'id_prodi',
                                    'id_lulusan',
                                    'id_kelas',
                                    'id_metode_belajar'
                                ])
                                ->get()
                                ->each(function($item,$index) use(&$id_kampus){
                                    $item->items = KampusItemBayar::with([
                                                        'item',
                                                        'gelombang',
                                                        'gelombang.tahun_akademik',
                                                        'kelas',
                                                        'metode_belajar',
                                                        'lulusan',
                                                        'prodi',
                                                        'prodi.kampus'
                                                    ])
                                                    ->whereKampus($id_kampus)
                                                    ->where([
                                                        'tahun_akademik'=>$item->tahun_akademik,
                                                        'id_data_gelombang'=>$item->id_data_gelombang,
                                                        'id_prodi'=>$item->id_prodi,
                                                        'id_lulusan'=>$item->id_lulusan,
                                                        'id_kelas'=>$item->id_kelas,
                                                        'id_metode_belajar'=>$item->id_metode_belajar
                                                    ])
                                                    ->get()
                                                    ->each(function($item_bayar,$index){
                                                        $item_bayar->template_angsuran = collect(json_decode($item_bayar->template_angsuran));
                                                        $item_bayar->total_bayar = $item_bayar->template_angsuran->sum('nominal');
                                                        $item_bayar->aksi = "<div class='d-flex gap-2'>
                                                            <a href='" . route('kampus.item-bayar.edit', ['item-bayar' => $item_bayar->id]) . "' class='btn btn-warning btn-sm'>Edit</a>
                                                            <form action='" . route('kampus.item-bayar.destroy', ['item-bayar' => $item_bayar->id]) . "' method='post'>
                                                                <input type='hidden' name='_token' value='" . csrf_token() . "'>
                                                                <input type='hidden' name='_method' value='DELETE'>
                                                                <button type='submit' class='btn btn-danger btn-sm'>Hapus</button>
                                                            </form>
                                                        </div>";
                                                    });
                                });
        
            $data->load([
                'gelombang',
                'gelombang.tahun_akademik',
                'kelas',
                'metode_belajar',
                'lulusan',
                'prodi',
                'prodi.kampus'
            ]);
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('kampus.item_bayar.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kampus.item_bayar.create',[
            "items"=>MasterItem::all(),
            'gelombangs'=>KampusGelombang::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->get(),
            'prodis'=>KampusProdi::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->get(),
            'metode_belajars'=>KampusMetodeBelajar::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->get(),
            'lulusans'=>KampusLulusan::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->get(),
            'kelass'=>KampusKelas::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->get(),
            'tahun_akademiks'=>KampusTahunAkademik::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->get()
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
        // dd($request->all());
        DB::transaction(function () use (&$request) {
            $anggaran = [];
            $anggaran_custom = [];
            $request->total_angsuran = collect($request->total_angsuran)->filter(function ($request){
                return is_string($request)&&!empty($request)||is_array($request)&&count($request);
            });
            $request->angsuranC = collect($request->angsuranC)->filter(function ($request){
                return is_string($request)&&!empty($request)||is_array($request)&&count($request);
            });

            if($request->jenis=="bulanan"){
                foreach($request->total_angsuran as $angsuran){
                    $_anggaran = [];
                    if($angsuran!=null || $angsuran!="" || $angsuran!=0){
                        $nominal = $request->nominal / $angsuran;
                        for ($i=0; $i < $angsuran; $i++) { 
                            array_push($_anggaran,[
                                "nama"=> (int) $i+1,
                                "nominal"=>(int) $nominal 
                            ]);
                        }
                        array_push($anggaran,[
                            "angsuran"=>(int) $angsuran,
                            "type"=>1,
                            "data"=>$_anggaran
                        ]);
                    }
                }

                foreach($request->angsuranC as $index => $angsuran){
                    $_anggaran = [];

                    if($angsuran!=null || $angsuran!="" || $angsuran!=0){
                        $max = count($request->nominalC[$index]);
                        for ($i=0; $i < $max; $i++) { 
                            array_push($_anggaran,[
                                "nama"=> (int) $request->cicilanC[$index][$i], 
                                "nominal"=>(int) $request->nominalC[$index][$i] 
                            ]);
                        }

                        $nominal_custom = ($request->nominal - array_sum($request->nominalC[$index]))/($angsuran-count($request->nominalC[$index]));
                        for ($i=1; $i <= $angsuran; $i++) { 
                            if(!in_array($i,$request->cicilanC[$index])){
                                array_push($_anggaran,[
                                    "nama"=> (int) $i,
                                    "nominal"=>(int) $nominal_custom
                                ]);
                            }
                        }
                                    
                        array_push($anggaran_custom,[
                            "angsuran"=>(int) $angsuran,
                            "type"=>0,
                            "data"=>$_anggaran
                        ]);
                    }
                }

                $anggaran_custom = collect($anggaran_custom)->map(function($items,$index){
                    $items['data'] = collect($items['data'])->sortBy('nama')->values()->toArray();
                    return $items;
                })->values()->toArray();
    
                $anggaran = collect(array_merge($anggaran,$anggaran_custom))->sortBy('angsuran')->values();
            }
            else if($request->jenis=="angsuran"){
                foreach($request->total_angsuran as $angsuran){
                    $_anggaran = [];
                    if($angsuran!=null || $angsuran!="" || $angsuran!=0){
                        $nominal = $request->nominal / $angsuran;
                        for ($i=0; $i < $angsuran; $i++) { 
                            array_push($_anggaran,[
                                "nama"=> (int) $i+1,
                                "nominal"=>(int) $nominal 
                            ]);
                        }
                        array_push($anggaran,[
                            "angsuran"=>(int) $angsuran,
                            "type"=>1,
                            "data"=>$_anggaran
                        ]);
                    }
                }
                $anggaran = collect($anggaran);
            }
            else if($request->jenis=="insidentil"){
                array_push($anggaran,[
                    "angsuran"=>1,
                    "type"=>1,
                    "data"=>[
                        [
                            "nama"=> 1,
                            "nominal"=>(int) $request->nominal
                        ]
                    ]
                ]);
                $anggaran = collect($anggaran);
            }
            else {
                array_push($anggaran,[
                    "angsuran"=>-1,
                    "type"=>1,
                    "data"=>[]
                ]);
                $anggaran = collect($anggaran);
            }

            $anggaran->each(function($items,$index) use(&$request){
                $kampusItemBayar                    = new KampusItemBayar();
                $kampusItemBayar->id_kampus         = Session::get('id_kampus');
                $kampusItemBayar->id_item           = $request->id_item;
                $kampusItemBayar->id_data_gelombang = $request->id_gelombang;
                $kampusItemBayar->jenis             = $request->jenis;
                $kampusItemBayar->id_prodi          = $request->id_prodi;
                $kampusItemBayar->id_kelas          = $request->id_kelas;
                $kampusItemBayar->id_metode_belajar = $request->id_metode_belajar;
                $kampusItemBayar->id_lulusan        = $request->id_lulusan;
                $kampusItemBayar->nominal           = $request->nominal;
                $kampusItemBayar->tahun_akademik    = $request->tahun_akademik;
                $kampusItemBayar->tanggal_awal      = $request->tanggal_awal;
                $kampusItemBayar->tanggal_akhir     = $request->tanggal_akhir;
                $kampusItemBayar->jumlah_angsuran   = $items['angsuran'];
                $kampusItemBayar->type              = $items['type'];
                $kampusItemBayar->status            = 1;
                $kampusItemBayar->template_angsuran = $request->jenis=="open"? "[]":json_encode($items['data']);
                $kampusItemBayar->save();
            });
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
        $kampusItemBayar::with('item');
        $kampusItemBayar->template_angsuran = json_decode($kampusItemBayar->template_angsuran);

        return view('kampus.item_bayar.edit',[
            "items"=>MasterItem::all(),
            'item_bayar'=>$kampusItemBayar,
            'gelombangs'=>KampusGelombang::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->where('id','!=',1)->get(),
            'prodis'=>KampusProdi::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->where('id','!=',1)->get(),
            'metode_belajars'=>KampusMetodeBelajar::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->where('id','!=',1)->get(),
            'lulusans'=>KampusLulusan::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->where('id','!=',1)->get(),
            'kelass'=>KampusKelas::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->where('id','!=',1)->get(),
            'tahun_akademiks'=>KampusTahunAkademik::whereKampus(Session::get('id_kampus'))->withDefaultKampus()->where('id','!=',1)->get()
        ]);
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
        try {
            DB::transaction(function () use (&$request,&$kampusItemBayar) {
                $_anggaran = [];
                if($request->jenis=="bulanan"){
                    if(!$request->has('key')){
                        $nominal = $request->nominal / $request->total_angsuran;
                        for ($i=0; $i < $request->total_angsuran; $i++) { 
                            array_push($_anggaran,[
                                "nama"=> "cicilan ke ".($i+1),
                                "nominal"=>(int) $nominal 
                            ]);
                        }
                    }
                    else if($request->has('key')){
                        if($request->total_angsuran != count($request->key)){
                            throw new \Exception("Total angsuran tidak sama dengan total input cicilan");
                        }
                        if($request->nominal != array_sum($request->angsuran)){
                            throw new \Exception("Nominal tidak sama dengan total nilai cicilan");
                        }

                        for ($i=0; $i < count($request->key); $i++) { 
                            array_push($_anggaran,[
                                "nama"=> $request->key[$i],
                                "nominal"=>(int) $request->angsuran[$i] 
                            ]);
                        }
                    }
                }
                else if($request->jenis=="angsuran"){
                    $nominal = $request->nominal / $request->total_angsuran;
                    for ($i=0; $i < $request->total_angsuran; $i++) { 
                        array_push($_anggaran,[
                            "nama"=> "cicilan ke ".($i+1),
                            "nominal"=>(int) $nominal 
                        ]);
                    }
                }
                else if($request->jenis=="insidentil"){
                    $request->total_angsuran = 1;
                    array_push($_anggaran,[
                        "nama"=> "cicilan ke 1",
                        "nominal"=>(int) $request->nominal
                    ]);
                }
                else{
                    $request->total_angsuran = -1;
                }

                $kampusItemBayar->id_kampus         = Session::get('id_kampus');
                $kampusItemBayar->id_item           = $request->id_item;
                $kampusItemBayar->id_data_gelombang = $request->id_gelombang;
                $kampusItemBayar->id_prodi          = $request->id_prodi;
                $kampusItemBayar->id_kelas          = $request->id_kelas;
                $kampusItemBayar->id_metode_belajar = $request->id_metode_belajar;
                $kampusItemBayar->id_lulusan        = $request->id_lulusan;
                $kampusItemBayar->nominal           = $request->nominal;
                $kampusItemBayar->jumlah_angsuran   = $request->total_angsuran;
                $kampusItemBayar->tahun_akademik    = $request->tahun_akademik;
                $kampusItemBayar->tanggal_awal      = $request->tanggal_awal;
                $kampusItemBayar->tanggal_akhir     = $request->tanggal_akhir;
                $kampusItemBayar->template_angsuran = $request->jenis=="open"? "[]":json_encode($_anggaran);
                // dd($kampusItemBayar);
                $kampusItemBayar->save();
            });

            return redirect(route('kampus.item-bayar.index'))
                    ->with('flash_message', (object)[
                        'type' => 'success',
                        'title' => 'Sukses',
                        'message' => 'Berhasil Mengubah Data'
                    ]);
        } catch (\Exception $e) {
            return redirect(route('kampus.item-bayar.index'))
                        ->with('flash_message', (object)[
                            'type' => 'danger',
                            'title' => 'Terjadi Kesalahan',
                            'message' => $e->getMessage()
                        ]);
        }
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
