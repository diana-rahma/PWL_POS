@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    
    <div class="card-body">
        @empty($stok)
            <div class="alert alert-danger alert-dismissible">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                Data yang Anda cari tidak ditemukan.
            </div>
        @else
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>ID</th>
                    <td>{{ $stok->barang_id }}</td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>{{ $stok->kategori->kategori_nama }}</td>
                </tr>
                <tr>
                    <th>Kode Barang</th>
                    <td>{{ $stok->barang_kode }}</td>
                </tr>
                <tr>
                    <th>Nama Barang</th>
                    <td>{{ $stok->barang_nama }}</td>
                </tr>
                <tr>
                    <th>Harga Beli</th>
                    <td>{{ $stok->harga_beli }}</td>
                </tr>
                <tr>
                    <th>Harga Jual</th>
                    <td>{{ $stok->harga_jual }}</td>
                </tr>
            </table>
        @endempty
        
        <a href="{{ url('barang') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
    </div>
</div>
@endsection

@push('css')
@endpush


@push('js') 
@endpush