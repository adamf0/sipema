@extends('layouts.app')

@section('page-title', 'gelombang')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.gelombang.create') }}" class="btn btn-primary">Tambah</a>
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
                                <a href="{{ route('kampus.gelombang.edit', ['gelombang' => $gelombang->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kampus.gelombang.destroy', ['gelombang' => $gelombang->id]) }}" method="post">
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
        {{ $gelombangs->links() }}
    </div>
@endsection
