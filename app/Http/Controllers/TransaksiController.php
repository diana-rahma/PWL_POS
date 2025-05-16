<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object)[
            'title' => 'Daftar barang yang tersedia'
        ];

        $activeMenu = 'transaksi';

        $transaksi = PenjualanModel::all();

        return view('transaksi.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'transaksi' => $transaksi, 'activeMenu' => $activeMenu]);
    }


    public function list(Request $request){
        $transaksi = PenjualanModel::select('penjualan_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal', 'user_id')->with('user');

        // filter
        if ($request->kategori_id) {
            $transaksi->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($transaksi)
            // Menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($transaksi) {
                // Menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\''.url('/transaksi/' . $transaksi->barang_id . '/show').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/transaksi/' . $transaksi->barang_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/transaksi/' . $transaksi->barang_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi mengandung HTML
            ->make(true);
    }

    public function show(string $id)
    {
        $transaksi = PenjualanModel::with('user')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Barang'
        ];
        $activeMenu = 'transaksi'; // set menu yang sedang aktif

        return view('transaksi.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'transaksi' => $transaksi, 'activeMenu' => $activeMenu]);
    }

    // jobsheet 6
    // public function create_ajax(){
    //     $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

    //     return view('transaksi.create_ajax')->with('kategori', $kategori);
    // }

    // public function store_ajax ( Request $request ) {
        
    //     // cek apakah request berupa ajax
    //     if ( $request->ajax() || $request->wantsJson() ) {
    
    //         $rules = [
    //             'kategori_id' => 'required|integer',
    //             'barang_kode' => 'required|string|min:1|unique:m_barang,barang_kode',
    //             'barang_nama' => 'required|string|max:100',
    //             'harga_beli' => 'required|string|max:30',
    //             'harga_jual' => 'required|string|max:30'
    //         ];
            
    
    //         // use Illuminate\Support\Facades\Validator;
    //         $validator = Validator::make($request->all(), $rules);
    
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false, // response status, false: error/gagal, true: berhasil
    //                 'message' => 'Validasi Gagal',
    //                 // 'errors' => $validator->errors()
    //                 'msgField' => $validator->errors(), // pesan error validasi
    //             ]);
    //         }
    
    //         BarangModel::create($request->all());
    
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Data barang berhasil disimpan'
    //         ]);
    //     }
    
    //     redirect('/');
    // }
    
    // public function edit_ajax(string $id)
    // {
    //     $barang = BarangModel::find($id);
    //     $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

    //     return view('transaksi.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    // }

    // public function update_ajax(Request $request, $id)
    // {
    //     // Cek apakah request berasal dari AJAX
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $rules = [
    //             'kategori_id' => 'required|integer',
    //             'barang_kode' => 'required|string|min:1|unique:m_barang,barang_kode,' . $id . ',barang_id',
    //             'barang_nama' => 'required|string|max:100',
    //             'harga_beli' => 'required|string|max:30',
    //             'harga_jual' => 'required|string|max:30'
    //         ];

    //         // Validasi input
    //         $validator = Validator::make($request->all(), $rules);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false, // Respon JSON, false berarti gagal
    //                 'message' => 'Validasi gagal.',
    //                 'msgField' => $validator->errors() // Menampilkan field yang error
    //             ]);
    //         }

    //         // Cek apakah user dengan ID tersebut ada
    //         $barang = BarangModel::find($id);
    //         if ($barang) {
    //             // Jika password tidak diisi, hapus dari request agar tidak terupdate
    //             if (!$request->filled('password')) {
    //                 $request->request->remove('password');
    //             }

    //             $barang->update($request->all());
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Data berhasil diupdate'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Data tidak ditemukan'
    //             ]);
    //         }
    //     }
        
    //     return redirect('/');
    // }

    // public function confirm_ajax(string $id){
    //     $barang = BarangModel::find($id);
    //     return view('transaksi.confirm_ajax', ['barang' => $barang]);
    // }

    // public function delete_ajax(Request $request, $id)
    // {
    //     // cek apakah request dari ajax
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $barang = BarangModel::find($id);

    //         if ($barang) {
    //             $barang->delete();
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Data berhasil dihapus'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Data tidak ditemukan'
    //             ]);
    //         }
    //     }

    //     return redirect('/');
    // }

    // public function import(){
    //     return view('transaksi.import');
    // }


    // public function import_ajax(Request $request)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $rules = [
    //             // validasi file harus xls atau xlsx, max 1MB
    //             'file_barang' => ['required', 'mimes:xlsx', 'max:1024'],
    //         ];

    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Validasi Gagal',
    //                 'msgField' => $validator->errors(),
    //             ]);
    //         }

    //         $file = $request->file('file_barang'); // ambil file dari request

    //         $reader = IOFactory::createReader('Xlsx'); // load reader file excel
    //         $reader->setReadDataOnly(true);                             // hanya membaca data
    //         $spreadsheet = $reader->load($file->getRealPath()); // load file excel
    //         $sheet = $spreadsheet->getActiveSheet();                     // ambil sheet yang aktif

    //         $data = $sheet->toArray(null, false, true, true);           // ambil data excel

    //         $insert = [];
    //         if (count($data) > 1) { // jika data lebih dari 1 baris
    //             foreach ($data as $baris => $value) {
    //                 if ($baris > 1) { // baris ke 1 adalah header, maka lewati
    //                     $insert[] = [
    //                         'kategori_id' => $value['A'],
    //                         'barang_kode' => $value['B'],
    //                         'barang_nama' => $value['C'],
    //                         'harga_beli' => $value['D'],
    //                         'harga_jual' => $value['E'],
    //                         'created_at' => now(),
    //                     ];
    //                 }
    //             }

    //             if (count($insert) > 0) {
    //                 // insert data ke database, jika data sudah ada, maka diabaikan
    //                 BarangModel::insertOrIgnore($insert);
    //             }

    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Data berhasil diimport',
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Tidak ada data yang diimport',
    //             ]);
    //         }
    //     }

    //     return redirect('/');
    // }

    // public function export_excel(){
    //     // ambil data barang yang akan di export
    //     $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
    //         ->orderBy('kategori_id')
    //         ->with('kategori')
    //         ->get();
    //     // load library excel
    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

    //     $sheet->setCellValue('A1', 'No');
    //     $sheet->setCellValue('B1', 'Kode Barang');
    //     $sheet->setCellValue('C1', 'Nama Barang');
    //     $sheet->setCellValue('D1', 'Harga Beli');
    //     $sheet->setCellValue('E1', 'Harga Jual');
    //     $sheet->setCellValue('F1', 'Kategori');

    //     $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold header

    //     $no = 1;
    //     $baris = 2;
    //     foreach ($barang as $key => $value) {
    //         $sheet->setCellValue('A' . $baris, $no);
    //         $sheet->setCellValue('B' . $baris, $value->barang_kode);
    //         $sheet->setCellValue('C' . $baris, $value->barang_nama);
    //         $sheet->setCellValue('D' . $baris, $value->harga_beli);
    //         $sheet->setCellValue('E' . $baris, $value->harga_jual);
    //         $sheet->setCellValue('F' . $baris, $value->kategori->kategori_nama);
    //         $no++;
    //         $baris++;
    //     }

    //     foreach (range('A', 'F') as $columnID) {
    //         $sheet->getColumnDimension($columnID)->setAutoSize(true); //set autosize untuk kolom
    //     }

    //     $sheet->setTitle('Data Barang'); // set title sheet

    //     $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $filename = 'Data Barang ' . date('Y-m-d H:i:s') . '.xlsx';

    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename="' . $filename . '"');
    //     header('Cache-Control: max-age=0');
    //     header('Cache-Control: max-age=1');

    //     header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    //     header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    //     header('Cache-Control: cache, must-revalidate');
    //     header('Pragma: public');

    //     $writer->save('php://output');
    //     exit;

    // }

    // public function export_pdf()
    // {
    //     $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
    //                 ->orderBy('kategori_id')
    //                 ->orderBy('barang_kode')
    //                 ->with('kategori')
    //                 ->get();

    //     // use Barryvdh\DomPDF\Facade\Pdf;
    //     $pdf = Pdf::loadView('transaksi.export_pdf', ['barang' => $barang]);
    //     $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
    //     $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
    //     $pdf->render();

    //     return $pdf->stream('Data Barang '.date('Y-m-d H:i:s').'.pdf');
    // }
}
