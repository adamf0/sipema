@extends('layouts.kampus')

@section('nama-kampus', $kampus->nama_kampus)

@section('page-title', 'Gelombang')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('detail-kampus.gelombang.create', ['kampus' => $kampus->id]) }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Gelombang</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Akhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($gelombangs as $gelombang)
                    <tr>
                        <th>{{ $gelombang->id }}</th>
                        <td>{{ $gelombang->nama_gelombang }}</td>
                        <td>{{ \Carbon\Carbon::parse($gelombang->tanggal_mulai)->format('d F Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($gelombang->tanggal_akhir)->format('d F Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('detail-kampus.gelombang.edit', ['kampus' => $kampus->id, 'gelombang' => $gelombang->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('detail-kampus.gelombang.destroy', ['kampus' => $kampus->id, 'gelombang' => $gelombang->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $gelombangs->links() }}
    </div>
@endsection
