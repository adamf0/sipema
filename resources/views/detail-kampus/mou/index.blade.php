@extends('layouts.kampus')

@section('nama-kampus', $kampus->nama_kampus)

@section('page-title', 'MOU')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('detail-kampus.mou.create', ['kampus' => $kampus->id]) }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>No. MOU</th>
                    <th>Status Gelombang</th>
                    <th>Max Reschedule</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kampusMous as $kampusMou)
                    <tr>
                        <th>{{ $kampusMou->id }}</th>
                        <td>{{ $kampusMou->no_mou }}</td>
                        <td>
                            <div class="badge bg-{{ $kampusMou->status_gelombang ? 'success' : 'danger' }}">
                                {{ $kampusMou->status_gelombang ? 'Aktif' : 'Nonaktif' }}
                            </div>
                        </td>
                        <td>{{ $kampusMou->max_reschedule }}</td>
                        <td>{{ $kampusMou->tanggal_dibuat->format('d F Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('detail-kampus.mou.edit', ['kampus' => $kampus->id, 'mou' => $kampusMou->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('detail-kampus.mou.destroy', ['kampus' => $kampus->id, 'mou' => $kampusMou->id]) }}" method="post">
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
        {{ $kampusMous->links() }}
    </div>
@endsection
