<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
            Copyright © 2026 <a href="#" target="_blank">{{ Auth::user()->name }}</a>. All rights reserved.
        </span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
            Sistem Manajemen Buku | Logged in as: <strong>{{ Auth::user()->email }}</strong>
        </span>
    </div>
</footer>