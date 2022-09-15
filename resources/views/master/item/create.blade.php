@extends('layouts.app')

@section('page-title', 'Tambah Master Item')

@section('content')
    <form action="{{ route('master.item.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Master Item</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="nama-master-item" class="form-label">Nama Master Item</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" id="nama-master-item" aria-describedby="nama-master-item" />
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
