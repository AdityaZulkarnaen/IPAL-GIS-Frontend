# FE Test Summary Result — IPAL-GIS

**Proyek:** IPAL-GIS (GIS Frontend — Laravel Blade + Tailwind CSS)  
**Branch:** `feat/auth-page`  
**Tanggal Eksekusi:** <!-- isi tanggal, contoh: 2026-03-05 -->  
**Dieksekusi oleh:** <!-- nama tester -->  

---

## Ringkasan Eksekusi

| Metrik | Nilai |
|---|---|
| Total Test Case | 18 |
| ✅ Pass | <!-- isi --> |
| ❌ Fail | <!-- isi --> |
| ⚠️ Blocked | <!-- isi --> |
| ⏭️ Skipped | <!-- isi --> |
| **Pass Rate** | <!-- hitung: Pass/Total × 100% --> |

---

## Environment Pengujian

| Item | Detail |
|---|---|
| OS | <!-- contoh: Windows 11 → |
| Browser | <!-- contoh: Chrome 122.0.6261.112 --> |
| Resolusi layar | <!-- contoh: 1920 × 1080 --> |
| URL yang ditest | `http://127.0.0.1:8000` |
| Backend API URL | <!-- contoh: http://127.0.0.1:8001 --> |
| Versi App | `feat/auth-page` |

---

## Detail Hasil Per Test Case

### Modul 1 — Login UI

| ID | Skenario | Expected Result | Actual Result | Status | Bug / Catatan |
|---|---|---|---|---|---|
| TC-UI-01 | Halaman login dapat diakses | HTTP 200, halaman tampil | | | |
| TC-UI-02 | Brand panel tampil | Background merah, logo, teks | | | |
| TC-UI-03 | Form login tampil | Judul, 2 field, tombol Masuk | | | |
| TC-UI-04 | Field password tersembunyi | Karakter di-mask | | | |
| TC-UI-05 | Page title benar | "Masuk — BPAL PJK DIY" | | | |

---

### Modul 2 — Validasi Form

| ID | Skenario | Expected Result | Actual Result | Status | Bug / Catatan |
|---|---|---|---|---|---|
| TC-VAL-01 | Submit form kosong | HTML5 validation di Username | | | |
| TC-VAL-02 | Username isi, password kosong | HTML5 validation di Password | | | |
| TC-VAL-03 | Username kosong, password isi | HTML5 validation di Username | | | |

---

### Modul 3 — Autentikasi

| ID | Skenario | Expected Result | Actual Result | Status | Bug / Catatan |
|---|---|---|---|---|---|
| TC-AUTH-01 | Login kredensial salah | Kembali ke `/login`, tampil pesan error | | | ⚠️ Lihat BUG-001 di bawah |
| TC-AUTH-02 | Login kredensial benar | Redirect ke `/admin/dashboard` | | | |
| TC-AUTH-03 | Logout | Redirect ke `/login`, cookie terhapus | | | |
| TC-GUARD-01 | Akses dashboard tanpa login | Redirect ke `/login` | | | |
| TC-GUARD-02 | Akses dengan token invalid | Redirect ke `/login`, cookie terhapus | | | |

---

### Modul 4 — Responsiveness

| ID | Skenario | Expected Result | Actual Result | Status | Bug / Catatan |
|---|---|---|---|---|---|
| TC-RESP-01 | Mobile 375px (iPhone 12) | Layout vertikal, tidak overflow | | | |
| TC-RESP-02 | Tablet 768px (iPad) | Layout horizontal, proporsional | | | |
| TC-RESP-03 | Desktop 1440px | Layout side-by-side, rapi | | | |
| TC-RESP-04 | Input overflow mobile | Input tidak keluar container | | | |

---

## Bug Report

### BUG-001 — Pesan error login tidak tampil di UI

| Atribut | Detail |
|---|---|
| **ID** | BUG-001 |
| **Severity** | High |
| **Test ID Terkait** | TC-AUTH-01 |
| **Ditemukan via** | Code review (static analysis) |
| **File** | `resources/views/components/auth/login-form.blade.php` |
| **Deskripsi** | Ketika login gagal (kredensial salah), `AuthController` mengirim flash message via `->with('error', $message)` ke session. Namun komponen `login-form.blade.php` tidak menampilkan `session('error')`, sehingga pengguna tidak mendapat feedback visual apapun setelah login gagal. |
| **Dampak** | User tidak tahu kenapa login ditolak; bingung apakah aksi berhasil atau gagal |
| **Status** | ✅ Fixed — lihat bagian "Perbaikan Bug" |
| **Solusi** | Tambahkan blok alert di `login-form.blade.php` untuk menampilkan `session('error')` |

---

*(Tambahkan baris baru untuk setiap bug yang ditemukan saat eksekusi)*

---

## Perbaikan Bug

### BUG-001 Fix — Tambah tampilan flash error di login form

**File:** `resources/views/components/auth/login-form.blade.php`  
**Perubahan:** Tambahkan blok alert di atas tag `<form>` untuk menampilkan `session('error')`.

```blade
@if (session('error'))
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg" role="alert">
        {{ session('error') }}
    </div>
@endif
```

**Status:** <!-- Belum / Sudah diimplementasikan -->  
**Retest:** <!-- TC-AUTH-01 Pass/Fail setelah fix -->

---

## Screenshot

> Lampirkan screenshot untuk test case yang Fail atau untuk bukti pass pada fitur penting.

| Test ID | Deskripsi Screenshot | File |
|---|---|---|
| TC-AUTH-01 | Tampilan halaman setelah login gagal | `docs/screenshots/tc-auth-01.png` |
| TC-AUTH-02 | Halaman dashboard setelah login berhasil | `docs/screenshots/tc-auth-02.png` |
| TC-RESP-01 | Layout mobile 375px | `docs/screenshots/tc-resp-01.png` |

*(Buat folder `docs/screenshots/` dan simpan screenshot di sana)*

---

## Catatan Tester

<!-- Tulis catatan tambahan, observasi, atau hal yang perlu ditindaklanjuti -->

---

## Checklist Sebelum Submit

- [ ] Semua test case P1 (Critical) sudah dieksekusi
- [ ] Semua test case P2 (High) sudah dieksekusi  
- [ ] Bug yang ditemukan sudah dicatat di tabel Bug Report
- [ ] BUG-001 (flash error) sudah diperbaiki dan di-retest
- [ ] Pass rate diisi di bagian Ringkasan Eksekusi
- [ ] Screenshot untuk test case penting sudah dilampirkan
- [ ] Dokumen ini sudah di-commit ke Git bersama fix
