@extends('layouts.kampus')

@section('nama-kampus', $kampus->nama_kampus)

@section('page-title', 'Tambah MOU')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <form action="{{ route('detail-kampus.mou.store', ['kampus' => $kampus->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form MOU</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="no_mou" class="form-label">No. MOU</label>
                    <input type="text" name="no_mou" value="{{ old('no_mou') }}" class="form-control @error('no_mou') is-invalid @enderror" id="no_mou" aria-describedby="no_mou" />
                    @error('no_mou')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="max-reschedule" class="form-label">Max. Penjadwalan Ulang</label>
                    <input type="number" name="max_reschedule" id="max-reschedule" value="{{ old('max_reschedule') }}" aria-describedby="max-reschedule" class="form-control @error('max_reschedule') is-invalid @enderror" />
                    @error('max_reschedule')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="status_gelombang" {{ old('status_gelombang') ? 'checked' : '' }} value="1" id="status-gelombang" class="form-check-input @error('status_gelombang') is-invalid @enderror">
                    <label class="form-check-label" for="status-gelombang">
                        Status Gelombang
                    </label>
                    @error('status_gelombang')
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('#kampus').select2({
                theme: 'bootstrap-5'
            });

            $("#kampus + span").addClass("w-100");

            @error('kampus')
                $("#kampus + span").addClass("is-invalid");
            @enderror
        });
    </script>
@endpush
