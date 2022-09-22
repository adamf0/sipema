@extends('layouts.app')

@section('page-title', 'MOU')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.mou.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>No. MOU</th>
                    <!-- <th>Kampus</th> -->
                    <th>Sharing Fee</th>
                    <th>Tanggal Dibuat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kampusMous as $kampusMou)
                    <tr>
                        <th>{{ $kampusMou->id }}</th>
                        <td>{{ $kampusMou->no_mou }}</td>
                        <td>{{ ($kampusMou->sharing_fee*100) }}%</td>
                        <td>{{ $kampusMou->tanggal_dibuat->format('d F Y') }}</td>
                        <td>
                            @if ($kampusMou->status==1)
                                <label class="badge bg-success">Aktif</label>
                            @else
                                <label class="badge bg-danger">Non-Aktif</label>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('kampus.mou.edit', ['kampus_mou' => $kampusMou->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('kampus.mou.destroy', ['kampus_mou' => $kampusMou->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                                @if ($kampusMou->status==0)
                                <a href="{{ route('kampus.mou.change', ['id' => $kampusMou->id]) }}" class="btn btn-primary btn-sm">Aktif</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">
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
