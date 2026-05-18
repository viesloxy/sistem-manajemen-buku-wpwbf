# Cara Cek HTTP Version

## 1. Via Browser DevTools (Chrome/Edge)

1. Buka aplikasi di browser
2. Tekan `F12` atau `Ctrl+Shift+I` untuk buka DevTools
3. Klik tab **Network**
4. Refresh halaman (`Ctrl+R`)
5. Klik salah satu request (misalnya request pertama)
6. Lihat di bagian **Headers** → cari **Protocol**
   - Jika tertulis `http/1.1` → HTTP/1.1
   - Jika tertulis `h2` → HTTP/2

## 2. Via Command Line (curl)

```bash
curl -I --http2 http://localhost/antrian/admin
```

Lihat baris pertama response:
- `HTTP/1.1 200 OK` → HTTP/1.1
- `HTTP/2 200` → HTTP/2

## 3. Via Online Tool

Buka: https://tools.keycdn.com/http2-test
Masukkan URL aplikasi kamu (harus public/online)

---

## Kesimpulan untuk Laragon

**Laragon default menggunakan HTTP/1.1** karena:
- Apache/Nginx bawaan Laragon tidak enable HTTP/2 secara default
- HTTP/2 memerlukan SSL/HTTPS (tidak bisa di HTTP biasa)

### Solusi untuk Masalah Session Blocking:

Karena kamu menggunakan:
```env
CACHE_DRIVER=file
SESSION_DRIVER=file
```

Dan kemungkinan besar HTTP/1.1, maka **solusi terbaik** adalah:

1. **Ubah CACHE_DRIVER ke database** (tidak perlu install Redis)
2. Atau **batasi jumlah tab SSE** yang dibuka (max 5-6 tab)

Route SSE sudah di-bypass dari session middleware, jadi seharusnya tidak ada blocking lagi.
