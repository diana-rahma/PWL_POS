<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object)[
            'title' => 'Daftar barang yang tersedia'
        ];

        $activeMenu = 'barang';

        $kategori = KategoriModel::all();

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }


    public function list(Request $request){
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual', 'kategori_id')->with('kategori');

        // filter
        if ($request->kategori_id) {
            $barang->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barang)
            // Menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) {
                // Menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi mengandung HTML
            ->make(true);
    }

    public function show(string $id)
    {
        $kategori = BarangModel::with('kategori')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Barang'
        ];
        $activeMenu = 'barang'; // set menu yang sedang aktif

        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    // jobsheet 6
    public function create_ajax(){
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.create_ajax')->with('kategori', $kategori);
    }

    public function store_ajax ( Request $request ) {
        
        // cek apakah request berupa ajax
        if ( $request->ajax() || $request->wantsJson() ) {
    
            $rules = [
                'kategori_id' => 'required|integer',
                'barang_kode' => 'required|string|min:1|unique:m_barang,barang_kode',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|string|max:30',
                'harga_jual' => 'required|string|max:30'
            ];
            
    
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    // 'errors' => $validator->errors()
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }
    
            BarangModel::create($request->all());
    
            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan'
            ]);
        }
    
        redirect('/');
    }
    
    public function edit_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|integer',
                'barang_kode' => 'required|string|min:1|unique:m_barang,barang_kode,' . $id . ',barang_id',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|string|max:30',
                'harga_jual' => 'required|string|max:30'
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // Respon JSON, false berarti gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // Menampilkan field yang error
                ]);
            }

            // Cek apakah user dengan ID tersebut ada
            $barang = BarangModel::find($id);
            if ($barang) {
                // Jika password tidak diisi, hapus dari request agar tidak terupdate
                if (!$request->filled('password')) {
                    $request->request->remove('password');
                }

                $barang->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        
        return redirect('/');
    }

    public function confirm_ajax(string $id){
        $barang = BarangModel::find($id);
        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);

            if ($barang) {
                $barang->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

}
