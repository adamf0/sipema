<?php

namespace App\Http\Controllers;

use App\MasterKampus;
use App\User;
use App\UserKampus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MasterUserController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with(['roles','user_kampus','user_kampus.kampus'])->simplePaginate(5);

        return view('master.user.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.user.create',[
            "kampuss"=>MasterKampus::all(),
            "roles"=>DB::table('roles')->select('id','name')->get(),
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
        $validator = Validator::make(
            $request->only([
                'nama',
                'email',
                'password',
                'role',
                'kampus'
            ]),
            [
                'nama' => ['required'],
                'email' => ['required','email'],
                'password' => ['required'],
                'role' => ['required'],
                'kampus' => ['required'],
            ],
            [],
            [
                'nama' => 'Nama',
                'email' => 'Email',
                'password' => 'Password',
                'role' => 'Level',
                'kampus' => 'Kampus',
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

        DB::transaction(function () use(&$request){
            $user = new User();
            $user->name = $request->nama;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            $user->assignRole($request->role);

            foreach($request->kampus as $id_kampus){
                $userKampus = new UserKampus();
                $userKampus->id_user = $user->id;
                $userKampus->id_kampus = $id_kampus;
                $userKampus->save();
            }
        });

        return redirect(route('master.user.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MasterTipeBiayaPotongan  $masterTipeBiayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MasterTipeBiayaPotongan  $masterTipeBiayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user->load('user_kampus');
        $user->load('roles');

        return view('master.user.edit', [
            'user' => $user,
            'kampuss' => MasterKampus::all(),
            "roles"=>DB::table('roles')->select('id','name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MasterTipeBiayaPotongan  $masterTipeBiayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->load('user_kampus');
        $validator = Validator::make(
            $request->only([
                'nama',
                'email',
                'role',
                'kampus'
            ]),
            [
                'nama' => ['required'],
                'email' => ['required','email'],
                'role' => ['required'],
                'kampus' => ['required'],
            ],
            [],
            [
                'nama' => 'Nama',
                'email' => 'Email',
                'role' => 'Password',
                'kampus' => 'Kampus',
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

        DB::transaction(function () use(&$request,&$user){
            $user->name = $request->nama;
            $user->email = $request->email;
            if( $request->password != "" ||  $request->password != null){
                $user->password =  Hash::make($request->password);
            }
            $user->save();
            $user->syncRoles([$request->role]);

            $diff = array_diff(
                $user->user_kampus->pluck('id_kampus')->toArray(),
                array_map('intval', $request->kampus)
            );
            $diff2 = array_diff(
                array_map('intval', $request->kampus),
                $user->user_kampus->pluck('id_kampus')->toArray()
            );
            
            if(count($diff)>0 || count($diff2)>0){
                UserKampus::where('id_user',$user->id)->delete();
                foreach($request->kampus as $id_kampus){
                    $userKampus = new UserKampus();
                    $userKampus->id_user = $user->id;
                    $userKampus->id_kampus = $id_kampus;
                    $userKampus->save();
                }
            }
        });

        return redirect(route('master.user.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MasterTipeBiayaPotongan  $masterTipeBiayaPotongan
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!$user->delete()) {
            return redirect(route('master.user.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        return redirect(route('master.user.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}