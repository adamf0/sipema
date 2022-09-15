@extends('layouts.app')

@section('page-title', 'Edit Master Channel Pembayaran : ' . $masterChannelPembayaran->nama)

@section('content')
    <form action="{{ route('master.channel-pembayaran.update', ['channel_pembayaran' => $masterChannelPembayaran->id]) }}" method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">Form Master Item</div>
            <div class="card-body">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="form-label">Logo Sebelumnya</label>
                    <br>
                    @if (Storage::disk('public')->exists('images/master/channel_pembayaran/' . $masterChannelPembayaran->logo))
                        <img width="100px" src="{{ asset('storage/images/master/channel_pembayaran/' . $masterChannelPembayaran->logo) }}" class="img-fluid">
                    @else
                        <img src="#" alt="Tidak Ditemukan">
                    @endif
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label">Logo Master Channel Pembayaran</label>
                    <input type="file" name="logo" value="{{ old('logo') }}" class="form-control @if ($errors->any()) is-invalid @endif" id="logo" aria-describedby="logo" />
                    @if ($errors->any() && !$errors->has('logo'))
                        <div class="invalid-feedback">Terjadi Kesalahan pada Form,Logo harus di inputkan kembali</div>
                    @endif

                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="nama-master-item" class="form-label">Nama Master Item</label>
                    <input type="text" name="nama" value="{{ old('nama', $masterChannelPembayaran->nama) }}" class="form-control @error('nama') is-invalid @enderror" id="nama-master-item" aria-describedby="nama-master-item" />
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
@endsection
