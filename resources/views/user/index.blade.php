@extends('layouts.app')

@section('style-page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .page-title-icon { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
</style>
@endsection

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account-multiple"></i>
        </span> Kelola Pengguna & Vendor
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Hak Akses Admin <i class="mdi mdi-shield-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Pengguna Sistem</h4>
                <p class="card-description">Admin dapat mengangkat customer/pengguna biasa menjadi Vendor.</p>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="user-table" class="table table-striped table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th> Foto </th>
                                <th> Nama Pengguna </th>
                                <th> Email </th>
                                <th class="text-center"> Role Saat Ini </th>
                                <th class="text-center"> Ubah Role </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <img src="{{ $user->avatar ?? asset('assets/images/faces/face28.jpeg') }}" alt="image" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random'">
                                </td>
                                <td>
                                    <b>{{ $user->name }}</b>
                                    @if(str_contains($user->name, 'Guest_'))
                                        <br><small class="text-muted">(Akun Otomatis POS)</small>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td class="text-center">
                                    @if($user->role == 'admin')
                                        <label class="badge badge-gradient-danger">ADMIN</label>
                                    @elseif($user->role == 'vendor')
                                        <label class="badge badge-gradient-success">VENDOR</label>
                                    @else
                                        <label class="badge badge-gradient-secondary">CUSTOMER</label>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- UBAH DI SINI: route name disesuaikan menjadi user.updateRole -->
                                    <form action="{{ route('user.updateRole', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group input-group-sm" style="width: 180px; margin: 0 auto;">
                                            <select name="role" class="form-select border-primary" style="font-size: 12px; padding-left: 10px;">
                                                <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                                                <option value="vendor" {{ $user->role == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                            <button type="submit" class="btn btn-gradient-primary btn-sm p-2" title="Simpan Role" onclick="return confirm('Yakin ingin mengubah role user ini?')">
                                                <i class="mdi mdi-content-save"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript-page')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#user-table').DataTable({ 
            "language": { 
                "search": "Cari Nama/Email:", 
                "lengthMenu": "Tampilkan _MENU_ data",
                "paginate": { "next": ">>", "previous": "<<" }
            }
        });
    });
</script>
@endsection