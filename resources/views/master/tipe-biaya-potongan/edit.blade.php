@extends('layouts.app')

@section('page-title', 'Edit Master Item : ' . $masterTipeBiayaPotongan->nama)

@section('content')
    <form action="{{ route('master.tipe-biaya-potongan.update', ['master_tipe_biaya_potongan' => $masterTipeBiayaPotongan->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form Master Tipe Biaya Potongan</div>
            <div class="card-body">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="nama-tipe-biaya-potongan" class="form-label">Nama Tipe Biaya Potongan</label>
                    <input type="text" name="nama" value="{{ old('nama', $masterTipeBiayaPotongan->nama) }}" class="form-control @error('nama') is-invalid @enderror" id="nama-tipe-biaya-potongan" aria-describedby="nama-tipe-biaya-potongan" />
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
