<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resep;
use App\Models\Produk;
use App\Models\BahanBaku;

class ResepController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->get();
        return view('resep.index', compact('produk'));
    }

    public function detail($id_produk)
    {
        $produk = Produk::find($id_produk);
        $resep = Resep::with('bahan_baku')->where('id_produk', $id_produk)->get();
        $bahan_baku = BahanBaku::orderBy('nama_bahan_baku')->get();

        return view('resep.detail', compact('produk', 'resep', 'bahan_baku'));
    }

    public function store(Request $request)
    {
        $resep = Resep::create($request->all());
        return back()->with('success', 'Bahan baku berhasil ditambahkan ke resep');
    }

    public function destroy($id)
    {
        $resep = Resep::find($id);
        $resep->delete();
        return back()->with('success', 'Bahan baku berhasil dihapus dari resep');
    }
}
