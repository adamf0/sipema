@extends('layouts.kampus')

@section('nama-kampus', $kampus->nama_kampus)

@section('page-title', 'Edit Gelombang : ' . $gelombang->nama_gelombang)

@section('content')
    <form action="{{ route('detail-kampus.gelombang.update', ['kampus' => $kampus->id, 'gelombang' => $gelombang->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form Prodi</div>
            <div class="card-body">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="nama_gelombang" class="form-label">Nama Gelombang</label>
                    <input type="text" name="nama_gelombang" value="{{ old('nama_gelombang', $gelombang->nama_gelombang) }}" class="form-control @error('nama_gelombang') is-invalid @enderror" id="nama_gelombang" aria-describedby="nama_gelombang" />
                    @error('nama_gelombang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tanggal_mulai" class="form-label">Tangggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $gelombang->tanggal_mulai) }}" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" aria-describedby="tanggal_mulai" />
                    @error('tanggal_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_akhir" class="form-label">tanggal_akhir</label>
                    <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir', $gelombang->tanggal_akhir) }}" class="form-control @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" aria-describedby="tanggal_akhir" />
                    @error('tanggal_akhir')
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
