@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/transaksi/import') }}')" class="btn btn-sm btn-info mt-1">Import Stok</button>
            <a href="{{ url('/transaksi/export_excel') }}" class="btn btn-sm btn-primary mt-1"><i class="fa fa-file-excel"></i> Export Stok</a>
            <a href="{{ url('/transaksi/export_pdf') }}" class="btn btn-sm btn-warning mt-1"><i class="fa fa-file-pdf"></i> Export Stok</a>
            <button onclick="modalAction('{{ url('/transaksi/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        </div>
    </div>

    <div class="card-body">
        {{-- filter data --}}
        <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm row text-sm mb-0">
                        <label for="filter_date" class="col-md-1 col-form-label">Filter</label>
                        <div class="col-md-3">
                            <input type="date" name="filter_stok" class="form-control form-control-sm filter_stok" />
                            <small class="form-text text-muted">Stok Tanggal</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-striped table-hover table-sm" id="table_transaksi">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Pembeli</th>
                    <th>Kode Penjualan</th>
                    <th>Tanggal Penjualan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('css')
@endpush

@push('js')  
    <script>
        function modalAction(url = ''){
            $('#myModal').load(url,function(){
            $('#myModal').modal('show');
        });
        }

        var dataStok;
        $(document).ready(function(){
            dataStok = $('#table_transaksi').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('transaksi/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.filter_stok = $('.filter_transaksi').val();
                    }
                },
                columns: [
                    {
                        // nomor urut dari laravel datatable addIndexColumn()
                        data: "DT_RowIndex",
                        className: "text-center",
                        width: "5%",
                        orderable: false,
                        searchable: false
                    },{
                        // mengambil data level hasil dari ORM berelasi
                        data: "user.username",
                        className: "",
                        width: "20%",
                        orderable: true,
                        searchable: true
                    },{
                        data: "pembeli",
                        className: "",
                        width: "15%",
                        orderable: true,
                        searchable: true,
                    },{
                        data: "penjualan_kode",
                        className: "",
                        width: "15%",
                        orderable: true,
                        searchable: false
                    },{
                        data: "penjualan_tanggal",
                        className: "",
                        width: "15%",
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            // konversi data menjadi objek tanggal
                            let date = new Date(data);
                            // return dalam format lokal (Indonesia)
                            return date.toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            });
                        }
                    },{
                        data: "aksi",
                        className: "text-center",
                        width: "20%",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#table-stok_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) { // enter key
                    tableStok.search(this.value).draw();
                }
            });

            $('.filter_stok').change(function() {
                tableStok.draw();
            });
        });
    </script>
@endpush