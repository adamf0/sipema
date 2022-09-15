@extends('layouts.app')

@section('page-title', 'Tambah Master Kampus')

@section('content')
    <form action="{{ route('master.kampus.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Master Kampus</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="nama-kampus" class="form-label">Nama Kampus</label>
                    <input type="text" name="nama_kampus" value="{{ old('nama_kampus') }}" class="form-control @error('nama_kampus') is-invalid @enderror" id="nama-kampus" aria-describedby="nama-kampus" />
                    @error('nama_kampus')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="singkatan-kampus" class="form-label">Singkatan Kampus</label>
                    <input type="text" name="singkatan" value="{{ old('singkatan') }}" class="form-control @error('singkatan') is-invalid @enderror" id="singkatan-kampus" aria-describedby="singkatan-kampus" />
                    @error('singkatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tahun-kerjasama" class="form-label">Tahun Kerjasama</label>
                    <input type="date" name="tahun_kerjasama" value="{{ old('tahun_kerjasama') }}" class="form-control @error('tahun_kerjasama') is-invalid @enderror" id="tahun-kerjasama" aria-describedby="tahun-kerjasama" />
                    @error('tahun_kerjasama')
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
