<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ Auth::user()->avatar ?? asset('assets/images/faces/face28.jpeg') }}"
                        referrerpolicy="no-referrer"
                        alt="profile" />
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ Auth::user()->name ?? 'User' }}</span>
                    <span class="text-secondary text-small">{{ Auth::user()->email }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ Request::is('kategori*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ Request::is('buku*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ Request::is('laporan*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#laporan-pdf" aria-expanded="false" aria-controls="laporan-pdf">
                <span class="menu-title">Laporan & PDF</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-file-document menu-icon"></i>
            </a>
            <div class="collapse {{ Request::is('laporan*') ? 'show' : '' }}" id="laporan-pdf">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('laporan.index') }}">Pilih Laporan</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item {{ Request::is('barang*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#umkm-dropdown" aria-expanded="{{ Request::is('barang*') ? 'true' : 'false' }}" aria-controls="umkm-dropdown">
                <span class="menu-title">Tag Harga UMKM</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-tag-multiple menu-icon"></i>
            </a>
            <div class="collapse {{ Request::is('barang*') ? 'show' : '' }}" id="umkm-dropdown">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('barang.index') ? 'active' : '' }}" href="{{ route('barang.index') }}">Kelola Barang</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item {{ Request::is('simulasi*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#simulasi-transaksi" aria-expanded="{{ Request::is('simulasi*') ? 'true' : 'false' }}" aria-controls="simulasi-transaksi">
                <span class="menu-title">Simulasi Transaksi</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-script-text menu-icon"></i>
            </a>
            <div class="collapse {{ Request::is('simulasi*') ? 'show' : '' }}" id="simulasi-transaksi">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> 
                        <a class="nav-link {{ Request::is('simulasi-produk') ? 'active' : '' }}" href="{{ route('simulasi.index') }}">Simulasi Produk</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ Request::is('simulasi-datatables') ? 'active' : '' }}" href="{{ route('simulasi.datatables') }}">Simulasi Produk (DataTables)</a>
                    </li>
                    <li class="nav-item"> 
                        <a class="nav-link {{ Request::is('simulasi-wilayah') ? 'active' : '' }}" href="{{ route('simulasi.wilayah') }}">Wilayah Pengiriman</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>