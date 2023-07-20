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
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PrintController extends Controller
{
    public static $printerName = "test";
    public static $lineCharacterLength = 32;

    public static function printPesanan($transaksiId)
    {
        $transaksi = Transaksi::findOrFail($transaksiId);
        $connector = new WindowsPrintConnector(self::$printerName);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->text("Toko SiMas\n");
        $printer->selectPrintMode();
        $printer->text("Jl. Toyareka Raya\n");
        $printer->feed();

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text(self::doubleLine());
        $printer->text(self::dualColumnText('No Nota', ': ' . Str::padRight($transaksi->kode, 16)));
        $printer->text(self::dualColumnText('Waktu', ': ' . Str::padRight(Carbon::parse($transaksi->waktu_transaksi)->format('d-m-Y H:i'), 16)));
        $printer->text(self::dualColumnText('Kasir', ': ' . Str::padRight($transaksi->user->nama, 16)));

        $printer->text(self::line());
        foreach ($transaksi->transaksi_detail as $detail) {
            $printer->text(self::dualColumnText($detail->jumlah_beli . ' ' . $detail->produk->nama, number_format($detail->harga_total, 0, ',', '.')));
        }
        // $printer->text(self::dualColumnText('1234567890', '1.000'));

        $printer->text(self::line());
        $printer->text(self::dualColumnText('Subtotal ' . $transaksi->transaksi_detail->count() . ' Produk', number_format($transaksi->transaksi_detail->sum('harga_total'), 0, ',', '.')));
        $printer->text(self::dualColumnText('Total Tagihan', number_format($transaksi->bayar->harga_total, 0, ',', '.')));

        $printer->text(self::line());
        $printer->text(self::dualColumnText('Tunai', number_format($transaksi->bayar->bayar, 0, ',', '.')));
        if ($transaksi->bayar->kembalian >= 0) {
            $printer->text(self::dualColumnText('Kembalian', number_format($transaksi->bayar->kembalian, 0, ',', '.')));
        } else {
            $printer->text(self::dualColumnText('Hutang', number_format($transaksi->bayar->hutang, 0, ',', '.')));
        }

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text(self::doubleLine());
        $printer->text("Terima kasih telah berbelanja\n");
        $printer->text("Insya Allah Penuh Berkah\n");
        $printer->feed();

        $printer->cut();
        $printer->close();
    }

    public static function printTutupKasir($sesiId)
    {
        $sesi = Sesi::findOrFail($sesiId);
        $waktuMulai = Carbon::parse($sesi->waktu_mulai);
        $waktuSelesai = Carbon::parse($sesi->waktu_selesai);

        $kasKasir = Kas::where('user_id', Auth::user()->id)
            ->whereBetween('created_at', [$waktuMulai, $waktuSelesai])
            ->get();
        $kasMasuk = $kasKasir->where('jenis', 'masuk')->sum('nominal');
        $kasKeluar = $kasKasir->where('jenis', 'keluar')->sum('nominal');

        $data = self::getData($waktuMulai, $waktuSelesai);
        $saldoAkhir = $data['terimaPembayaran'] + $kasMasuk - $kasKeluar + $sesi->saldo_awal;

        $connector = new WindowsPrintConnector(self::$printerName);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer->text("Toko SiMas\n");
        $printer->selectPrintMode();
        $printer->text("Jl. Toyareka Raya\n");
        $printer->feed();

        $printer->text(self::doubleLine());
        $printer->text("LAPORAN TUTUP KASIR\n");
        $printer->text("TRANSAKSI PENJUALAN\n");
        $printer->feed();

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text(self::dualColumnText('Kasir', ': ' . Str::padRight($sesi->user->nama, 16)));
        $printer->text(self::dualColumnText('Waktu Buka', ': ' . Str::padRight(Carbon::parse($sesi->waktu_mulai)->format('d-m-Y H:i'), 16)));
        $printer->text(self::dualColumnText('Waktu Tutup', ': ' . Str::padRight(Carbon::parse($sesi->waktu_selesai)->format('d-m-Y H:i'), 16)));
        $printer->text(self::line());
        $printer->text(self::dualColumnText('Modal Awal', number_format($sesi->saldo_awal, 0, ',', '.')));
        $printer->text(self::line());
        $printer->text(self::dualColumnText('Total Penjualan', number_format($data['totalPenjualan'], 0, ',', '.')));
        $printer->text(self::dualColumnText('Terima Pembayaran', number_format($data['terimaPembayaran'], 0, ',', '.')));
        $printer->text(self::line());
        $printer->text(self::dualColumnText('Kas Masuk', number_format($kasMasuk, 0, ',', '.')));
        $printer->text(self::dualColumnText('Kas Keluar', number_format($kasKeluar, 0, ',', '.')));
        $printer->text(self::line());
        $printer->text(self::dualColumnText('Hutang', number_format($data['sumHutang'], 0, ',', '.')));
        $printer->text(self::dualColumnText('Refund', number_format($data['sumRefund'], 0, ',', '.')));
        $printer->text(self::line());
        $printer->text(self::dualColumnText('Saldo Akhir', number_format($saldoAkhir, 0, ',', '.')));
        $printer->text(self::line());
        $printer->text(self::dualColumnText('Total Transaksi', number_format($data['totalTransaksi'], 0, ',', '.')));
        $printer->text(self::dualColumnText('Total Transaksi Hutang', number_format($data['totalTransaksiHutang'], 0, ',', '.')));
        $printer->text(self::line());
        $printer->text(self::dualColumnText('Total Tunai Sistem', number_format($saldoAkhir, 0, ',', '.')));
        $printer->text(self::dualColumnText('Total Tunai Aktual', number_format($sesi->saldo_akhir, 0, ',', '.')));
        $printer->text(self::dualColumnText('Selisih', number_format(abs($saldoAkhir - $sesi->saldo_akhir), 0, ',', '.')));
        $printer->text(self::doubleLine());
        $printer->feed();

        $printer->cut();
        $printer->close();
    }

    public static function getData($waktuMulai, $waktuSelesai)
    {
        $transaksiAll = Transaksi::where('status', 'selesai')
            ->whereBetween('waktu_transaksi', [$waktuMulai, $waktuSelesai])
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

    public static function dualColumnText(string $left, string $right): string
    {
        $left = substr($left, 0, 22);

        $remaining = self::$lineCharacterLength - (strlen($left) + strlen($right));

        if ($remaining <= 0) {
            $remaining = 1;
        }

        return $left . str_repeat(' ', $remaining) . $right . "\n";
    }

    public static function doubleLine(): string
    {
        return str_repeat('=', self::$lineCharacterLength) . "\n";
    }

    public static function line(): string
    {
        return str_repeat('-', self::$lineCharacterLength) . "\n";
    }
}
