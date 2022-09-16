<?php

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes(['verify' => false]);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::prefix('kampus')->name('kampus.')->group(function () {
        Route::resource('mou', 'KampusMouController')->parameter('mou', 'kampus_mou');
        Route::resource('prodi', 'KampusProdiController')->parameter('prodi', 'kampus_prodi');
        Route::resource('item-bayar', 'KampusItemBayarController')->parameter('item-bayar', 'kampus_item_bayar');
        Route::resource('gelombang', 'KampusGelombangController')->parameter('gelombang', 'kampus_gelombang'); 
        Route::resource('pembayaran', 'KampusPembayaranController')->parameter('pambayaran', 'kampus_pembayaran');
        Route::resource('mahasiswa', 'KampusMahasiswaController')->parameter('mahasiswa', 'kampus_mahasiswa');
        Route::get('switch/{id_kampus}/{to}', function ($id_kampus, $to) {
            foreach (Auth::user()->load('user_kampus')->user_kampus as $kampus) {
                if ($kampus->id_kampus == $id_kampus) {
                    Session::put('id_kampus', $kampus->kampus->id);
                    Session::put('nama_kampus', $kampus->kampus->nama_kampus);
                    return redirect(route(base64_decode($to)));
                }
            }
        })->name('switch');
    });
    Route::resource('biaya-potongan', 'BiayaPotonganController')->parameter('biaya_potong', 'biaya_potongan');
    Route::prefix('jadwal_ulang')->group(function () {
        Route::get('/', 'KampusJadwalUlangTagihan@index'); //untuk mahasiswa 
        Route::post('/', 'KampusJadwalUlangTagihan@create');  //untuk mahasiswa
        Route::post('/{id}', 'KampusJadwalUlangTagihan@update'); //untuk kampus/admin
        Route::get('/{id}', 'KampusJadwalUlangTagihan@delete'); //untuk mahasiswa
    });
     
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('user', 'MasterUserController')->except('view')->parameter('user', 'user');
        Route::resource('item', 'MasterItemController')->except('view')->parameter('item', 'master_item');
        Route::resource('channel-pembayaran', 'MasterChannelPembayaranController')->except('view');
        Route::resource('kampus', 'MasterKampusController')->except('view')->parameter('kampus', 'master_kampus');
         Route::resource('kelompok', 'MasterKelompokController')->parameter('kelompok', 'master_kelompok');
        Route::resource('tipe-biaya-potongan', 'MasterTipeBiayaPotonganController')->except('view')->parameter('tipe-biaya-potongan', 'master_tipe_biaya_potongan');
    });

    Route::prefix('detail-kampus/{kampus}')->name('detail-kampus.')->group(function () {
        Route::get('/', 'DetailKampusController@index')->name('dashboard');
        Route::resource('mou', 'AdminKampusMouController')->except('view');
        Route::resource('prodi', 'KampusProdiController')->except('view');
        Route::resource('item-bayar', 'KampusItemBayarController')->except('view');
        Route::resource('gelombang', 'KampusGelombangController')->except('view');
        Route::resource('pembayaran', 'KampusPembayaranController')->except('view');
        Route::resource('mahasiswa', 'KampusMahasiswaController')->except('view');
    });
});
// Route::get('add-role/{id}/{role}', function($id,$role){
//     $user = User::findOrFail($id);
//     $user->assignRole($role);
// });
// Route::get('tes-transaksi', function(){
//         $data_group_mahasiwa = DB::table('kampus_rencana_mahasiswa as krm')
//                             ->select(
//                                 DB::raw('concat(mk.kode_kampus,"'.rand(0,100).'",IF(km.nim!="", km.nim, km.nim_sementara)) as nomor_transaksi'),
//                                 'krm.id_mahasiswa',
//                                 'krm.tanggal_bayar as tanggal',
//                                 DB::raw('concat("0") as status'),
//                                 'krm.id_mahasiswa'
//                             )
//                             ->join('kampus_mahasiswa as km','krm.id_mahasiswa','=','km.id')
//                             ->join('kampus_prodi as kp','km.id_prodi','=','kp.id')
//                             ->join('master_kampus as mk','kp.id_kampus','=','mk.id')
//                             ->where('krm.tanggal_bayar',date('Y-m-d')) //date('Y-m-d')
//                             ->groupBy('krm.id_mahasiswa')
//                             ->get();
//         $data_mahasiwa = DB::table('kampus_rencana_mahasiswa as krm')
//                     ->select('krm.id as id_tagihan_mahasiswa','krm.biaya')
//                     ->join('kampus_mahasiswa as km','krm.id_mahasiswa','=','km.id')
//                     ->join('kampus_prodi as kp','km.id_prodi','=','kp.id')
//                     ->join('master_kampus as mk','kp.id_kampus','=','mk.id')
//                     ->where('krm.tanggal_bayar',date('Y-m-d')) //date('Y-m-d')
//                     ->get();

//         dd($data_group_mahasiwa,$data_mahasiwa);
//         DB::transaction(function () use (&$data_group_mahasiwa,&$data_mahasiwa) {
//             foreach($data_group_mahasiwa as $dgm){
//                 if(DB::table('kampus_tagihan')->where('tanggal','like','%'.date('Y-m-d').'%')->count()==0){ //date('Y-m-d')
//                     $id_transaksi = DB::table('kampus_tagihan')->insertGetId((array) $dgm);

//                     foreach($data_mahasiwa as $dm){
//                         DB::table('kampus_tagihan_detail')->insertGetId([
//                             "id_transaksi"=>$id_transaksi,
//                             "id_tagihan_mahasiswa"=>$dm->id_tagihan_mahasiswa,
//                             "biaya"=>$dm->biaya
//                         ]);
//                     }
//                 }
//             }
//         });
// });