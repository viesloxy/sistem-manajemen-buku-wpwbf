<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!-- PROFIL USER -->
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
                    <!-- Tampilkan Badge Role -->
                    <span class="badge badge-success mt-1" style="width: fit-content;">{{ strtoupper(Auth::user()->role) }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        <!-- MENU GLOBAL (Dilihat oleh Admin & Vendor) -->
        <!-- PERBAIKAN DI SINI: Mengarah ke /dashboard, bukan lagi / -->
        <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <!-- ============================== -->
        <!-- MULAI MENU KHUSUS ADMIN -->
        <!-- ============================== -->
        @if(Auth::user()->role == 'admin')
            <li class="nav-item pt-3">
                <span class="nav-link text-muted font-weight-bold" style="font-size: 12px; letter-spacing: 1px;">MENU ADMIN</span>
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
                            <a class="nav-link {{ Request::is('simulasi-datatables') ? 'active' : '' }}" href="{{ route('simulasi.datatables') }}">Simulasi Produk (DT)</a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link {{ Request::is('simulasi-wilayah') ? 'active' : '' }}" href="{{ route('simulasi.wilayah') }}">Wilayah Pengiriman</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item {{ Request::is('wilayah*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('wilayah.index') }}">
                    <span class="menu-title">Wilayah Administrasi</span>
                    <i class="mdi mdi-map-marker-radius menu-icon"></i>
                </a>
            </li>

            <li class="nav-item {{ Request::is('kasir*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kasir.index') }}">
                    <span class="menu-title">Transaksi Kasir (POS)</span>
                    <i class="mdi mdi-cash-register menu-icon"></i>
                </a>
            </li>

            <li class="nav-item {{ Request::is('kelola-user*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.index') }}">
                    <span class="menu-title">Kelola Pengguna</span>
                    <i class="mdi mdi-account-multiple menu-icon"></i>
                </a>
            </li>

            <!-- STUDI KASUS 3: AKSES KAMERA -->
            <li class="nav-item {{ Request::is('customer-data') || Request::is('customer-data/create-blob') || Request::is('customer-data/create-file') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#customer-menu" aria-expanded="{{ Request::is('customer-data') || Request::is('customer-data/create-blob') || Request::is('customer-data/create-file') ? 'true' : 'false' }}" aria-controls="customer-menu">
                    <span class="menu-title">Customer</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-account-box menu-icon"></i>
                </a>
                <div class="collapse {{ Request::is('customer-data') || Request::is('customer-data/create-blob') || Request::is('customer-data/create-file') ? 'show' : '' }}" id="customer-menu" data-bs-parent=".nav">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('customer-data') ? 'active' : '' }}" href="{{ route('customer-data.index') }}">
                                Data Customer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('customer-data/create-blob') ? 'active' : '' }}" href="{{ route('customer-data.create-blob') }}">
                                Tambah Customer 1
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('customer-data/create-file') ? 'active' : '' }}" href="{{ route('customer-data.create-file') }}">
                                Tambah Customer 2
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- PRaktikum 1: SCANNER BARCODE & QR CODE -->
            <li class="nav-item {{ Request::is('scanner*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('scanner.index') }}">
                    <span class="menu-title">Scanner Barcode</span>
                    <i class="mdi mdi-qrcode-scan menu-icon"></i>
                </a>
            </li>

            <!-- MODUL 9: GEOLOCATION -->
            <li class="nav-item {{ Request::is('geolocation/map') || Request::is('geolocation/list') || Request::is('geolocation/create') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#geolocation-menu" aria-expanded="{{ Request::is('geolocation/map') || Request::is('geolocation/list') || Request::is('geolocation/create') ? 'true' : 'false' }}" aria-controls="geolocation-menu">
                    <span class="menu-title">Geolocation</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-map-marker-radius menu-icon"></i>
                </a>
                <div class="collapse {{ Request::is('geolocation/map') || Request::is('geolocation/list') || Request::is('geolocation/create') ? 'show' : '' }}" id="geolocation-menu" data-bs-parent=".nav">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('geolocation/map') ? 'active' : '' }}" href="{{ route('geolocation.map') }}">
                                Peta
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('geolocation/list') ? 'active' : '' }}" href="{{ route('geolocation.list') }}">
                                Daftar Lokasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('geolocation/create') ? 'active' : '' }}" href="{{ route('geolocation.create') }}">
                                Tambah Lokasi
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- MASTER BARCODE (Sidebar Terpisah) -->
            <li class="nav-item {{ Request::is('geolocation/barcode*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('geolocation.barcode.index') }}">
                    <span class="menu-title">Master Barcode</span>
                    <i class="mdi mdi-barcode menu-icon"></i>
                </a>
            </li>
        @endif
        <!-- ============================== -->
        <!-- AKHIR MENU KHUSUS ADMIN -->
        <!-- ============================== -->

        <!-- ============================== -->
        <!-- MULAI MENU KHUSUS VENDOR -->
        <!-- ============================== -->
        @if(Auth::user()->role == 'vendor')
            <li class="nav-item pt-3">
                <span class="nav-link text-muted font-weight-bold" style="font-size: 12px; letter-spacing: 1px;">MENU VENDOR</span>
            </li>

            <li class="nav-item {{ Request::is('vendor/menu*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('vendor.menu.index') }}">
                    <span class="menu-title">Master Menu Kantin</span>
                    <i class="mdi mdi-food menu-icon"></i>
                </a>
            </li>
            
            <li class="nav-item {{ Request::is('vendor/pesanan*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('vendor.pesanan.index') }}">
                    <span class="menu-title">Pesanan Masuk</span>
                    <i class="mdi mdi-cart-arrow-down menu-icon"></i>
                </a>
            </li>

            <!-- PRaktikum 2: SCAN QR CODE VENDOR -->
            <li class="nav-item {{ Request::is('vendor/scan*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('vendor.scan-qr') }}">
                    <span class="menu-title">Scan QR Pesanan</span>
                    <i class="mdi mdi-qrcode-scan menu-icon"></i>
                </a>
            </li>

            <!-- MODUL 9: GEOLOCATION (VENDOR) -->
            <li class="nav-item {{ Request::is('vendor/geolocation*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#geolocation-vendor-menu" aria-expanded="{{ Request::is('vendor/geolocation*') ? 'true' : 'false' }}" aria-controls="geolocation-vendor-menu">
                    <span class="menu-title">Geolocation</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-map-marker-radius menu-icon"></i>
                </a>
                <div class="collapse {{ Request::is('vendor/geolocation*') ? 'show' : '' }}" id="geolocation-vendor-menu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('vendor/geolocation/titik-awal') ? 'active' : '' }}" href="{{ route('vendor.geolocation.titik-awal') }}">
                                Input Titik Awal
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('vendor/geolocation/titik-kunjungan') ? 'active' : '' }}" href="{{ route('vendor.geolocation.titik-kunjungan') }}">
                                Titik Kunjungan
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        <!-- ============================== -->
        <!-- AKHIR MENU KHUSUS VENDOR -->
        <!-- ============================== -->

        <!-- ============================== -->
        <!-- MULAI MENU KHUSUS ADMIN ANTRIAN -->
        <!-- ============================== -->
        @if(Auth::user()->role == 'antrian_admin')
            <li class="nav-item pt-3">
                <span class="nav-link text-muted font-weight-bold" style="font-size: 12px; letter-spacing: 1px;">MENU ADMIN ANTRIAN</span>
            </li>

            <li class="nav-item {{ Request::is('antrian/admin*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('antrian.admin') }}">
                    <span class="menu-title">Manajemen Antrian</span>
                    <i class="mdi mdi-account-clock menu-icon"></i>
                </a>
            </li>
        @endif
        <!-- ============================== -->
        <!-- AKHIR MENU KHUSUS ADMIN ANTRIAN -->
        <!-- ============================== -->
    </ul>
</nav>