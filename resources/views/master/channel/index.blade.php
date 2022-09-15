@extends('layouts.app')

@section('page-title', 'Master Channel Pembayaran')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('master.channel-pembayaran.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Logo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($masterChannelPembayarans as $masterChannelPembayaran)
                    <tr>
                        <th>{{ $masterChannelPembayaran->id }}</th>
                        <td>{{ $masterChannelPembayaran->nama }}</td>
                        <td>
                            @if (Storage::disk('public')->exists('images/master/channel_pembayaran/' . $masterChannelPembayaran->logo))
                                <img width="100px" src="{{ asset('storage/images/master/channel_pembayaran/' . $masterChannelPembayaran->logo) }}" class="img-fluid">
                            @else
                                <img src="#" alt="Tidak Ditemukan">
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('master.channel-pembayaran.edit', ['channel_pembayaran' => $masterChannelPembayaran->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('master.channel-pembayaran.destroy', ['channel_pembayaran' => $masterChannelPembayaran->id]) }}" method="post">
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
        {{ $masterChannelPembayarans->links() }}
    </div>
@endsection
