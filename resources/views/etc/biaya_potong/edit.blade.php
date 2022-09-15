@extends('layouts.app')

@section('page-title', 'Edit Biaya Potong : ' . $biaya_potong->nama_beasiwa)

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <form action="{{ route('biaya-potongan.update', ['biaya_potong' => $biaya_potong->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form Biaya Potong</div>
            <div class="card-body">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label for="nama_beasiswa" class="form-label">Nama Potongan</label>
                    <input type="text" name="nama_beasiswa" value="{{ old('nama_beasiswa',$biaya_potong->nama_beasiswa) }}" class="form-control @error('nama_beasiswa') is-invalid @enderror" id="nama_beasiswa" aria-describedby="nama_beasiswa" />
                    @error('nama_beasiswa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="persentase_potongan" class="form-label">Persentase Potongan</label>
                    <input type="number" name="persentase_potongan" value="{{ old('persentase_potongan',($biaya_potong->persentase_potongan*100)) }}" class="form-control @error('persentase_potongan') is-invalid @enderror" id="persentase_potongan" aria-describedby="persentase_potongan" />
                    @error('persentase_potongan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="id_item" class="form-label">
                        Nama Item
                    </label>
                    <select class="form-select @error('id_item') is-invalid @enderror" name="id_item" id="id_item">
                        <option value="" {{ $errors->get('id_item') ? '' : 'selected' }}>Pilih Item</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ old('id_item',$biaya_potong->id_item) == $item->id ? 'selected' : '' }}>{{ ucwords($item->nama) }}</option>
                        @endforeach
                    </select>
                    @error('id_item')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_berlaku" class="form-label">Tanggal Berlaku</label>
                    <input type="date" name="tanggal_berlaku" value="{{ old('tanggal_berlaku',Carbon\Carbon::parse($biaya_potong->tanggal_berlaku)->format('Y-m-d') ) }}" class="form-control @error('tanggal_berlaku') is-invalid @enderror" id="tanggal_berlaku" aria-describedby="tanggal_berlaku" />
                    @error('tanggal_berlaku')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_berakhir" class="form-label">Tanggal Berakhir</label>
                    <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir',Carbon\Carbon::parse($biaya_potong->tanggal_berakhir)->format('Y-m-d') ) }}" class="form-control @error('tanggal_berakhir') is-invalid @enderror" id="tanggal_berakhir" aria-describedby="tanggal_berakhir" />
                    @error('tanggal_berakhir')
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
            $('#id_item').select2({
                theme: 'bootstrap-5'
            });

            $("#id_item + span").addClass("w-100");

            @error('id_item')
                $("#id_item + span").addClass("is-invalid");
            @enderror
        });
    </script>
@endpush
