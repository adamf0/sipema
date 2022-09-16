@extends('layouts.app')

@section('page-title', 'Master Kampus')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('master.kampus.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Kode Kampus</th>
                    <th>Nama Kampus</th>
                    <th>Singkatan Kampus</th>
                    <th>Tahun Kerjasama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($masterKampuss as $masterKampus)
                    <tr>
                        <th>{{ $masterKampus->id }}</th>
                        <th>{{ $masterKampus->kode_kampus }}</th>
                        <td>{{ $masterKampus->nama_kampus }}</td>
                        <td>{{ $masterKampus->singkatan }}</td>
                        <td>{{ $masterKampus->tahun_kerjasama }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('detail-kampus.dashboard', ['kampus' => $masterKampus->id]) }}" class="btn btn-primary btn-sm">Detail</a>
                                <a href="{{ route('master.kampus.edit', ['master_kampus' => $masterKampus->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('master.kampus.destroy', ['master_kampus' => $masterKampus->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $masterKampuss->links() }}
    </div>
@endsection
