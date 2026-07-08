<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;

class BahanBakuController extends Controller
{
    public function index()
    {
        return view('bahan_baku.index');
    }

    public function data()
    {
        $bahan_baku = BahanBaku::orderBy('id_bahan_baku', 'desc')->get();

        return datatables()
            ->of($bahan_baku)
            ->addIndexColumn()
            ->addColumn('harga_satuan', function ($bahan_baku) {
                return 'Rp. '. format_uang($bahan_baku->harga_satuan);
            })
            ->addColumn('stok', function ($bahan_baku) {
                return format_uang($bahan_baku->stok) . ' ' . $bahan_baku->satuan;
            })
            ->addColumn('aksi', function ($bahan_baku) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('bahan_baku.update', $bahan_baku->id_bahan_baku) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'. route('bahan_baku.destroy', $bahan_baku->id_bahan_baku) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $bahan_baku = BahanBaku::create($request->all());
        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $bahan_baku = BahanBaku::find($id);
        return response()->json($bahan_baku);
    }

    public function update(Request $request, $id)
    {
        $bahan_baku = BahanBaku::find($id);
        $bahan_baku->update($request->all());
        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy($id)
    {
        $bahan_baku = BahanBaku::find($id);
        $bahan_baku->delete();
        return response(null, 204);
    }
}
