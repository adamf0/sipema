@extends('layouts.app')

@section('page-title', 'Tambah Metode Pembayaran')

@section('content')
    <form action="{{ route('kampus.pembayaran.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Metode Pembayaran Kampus</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="id_chanel_pembayaran" class="form-label">
                        Chanel Pembayaran
                    </label>
                    <select class="form-select @error('id_chanel_pembayaran') is-invalid @enderror" name="id_chanel_pembayaran" id="id_chanel_pembayaran">
                        <option value="" {{ $errors->get('id_chanel_pembayaran') ? '' : 'selected' }}>Pilih Chanel Pembayaran</option>
                        @foreach ($chanel_pembayarans as $chanel_pembayaran)
                            <option value="{{ $chanel_pembayaran->id }}" {{ old('chanel_pembayaran') == $chanel_pembayaran->id ? 'selected' : '' }}>{{ ucwords($chanel_pembayaran->nama) }}</option>
                        @endforeach
                    </select>
                    @error('id_chanel_pembayaran')
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
