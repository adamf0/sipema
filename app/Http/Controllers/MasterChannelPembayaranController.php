<?php

namespace App\Http\Controllers;

use App\MasterChannelPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MasterChannelPembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $masterChannelPembayarans = MasterChannelPembayaran::simplePaginate(5);

        return view('master.channel.index', [
            'masterChannelPembayarans' => $masterChannelPembayarans
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.channel.create');
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
                'logo',
                'nama',
            ]),
            [
                'logo' => ['required', 'mimes:jpeg,png,jpg', 'max:2048'],
                'nama' => ['required'],
            ],
            [],
            [
                'logo' => 'Logo',
                'nama' => 'Nama',
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

        $image_file = $request->file('logo');
        $image_name = 'sipema' . '-' . date('dmY') . '-' . time() . '.' . $image_file->getClientOriginalExtension();

        $uploadImage = $image_file->storePubliclyAs('images/master/channel_pembayaran', $image_name, 'public');
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

        $masterChannelPembayaran = new MasterChannelPembayaran();
        $masterChannelPembayaran->logo = $image_name;
        $masterChannelPembayaran->nama = $request->nama;
        $masterChannelPembayaran->save();

        return redirect(route('master.channel-pembayaran.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menambah Data'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MasterChannelPembayaran  $channelPembayaran
     * @return \Illuminate\Http\Response
     */
    public function show(MasterChannelPembayaran $channelPembayaran)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MasterChannelPembayaran  $channelPembayaran
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterChannelPembayaran $channelPembayaran)
    {
        return view('master.channel.edit', [
            'masterChannelPembayaran' => $channelPembayaran
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MasterChannelPembayaran  $channelPembayaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterChannelPembayaran $channelPembayaran)
    {
        $validator = Validator::make(
            $request->only([
                'logo',
                'nama',
            ]),
            [
                'logo' => [Rule::requiredIf(function () use ($channelPembayaran) {
                    if (!empty($channelPembayaran->logo) && !Storage::disk('public')->exists('/' . $channelPembayaran->logo)) {
                        return true;
                    } else {
                        return false;
                    }
                }), 'mimes:jpeg,png,jpg', 'max:2048'],
                'nama' => ['required'],
            ],
            [],
            [
                'logo' => 'Logo',
                'nama' => 'Nama',
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

        if ($request->file('logo')) {
            $image_file = $request->file('logo');
            $image_name = 'sipema' . '-' . date('dmY') . '-' . time() . '.' . $image_file->getClientOriginalExtension();

            $uploadImage = $image_file->storePubliclyAs('images/master/channel_pembayaran', $image_name, 'public');

            if (!Storage::disk('public')->exists($uploadImage)) {
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

            if (!empty($channelPembayaran->logo) && Storage::disk('public')->exists('images/master/channel_pembayaran/' . $channelPembayaran->logo)) {
                Storage::disk('public')->delete('images/master/channel_pembayaran/' . $channelPembayaran->logo);
            }
        }

        $channelPembayaran->logo = $image_name ?? $channelPembayaran->logo;
        $channelPembayaran->nama = $request->nama;

        if (!$channelPembayaran->getDirty()) {
            return redirect()
                ->route('master.channel-pembayaran.index')
                ->with('flash_message', (object)[
                    'type' => 'warning',
                    'title' => 'Peringatan',
                    'message' => 'Perubahan Dibatalkan karena tidak ada perubahan'
                ]);
        }

        $channelPembayaran->save();

        return redirect(route('master.channel-pembayaran.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Mengubah Data'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MasterChannelPembayaran  $channelPembayaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterChannelPembayaran $channelPembayaran) //bug
    {
        if (!$channelPembayaran->delete()) {
            return redirect(route('master.channel-pembayaran.index'))
                ->with('flash_message', (object)[
                    'type' => 'danger',
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Silahkan Coba Kembali.'
                ]);
        }

        if (!empty($channelPembayaran->logo) && Storage::disk('public')->exists('images/master/channel_pembayaran/' . $channelPembayaran->logo)) {
            Storage::disk('public')->delete('images/master/channel_pembayaran/' . $channelPembayaran->logo);
        }

        return redirect(route('master.channel-pembayaran.index'))
            ->with('flash_message', (object)[
                'type' => 'success',
                'title' => 'Sukses',
                'message' => 'Berhasil Menghapus Data'
            ]);
    }
}