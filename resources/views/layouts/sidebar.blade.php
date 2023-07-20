<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Kasir SiMas</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->nama }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <!-- Cek jika ada sesi dengan user id saat ini -->
                @if (Auth::user()->sesi()->where('status', 'mulai')->whereDate('waktu_mulai', now())->first())
                <li class="nav-item">
                    <a href="{{ route('kasir') }}" class="nav-link nav-link @if(Request::segment(1) == 'kasir') active @endif">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>Kasir</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/penjualan" class="nav-link @if(Request::segment(1) == 'penjualan') active @endif">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Penjualan</p>
                    </a>
                </li>
                @if (Auth::user()->role == 'admin')

                @endif
                @endif
                <li class="nav-item @if(Request::segment(1) == 'gudang') menu-open @endif">
                    <a href="#" class="nav-link @if(Request::segment(1) == 'gudang') active @endif">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            Gudang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('produk.index') }}" class="nav-link @if(Request::segment(2) == 'produk') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kategori.index') }}" class="nav-link @if(Request::segment(2) == 'kategori') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @if (Auth::user()->role == 'admin')
                <li class="nav-item @if(Request::segment(1) == 'laporan') menu-open @endif">
                    <a href="#" class="nav-link @if(Request::segment(1) == 'laporan') active @endif">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Laporan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('laporan.ringkasan_penjualan') }}" class="nav-link @if(Request::segment(2) == 'ringkasan_penjualan') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ringkasan Penjualan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('laporan.top_report') }}" class="nav-link @if(Request::segment(2) == 'top_report') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Top Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('laporan.tutup_kasir') }}" class="nav-link @if(Request::segment(2) == 'tutup_kasir') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tutup Kasir</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('laporan.kas_kasir') }}" class="nav-link @if(Request::segment(2) == 'kas_kasir') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kas Kasir</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('laporan.hutang') }}" class="nav-link @if(Request::segment(2) == 'hutang') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hutang</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pelanggan.index') }}" class="nav-link @if(Request::segment(1) == 'pelanggan') active @endif">
                        <i class="nav-icon fas fa-user-tag"></i>
                        <p>Pelanggan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('karyawan.index') }}" class="nav-link @if(Request::segment(1) == 'karyawan') active @endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>User</p>
                    </a>
                </li>
                @endif

                <li class="nav-header">LAINNYA</li>
                @if (Auth::user()->sesi()->where('status', 'mulai')->whereDate('waktu_mulai', now())->first())
                <li class="nav-item">
                    <a href="{{ route('tutup_kasir') }}" class="nav-link @if(Request::segment(1) == 'tutup_kasir') active @endif">
                        <i class="nav-icon fas fa-door-closed"></i>
                        <p>Tutup Kasir</p>
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a href="{{ route('buka_kasir') }}" class="nav-link @if(Request::segment(1) == 'buka_kasir') active @endif">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p>Buka Kasir</p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout Kasir</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>