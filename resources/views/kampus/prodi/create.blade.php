@extends('layouts.app')

@section('page-title', 'Tambah Prodi')

@section('content')
    <form action="{{ route('kampus.prodi.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Prodi</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="kode_prodi" class="form-label">Kode Prodi</label>
                    <input type="text" name="kode_prodi" value="{{ old('kode_prodi') }}" class="form-control @error('kode_prodi') is-invalid @enderror" id="kode_prodi" aria-describedby="kode_prodi" />
                    @error('kode_prodi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Prodi</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" id="nama" aria-describedby="nama" />
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jenjang" class="form-label">jenjang</label>
                    <select name="jenjang" id="jenjang" class="form-select @error('jenjang') is-invalid @enderror">
                        <option value="" {{ $errors->get('jenjang') ? '' : 'selected' }}>Pilih jenjang</option>
                        @foreach ($jenjangs as $jenjang)
                            <option value="{{ $jenjang->id }}" {{ old('jenjang') == $jenjang->id ? 'selected' : '' }}>{{ ucwords($jenjang->nama) }}</option>
                        @endforeach
                    </select>
                    @error('jenjang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="masa_studi" class="form-label">Jenjang</label>
                    <small>(Tahun)</small>
                    <input type="text" name="masa_studi" value="{{ old('masa_studi') }}" class="form-control @error('masa_studi') is-invalid @enderror" id="masa_studi" aria-describedby="masa_studi" />
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
