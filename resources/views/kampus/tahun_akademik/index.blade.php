@extends('layouts.app')

@section('page-title', 'tahun akademik')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.tahun_akademik.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Tahun Akademik</th>
                    <th>Tanggal Ajaran Baru</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tahun_akademiks as $tahun_akademik)
                    <tr>
                        <th>{{ $tahun_akademik->id }}</th>
                        <td>{{ $tahun_akademik->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($tahun_akademik->tanggal_ajaran_baru)->format('d F Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kampus.tahun_akademik.edit', ['tahun_akademik' => $tahun_akademik->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kampus.tahun_akademik.destroy', ['tahun_akademik' => $tahun_akademik->id]) }}" method="post">
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
        {{ $tahun_akademiks->links() }}
    </div>
@endsection
