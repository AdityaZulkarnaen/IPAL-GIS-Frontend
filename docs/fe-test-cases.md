# FE Test Cases — IPAL-GIS

**Proyek:** IPAL-GIS (GIS Frontend — Laravel Blade + Tailwind CSS)  
**Dibuat:** 2026-03-05  
**Dibuat oleh:** Frontend Developer  
**Versi aplikasi:** `feat/auth-page`  

---

## Cakupan Pengujian

| Area | Modul |
|---|---|
| UI | Halaman Login |
| Validasi | Form Login |
| Autentikasi | Login flow (E2E), Logout, Proteksi Route |
| Responsiveness | Mobile / Tablet / Desktop |

---

## Daftar Test Case

### Modul 1 — Login UI

| ID | Skenario | Precondition | Langkah | Expected Result |
|---|---|---|---|---|
| TC-UI-01 | Halaman login dapat diakses | App running, user belum login | Buka `GET /login` di browser | HTTP 200, halaman login tampil tanpa error |
| TC-UI-02 | Brand panel tampil dengan benar | `GET /login` berhasil | Perhatikan sisi kiri halaman (panel merah) | Background merah (#C03F3F), logo SVG tampil, teks "Selamat Datang di BPAL PJK DIY" terlihat |
| TC-UI-03 | Form login tampil dengan benar | `GET /login` berhasil | Perhatikan sisi kanan halaman | Judul "Masuk ke Akun Anda", field Username, field Password, link "Lupa Password?", tombol "Masuk" berwarna biru terlihat |
| TC-UI-04 | Field password menggunakan type password | `GET /login` berhasil | Inspect elemen field Password | `type="password"` — karakter tersembunyi saat diketik |
| TC-UI-05 | Page title sesuai | `GET /login` berhasil | Lihat tab browser | Title tab: "Masuk — BPAL PJK DIY" |

---

### Modul 2 — Validasi Form

| ID | Skenario | Precondition | Langkah | Expected Result |
|---|---|---|---|---|
| TC-VAL-01 | Submit form kosong (kedua field) | Buka `/login` | Klik tombol "Masuk" tanpa mengisi field apapun | Browser HTML5 validation mencegah submit; muncul tooltip "Please fill out this field" di field Username |
| TC-VAL-02 | Submit dengan username saja (password kosong) | Buka `/login` | Isi Username → kosongkan Password → klik "Masuk" | Browser HTML5 validation mencegah submit; muncul tooltip di field Password |
| TC-VAL-03 | Submit dengan password saja (username kosong) | Buka `/login` | Kosongkan Username → isi Password → klik "Masuk" | Browser HTML5 validation mencegah submit; muncul tooltip di field Username |

---

### Modul 3 — Autentikasi (Login / Logout / Proteksi Route)

| ID | Skenario | Precondition | Langkah | Expected Result |
|---|---|---|---|---|
| TC-AUTH-01 | Login dengan kredensial salah | App + backend API running; buka `/login` | Isi username salah / password salah → klik "Masuk" | Halaman kembali ke `/login`, muncul pesan error (mis. "Username atau password salah."), field username tetap terisi (nilai lama) |
| TC-AUTH-02 | Login dengan kredensial benar (role admin) | Punya akun admin valid di backend | Isi username + password valid → klik "Masuk" | Redirect ke `GET /admin/dashboard`; cookie `api_token` terset (HttpOnly); halaman dashboard tampil |
| TC-AUTH-03 | Logout dari dashboard | Sudah login sebagai admin | Di halaman dashboard, klik tombol "Logout" | POST `/logout` diproses; cookie `api_token` dihapus; session di-flush; redirect ke `/login` |
| TC-GUARD-01 | Akses dashboard tanpa autentikasi | Belum login / cookie tidak ada | Buka langsung `GET /admin/dashboard` di browser baru | Redirect ke `/login` (middleware `CheckApiAuthenticated` memblokir) |
| TC-GUARD-02 | Akses dashboard dengan token tidak valid/expired | Cookie `api_token` ada tapi tidak valid | Set cookie `api_token=invalid_token` via DevTools → buka `/admin/dashboard` | Redirect ke `/login`, cookie `api_token` dihapus oleh middleware |

---

### Modul 4 — Responsiveness

| ID | Skenario | Precondition | Langkah | Expected Result |
|---|---|---|---|---|
| TC-RESP-01 | Layout login di mobile (375px — iPhone 12) | Buka `/login` | DevTools → Toggle Device Toolbar → preset "iPhone 12" (375×812) | Brand panel (merah) menumpuk di atas form; layout vertikal; tidak ada horizontal scroll; elemen tidak overlap |
| TC-RESP-02 | Layout login di tablet (768px — iPad) | Buka `/login` | DevTools → preset "iPad Mini" (768×1024) | Brand panel di kiri (1/3 lebar), form di kanan (2/3); layout horizontal; semua elemen terlihat proporsional |
| TC-RESP-03 | Layout login di desktop (1440px) | Buka `/login` | DevTools → set width 1440px, atau resize window | Brand panel di kiri, form di kanan; tidak ada overflow; tombol dan field sejajar dengan baik |
| TC-RESP-04 | Konten form tidak overflow di mobile | Resolusi 375px | DevTools → iPhone 12 → ketik karakter panjang di field Username | Input field tidak keluar dari container; padding tetap ada |

---

## Environment untuk Testing

| Item | Nilai yang Direkomendasikan |
|---|---|
| Browser utama | Google Chrome (latest) |
| Browser tambahan | Firefox (latest) |
| URL lokal | `http://127.0.0.1:8000/login` |
| Resolusi desktop | 1440 × 900 |
| Alat responsive | Chrome DevTools → Toggle Device Toolbar |
| Backend API | Harus running (lihat `ipal-be`) |

---

## Prioritas Test

| Prioritas | Test ID |
|---|---|
| P1 (Critical) | TC-AUTH-01, TC-AUTH-02, TC-AUTH-03, TC-GUARD-01 |
| P2 (High) | TC-VAL-01, TC-VAL-02, TC-VAL-03, TC-UI-01 |
| P3 (Medium) | TC-UI-02, TC-UI-03, TC-UI-04, TC-UI-05, TC-GUARD-02 |
| P4 (Low) | TC-RESP-01, TC-RESP-02, TC-RESP-03, TC-RESP-04 |
