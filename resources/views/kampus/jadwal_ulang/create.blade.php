@extends('layouts.app')

@section('page-title', 'Tambah Jadwal Ulang Tagihan')

@section('content')
    <form action="{{ route('kampus.jadwal_ulang.store') }}" method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header">Form Jadwal Ulang Tagihan</div>
            <div class="card-body">
                @csrf
                <div class="mb-3">
                    <label for="id_mahasiswa" class="form-label">Mahasiswa</label>
                    <select name="id_mahasiswa" id="id_mahasiswa" class="form-select @error('id_mahasiswa') is-invalid @enderror">
                        <option value="" {{ $errors->get('id_mahasiswa') ? '' : 'selected' }}>Pilih Mahasiswa</option>
                        @foreach ($mahasiswas as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}" {{ old('id_mahasiswa') == $mahasiswa->id ? 'selected' : '' }}>{{ $mahasiswa->nama_lengkap }}</option>
                        @endforeach
                    </select>
                    @error('id_mahasiswa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="dokumen" class="form-label">Dokumen</label>
                    <input type="file" name="dokumen" value="{{ old('dokumen') }}" class="form-control @if ($errors->any()) is-invalid @endif" id="dokumen" aria-describedby="dokumen" />
                    @if ($errors->any())
                        <div class="invalid-feedback">Terjadi Kesalahan pada Form,dokumen harus di inputkan kembali</div>
                    @endif

                    @error('dokumen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tanggal_diundur" class="form-label">Tanggal Diundur</label>
                    <input type="date" name="tanggal_diundur" value="{{ old('tanggal_diundur') }}" class="form-control @error('tanggal_diundur') is-invalid @enderror" id="tanggal_diundur" aria-describedby="tanggal_diundur" />
                    @error('tanggal_diundur')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="item_tagihan_selected" class="form-label @error('tanggal_diundur') is-invalid @enderror">Tagihan</label>
                    @error('id_tagihan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <table class="table table-responsive table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nomor Transaksi</th>
                                <th>Tanggal</th>
                                <th>Tagihan</th>
                                <th>Biaya</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tagihans as $tagihan)
                                @foreach ($tagihan->tagihan_detail as $index => $detail)
                                    <tr>
                                        @if ($tagihan->tagihan_detail->count()>=1 && $index==0)
                                        <td rowspan="{{ $tagihan->tagihan_detail->count() }}"><input type="checkbox" name="id_tagihan[]" value="{{$tagihan->id}}" {{ ($tagihan->status==1)? "disabled":"" }}></td>
                                        <td rowspan="{{ $tagihan->tagihan_detail->count() }}">{{$tagihan->nomor_transaksi}}</td>
                                        <td rowspan="{{ $tagihan->tagihan_detail->count() }}">{{ \Carbon\Carbon::parse($tagihan->tanggal)->format('j F Y') }}</td>
                                        @endif                                        
                                        <td>{{ $detail->rencana->item_bayar->item->nama }}</td>
                                        <td>{{ "Rp ".number_format($detail->biaya, 0, ",", ".") }}</td>
                                        @if ($tagihan->tagihan_detail->count()>=1 && $index==0)
                                        <td rowspan="{{ $tagihan->tagihan_detail->count() }}">{{ "Rp ".number_format(array_sum( $tagihan->tagihan_detail->pluck('biaya')->toArray() ), 0, ",", ".") }}</td>
                                        <td rowspan="{{ $tagihan->tagihan_detail->count() }}">
                                            @switch($tagihan->status)
                                                @case(0)
                                                    <label class="badge bg-warning">Menunggu Pembayaran</label>
                                                    @break
                                                @case(1)
                                                    <label class="badge bg-success">Sudah Bayar</label>
                                                    @break
                                                @case(-1)
                                                    <label class="badge bg-secondary">Jadwal Ulang</label>
                                                    @break
                                                @default
                                                    
                                            @endswitch
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @empty
                                Tidak ada tagihan
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </form>
@endsection
