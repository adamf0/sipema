@extends('layouts.app')

@section('page-title', 'Jadwal Ulang Tagihan')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.jadwal_ulang.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Mahasiswa</th>
                    <th>File</th>
                    <th>Tanggal Diundur</th>
                    <th>Nomor Transaksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal_ulangs as $jadwal_ulang)
                    <tr>
                        <th>{{ $jadwal_ulang->id }}</th>
                        <td>{{ $jadwal_ulang->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $jadwal_ulang->dokumen }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal_ulang->tanggal_diundur)->format('j F Y') }}</td>
                        <td>
                            <ul>
                                @forelse ($jadwal_ulang->tagihan as $item)
                                    <li>{{ $item->nomor_transaksi }}</li>
                                @empty
                                    Tidak ada item
                                @endforelse
                            </ul>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kampus.jadwal_ulang.edit', ['jadwal_ulang' => $jadwal_ulang->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kampus.jadwal_ulang.destroy', ['jadwal_ulang' => $jadwal_ulang->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        
    </div>
@endsection
