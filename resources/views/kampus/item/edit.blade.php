@extends('layouts.app')

@section('page-title', 'Edit Komponen Biaya : ' . $masterItem->nama)

@section('content')
    <form action="{{ route('kampus.item.update', ['master_item' => $masterItem->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form Komponen Biaya</div>
            <div class="card-body">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="nama-master-item" class="form-label">Nama Biaya</label>
                    <input type="text" name="nama" value="{{ old('nama', $masterItem->nama) }}" class="form-control @error('nama') is-invalid @enderror" id="nama-master-item" aria-describedby="nama-master-item" />
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
