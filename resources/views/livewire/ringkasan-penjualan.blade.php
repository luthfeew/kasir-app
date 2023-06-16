<div>

    <div class="row">
        <div class="col-6">
            <!-- Date range -->
            <div class="form-group">
                <label>Rentang waktu:</label>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    <input wire:model="tanggal" type="text" class="form-control float-right" id="reservation">
                </div>
                <!-- /.input group -->
            </div>
            <!-- /.form group -->
        </div>
    </div>

    <!-- <div class="chart">
        <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
    </div> -->

    <div class="row">
        <div class="col-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Penjualan</span>
                    <span class="info-box-number">
                        Rp. {{number_format($total_penjualan, 0, ',', '.')}}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-funnel-dollar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Laba Kotor</span>
                    <span class="info-box-number">
                        Rp. {{number_format($laba_kotor, 0, ',', '.')}}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-hand-holding-usd"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Terima Pembayaran</span>
                    <span class="info-box-number">
                        Rp. {{number_format($total_penjualan, 0, ',', '.')}}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-undo"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Refund</span>
                    <span class="info-box-number">
                        Rp. {{number_format($refund, 0, ',', '.')}}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-hashtag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Rata-rata Transaksi</span>
                    <span class="info-box-number">
                        Rp. {{number_format($rata_penjualan, 0, ',', '.')}}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-user-tag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Transaksi</span>
                    <span class="info-box-number">
                        {{ $total_transaksi }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-boxes"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Produk</span>
                    <span class="info-box-number">
                        {{ $total_produk }}
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>