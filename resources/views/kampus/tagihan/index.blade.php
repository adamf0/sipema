@extends('layouts.app')

@section('page-title', 'Tagihan')

@section('content')
    <div class="d-flex justify-content-between mb-2">
        <div class="row">
            <div class="col-3">
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" id="tanggal" @if(isset($tanggal)) value="{{$tanggal}}" @endif/>
                </div>
            </div>
            <div class="col-3">
                <div class="mb-3">
                    <label class="form-label">mahasiswa</label>
                    <input type="text" name="tanggal" placeholder="Cari Mahasiswa..." class="form-control" id="mahasiswa" @if(isset($mahasiswa)) value="{{$mahasiswa}}" @endif/>
                </div>
            </div>
            <div class="col-3">
                <label class="form-label">Prodi</label>
                <div class="input-group mb-3">
                    <select class="form-select" name="prodi" id="prodi">
                        <option value="">Pilih Prodi</option>
                        @foreach ($prodis as $p)
                        <option value="{{$p->id}}" @if(isset($prodi) && $prodi==$p->id) selected @endif>{{$p->nama}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-3">
                <label class="form-label">Status</label>
                <div class="input-group mb-3">
                    <select class="form-select" name="status" id="status">
                        <option value="">Pilih Status</option>
                        <option value="1" @if(isset($status) && $status=="1") selected @endif>Sudah Bayar</option>
                        <option value="0" @if(isset($status) && $status=="0") selected @endif>Belum Bayar</option>
                    </select>
                    <button class="input-group-text" id="filter">Filter</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <!-- <a href="#" class="btn btn-primary mt-4">Tambah</a> -->
                </div>
            </div>
        </div>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nomor Transaksi</th>
                    <th>Tanggal</th>
                    <th>Mahasiswa</th>
                    <th>Prodi</th>
                    <th>Pembayaran</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kampusTagihans as $tagihan)
                    @foreach ($tagihan->tagihan_detail as $index => $detail)
                    @if ($index==0)
                    <tr>
                        <th rowspan="{{ $detail->count() }}">{{ $tagihan->id }}</th>
                        <td rowspan="{{ $detail->count() }}">{{ $tagihan->nomor_transaksi }}</td>
                        <td rowspan="{{ $detail->count() }}">{{ \Carbon\Carbon::parse($tagihan->tanggal)->format('j F Y') }}</td>
                        <td rowspan="{{ $detail->count() }}">{{ $tagihan->mahasiswa->nama_lengkap }}</td>
                        <td rowspan="{{ $detail->count() }}">{{ $tagihan->mahasiswa->prodi->nama }}</td>
                        <td>{{ $detail->rencana->item_bayar->item->nama }}</td>
                        <td rowspan="{{ $detail->count() }}">Rp {{ number_format(array_sum($detail->pluck('biaya')->toArray()), 2, ',', '.') }}</td>
                        <td rowspan="{{ $detail->count() }}">
                            @if ($tagihan->status==1)
                                <label class="badge bg-success">Sudah Bayar</label>
                            @else
                                <label class="badge bg-danger">Belum Bayar</label>
                            @endif
                        </td>
                    </tr>
                    @else
                        <tr>
                            <td>{{ $detail->rencana->item_bayar->item->nama }}</td>
                        </tr>
                    @endif
                    @endforeach
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
        {{ $kampusTagihans->links() }}
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="{{ asset('js/jquery.redirect.js') }}"></script>
    <script type="text/javascript">
        var tanggal = null;
        var mahasiswa = null;
        var prodi = null;
        var status = null;
        var data = {};

        $('#tanggal').on('change', function(e) {
            // $("#elementId :selected").text();
            tanggal = $(this).val();
            data.tanggal = tanggal;
        });
        $('#mahasiswa').on('change', function(e) {
            // $("#elementId :selected").text();
            mahasiswa = $(this).val();
            data.mahasiswa = mahasiswa;
        });
        $('#status').on('change', function(e) {
            // $("#elementId :selected").text();
            status = $(this).val();
            data.status = status;
        });
        $('#prodi').on('change', function(e) {
            // $("#elementId :selected").text();
            prodi = $(this).val();
            data.prodi = prodi;
        });
        $('#filter').click(function(e) {
            console.log(data);
            $.redirect("{{ route('kampus.tagihan.index') }}", data, 'POST');
        });
    </script>
@endpush