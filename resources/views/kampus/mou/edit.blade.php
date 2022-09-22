@extends('layouts.app')

@section('page-title', 'Edit MOU : ' . $kampusMou->nama)

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <form action="{{ route('kampus.mou.update', ['kampus_mou' => $kampusMou->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form MOU</div>
            <div class="card-body">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="no_mou" class="form-label">No. MOU</label>
                    <input type="text" name="no_mou" value="{{ old('no_mou', $kampusMou->no_mou) }}" class="form-control @error('no_mou') is-invalid @enderror" id="no_mou" aria-describedby="no_mou" />
                    @error('no_mou')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="sharing_fee" class="form-label">Sharing Fee</label>
                    <input type="number" name="sharing_fee" step="0.01" min="0.01" max="1" id="sharing_fee" value="{{ old('sharing_fee', $kampusMou->sharing_fee) }}" aria-describedby="sharing_fee" class="form-control @error('sharing_fee') is-invalid @enderror" />
                    @error('sharing_fee')
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

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
@endpush
