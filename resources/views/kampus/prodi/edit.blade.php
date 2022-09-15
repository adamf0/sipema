@extends('layouts.app')

@section('page-title', 'Edit Prodi : ' . $prodi->nama)

@section('content')
    <form action="{{ route('kampus.prodi.update', ['kampus_prodi' => $prodi->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form Prodi</div>
            <div class="card-body">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="kode_prodi" class="form-label">Kode Prodi</label>
                    <input type="text" name="kode_prodi" value="{{ old('kode_prodi', $prodi->kode_prodi) }}" class="form-control @error('kode_prodi') is-invalid @enderror" id="kode_prodi" aria-describedby="kode_prodi" />
                    @error('kode_prodi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Prodi</label>
                    <input type="text" name="nama" value="{{ old('nama', $prodi->nama) }}" class="form-control @error('nama') is-invalid @enderror" id="nama" aria-describedby="nama" />
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jenjang" class="form-label">Jenjang</label>
                    <input type="text" name="jenjang" value="{{ old('jenjang', $prodi->jenjang) }}" class="form-control @error('jenjang') is-invalid @enderror" id="jenjang" aria-describedby="jenjang" />
                    @error('jenjang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="masa_studi" class="form-label">Masa Studi</label>
                    <small>(Tahun)</small>
                    <input type="text" name="masa_studi" value="{{ old('masa_studi', $prodi->masa_studi) }}" class="form-control @error('masa_studi') is-invalid @enderror" id="masa_studi" aria-describedby="masa_studi" />
                    @error('masa_studi')
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
