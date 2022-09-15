@extends('layouts.app')

@section('page-title', 'pembayaran')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.pembayaran.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Chanel Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kampusPembayarans as $kampusPembayaran)
                    <tr>
                        <th>{{ $kampusPembayaran->id }}</th>
                        <td>{{ $kampusPembayaran->chanel_pembayaran->nama }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kampus.pembayaran.edit', ['pembayaran' => $kampusPembayaran->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kampus.pembayaran.destroy', ['pembayaran' => $kampusPembayaran->id]) }}" method="post">
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
        {{ $kampusPembayarans->links() }}
    </div>
@endsection
