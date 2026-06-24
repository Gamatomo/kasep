<aside class="modern-sidebar">
    <div class="modern-sidebar-user">
        <img src="{{ url(auth()->user()->foto ?? '') }}" alt="User Image" class="modern-sidebar-avatar">
        <div class="modern-sidebar-user-info">
            <strong>{{ auth()->user()->name }}</strong>
            <span><i class="fa fa-circle text-success"></i> Online</span>
        </div>
    </div>

    <nav class="modern-sidebar-nav">
        <ul>
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
            <li class="modern-sidebar-section">MASTER</li>
            <li>
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-cube"></i>
                    <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cubes"></i>
                    <span>Produk</span>
                </a>
            </li>

            <li class="modern-sidebar-section">TRANSAKSI</li>
            <li>
                <a href="{{ route('pengeluaran.index') }}">
                    <i class="fa fa-money"></i>
                    <span>Pengeluaran</span>
                </a>
            </li>

            <li>
                <a href="{{ route('penjualan.index') }}">
                    <i class="fa fa-upload"></i>
                    <span>Penjualan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi.index') }}">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span>Transaksi Aktif</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span>Transaksi Baru</span>
                </a>
            </li>
            <li class="modern-sidebar-section">REPORT</li>
            <li>
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-file-pdf-o"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li class="modern-sidebar-section">LAPORAN SAK ETAP</li>
            <li>
                <a href="{{ route('accounting.balance_sheet') }}">
                    <i class="fa fa-balance-scale"></i>
                    <span>Neraca</span>
                </a>
            </li>
            <li>
                <a href="{{ route('accounting.income_statement') }}">
                    <i class="fa fa-bar-chart"></i>
                    <span>Laba Rugi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('accounting.changes_in_equity') }}">
                    <i class="fa fa-exchange"></i>
                    <span>Perubahan Ekuitas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('accounting.cash_flow') }}">
                    <i class="fa fa-money"></i>
                    <span>Arus Kas</span>
                </a>
            </li>
            <li class="modern-sidebar-section">SYSTEM</li>
            <li>
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-users"></i>
                    <span>User</span>
                </a>
            </li>
            <li>
                <a href="{{ route('attendance.index') }}">
                    <i class="fa fa-clock-o"></i>
                    <span>Kehadiran Kasir</span>
                </a>
            </li>
            <li>
                <a href="{{ route('setting.index') }}">
                    <i class="fa fa-cogs"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            @else
            <li>
                <a href="{{ route('transaksi.index') }}">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span>Transaksi Aktif</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-cart-arrow-down"></i>
                    <span>Transaksi Baru</span>
                </a>
            </li>
            <li>
                <a href="{{ route('attendance.index') }}">
                    <i class="fa fa-clock-o"></i>
                    <span>Kehadiran (Absen)</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>
</aside>
