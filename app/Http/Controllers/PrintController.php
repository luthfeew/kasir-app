<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Receipts\ReceiptPrinter;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Bayar;
use App\Models\Kas;
use App\Models\Sesi;

class PrintController extends Controller
{
    public static $printerId = 72437554;

    public static function printPesanan($transaksiId)
    {
        $transaksi = Transaksi::findOrFail($transaksiId);

        // // dd(Printing::defaultPrinterId());
        // $printers = Printing::printers();
        // // dd($printers);

        // foreach ($printers as $printer) {
        //     echo $printer->id() . ' - ' . $printer->name() . '<br>';
        // }

        // inisialisasi receipt printer
        $printer = new ReceiptPrinter;
        $printer->centerAlign()->text('Toko SiMas');
        // $printer->centerAlign()->text('Jl. Raya Cikarang');
        $printer->feed(1);

        $printer->leftAlign()->doubleLine();
        $printer->twoColumnText('No Nota', Str::padRight(': ' . $transaksi->kode, 18));
        $printer->twoColumnText('Waktu', Str::padRight(': ' . Carbon::parse($transaksi->updated_at)->format('d-m-Y H:i'), 18));
        $printer->twoColumnText('Kasir', Str::padRight(': ' . $transaksi->user->nama, 18));

        $printer->leftAlign()->line();
        foreach ($transaksi->transaksi_detail as $detail) {
            $printer->twoColumnText($detail->jumlah_beli . ' ' . $detail->produk->nama, number_format($detail->harga_total, 0, ',', '.'));
        }

        $printer->leftAlign()->line();
        $printer->twoColumnText('Subtotal ' . $transaksi->transaksi_detail->count() . ' Produk', number_format($transaksi->transaksi_detail->sum('harga_total'), 0, ',', '.'));
        $printer->twoColumnText('Total Tagihan', number_format($transaksi->bayar->harga_total, 0, ',', '.'));

        $printer->leftAlign()->line();
        $printer->twoColumnText('Tunai', number_format($transaksi->bayar->bayar, 0, ',', '.'));
        if ($transaksi->bayar->kembalian >= 0) {
            $printer->twoColumnText('Kembalian', number_format($transaksi->bayar->kembalian, 0, ',', '.'));
        } else {
            $printer->twoColumnText('Hutang', number_format($transaksi->bayar->hutang, 0, ',', '.'));
        }

        $printer->leftAlign()->doubleLine();
        $printer->feed(1);
        $printer->centerAlign()->text('Terima kasih telah berbelanja');
        $printer->centerAlign()->text('Insya Allah Penuh Berkah');
        $printer->cut();

        $text = (string) $printer;

        // dd($text);

        Printing::newPrintTask()
            ->printer(self::$printerId)
            ->content($text) // content will be base64_encoded if using PrintNode
            ->send();
    }

    public static function printTutupKasir($SesiId)
    {
        $sesi = Sesi::findOrFail($SesiId);
        $waktuMulai = Carbon::parse($sesi->waktu_mulai);
        $waktuSelesai = Carbon::parse($sesi->waktu_selesai);

        $kasKasir = Kas::where('user_id', Auth::user()->id)
            ->whereBetween('created_at', [$waktuMulai, $waktuSelesai])
            ->get();
        $kasMasuk = $kasKasir->where('jenis', 'masuk')->sum('nominal');
        $kasKeluar = $kasKasir->where('jenis', 'keluar')->sum('nominal');

        // $data = $this->getData($waktuMulai, $waktuSelesai);
        $data = self::getData($waktuMulai, $waktuSelesai);
        $saldoAkhir = $data['terimaPembayaran'] + $kasMasuk - $kasKeluar + $sesi->saldo_awal;

        // inisialisasi receipt printer
        $printer = new ReceiptPrinter;
        $printer->centerAlign()->text('Toko SiMas');
        // $printer->centerAlign()->text('Jl. Raya Cikarang');
        $printer->feed(1);

        $printer->leftAlign()->doubleLine();
        $printer->centerAlign()->text('LAPORAN TUTUP KASIR');
        $printer->centerAlign()->text('TRANSAKSI PENJUALAN');
        $printer->feed(1);
        $printer->twoColumnText('Kasir', Str::padRight(': ' . $sesi->user->nama, 18));
        $printer->twoColumnText('Waktu Buka', Str::padRight(': ' . Carbon::parse($sesi->waktu_mulai)->format('d-m-Y H:i'), 18));
        $printer->twoColumnText('Waktu Tutup', Str::padRight(': ' . Carbon::parse($sesi->waktu_selesai)->format('d-m-Y H:i'), 18));
        $printer->leftAlign()->line();
        $printer->twoColumnText('Modal Awal', number_format($sesi->saldo_awal, 0, ',', '.'));
        $printer->leftAlign()->line();
        $printer->twoColumnText('Total Penjualan', number_format($data['totalPenjualan'], 0, ',', '.'));
        $printer->twoColumnText('Terima Pembayaran', number_format($data['terimaPembayaran'], 0, ',', '.'));
        $printer->leftAlign()->line();
        $printer->twoColumnText('Kas Masuk', number_format($kasMasuk, 0, ',', '.'));
        $printer->twoColumnText('Kas Keluar', number_format($kasKeluar, 0, ',', '.'));
        $printer->leftAlign()->line();
        $printer->twoColumnText('Hutang', number_format($data['sumHutang'], 0, ',', '.'));
        $printer->twoColumnText('Refund', number_format($data['sumRefund'], 0, ',', '.'));
        $printer->leftAlign()->line();
        $printer->twoColumnText('Saldo Akhir', number_format($saldoAkhir, 0, ',', '.'));
        $printer->leftAlign()->line();
        $printer->twoColumnText('Total Transaksi', number_format($data['totalTransaksi'], 0, ',', '.'));
        $printer->twoColumnText('Total Transaksi Hutang', number_format($data['totalTransaksiHutang'], 0, ',', '.'));
        $printer->leftAlign()->line();
        $printer->twoColumnText('Total Tunai Sistem', number_format($saldoAkhir, 0, ',', '.'));
        $printer->twoColumnText('Total Tunai Aktual', number_format($sesi->saldo_akhir, 0, ',', '.'));
        $printer->twoColumnText('Selisih', number_format(abs($saldoAkhir - $sesi->saldo_akhir), 0, ',', '.'));
        $printer->leftAlign()->doubleLine();
        $printer->feed(1);
        $printer->cut();

        $text = (string) $printer;

        // dd($text);

        Printing::newPrintTask()
            ->printer(self::$printerId)
            ->content($text) // content will be base64_encoded if using PrintNode
            ->send();
    }

    public static function getData($waktuMulai, $waktuSelesai)
    {
        $transaksiAll = Transaksi::where('status', 'selesai')
            ->whereBetween('updated_at', [$waktuMulai, $waktuSelesai])
            ->get();

        // totalPenjualan = sum harga_total from transaksi detail table
        $totalPenjualan = TransaksiDetail::whereIn('transaksi_id', $transaksiAll->where('is_refund', false)->pluck('id'))->sum('harga_total');

        // labaKotor = totalPenjualan - hpp - sumHutang - sumRefund
        $totalHargaBeli = 0;
        foreach ($transaksiAll->where('is_refund', false) as $transaksi) {
            foreach ($transaksi->transaksi_detail as $detail) {
                $totalHargaBeli += $detail->produk->harga_beli * $detail->jumlah_beli;
            }
        }
        $totalHargaBeliRefund = 0;
        foreach ($transaksiAll->where('is_refund', true) as $transaksi) {
            foreach ($transaksi->transaksi_detail as $detail) {
                $totalHargaBeliRefund += $detail->produk->harga_beli * $detail->jumlah_beli;
            }
        }
        $sumRefund = TransaksiDetail::whereIn('transaksi_id', $transaksiAll->where('is_refund', true)->pluck('id'))->sum('harga_total');
        $sumHutang = Bayar::whereIn('transaksi_id', $transaksiAll->where('is_hutang', true)->pluck('id'))->sum('hutang');
        $labaKotor = $totalPenjualan - ($totalHargaBeli - $totalHargaBeliRefund) - $sumRefund - $sumHutang;

        // terimaPembayaran
        $terimaPembayaran = $totalPenjualan - $sumRefund - $sumHutang;

        // rata-rata transaksi = totalPenjualan / totalTransaksi
        $totalTransaksi = $transaksiAll->where('is_refund', false)->count();
        $totalTransaksiHutang = $transaksiAll->where('is_refund', false)->where('is_hutang', true)->count();
        $rataTransaksi = $totalTransaksi == 0 ? 0 : $totalPenjualan / $totalTransaksi;

        // totalProduk terjual
        $totalProdukTerjual = 0;
        foreach ($transaksiAll->where('is_refund', false) as $transaksi) {
            foreach ($transaksi->transaksi_detail as $detail) {
                $totalProdukTerjual += $detail->jumlah_beli;
            }
        }

        return [
            'totalPenjualan' => $totalPenjualan,
            'labaKotor' => $labaKotor,
            'terimaPembayaran' => $terimaPembayaran,
            'sumRefund' => $sumRefund,
            'sumHutang' => $sumHutang,
            'rataTransaksi' => $rataTransaksi,
            'totalTransaksi' => $totalTransaksi,
            'totalTransaksiHutang' => $totalTransaksiHutang,
            'totalProdukTerjual' => $totalProdukTerjual
        ];
    }
}
