@extends('layouts.app')

@section('page-title', 'MOU')

@section('content')
<div class="d-flex justify-content-between mb-2">
    <a href="{{ route('kampus.mou.create') }}" class="btn btn-primary">Tambah</a>
</div>
<div class="w-100 overflow-auto">
    <table id="example" class="table table-responsive table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>No. MOU</th>
                <th>Sharing Fee</th>
                <th>Tanggal Dibuat</th>
                <th>Status</th>
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
                url: '{{ route("kampus.mou.index") }}',
                type: 'POST',
                data: {
                    id_kampus: <?php echo Session::get("id_kampus"); ?>
                }
            },
            processing: true,
            serverSide: true,
            columns: [{
                    data: null,
                    defaultContent: ''
                },
                {
                    data: "no_mou"
                },
                {
                    data: "sharing_fee",
                    render: function(data) {
                        return `${data*100}%`;
                    }
                },
                {
                    data: "tanggal_dibuat"
                },
                {
                    data: "status",
                    render: function(data) {
                        if (data == 1)
                            return '<label class="badge bg-success">Aktif</label>';
                        else
                            return '<label class="badge bg-danger">Non-Aktif</label>';
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