<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Receipts\ReceiptPrinter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Kas;

class LaporanController extends Controller
{
    public function ringkasanPenjualan()
    {
        return view('laporan.ringkasan_penjualan');
    }

    public function topReport()
    {
        return view('laporan.top_report');
    }

    public function tutupKasir()
    {
        return view('laporan.tutup_kasir');
    }

    public function kasKasir()
    {
        return view('laporan.kas_kasir');
    }

    public function kasKasirCreate()
    {
        return view('laporan.kas_kasir-tambah');
    }

    public function kasKasirStore(Request $request)
    {
        $request->validate([
            'nama_transaksi' => 'required',
            'nominal' => 'required|numeric',
            'jenis' => 'required',
        ]);

        Kas::create([
            'nama_transaksi' => $request->nama_transaksi,
            'catatan' => $request->catatan,
            'nominal' => $request->nominal,
            'jenis' => $request->jenis,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('laporan.kas_kasir')->with('success', 'Berhasil menambahkan transaksi kas kasir');
    }

    public function test()
    {
        // dd(Printing::defaultPrinterId());
        $printers = Printing::printers();
        // dd($printers);

        foreach ($printers as $printer) {
            echo $printer->id() . ' - ' . $printer->name() . '<br>';
        }

        $printerId = 72437554;
        // $text = 'Hello World!';

        $text = (string) (new ReceiptPrinter)
            ->centerAlign()->text('Toko SiMas')
            ->centerAlign()->text('Jl. Raya Cikarang')
            ->feed(1)
            ->leftAlign()->doubleLine()
            ->twoColumnText('No Nota', Str::padRight(': XCA12312312', 18))
            ->twoColumnText('Waktu', Str::padRight(': 12-12-2021 12:12', 18))
            ->twoColumnText('Kasir', Str::padRight(': Test Kasir', 18))
            ->leftAlign()->line()
            ->twoColumnText('2 Aci 1kg', '7,000')
            ->twoColumnText('2 ABC White Saset', '22,000')
            ->leftAlign()->line()
            ->twoColumnText('Subtotal 2 Produk', '29,000')
            ->twoColumnText('Total Tagihan', '29,000')
            ->leftAlign()->line()
            ->twoColumnText('Tunai', '20,000')
            // Kembalian / Hutang
            // ->twoColumnText('Kembalian', '21,000')
            ->twoColumnText('Hutang', '9,000')
            ->leftAlign()->doubleLine()
            ->feed(1)
            ->centerAlign()->text('Terima kasih telah berbelanja')
            ->centerAlign()->text('Insya Allah Penuh Berkah')
            ->cut();

        // dd($text);

        Printing::newPrintTask()
            ->printer($printerId)
            ->content($text) // content will be base64_encoded if using PrintNode
            ->send();
    }
}
