@extends('layouts.app')

@section('page-title', 'Tambah Lulusan')

@section('content')
    <form action="{{ route('kampus.lulusan.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Lulusan</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">
                        Nama Lulusan
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
