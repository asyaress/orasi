# Orasi Guru Besar Unmul — Agent Roles & Boundaries

Dokumen ini jadi “kontrak kerja” untuk membangun **admin panel** dan **public site** Orasi Guru Besar Universitas Mulawarman dengan standar production.

## Prinsip Produk (UI/UX)

- **Tujuan utama admin**: input cepat, minim klik, minim teks, jelas status data, aman untuk produksi.
- **Tema**: dominan **putih** dengan aksen **kuning** (brand). Hindari area kuning besar yang melelahkan mata; pakai sebagai highlight/CTA/badge.
- **Layout**:
  - Public: mengikuti template existing (UpTech) tetapi konten diganti sesuai Orasi.
  - Admin: layout bersih (sidebar/topbar), tabel rapi, filter ringkas, aksi jelas.
- **Komponen wajib**:
  - Tabel list dengan search + filter + pagination
  - Form create/edit dengan validasi dan error message ringkas
  - Upload file/foto dengan preview + ukuran/format guidance singkat
  - Status badge (Draft/Published/Archived, Registration Open/Closed, Has Video/File)

## Sumber Data & “Mana yang Dinamis”

### Data internal (disimpan di MySQL)
- **Orasi event**: judul, tanggal pelaksanaan, jenis (luring/daring), periode pendaftaran, urutan, banner/foto, YouTube URL, file Orasi/PPT/Piagam, publish status.
- **Pengumuman**: judul, ringkasan, konten, tanggal tayang, pin/featured.
- **Arsip**: konsepnya pada dasarnya filter per tahun/periode dan status; bisa berupa view dari data orasi + metadata tambahan bila perlu.

### Data external (consume API kepegawaian)
- **Dosen/Guru Besar**: nama, NIP/NIDN/identifier, fakultas, prodi, bidang ilmu, TMT, foto (jika ada), dsb.
- Admin **tetap harus punya tombol input manual** untuk fallback (kalau API down / data belum ada).

### Batasan penting
- Data dari API **tidak boleh jadi single point of failure** untuk admin/public.
  - Simpan minimal: `pegawai_id` + snapshot string (nama, fakultas, prodi) agar halaman tetap bisa render walau API down.

## Struktur Kerja (Agent Roles)

> Ini “agent” adalah pembagian kerja modular (bisa dikerjakan paralel), bukan fitur produk.

### 1) UI Agent (Public + Admin Blade)
- **Scope**:
  - Merapikan navbar public jadi: **Home, Daftar Orasi, Statistic, Pengumuman, Arsip**
  - Home banner: **video only** (tanpa CTA) sesuai request
  - Admin layout: sidebar/topbar + halaman CRUD (list/create/edit/show)
  - Styling: konsisten dengan CSS template existing, tetap dominan putih + aksen kuning
- **Tidak boleh**:
  - Mengubah skema database tanpa koordinasi dengan DB Agent
  - Menambahkan library besar tanpa alasan

### 2) DB Agent (MySQL + Migration + Model)
- **Scope**:
  - Mendesain tabel: `orasis`, `pengumumans` (dan tabel pendukung bila perlu)
  - Menentukan tipe kolom untuk file/url/status/date range
  - Menambahkan index yang relevan (tanggal, status, pegawai_id, tahun)
- **Tidak boleh**:
  - Menyimpan data sensitif tanpa enkripsi/justifikasi
  - Membuat skema yang “mengunci” integrasi API (harus bisa fallback manual)

### 3) API Integration Agent (Kepegawaian)
- **Scope**:
  - Membuat service class untuk consume API pegawai
  - Caching & timeout, error handling, fallback ke snapshot DB
  - Normalisasi data (nama/fakultas/prodi/bidang) untuk kebutuhan view
- **Tidak boleh**:
  - Memanggil API dari Blade langsung
  - Membuat request tanpa timeout/caching

### 4) Admin Workflow Agent (CRUD + Validasi + Upload)
- **Scope**:
  - Controller + request validation
  - Upload media (banner/foto/ppt/piagam/file orasi) ke storage disk yang benar
  - Status publish/arsip, dan aturan “periode pendaftaran”
- **Tidak boleh**:
  - Menulis file di `public/` secara manual tanpa storage link/konvensi

### 5) Quality & Production Agent (Hardening)
- **Scope**:
  - Security baseline: auth gate untuk `/admin`, CSRF, validation, file type limits
  - Observability: logging error API, empty-state yang user-friendly
  - Performance: query pagination, eager loading, caching ringkas
- **Tidak boleh**:
  - Menghapus fitur yang diminta demi “rapih”

## Standar Teknis (Laravel)

- **Routing**: group prefix `/admin` + naming `admin.*`
- **Views**:
  - Public: `resources/views/pages/*` + `partials/*`
  - Admin: `resources/views/admin/*` + `admin/layouts/*`
- **Validation**: gunakan Form Request (lebih rapi, mudah dites)
- **Uploads**:
  - Store via `Storage` (disk `public`), simpan path di DB
  - Batasi ukuran & mime (jpg/png/webp/pdf/ppt/pptx sesuai kebutuhan)
- **Charts**:
  - Statistik admin pakai Chart.js (ringan, jelas, bagus)

## Checklist Admin (yang harus “dinamis”)

- **Orasi**
  - CRUD Orasi Event
  - Pilih dosen dari API (search) + fallback manual
  - Upload: banner/foto + file orasi + PPT + piagam
  - YouTube URL
  - Status (Draft/Published/Archived)
- **Statistic**
  - Counter: Total Orasi, Total Guru Besar, Fakultas Terlibat, Orasi Tahun Berjalan
  - Chart: tren orasi per tahun/bulan + distribusi fakultas
- **Pengumuman**
  - CRUD pengumuman + status tayang + pinned
- **Arsip**
  - Filter year/jenis/fakultas/status (view dari Orasi)

