@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('stok') }}" class="form-horizontal">
            @csrf
            
            <div class="form-group">
                <label for="barang_id">Pilih Barang</label>
                <select class="form-control select2-search" name="barang_id" id="barang_id">
                    <option value="">-- Semua Barang --</option>
                    @foreach ($barangList as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->barang_kode }} - {{ $barang->barang_nama }}</option>
                    @endforeach
                </select>
            </div>            
            
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama User</label>
                <div class="col-11">
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    <input type="hidden" name="nama" value="{{ Auth::user()->name }}">
                </div>
            </div>            
            
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Tanggal</label>
                <div class="col-11">
                    <input type="date" class="form-control" id="stok_tanggal" name="stok_tanggal" required>
                    @error('stok_tanggal')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Jumlah</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="stok_jumlah" name="stok_jumlah" value="{{ old('stok_jumlah') }}" required>
                    @error('stok_jumlah')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-1 control-label col-form-label"></label>
                <div class="col-11">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a class="btn btn-sm btn-default ml-1" href="{{ url('stok') }}">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-search').select2({
            placeholder: "Pilih barang...",
            allowClear: true
        });
    });
</script>
@endpush


