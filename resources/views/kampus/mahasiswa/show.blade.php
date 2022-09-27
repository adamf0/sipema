@extends('layouts.app')

@section('page-title', 'Rencana Pembayaran Mahasiswa : ' . $mahasiswa->nama_lengkap)

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://www.jqueryscript.net/demo/Merge-Cells-HTML-Table/jquery.table.marge.js"></script>
    <script src="{{ asset('js/jquery.redirect.js') }}"></script>
    <script>
        let id_rencana_mahasiswa = [];
        let mahasiswa = {{ $mahasiswa->id }};

        function disabledClass(element,disabled){
            if(!disabled){
                element.removeClass("disabled");
            }   
            else{
                element.addClass("disabled");
            } 
        }

        $('#bulanan').margetable({
            type: 2,
            colindex: [1,4]
        });
        $('#nonbulanan').margetable({
            type: 2,
            colindex: [0,1]
        });

        $('#AllRencanaBulan').click(function(event) {   
            if(this.checked) {
                $('.rencana_bulan').each(function() {
                    this.checked = true;
                    id_rencana_mahasiswa.push(this.value);                        
                });
            } else {
                $('.rencana_bulan').each(function() {
                    this.checked = false;  
                    id_rencana_mahasiswa.splice(id_rencana_mahasiswa.indexOf(this.value), 1);     
                });
            }
            disabledClass($('.btn-hapus'),id_rencana_mahasiswa.length==0);
        });
        $('#AllRencanaNonBulan').click(function(event) {   
            if(this.checked) {
                $('.rencana_nonbulan').each(function() {
                    this.checked = true;
                    id_rencana_mahasiswa.push(this.value);
                });
            } else {
                $('.rencana_nonbulan').each(function() {
                    this.checked = false;  
                    id_rencana_mahasiswa.splice(id_rencana_mahasiswa.indexOf(this.value), 1);
                });
            }
            disabledClass($('.btn-hapus'),id_rencana_mahasiswa.length==0);
        });
        $('input[type=checkbox]').on('change',function() {   
            if(parseInt(this.value)!=NaN){
                if(this.checked){
                    id_rencana_mahasiswa.push(this.value);
                }
                else{
                    id_rencana_mahasiswa.splice(id_rencana_mahasiswa.indexOf(this.value), 1);
                }
            }
            disabledClass($('.btn-hapus'),id_rencana_mahasiswa.length==0);
            console.log(id_rencana_mahasiswa);
        });
        $('.btn-hapus').click(function() {
            $.redirect("{{ route('kampus.mahasiswa.rencana.destroy') }}", {'id_rencana_mahasiswa':id_rencana_mahasiswa,'id_mahasiswa':mahasiswa}, 'POST');
        });
    </script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 mb-3">
        <a href="#" class="btn btn-warning">Reschedule</a>
        <a href="#" class="btn btn-danger btn-hapus disabled">Hapus</a>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Rencana Pembayaran Mahasiswa</div>
            <div class="card-body">
                <div class="overflow-auto">
                    <table class="table table-responsive table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="AllRencanaBulan"></th>
                                <th>Tanggal</th>
                                <th>Pembayaran</th>
                                <th>Semester</th>
                                <th>Biaya</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i=0; @endphp
                            @foreach ($bulanans as $key_group => $item_group)
                                @foreach ($item_group as $index => $rencana)
                                @php
                                    $date1 = strtotime($key_group);
                                    $date2 = strtotime(date('Y-m-d'));
                                    $diff = $date1-$date2;
                                @endphp
                                <tr>
                                    <td><input type="checkbox" name="id_rencana_mahasiswa[]" value="{{ $rencana->id }}" class="rencana_bulan"></td>
                                    @if (count($item_group)>=1 && $index==0)
                                        <td rowspan="{{ count($item_group) }}">{{ \Carbon\Carbon::parse($rencana->tanggal_bayar)->format('j F Y') }}</td>
                                    @endif                            
                                    <td>{{ $rencana->item_bayar->item->nama }}</td>
                                    <td>{{ "Rp ".number_format($rencana->biaya, 0, ",", ".") }}</td>
                                    <td>{{ $rencana->nama }}</td>
                                    <td>Semester {{ floor($i/$max_cicilan)+1 }}</td>
                                    <td>
                                        @if ($rencana->status==0 && $diff==0)
                                        <label class="badge bg-warning">Menunggu Pembayaran</label>
                                        @elseif ($rencana->status==0 && $diff < 0)
                                        <label class="badge bg-danger">Belum Bayar</label>
                                        @elseif ($rencana->status==0 && $diff > 0)
                                        <label class="badge bg-secondary">Belum Ada Tagihan</label>
                                        @elseif ($rencana->status==1)
                                        <label class="badge bg-success">Sudah Bayar</label>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @php $i++ @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Pembayaran Non-Bulanan</div>
            <div class="card-body">
                <div class="overflow-auto">
                    <table class="table table-responsive table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="AllRencanaNonBulan"></th>
                                <th>Pembayaran</th>
                                <th>Biaya</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($non_bulanans as $index => $item)
                                <tr>
                                    <td><input type="checkbox" name="id_rencana_mahasiswa[]" value="{{ $item->id }}" class="rencana_nonbulan"></td>
                                    <td>{{ $item->item_bayar->item->nama }}</td>
                                    <td>{{ "Rp ".number_format($item->biaya, 0, ",", ".") }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>
                                        @if ($item->status==0)
                                        <label class="badge bg-danger">Belum Bayar</label>
                                        @else
                                        <label class="badge bg-success">Sudah Bayar</label>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection