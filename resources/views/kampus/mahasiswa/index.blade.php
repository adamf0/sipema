@extends('layouts.app')

@section('page-title', 'Mahasiswa')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.mahasiswa.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>NIM</th>
                    <th>NIM Sementara</th>
                    <th>Nama Lengkap</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Prodi</th>
                    <th>Tanggal Pembayaran</th>
                    <th>No. MOU</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswas as $mahasiswa)
                    <tr>
                        <th>{{ $mahasiswa->id }}</th>
                        <td>{{ $mahasiswa->nim }}</td>
                        <td>{{ $mahasiswa->nim_sementara }}</td>
                        <td>{{ $mahasiswa->nama_lengkap }}</td>
                        <td>{{ $mahasiswa->tanggal_lahir }}</td>
                        <td>
                            @if ($mahasiswa->jenis_kelamin == '1')
                                Laki - Laki
                            @else
                                Perempuan
                            @endif
                        </td>
                        <td>{{ $mahasiswa->prodi->nama }}</td>
                        <td>{{ $mahasiswa->tanggal_pembayaran }}</td>
                        <td>{{ $mahasiswa->kampusMou->no_mou ?? 'Tidak Ditemukan' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kampus.mahasiswa.edit', ['mahasiswa' => $mahasiswa->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kampus.mahasiswa.destroy', ['mahasiswa' => $mahasiswa->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $mahasiswas->links() }}
    </div>
@endsection
