@extends('layouts.app')

@section('page-title', 'Tambah Rencana Pembayaran')

@section('content')
    <form action="" method="POST">
        <div class="card">
            <div class="card-header">Form Rencana Pembayaran</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="mahasiswa" class="form-label">Mahasiswa</label>
                    <select name="mahasiswa" id="mahasiswa" class="form-select">
                        <option value="">Pilih Mahasiswa</option>
                        @foreach ($mahasiswas as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}">{{ ucwords($mahasiswa->nama) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="item" class="form-label">Komponen Biaya</label>
                    <select name="item" id="item" class="form-select">
                        <option value="">Pilih Komponen Biaya</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ ucwords($item->nama) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
@endsection
