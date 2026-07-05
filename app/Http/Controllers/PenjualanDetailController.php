<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $kategoriMain = \App\Models\Kategori::where('nama_kategori', 'Mix Topping Series')->first();
        $produk = Produk::where('id_kategori', $kategoriMain->id_kategori ?? 0)->orderBy('nama_produk')->get();
        
        $kategoriMie = \App\Models\Kategori::where('nama_kategori', 'Pilihan Mie')->first();
        $mie = Produk::where('id_kategori', $kategoriMie->id_kategori ?? 0)->get();

        $kategoriKuah = \App\Models\Kategori::where('nama_kategori', 'Jenis Kuah')->first();
        $kuah = Produk::where('id_kategori', $kategoriKuah->id_kategori ?? 0)->get();

        $kategoriLevel = \App\Models\Kategori::where('nama_kategori', 'Pilihan Level')->first();
        $level = Produk::where('id_kategori', $kategoriLevel->id_kategori ?? 0)->get();

        $kategoriTopping = \App\Models\Kategori::where('nama_kategori', 'Topping Tambahan')->first();
        $topping = Produk::where('id_kategori', $kategoriTopping->id_kategori ?? 0)->get();

        // Cek apakah ada transaksi yang sedang berjalan
        if ($id_penjualan = session('id_penjualan')) {
            $penjualan = Penjualan::find($id_penjualan);

            return view('penjualan_detail.index', compact('produk', 'mie', 'kuah', 'level', 'topping', 'id_penjualan', 'penjualan'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaksi.baru');
            } else {
                return redirect()->route('transaksi.baru');
            }
        }
    }

    public function data($id)
    {
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] .'</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_jual']  = 'Rp. '. format_uang($item->harga_jual);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_penjualan_detail .'" value="'. $item->jumlah .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('transaksi.destroy', $item->id_penjualan_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_produk' => '',
            'harga_jual'  => '',
            'jumlah'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $items_to_add = [];

        // Base Product
        if ($request->id_produk) {
            $items_to_add[] = ['id' => $request->id_produk, 'qty' => 1];
        }

        // Mie
        if ($request->id_mie) {
            $items_to_add[] = ['id' => $request->id_mie, 'qty' => 1];
        }

        // Kuah
        if ($request->id_kuah) {
            $items_to_add[] = ['id' => $request->id_kuah, 'qty' => 1];
        }

        // Level
        if ($request->id_level) {
            $items_to_add[] = ['id' => $request->id_level, 'qty' => 1];
        }

        // Toppings
        if ($request->has('toppings')) {
            foreach ($request->toppings as $topping_id => $qty) {
                if ($qty > 0) {
                    $items_to_add[] = ['id' => $topping_id, 'qty' => $qty];
                }
            }
        }

        foreach ($items_to_add as $item) {
            $produk = Produk::where('id_produk', $item['id'])->first();
            if (! $produk) continue;

            $detail = new PenjualanDetail();
            $detail->id_penjualan = $request->id_penjualan;
            $detail->id_produk = $produk->id_produk;
            $detail->harga_jual = $produk->harga_jual;
            $detail->jumlah = $item['qty'];
            $detail->diskon = 0;
            $detail->subtotal = $produk->harga_jual * $item['qty'];
            $detail->save();
        }

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali). ' Rupiah'),
        ];

        return response()->json($data);
    }
}
