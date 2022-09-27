@extends('layouts.app')

@section('page-title', 'Detail Tagihan : ' . $pelaporan->nomor_transaksi)

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="fw-semibold">
                Detail Tagihan
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
                            <th>Nama Tagihan</th>
                            <th>Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detail_tagihans as $detail_tagihan)
                            <tr>
                                <td>{{ $detail_tagihan->id }}</td>
                                <td>{{ $detail_tagihan->rencana->nama }}</td>
                                <td>{{ 'Rp. ' . number_format($detail_tagihan->biaya, 2, ',', '.') }}</td>
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
                {{ $detail_tagihans->links() }}
            </div>
        </div>
    </div>
@endsection
