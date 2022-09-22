<?php

use App\KampusTagihan;
use App\KampusTagihanDetail;
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
        Route::get('mou/change-status/{id}', 'KampusMouController@change')->name('mou.change');

        Route::resource('prodi', 'KampusProdiController')->parameter('prodi', 'kampus_prodi');
        Route::resource('metode_belajar', 'KampusMetodeBelajarController')->parameter('metode_belajar', 'kampus_metode_belajar');
        Route::resource('kelas', 'KampusKelasController')->parameter('kelas', 'kampus_kelas');
        Route::resource('item', 'KampusItemController')->except('view')->parameter('item', 'master_item'); //migrasi ke kampus
        Route::resource('item-bayar', 'KampusItemBayarController')->parameter('item-bayar', 'kampus_item_bayar');
        Route::resource('gelombang', 'KampusGelombangController')->parameter('gelombang', 'kampus_gelombang');

        Route::resource('pembayaran', 'KampusPembayaranController')->parameter('pambayaran', 'kampus_pembayaran');
        Route::resource('mahasiswa', 'KampusMahasiswaController')->parameter('mahasiswa', 'kampus_mahasiswa');
        Route::resource('jadwal_ulang', 'KampusJadwalUlangTagihan')->parameter('mahasiswa', 'kampus_mahasiswa');
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

    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('user', 'MasterUserController')->except('view')->parameter('user', 'user');
        Route::resource('channel-pembayaran', 'MasterChannelPembayaranController')->except('view');
        Route::resource('kampus', 'MasterKampusController')->except('view')->parameter('kampus', 'master_kampus');
        Route::resource('tipe-biaya-potongan', 'MasterTipeBiayaPotonganController')->except('view')->parameter('tipe-biaya-potongan', 'master_tipe_biaya_potongan');
    });

    Route::prefix('detail-kampus/{kampus}')->name('detail-kampus.')->group(function () {
        Route::get('/', 'DetailKampusController@index')->name('dashboard');
        Route::resource('mou', 'AdminKampusMouController')->except('view');
        Route::resource('prodi', 'AdminKampusProdiController')->except('view');
        Route::resource('item-bayar', 'AdminKampusItemBayarController')->except('view');
        Route::resource('gelombang', 'AdminKampusGelombangController')->except('view');
        Route::resource('pembayaran', 'KampusPembayaranController')->except('view');
        Route::resource('mahasiswa', 'AdminKampusMahasiswaController')->except('view');
    });
});
// Route::get('add-role/{id}/{role}', function($id,$role){
//     $user = User::findOrFail($id);
//     $user->assignRole($role);
// });
Route::get('tes-transaksi', function(){
        $data_group_mahasiwa = DB::table('kampus_rencana_mahasiswa as krm')
                            ->select(
                                DB::raw('concat(mk.kode_kampus,"'.rand(0,100).'",IF(km.nim!="", km.nim, km.nim_sementara)) as nomor_transaksi'),
                                'krm.id_mahasiswa',
                                'krm.tanggal_bayar as tanggal',
                                DB::raw('concat("0") as status'),
                                'krm.id_mahasiswa'
                            )
                            ->join('kampus_mahasiswa as km','krm.id_mahasiswa','=','km.id')
                            ->join('kampus_prodi as kp','km.id_prodi','=','kp.id')
                            ->join('master_kampus as mk','kp.id_kampus','=','mk.id')
                            ->where('krm.tanggal_bayar',date('Y-m-d')) //date('Y-m-d')
                            ->groupBy('krm.id_mahasiswa')
                            ->get();
        $data_mahasiwa = DB::table('kampus_rencana_mahasiswa as krm')
                    ->select('krm.id as id_tagihan_mahasiswa','krm.biaya','krm.id_mahasiswa')
                    ->join('kampus_mahasiswa as km','krm.id_mahasiswa','=','km.id')
                    ->join('kampus_prodi as kp','km.id_prodi','=','kp.id')
                    ->join('master_kampus as mk','kp.id_kampus','=','mk.id')
                    ->where('krm.tanggal_bayar',date('Y-m-d')) //date('Y-m-d')
                    ->get();

        DB::transaction(function () use (&$data_group_mahasiwa,&$data_mahasiwa) {
            foreach($data_group_mahasiwa as $dgm){
                if(KampusTagihan::where('tanggal','like','%'.date('Y-m-d').'%')->where('id_mahasiswa',$dgm->id_mahasiswa)->count()==0){
                    $tagihan = new KampusTagihan();
                    $tagihan->nomor_transaksi = $dgm->nomor_transaksi;
                    $tagihan->tanggal = $dgm->tanggal;
                    $tagihan->status = $dgm->status;
                    $tagihan->id_mahasiswa = $dgm->id_mahasiswa;
                    $tagihan->save();

                    foreach($data_mahasiwa as $dm){
                        if($dgm->id_mahasiswa == $dm->id_mahasiswa){
                            $tagihan_detail = new KampusTagihanDetail();
                            $tagihan_detail-> id_transaksi = $tagihan->id;
                            $tagihan_detail->id_tagihan_mahasiswa = $dm->id_tagihan_mahasiswa;
                            $tagihan_detail->biaya = $dm->biaya;
                            $tagihan_detail->save();
                        }
                    }
                }
            }
        });
});
