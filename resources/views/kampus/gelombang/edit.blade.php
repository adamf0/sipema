@extends('layouts.app')

@section('page-title', 'Edit Gelombang : ' . $gelombang->nama_gelombang)

@section('content')
    <form action="{{ route('kampus.gelombang.update', ['gelombang' => $gelombang->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form Prodi</div>
            <div class="card-body">
                @csrf
                @method('PATCH')
                
                <div class="mb-3">
                    <label for="tahun_akademik" class="form-label">
                        Tahun Akademik
                    </label>
                    <select class="form-select @error('tahun_akademik') is-invalid @enderror" id="tahun_akademik" name="tahun_akademik" id="tahun_akademik">
                        <option value="">Pilih Tahun Akademik</option>
                        @foreach ($tahun_akademiks as $tahun_akademik)
                            <option value="{{ $tahun_akademik->id }}" {{ old('tahun_akademik',$gelombang->id_tahun_akademik)==$tahun_akademik->id? "selected":"" }}>{{ ucwords($tahun_akademik->nama) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nama_gelombang" class="form-label">Nama Gelombang</label>
                    <input type="text" name="nama_gelombang" value="{{ old('nama_gelombang',$gelombang->nama_gelombang) }}" class="form-control @error('nama_gelombang') is-invalid @enderror" id="nama_gelombang" aria-describedby="nama_gelombang" />
                    @error('nama_gelombang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tanggal_mulai" class="form-label">Tangggal Mulai</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai',$gelombang->tanggal_mulai) }}" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" aria-describedby="tanggal_mulai" />
                    @error('tanggal_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_akhir" class="form-label">tanggal_akhir</label>
                    <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir',$gelombang->tanggal_akhir) }}" class="form-control @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" aria-describedby="tanggal_akhir" />
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
