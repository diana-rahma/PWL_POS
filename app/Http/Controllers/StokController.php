<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Stok Barang',
            'list' => ['Home', 'Stok']
        ];

        $page = (object)[
            'title' => 'Stok barang yang tersedia'
        ];

        $activeMenu = 'stok';

        $stok = StokModel::all();

        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'stok' => $stok, 'activeMenu' => $activeMenu]);
        
    }


    public function list(Request $request){
        $stok = StokModel::select('stok_id', 'stok_tanggal', 'stok_jumlah', 'barang_id', 'user_id')->with('barang', 'user');

        // filter
        if ($request->filter_stok) {
            $stok->whereDate('stok_tanggal', $request->filter_stok);
        }

        return DataTables::of($stok)
            // Menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($stok) {
                // Menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi mengandung HTML
            ->make(true);
    }

    public function show(string $id)
    {
        $stok = StokModel::with('barang', 'stok')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Stok',
            'list' => ['Home', 'Stok', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Stok'
        ];
        $activeMenu = 'stok'; // set menu yang sedang aktif
        

        return view('stok.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'stok' => $stok, 'activeMenu' => $activeMenu]);
    }

    public function show_ajax(string $id){
        $stok = StokModel::find($id);
        return view('stok.show_ajax', ['stok' => $stok]);
    }

    public function create_ajax(){
        $stok = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.create_ajax')->with('barang', $stok, 'user', $user);
    }

    public function store_ajax ( Request $request ) {
        
        // cek apakah request berupa ajax
        if ( $request->ajax() || $request->wantsJson() ) {
    
            $rules = [
                'barang_id'     => 'required|integer|exists:m_barang,id', // validasi foreign key
                'nama'          => 'required|string|min:2|max:100',
                'stok_tanggal'  => 'required|date',
                'stok_jumlah'   => 'required|numeric|min:1'
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
    
            StokModel::create($request->all());
    
            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan'
            ]);
        }
    
        redirect('/stok');
    }
    
    public function edit_ajax(string $id)
    {
        $stok = StokModel::find($id);
        $user = UserModel::select('user_id', 'username')->get();

        return view('stok.edit_ajax', ['stok' => $stok, 'user' => $user]);
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
        $stok = StokModel::find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $stok = BarangModel::find($id);

            if ($stok) {
                $stok->delete();
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
