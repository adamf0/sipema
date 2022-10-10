@extends('layouts.app')

@section('page-title', 'Metode Belajar')

@section('content')
<div class="d-flex justify-content-between mb-2">
    <a href="{{ route('kampus.metode_belajar.create') }}" class="btn btn-primary">Tambah</a>
</div>
<div class="w-100 overflow-auto">
    <table id="example" class="table table-responsive table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Metode Belajar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody< /tbody>
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
                url: '{{ route("kampus.metode_belajar.index") }}',
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
                    data: "nama"
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