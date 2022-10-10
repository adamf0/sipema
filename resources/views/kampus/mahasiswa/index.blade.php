@extends('layouts.app')

@section('page-title', 'Mahasiswa')

@section('content')
<div class="d-flex mb-2">
    <a href="{{ route('kampus.mahasiswa.create') }}" class="btn btn-primary">Tambah</a>
    <a href="{{ route('kampus.mahasiswa.rencana.create') }}" class="btn btn-secondary mx-2">Tambah Rincian Pembayaran</a>
</div>
<div class="w-100 overflow-auto">
    <table id="example" class="table table-responsive table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>NIM</th>
                <th>NIM Sementara</th>
                <th>Nama Lengkap</th>
                <th>Tanggal Lahir</th>
                <th>Jenis Kelamin</th>
                <th>Prodi</th>
                <th>Kelas</th>
                <th>Metode Belajar</th>
                <th>Lulusan</th>
                <th>Tanggal Pembayaran</th>
                <th>No. MOU</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection

@push('js')
<script>
    // function format ( d ) {
    //     return '<table>'+
    //         '<tr>'+
    //             '<td>Full name:</td>'+
    //             '<td>'+d.nama+'</td>'+
    //         '</tr>'+
    //         '<tr>'+
    //             '<td>Extra info:</td>'+
    //             '<td>And any further details here (images etc)...</td>'+
    //         '</tr>'+
    //     '</table>';
    // }

    $(document).ready(function() {
        let i = 1;
        var table = $('#example').DataTable({
            ajax: {
                url: '{{ route("kampus.mahasiswa.index") }}',
                type: 'POST',
                data: {
                    "id_kampus": <?php echo Session::get("id_kampus"); ?>
                }
            },
            processing: true,
            serverSide: true,
            columns: [{
                    data: null,
                    defaultContent: ''
                },
                {
                    data: "nim",
                    render: function(data) {
                        return data == null ? "-" : data;
                    }
                },
                {
                    data: "nim_sementara"
                },
                {
                    data: "nama_lengkap"
                },
                {
                    data: "tanggal_lahir"
                },
                {
                    data: "jenis_kelamin",
                    render: function(data) {
                        return data == 1 ? "Laki-laki" : "Perempuan";
                    }
                },
                {
                    data: "prodi",
                    render: function(data) {
                        return `
                        ${data.nama}
                        (${data.jenjang.nama})
                        `;
                    }
                },
                {
                    data: "kelas.nama"
                },
                {
                    data: "metode_belajar.nama"
                },
                {
                    data: "lulusan.nama"
                },
                {
                    data: "tanggal_pembayaran"
                },
                {
                    data: "kampusMou.no_mou",
                    render: function(data) {
                        return data == null ? 'Tidak Ditemukan' : data;
                    }
                },
                {
                    data: "aksi"
                }
            ]
            // "order": [[1, 'asc']]
        });

        // $('#example tbody').on('click', 'td.details-control', function () {
        //     var tr = $(this).closest('tr');
        //     var row = table.row( tr );

        //     if ( row.child.isShown() ) {
        //         row.child.hide();
        //         tr.removeClass('shown');
        //     }
        //     else {
        //         row.child( format(row.data()) ).show();
        //         tr.addClass('shown');
        //     }
        // } );
    });
</script>
@endpush