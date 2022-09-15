@extends('layouts.app')

@section('page-title', 'Tambah Master Kelompok')

@section('content')
    <form action="{{ route('master.kelompok.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Master Kelompok</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Kelompok</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" id="nama" aria-describedby="nama" />
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
