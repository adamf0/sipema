@extends('layouts.app')

@section('page-title', 'Tambah Tahun Akademik')

@section('content')
    <form action="{{ route('kampus.tahun_akademik.store') }}" method="POST">
        <div class="card">
            <div class="card-header">Form Tahun Akademik</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Tahun Akademik</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Contoh 2021-01" />
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tanggal_ajaran_baru" class="form-label">Tangggal Mulai</label>
                    <input type="date" name="tanggal_ajaran_baru" value="{{ old('tanggal_ajaran_baru') }}" class="form-control @error('tanggal_ajaran_baru') is-invalid @enderror" id="tanggal_ajaran_baru" aria-describedby="tanggal_ajaran_baru" />
                    @error('tanggal_ajaran_baru')
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
    <script>
        @error('nama')
            $("input[name='nama']").val({{ old('nama') }});
        @enderror
        $("input[name='nama']").on("keyup change", function(){
            $("input[name='nama']").val(destroyMask(this.value));
            this.value = createMask($("input[name='nama']").val());
        })

        function createMask(string){
            return string.replace(/(\d{4})(\d{2})/,"$1-$2");
        }

        function destroyMask(string){
            return string.replace(/\D/g,'').substring(0,6);
        }
    </script>
@endpush