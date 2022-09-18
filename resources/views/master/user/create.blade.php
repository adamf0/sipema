@extends('layouts.app')

@section('page-title', 'Tambah Master User')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
    <form action="{{ route('master.user.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Master User</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Pengguna</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" id="nama" aria-describedby="nama" />
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="email" aria-describedby="email" />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" id="password" aria-describedby="password" />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Level</label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                        <option value="">-- Pilih Level --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ ucwords($role->name) }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 panel-kampus">
                    <label for="kampus" class="form-label">Kampus</label>
                    <select name="kampus[]" id="kampus" class="form-select @error('kampus') is-invalid @enderror" multiple>
                        @foreach ($kampuss as $kampus)
                            <option value="{{ $kampus->id }}" {{ old('kampus') == $kampus->id ? 'selected' : '' }}>{{ ucwords($kampus->nama_kampus) }}</option>
                        @endforeach
                    </select>
                    @error('kampus')
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
            $('.panel-kampus').hide();

            $('#kampus').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Kampus"
            });

            $('#role').change(function(){
                if($(this).val()=="Admin"){
                    $('.panel-kampus').hide();
                }
                else{
                    $('.panel-kampus').show();
                }
            });

            $("#kampus + span").addClass("w-100");

            @error('kampus')
                $("#kampus + span").addClass("is-invalid");
            @enderror
        });
    </script>
@endpush