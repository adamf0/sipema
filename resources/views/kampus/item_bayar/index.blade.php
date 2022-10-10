@extends('layouts.app')

@section('page-title', 'Rincian Biaya')


    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <!-- <script src="https://www.jqueryscript.net/demo/Merge-Cells-HTML-Table/jquery.table.marge.js"></script>
    <script>
        $('#textTable').margetable({
            type: 2,
            colindex: [0,1,2,3,4,5,6]
        });
    </script> -->


@section('content')
    <div class="d-flex justify-content-between mb-2">
        <a href="{{ route('kampus.item-bayar.create') }}" class="btn btn-primary">Tambah</a>
    </div>
    <div class="w-100 overflow-auto">
        <table class="table table-responsive table-bordered text-center align-middle" id="example">
            <thead class="table-light">
                <tr>
                    <th></th>
                    <th>Tahun Akademik</th>
                    <th>Gelombang</th>
                    <th>Prodi</th>
                    <th>Lulusan</th>
                    <th>Kelas</th>
                    <th>Metode Belajar</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">

    </div>
@endsection

@push('js')
<script>
    var groupBy = function(xs, key) {
                        return xs.reduce(function(rv, x) {
                            (rv[x[key]] = rv[x[key]] || []).push(x);
                            return rv;
                        }, {});
    };
    function htmlDecode(input){
        var e = document.createElement('div');
        e.innerHTML = input;
        return e.childNodes[0].nodeValue;
    }

    function format ( d ) {
        const all_items_per_group = d.items.reduce(function(item, key) {
                    (item[key['item']['nama']] = item[key['item']['nama']] || []).push(key);
                    return item;
                }, {});
        const all_keys = $.map(all_items_per_group, function(item, key){
            return key;
        });
        console.log(all_items_per_group);

        let view  = '';
            all_keys.forEach(function(val, index){
                const i = index+1;
                if(i%3==1){
                    view  += '<div class="row mt-3">';
                }                    
                    view  += `<div class="col-4">
                                <h4>${val}</h4>`;
                    view  += `  <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Total Bayar</th>
                                            <th>Angsuran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                    all_items_per_group[val].forEach(function(item, ii){
                        view  +=        '<tr>'
                        view  +=            '<th>'+(ii+1)+'</th>'
                        view  +=            '<th>'+item.nominal+'</th>'
                        view  +=            '<th>'+item.jumlah_angsuran+'</th>'
                        view  +=            '<th>'+htmlDecode(item.aksi)+'</th>'
                        view  +=       '</tr>';
                    });
                    view  +=        `</tbody>
                                </table>`;
                    view  += `</div>`;
                if(i%3==0){
                    view  += '</div>';
                }                    
            });            
        return view;
    }

    $(document).ready(function() {
        let i = 1;
        var table = $('#example').DataTable({
            ajax: {
                url: '{{ route("kampus.item-bayar.index") }}',
                type: 'GET',
                data: {
                    "id_kampus": <?php echo Session::get("id_kampus"); ?>
                }
            },
            processing: true,
            serverSide: true,
            columns: [
                {
                    class: 'details-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                {
                    data: "gelombang.tahun_akademik.nama"
                },
                {
                    data: "gelombang.nama_gelombang"
                },
                {
                    data: "prodi.nama"
                },
                {
                    data: "lulusan.nama"
                },
                {
                    data: "kelas.nama"
                },
                {
                    data: "metode_belajar.nama"
                }
            ]
            // "order": [[1, 'asc']]
        });

        $('#example tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                console.log(row.data().items);
                row.child(format( row.data() )).show();
                tr.addClass('shown');
            }
        } );
    });
</script>
@endpush