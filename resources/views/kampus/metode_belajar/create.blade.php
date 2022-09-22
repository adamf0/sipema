@extends('layouts.app')

@section('page-title', 'Tambah Metode Belajar')

@section('content')
    <form action="{{ route('kampus.metode_belajar.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Metode Belajar</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">
                        Nama Metode Belajar
                    </label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama">
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
