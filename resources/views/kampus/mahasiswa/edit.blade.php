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
                    <option value="{{ $prodi->id }}" {{ old('prodi', $mahasiswa->id_prodi) == $prodi->id ? 'selected' : '' }}>{{ ucwords($prodi->nama) }} ({{ ucwords($prodi->jenjang->nama) }})</option>
                    @endforeach
                </select>
                @error('prodi')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="kelas" class="form-label">Kelas</label>
                <select name="kelas" id="kelas" class="form-select @error('kelas') is-invalid @enderror">
                    <option value="" {{ $errors->get('kelas') ? '' : 'selected' }}>Pilih Kelas</option>
                    @foreach ($kelass as $kelas)
                    <option value="{{ $kelas->id }}" {{ old('kelas', $mahasiswa->id_kelas) == $kelas->id ? 'selected' : '' }}>{{ ucwords($kelas->nama) }}</option>
                    @endforeach
                </select>
                @error('kelas')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="metode_belajar" class="form-label">Metode Belajar</label>
                <select name="metode_belajar" id="metode_belajar" class="form-select @error('metode_belajar') is-invalid @enderror">
                    <option value="" {{ $errors->get('metode_belajar') ? '' : 'selected' }}>Pilih Metode Belajar</option>
                    @foreach ($metode_belajars as $metode_belajar)
                    <option value="{{ $metode_belajar->id }}" {{ old('metode_belajar', $mahasiswa->id_metode_belajar) == $metode_belajar->id ? 'selected' : '' }}>{{ ucwords($metode_belajar->nama) }}</option>
                    @endforeach
                </select>
                @error('metode_belajar')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="lulusan" class="form-label">Lulusan</label>
                <select name="lulusan" id="lulusan" class="form-select @error('lulusan') is-invalid @enderror">
                    <option value="" {{ $errors->get('lulusan') ? '' : 'selected' }}>Pilih Lulusan</option>
                    @foreach ($lulusans as $lulusan)
                    <option value="{{ $lulusan->id }}" {{ old('lulusan', $mahasiswa->id_lulusan) == $lulusan->id ? 'selected' : '' }}>{{ ucwords($lulusan->nama) }}</option>
                    @endforeach
                </select>
                @error('lulusan')
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
            @foreach ($items as $index=> $item)
            <div class="mb-3">
                <label for="" class="form-label">{{ $item }}</label>
                <select name="item_bayar_selected[{{$index}}]" id="item_bayar_selected_{{$index}}" class="form-select">
                    <option value="">Pilih Jumlah Angsuran</option>
                </select>
            </div>
            @endforeach
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
    $(document).ready(function() {
        var selected = [];
        @foreach($mahasiswa->item_bayar_selected as $select)
        selected.push(<?php echo $select ?>);
        @endforeach

        var prodi = <?php echo old('prodi', $mahasiswa->id_prodi) ?>;
        var kelas = <?php echo old('prodi', $mahasiswa->id_kelas) ?>;
        var metode_belajar = <?php echo old('prodi', $mahasiswa->id_metode_belajar) ?>;
        var lulusan = <?php echo old('prodi', $mahasiswa->id_lulusan) ?>;
        var kampus = <?php echo Session::has('id_kampus') ? Session::get('id_kampus') : null ?>;

        function init_dropdown(element, isfirst) {
            $(element).select2({
                theme: 'bootstrap-5'
            });
            if (isfirst == 0) {
                $(element + ' + span').addClass("w-100");
            }
        }

        function normalizeValue(val) {
            return val == "" || val == "null" || val == null || val == undefined ? null : val
        }

        function callDataItems() {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{ route('getData') }}",
                    type: 'POST',
                    // contentType: "application/json",
                    dataType: "json",
                    data: {
                        id_kampus: kampus,
                        id_prodi: prodi,
                        id_kelas: kelas,
                        id_metode_belajar: metode_belajar,
                        id_lulusan: lulusan,
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            resolve(response.data)
                        } else {
                            reject(response.error)
                        }
                    },
                    error: function(xhr, status, error) {
                        if (status == "error") {
                            reject('Something wrong in the system')
                        } else if (status == "timeout") {
                            reject('Connection seems dead!')
                        } else {
                            reject(JSON.stringify(error));
                        }
                    },
                })
            })
        }

        function getDataItems() {
            if (prodi != null && kelas != null && metode_belajar != null && lulusan != null && kelas != null) {
                callDataItems().then((data) => {
                    console.log(data)
                    if (data.length > 0) {
                        data.forEach((element, index) => {
                            $("#item_bayar_selected_" + index).empty();
                            $("#item_bayar_selected_" + index).append("<option value=''>-- Pilih Jumlah Angsuran " + element[0].item.nama + " --</option>");
                            $("#item_bayar_selected_" + index).select2({
                                data: element
                            });
                            init_dropdown("#item_bayar_selected_" + index, 1);
                            $("#item_bayar_selected_" + index).val(selected[index] == null ? '' : selected[index]).trigger('change');
                        });
                    } else {
                        @foreach($items as $index => $item)
                        $("#item_bayar_selected_{{$index}}").empty();
                        $("#item_bayar_selected_{{$index}}").append("<option>-- Pilih Jumlah Angsuran {{$item}} --</option>");
                        @endforeach
                    }
                }).catch((error) => {
                    alert(error);
                    @foreach($items as $index => $item)
                    $("#item_bayar_selected_{{$index}}").empty();
                    $("#item_bayar_selected_{{$index}}").append("<option value=''>-- Pilih Jumlah Angsuran {{$item}} --</option>");
                    @endforeach
                })
            } else {
                console.log(`prodi: ${prodi} kelas: ${kelas} metode_belajar: ${metode_belajar} lulusan: ${lulusan} kampus: ${kampus}`);
            }
        }

        getDataItems();

        $('#prodi').on('change', function() {
            prodi = normalizeValue($(this).val());
            getDataItems();
        });
        $('#kelas').on('change', function() {
            kelas = normalizeValue($(this).val());
            getDataItems();
        });
        $('#metode_belajar').on('change', function() {
            metode_belajar = normalizeValue($(this).val());
            getDataItems();
        });
        $('#lulusan').on('change', function() {
            lulusan = normalizeValue($(this).val());
            getDataItems();
        });
        @foreach($items as $index => $item)
        init_dropdown("#item_bayar_selected_{{$index}}", 0);
        @endforeach

    });
</script>
@endpush