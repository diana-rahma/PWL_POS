<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Data Supplier',
            'list' => ['Home', 'Supplier']
        ];

        $page = (object)[
            'title' => 'Daftar Supplier Barang'
        ];

        $activeMenu = 'supplier';

        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request){
        $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'alamat');

        // filter
        // if ($request->supplier_id) {
        //     $supplier->where('supplier_id', $request->supplier_id);
        // }

        return DataTables::of($supplier)
            // Menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                // Menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

                
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi mengandung HTML
            ->make(true);
    }


    public function show(string $id)
    {
        $supplier = SupplierModel::with('supplier')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Supplier'
        ];
        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('supplier.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }

    // jobsheet 6
    public function create_ajax(){
        $supplier = SupplierModel::select('supplier_kode', 'supplier_nama')->get();

        return view('supplier.create_ajax');
    }

    public function store_ajax ( Request $request ) {
        
        // cek apakah request berupa ajax
        if ( $request->ajax() || $request->wantsJson() ) {
    
            $rules = [
                'supplier_kode' => 'required|string|max:10',
                'supplier_nama' => 'required|string|max:50',
                'alamat' => 'required|string|max:200'
            ];
    
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }
    
            SupplierModel::create($request->all());
    
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
    
        redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);

        return view('supplier.edit_ajax', ['supplier' => $supplier]);
    }

    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_kode' => 'required|string|max:50',
                'supplier_nama' => 'required|string|max:50',
                'alamat' => 'required|string|max:200'
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
            $supplier = SupplierModel::find($id);
            if ($supplier) {
                // Jika password tidak diisi, hapus dari request agar tidak terupdate
                if (!$request->filled('password')) {
                    $request->request->remove('password');
                }

                $supplier->update($request->all());
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
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);

            if ($supplier) {
                $supplier->delete();
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

    public function show_ajax(string $id){
        $supplier = SupplierModel::find($id);
        return view('supplier.show_ajax', ['supplier' => $supplier]);
    }
}
