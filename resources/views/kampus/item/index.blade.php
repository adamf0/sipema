@extends('layouts.app')

@section('page-title', 'Komponen Biaya')

@section('content')
<div class="d-flex justify-content-between mb-2">
    <a href="{{ route('kampus.item.create') }}" class="btn btn-primary">Tambah</a>
</div>
<div class="w-100 overflow-auto">
    <table id="example" class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            ajax: {
                url: '{{ route("kampus.item.index") }}',
                type: 'GET'
            },
            processing: true,
            serverSide: true,
            paging: true,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            columns: [
                {
                    data: 'id',
                    searchable: true
                },
                {
                    data: "nama",
                    name: 'nama',
                    searchable: true
                },
                {
                    data: "aksi",
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush