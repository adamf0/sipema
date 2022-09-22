@extends('layouts.app')

@section('page-title', 'Edit Mahasiswa : ' . $mahasiswa->nama_lengkap)

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <form action="{{ route('kampus.mahasiswa.update', ['kampus_mahasiswa' => $mahasiswa->id]) }}" method="POST">
        <div class="card">
            <div class="card-header">Form Mahasiswa</div>
            <div class="card-body">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="nim" class="form-label">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" class="form-control @error('nim') is-invalid @enderror" id="nim" aria-describedby="nim" />
                    @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama-lengkap-mahasiswa" class="form-label">Nama Mahasiswa</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama-lengkap-mahasiswa" aria-describedby="nama-lengkap-mahasiswa" />
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal-lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir) }}" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal-lahir" aria-describedby="tanggal-lahir" />
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jenis-kelamin" class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                        <option value="" {{ $errors->get('jenis_kelamin') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                        <option value="1" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == '1' ? 'selected' : '' }}>Laki - Laki</option>
                        <option value="2" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == '2' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="prodi" class="form-label">Prodi</label>
                    <select name="prodi" id="prodi" class="form-select @error('prodi') is-invalid @enderror">
                        <option value="" {{ $errors->get('prodi') ? '' : 'selected' }}>Pilih Prodi</option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('prodi', $mahasiswa->id_prodi) == $prodi->id ? 'selected' : '' }}>{{ ucwords($prodi->nama) }}</option>
                        @endforeach
                    </select>
                    @error('prodi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal-pembayaran" class="form-label">Tanggal Pembayaran</label>
                    <input type="date" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', $mahasiswa->tanggal_pembayaran) }}" class="form-control @error('tanggal_pembayaran') is-invalid @enderror" id="tanggal-pembayaran" aria-describedby="tanggal-pembayaran" />
                    @error('tanggal_pembayaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @dd($itemBayars)
                <div class="mb-3">
                    @foreach ($itemBayars as $index => $group)
                        @foreach ($group as $loop => $itemBayar)
                            @if ($loop->index==0)
                            <label for="{{$itemBayar->item->nama}}" class="form-label">{{$itemBayar->item->nama}}</label>
                            <select name="item_bayar_selected[{{ $index }}]" id="item_bayar_selected_{{$index}}" class="form-select @error('item_bayar_selected.*') is-invalid @enderror">
                                <option value="" {{ $errors->get('item_bayar_selected') ? '' : 'selected' }}>Pilih Jumlah Angsuran</option>
                            @endif
                            <option value="{{$itemBayar->id}}" {{ old('item_bayar_selected.*', $mahasiswa->item_bayar_selected[$index]) == $itemBayar->id ? 'selected' : '' }}>x{{$itemBayar->jumlah_angsuran}}</option>

                            <script>console.log(<?php echo json_encode($mahasiswa->item_bayar_selected); ?>);</script>
                            <script>console.log(<?php echo json_encode($itemBayar->id); ?>);</script>                                
                        @endforeach
                        </select>
                        @error('item_bayar_selected.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endforeach
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
            @foreach ($itemBayars as $index => $group)
                $('#item_bayar_selected_{{$index}}').select2({
                    theme: 'bootstrap-5'
                });

                $("#item_bayar_selected_{{$index}} + span").addClass("w-100");

                @error('item_bayar_selected.*')
                    $("#item_bayar_selected_{{$index}} + span").addClass("is-invalid");
                @enderror
            @endforeach
        });
    </script>
@endpush
