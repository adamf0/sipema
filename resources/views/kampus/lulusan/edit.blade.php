@extends('layouts.app')

@section('page-title', 'Edit lulusan : ' . $lulusan->nama)

@section('content')
    <form action="{{ route('kampus.lulusan.update', ['kampus_lulusan' => $lulusan->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form Metode Belajar</div>
            <div class="card-body">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="nama" class="form-label">
                        Nama Lulusan
                    </label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama',$lulusan->nama) }}" name="nama" id="nama">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jenjang" class="form-label">Prasyarat Jenjang</label>
                    <select name="jenjang" id="jenjang" class="form-select">
                        <option value="">Pilih jenjang</option>
                        @foreach ($jenjangs as $jenjang)
                            <option value="{{ $jenjang->id }}" {{ old('jenjang',$lulusan->prasyarat_jenjang)==$jenjang->id ? 'selected' : '' }}>{{ ucwords($jenjang->nama) }}</option>
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
