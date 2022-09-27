@extends('layouts.app')

@section('page-title', 'Pelaporan')

@section('content')
    <div class="row mb-2">
        <div class="col-12 col-lg-4 pb-3">
            <div class="card">
                <div class="card-body">
                    <div class="fw-semibold">
                        Total yang harus ditagih
                    </div>
                    <div>
                        @php
                            $harus_ditagih = App\KampusTagihanDetail::whereHas('tagihan', function ($query) {
                                return $query->where('status', 0);
                            })->sum('biaya');
                        @endphp
                        Rp. {{ number_format($harus_ditagih, 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 pb-3">
            <div class="card">
                <div class="card-body">
                    <div class="fw-semibold">
                        Jumlah Tagihan
                    </div>
                    <div>
                        {{ App\KampusTagihan::count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 pb-3">
            <div class="card">
                <div class="card-body">
                    <div class="fw-semibold">
                        Ekspetasi
                    </div>
                    <div>
                        Rp. {{ number_format(App\KampusTagihanDetail::all()->sum('biaya'), 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="fw-semibold">
                Tagihan
            </div>
            <hr>
            <div class="mb-3">
                <button class="btn btn-sm btn-success">Excel</button>
            </div>
            <div class="w-100 overflow-auto">
                <table class="table table-responsive table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nomor Transaksi</th>
                            <th>Nama Mahasiswa</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelaporans as $pelaporan)
                            <tr>
                                <td>{{ $pelaporan->id }}</td>
                                <td>{{ $pelaporan->nomor_transaksi }}</td>
                                <td>{{ $pelaporan->mahasiswa->nama_lengkap }}</td>
                                <td>
                                    @if ($pelaporan->status)
                                        <span class="badge text-bg-success">Sudah</span>
                                    @else
                                        <span class="badge text-bg-danger">Belum</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($pelaporan->tagihan_detail->count())
                                        <a href="{{ route('kampus.pelaporan.show', ['pelaporan' => $pelaporan->id]) }}" target="_blank" rel="noopener noreferrer">
                                            <button class="btn btn-sm btn-primary">Detail</button>
                                        </a>
                                    @else
                                        <button disabled class="btn btn-sm btn-secondary">Detail</button>
                                    @endif
                                </td>

                                {{-- @foreach ($pelaporan->tagihan_detail[$loop->index] as $detail)
                                    <td>{{ $detail->rencana->nama }}</td>
                                    <td>{{ $detail->biaya }}</td>
                                @endforeach --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="1" class="text-center">
                                    Data Kosong
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $pelaporans->links() }}
            </div>
        </div>
    </div>
@endsection
