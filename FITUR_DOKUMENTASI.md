# DOKUMENTASI FITUR SISTEM MANAJEMEN PRODUK

## 1. HALAMAN LOGIN (login.php)

### Struktur Layout
- Full-height page dengan background gradient
- Centered form card di tengah viewport
- Responsive design untuk mobile dan desktop

### Styling & Warna
- **Background Body**: 
  - Gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%)
  - Color: Ungu ke Ungu Tua
  - Min-height: 100vh

- **Card Container**:
  - Background: #ffffff (putih)
  - Border-radius: 15px
  - Box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2)
  - Max-width: 400px
  - Padding: 40px
  - Margin: auto
  - Transform: scale on hover untuk effect

- **Header/Judul**:
  - Font-family: Arial, sans-serif
  - Font-size: 28px
  - Font-weight: bold
  - Color: #333333 (gelap)
  - Text-align: center
  - Margin-bottom: 30px

### Form Elements

#### Input Fields (Username & Password)
- **Input Type**: text dan password
- **Width**: 100%
- **Height**: 45px
- **Padding**: 12px 15px
- **Border**: 1px solid #dddddd
- **Border-radius**: 8px
- **Font-size**: 14px
- **Font-family**: Arial, sans-serif
- **Background**: #f8f9fa
- **Color**: #333333
- **Margin-bottom**: 15px
- **Placeholder color**: #999999
- **Placeholder text**: "Username" dan "Masukkan Password"

- **Focus State**:
  - Border-color: #667eea
  - Background: #ffffff
  - Box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1)
  - Outline: none

#### Submit Button (Login)
- **Type**: submit
- **Width**: 100%
- **Height**: 50px
- **Background**: linear-gradient(135deg, #667eea 0%, #764ba2 100%)
- **Color**: #ffffff (putih)
- **Font-size**: 16px
- **Font-weight**: bold
- **Font-family**: Arial, sans-serif
- **Border**: none
- **Border-radius**: 8px
- **Cursor**: pointer
- **Transition**: all 0.3s ease
- **Margin-top**: 20px
- **Box-shadow**: 0 4px 15px rgba(102, 126, 234, 0.4)

- **Hover State**:
  - Background: linear-gradient(135deg, #764ba2 0%, #667eea 100%)
  - Transform: translateY(-2px)
  - Box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6)

- **Active State**:
  - Transform: translateY(0px)

### Form Struktur
```
[Judul: "Login Sistem Manajemen Produk"]

[Input: Username]
[Input: Password]
[Button: Login]
```

### Validasi
- Method: POST
- Validasi PHP: trim(), empty check, query validation
- Password: Verified dengan password_hash (bcrypt)
- Error display: Alert box merah jika login gagal
- Success: Redirect ke home.php jika login berhasil

### Database Query
- Table: users
- Columns: id, username, password
- Prepared statement untuk security

---

## 2. HALAMAN DASHBOARD (home.php)

### Navbar
- **Background**: #2c3e50 (abu-abu gelap)
- **Height**: 60px
- **Padding**: 10px 20px
- **Display**: flex dengan justify-content: space-between
- **Box-shadow**: 0 2px 5px rgba(0, 0, 0, 0.1)

- **Logo/Brand**:
  - Font-size: 22px
  - Font-weight: bold
  - Color: #ffffff
  - Text: "Manajemen Produk"

- **Menu Items**:
  - Color: #ecf0f1 (abu-abu terang)
  - Font-size: 14px
  - Padding: 20px 15px
  - Margin: 0 5px
  - Links color: #ffffff
  - Hover color: #667eea
  - Transition: 0.3s ease

- **Logout Button**:
  - Background: #e74c3c (merah)
  - Color: #ffffff
  - Padding: 8px 15px
  - Border: none
  - Border-radius: 5px
  - Cursor: pointer
  - Hover: darken #c0392b

### Statistics Cards (Grid)
- **Container**: Grid 2x2 (responsive menjadi 1 kolom di mobile)
- **Grid-gap**: 20px
- **Padding**: 30px

- **Individual Card**:
  - Background: gradient (bervariasi per card)
  - Border-radius: 15px
  - Padding: 25px
  - Box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1)
  - Min-height: 150px
  - Display: flex
  - Flex-direction: column
  - Justify-content: space-between

#### Card 1: Total Produk
- **Background**: linear-gradient(135deg, #667eea 0%, #764ba2 100%)
- **Label Color**: rgba(255, 255, 255, 0.8)
- **Label Font-size**: 14px
- **Label Font-weight**: 500
- **Value Color**: #ffffff
- **Value Font-size**: 42px
- **Value Font-weight**: bold
- **Margin-top**: 10px

#### Card 2: Nilai Pembelian Total
- **Background**: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)
- **Sama styling dengan Card 1**
- **Value**: Sum dari (harga_beli × stok) semua produk
- **Format**: Currency Rp

#### Card 3: Nilai Penjualan Total
- **Background**: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)
- **Sama styling dengan Card 1**
- **Value**: Sum dari (harga_jual × stok) semua produk
- **Format**: Currency Rp

#### Card 4: Keuntungan Total
- **Background**: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)
- **Sama styling dengan Card 1**
- **Value**: Total Nilai Penjualan - Total Nilai Pembelian
- **Format**: Currency Rp

### Product List Section
- **Title**: "Produk Terbaru" (Newest Products)
- **Font-size**: 20px
- **Font-weight**: bold
- **Color**: #333333
- **Margin**: 0 0 15px 0

### Product Cards (Latest 5 Products)
- **Container**: Grid 3 kolom (responsive 2 kolom tablet, 1 kolom mobile)
- **Grid-gap**: 16px
- **Kartu**:
  - Menampilkan gambar produk (height 180px, object-fit: cover)
  - Nama produk + deskripsi singkat
  - Badge harga jual + tombol Edit
  - Jika gambar kosong, tampil placeholder "No Image"

### Tabel Produk Terbaru
- **Tabel ringkas** di bawah kartu untuk detail harga + aksi cepat
- Menampilkan: Produk, Harga Beli, Harga Jual, Profit, Aksi (Edit)
    - Box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15)

  - **Image Container**:
    - Height: 200px
    - Width: 100%
    - Object-fit: cover
    - Background: #f5f5f5

  - **Content Area**:
    - Padding: 15px

  - **Product Name**:
    - Font-size: 16px
    - Font-weight: bold
    - Color: #333333
    - Margin-bottom: 8px
    - Max-height: 40px
    - Overflow: hidden

  - **Product Info Row**:
    - Display: flex
    - Justify-content: space-between
    - Font-size: 13px
    - Color: #666666
    - Margin: 8px 0

    - **Labels**: "Harga Beli:", "Harga Jual:", "Stok:"

  - **Values**:
    - Font-weight: bold
    - Color: #667eea

  - **Stok Badge**:
    - Display: inline-block
    - Background: #e8f4f8
    - Color: #0284c7
    - Padding: 4px 8px
    - Border-radius: 4px
    - Font-size: 12px
    - Font-weight: bold

---

## 3. HALAMAN DAFTAR PRODUK (index.php)

### Top Section
- **Navbar**: Same as home.php
- **Page Title**: "Daftar Produk"
  - Font-size: 28px
  - Font-weight: bold
  - Color: #333333
  - Padding: 30px 30px 20px

### Action Bar
- **Container**: Padding 20px 30px
- **Display**: flex
- **Justify-content**: space-between
- **Align-items**: center
- **Background**: #f8f9fa
- **Border-radius**: 10px
- **Margin**: 20px 30px

#### Search & Filter Section
- **Display**: flex
- **Gap**: 15px

- **Search Input**:
  - Width: 300px (responsive)
  - Height: 40px
  - Padding: 10px 15px
  - Border: 1px solid #dddddd
  - Border-radius: 8px
  - Font-size: 14px
  - Placeholder: "Cari produk..."
  - Background: #ffffff

- **Filter Dropdown**:
  - Same styling as search input
  - Default option: "Semua Kategori"
  - Font-size: 14px

#### Add Product Button
- **Text**: "+ Tambah Produk"
- **Background**: #667eea
- **Color**: #ffffff
- **Padding**: 10px 20px
- **Border**: none
- **Border-radius**: 8px
- **Font-size**: 14px
- **Font-weight**: bold
- **Cursor**: pointer
- **Transition**: 0.3s ease
- **Hover**: #764ba2

### Products Table

#### Table Structure
- **Width**: 100%
- **Margin**: 30px
- **Border-collapse**: collapse
- **Background**: #ffffff
- **Border-radius**: 10px
- **Overflow**: hidden
- **Box-shadow**: 0 2px 10px rgba(0, 0, 0, 0.1)

#### Table Header
- **Background**: #2c3e50
- **Color**: #ffffff
- **Padding**: 15px
- **Font-weight**: bold
- **Font-size**: 14px
- **Text-align**: left
- **Border-bottom**: 3px solid #667eea

- **Columns**: No, Gambar, Nama Produk, Harga Beli, Harga Jual, Stok, Aksi

#### Table Body Rows
- **Padding**: 12px 15px
- **Border-bottom**: 1px solid #ecf0f1
- **Font-size**: 13px
- **Color**: #333333

- **Alternate Row Colors**: 
  - Row 1,3,5... : #ffffff
  - Row 2,4,6... : #f8f9fa

- **Hover State**:
  - Background: #f0f2ff
  - Transition: 0.2s ease

#### Table Cells Details

**Gambar Column**:
- Height: 50px
- Width: 50px
- Object-fit: cover
- Border-radius: 5px

**Nama Produk Column**:
- Font-weight: bold
- Color: #333333

**Harga Beli/Jual Columns**:
- Text-align: right
- Font-family: monospace
- Color: #667eea

**Stok Column**:
- Badge styling:
  - Padding: 6px 12px
  - Border-radius: 20px
  - Font-weight: bold
  - Font-size: 12px

  - **Stok Tinggi (> 50)**:
    - Background: #d4edda (hijau terang)
    - Color: #155724 (hijau gelap)

  - **Stok Sedang (10-50)**:
    - Background: #fff3cd (kuning terang)
    - Color: #856404 (kuning gelap)

  - **Stok Rendah (< 10)**:
    - Background: #f8d7da (merah terang)
    - Color: #721c24 (merah gelap)

**Aksi Column**:
- Display: flex
- Gap: 8px

- **Edit Button**:
  - Background: #667eea
  - Color: #ffffff
  - Padding: 6px 12px
  - Border: none
  - Border-radius: 5px
  - Font-size: 12px
  - Cursor: pointer
  - Hover: #764ba2

- **Delete Button**:
  - Background: #e74c3c
  - Color: #ffffff
  - Padding: 6px 12px
  - Border: none
  - Border-radius: 5px
  - Font-size: 12px
  - Cursor: pointer
  - Hover: #c0392b
  - OnClick: Confirm dialog

- **Print Button** (jika ada):
  - Background: #27ae60
  - Color: #ffffff
  - Padding: 6px 12px
  - Border: none
  - Border-radius: 5px
  - Font-size: 12px
  - Cursor: pointer
  - Hover: #1e8449

### Pagination
- **Container**: Text-align: center
- **Margin**: 30px
- **Font-size**: 14px

- **Links**:
  - Display: inline-block
  - Margin: 0 5px
  - Padding: 8px 12px
  - Background: #f0f0f0
  - Color: #667eea
  - Border-radius: 5px
  - Text-decoration: none
  - Cursor: pointer
  - Transition: 0.3s ease

- **Active Page**:
  - Background: #667eea
  - Color: #ffffff
  - Font-weight: bold

- **Hover State**:
  - Background: #667eea
  - Color: #ffffff

### No Results Message
- **Text**: "Tidak ada produk yang ditemukan"
- **Font-size**: 16px
- **Color**: #999999
- **Text-align**: center
- **Padding**: 50px
- **Font-style**: italic

---

## 4. HALAMAN TAMBAH PRODUK (tambah_produk.php)

### Page Structure
- **Navbar**: Same as home.php
- **Page Title**: "Tambah Produk Baru"
  - Font-size: 28px
  - Font-weight: bold
  - Color: #333333
  - Padding: 30px 30px 20px

### Form Container
- **Width**: 100% max-width 600px
- **Margin**: 30px auto
- **Padding**: 30px
- **Background**: #ffffff
- **Border-radius**: 15px
- **Box-shadow**: 0 5px 20px rgba(0, 0, 0, 0.1)

### Form Group (Label + Input)
- **Margin-bottom**: 20px

- **Label**:
  - Font-size: 14px
  - Font-weight: bold
  - Color: #333333
  - Display: block
  - Margin-bottom: 8px

  - **Required Indicator**: "*"
    - Color: #e74c3c (merah)
    - Margin-left: 3px

#### Form Fields

**Nama Produk Input**:
- Type: text
- Width: 100%
- Padding: 12px 15px
- Border: 1px solid #dddddd
- Border-radius: 8px
- Font-size: 14px
- Placeholder: "Masukkan nama produk"
- Background: #f8f9fa

- Focus State:
  - Border-color: #667eea
  - Background: #ffffff
  - Box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1)

**Harga Beli Input**:
- Type: number
- Step: 0.01
- Min: 0
- Width: 100%
- Padding: 12px 15px
- Border: 1px solid #dddddd
- Border-radius: 8px
- Font-size: 14px
- Placeholder: "0"
- Background: #f8f9fa

- Focus State: Same as Nama Produk

**Harga Jual Input**:
- Same as Harga Beli
- Placeholder: "0"

**Stok Input**:
- Type: number
- Step: 1
- Min: 0
- Width: 100%
- Same styling as Harga Beli
- Default: 0

**Deskripsi Input**:
- Type: textarea
- Rows: 5
- Width: 100%
- Padding: 12px 15px
- Border: 1px solid #dddddd
- Border-radius: 8px
- Font-size: 14px
- Placeholder: "Deskripsi produk (opsional)"
- Background: #f8f9fa
- Font-family: Arial, sans-serif
- Resize: vertical

**Gambar Input**:
- Type: file
- Accept: image/jpeg, image/png, image/gif
- Width: 100%
- Padding: 12px 15px
- Border: 2px dashed #667eea
- Border-radius: 8px
- Background: #f0f2ff
- Cursor: pointer

- Focus State:
  - Border-color: #764ba2
  - Background: #e8eef8

**Preview Image**:
- Max-width: 100%
- Height: auto
- Border-radius: 8px
- Margin-top: 15px
- Display: none (muncul setelah upload)

### Form Buttons
- **Container**: Display: flex
- **Gap**: 15px
- **Margin-top**: 30px
- **Justify-content**: flex-end

**Simpan Button**:
- Text: "Simpan Produk"
- Type: submit
- Width: 150px
- Padding: 12px 20px
- Background: #667eea
- Color: #ffffff
- Border: none
- Border-radius: 8px
- Font-size: 14px
- Font-weight: bold
- Cursor: pointer
- Transition: 0.3s ease
- Hover: #764ba2

**Batal Button**:
- Text: "Batal"
- Type: button
- Width: 150px
- Padding: 12px 20px
- Background: #ecf0f1
- Color: #333333
- Border: 1px solid #bdc3c7
- Border-radius: 8px
- Font-size: 14px
- Font-weight: bold
- Cursor: pointer
- Transition: 0.3s ease
- Hover: #bdc3c7 border, #d5dbde background
- OnClick: Redirect ke index.php

### Validasi
- JavaScript real-time validation untuk harga (angka positif)
- Image preview sebelum upload
- File size check: max 5MB
- MIME type check: jpeg, png, gif
- Error messages dalam red color (#e74c3c)
- Success message dalam green color (#27ae60)

---

## 5. HALAMAN EDIT PRODUK (edit_produk.php)

### Page Structure
- **Navbar**: Same as home.php
- **Page Title**: "Edit Produk"
  - Font-size: 28px
  - Font-weight: bold
  - Color: #333333
  - Padding: 30px 30px 20px

### Form Container
- Same styling as tambah_produk.php
- Width: 100% max-width 600px
- Margin: 30px auto
- Padding: 30px
- Background: #ffffff
- Border-radius: 15px
- Box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1)

### Form Fields
- Same as tambah_produk.php
- Values pre-filled dari database
- Current image displayed dengan preview

**Current Image Section** (jika ada):
- **Label**: "Gambar Saat Ini"
- **Display**: flex
- **Gap**: 15px
- **Margin-bottom**: 20px
- **Align-items**: flex-start

- **Image**:
  - Max-width: 200px
  - Height: auto
  - Border-radius: 8px
  - Border: 1px solid #ecf0f1

- **Change Image Checkbox**:
  - Label: "Ganti gambar?"
  - Margin-bottom: 10px
  - Display: block

- **New Image Input** (hidden by default):
  - Same styling as tambah_produk.php
  - Muncul ketika checkbox dicheck

### Form Buttons
- Same as tambah_produk.php

**Simpan Perubahan Button**:
- Text: "Simpan Perubahan"
- Background: #667eea
- Hover: #764ba2

**Batal Button**:
- Same as tambah_produk.php

### Validasi
- Same as tambah_produk.php
- Check if product exists
- Handle image replacement
- Keep old image jika tidak ada upload baru

---

## 6. HALAMAN CETAK LAPORAN (cetak_laporan.php)

### Page Structure
- **No Navbar** (print-friendly)
- **Page Title**: "Laporan Produk"
  - Font-size: 24px
  - Font-weight: bold
  - Color: #333333
  - Text-align: center
  - Margin-bottom: 10px

- **Date/Time Printed**:
  - Font-size: 12px
  - Color: #666666
  - Text-align: center
  - Margin-bottom: 20px

### Filter Section (sebelum print)
- **Container**: Padding 20px 30px
- **Background**: #f8f9fa
- **Border-radius**: 10px
- **Margin**: 20px 30px

**Filter Options**:
- **Tanggal Dari** & **Tanggal Sampai** (date inputs)
- **Harga Min** & **Harga Max** (number inputs)
- **Stok Min** (number input)

- **Input Styling**:
  - Width: 200px
  - Padding: 10px 15px
  - Border: 1px solid #dddddd
  - Border-radius: 8px
  - Font-size: 14px
  - Margin-right: 15px

**Filter Button**:
- Text: "Terapkan Filter"
- Background: #667eea
- Color: #ffffff
- Padding: 10px 20px
- Border: none
- Border-radius: 8px
- Cursor: pointer
- Hover: #764ba2

**Print Button**:
- Text: "Cetak Laporan"
- Background: #27ae60
- Color: #ffffff
- Padding: 10px 20px
- Border: none
- Border-radius: 8px
- Cursor: pointer
- Hover: #1e8449
- OnClick: window.print()

### Report Table

#### Table Structure
- **Width**: 100%
- **Margin**: 30px
- **Border-collapse**: collapse
- **Background**: #ffffff
- **Border-radius**: 10px
- **Box-shadow**: 0 2px 10px rgba(0, 0, 0, 0.1)

#### Table Header
- **Background**: #2c3e50
- **Color**: #ffffff
- **Padding**: 12px 15px
- **Font-weight**: bold
- **Font-size**: 13px
- **Text-align**: left
- **Border-bottom**: 2px solid #667eea

- **Columns**: No, Nama Produk, Harga Beli, Harga Jual, Stok, Nilai Beli, Nilai Jual, Keuntungan

#### Table Body Rows
- **Padding**: 10px 15px
- **Border-bottom**: 1px solid #ecf0f1
- **Font-size**: 12px
- **Color**: #333333

- **Alternate Row Colors**:
  - Odd rows: #ffffff
  - Even rows: #f8f9fa

**Nama Produk**: Bold
**Nilai Beli** (harga_beli × stok): Right-align, monospace, color #667eea
**Nilai Jual** (harga_jual × stok): Right-align, monospace, color #667eea
**Keuntungan** (nilai_jual - nilai_beli): Right-align, monospace
  - Positif: color #27ae60 (hijau)
  - Negatif: color #e74c3c (merah)

### Summary Section (di bawah table)
- **Background**: #f8f9fa
- **Padding**: 20px 15px
- **Border-top**: 2px solid #667eea
- **Font-size**: 13px
- **Font-weight**: bold

**Summary Rows**:
- Total Nilai Pembelian: Right-align
- Total Nilai Penjualan: Right-align
- Total Keuntungan: Right-align, color: #27ae60

- **Text Color**: #333333
- **Value Color**: #667eea

### Print Styles (@media print)
- Remove filter section
- Remove buttons
- Full width table
- Maintain colors and formatting
- Page break handling
- Margin: 10mm all sides

---

## 7. SUPPORT FILES

### csrf.php
**Fungsi**: Token management untuk CSRF protection
**Functions**:
- generate_csrf_token(): Membuat token baru
- csrf_field(): Menampilkan hidden input field dengan token
- verify_csrf_token(): Verifikasi token dari request
- validate_csrf_post(): Validate POST request dengan CSRF check

**Implementation**:
- Menggunakan SESSION untuk store token
- Menggunakan hash_equals untuk timing attack prevention
- Token unique per session
- Dipakai di form login, tambah produk, edit produk, dan hapus produk (POST)

### koneksi.php
**Functions**:
- Database connection setup
- Query execution helpers
- Input sanitization
- Error handling

**Key Functions**:
- mysqli connection dengan error handling
- sanitize_input(): XSS protection
- execute_query(): Prepared statement wrapper
- fetch_results(): Get query results

---

## 8. SUMMARY FITUR

### Security Features
✓ Password hashing dengan bcrypt
✓ SQL Injection prevention dengan prepared statements
✓ XSS protection dengan input sanitization
✓ CSRF protection dengan token validation pada form POST (login/CRUD)
✓ Hapus data via POST + CSRF (mencegah CSRF GET)
✓ Session-based authentication
✓ Session hardening (regenerate ID & cookie flags HttpOnly/SameSite/Secure)
✓ Image MIME type validation
✓ File upload size limiting
✓ Error message sanitization (tanpa leak detail internal)
✓ Audit log (IP publik/lokal, user-agent/browser, aksi login/CRUD)

### Core Features
✓ User authentication (login/logout)
✓ Product CRUD operations
✓ Product image management
✓ Stock tracking
✓ Pagination
✓ Search & filter functionality
✓ Dashboard with statistics
✓ Report generation with filtering
✓ Print-friendly layouts

### UI/UX Elements
✓ Responsive design untuk mobile, tablet, desktop
✓ Gradient backgrounds dengan warna profesional
✓ Smooth transitions dan hover effects
✓ Clear visual hierarchy dengan typography
✓ Intuitive form validation
✓ Color-coded status badges
✓ Bootstrap framework untuk consistency
✓ Accessible form elements
✓ Footer fixed di bawah (sticky) pada halaman utama

### Database Structure
**users table**:
- id (INT, PRIMARY KEY, AUTO INCREMENT)
- username (VARCHAR 255, UNIQUE)
- password (VARCHAR 255)
- created_at (TIMESTAMP)

**produk table**:
- id (INT, PRIMARY KEY, AUTO INCREMENT)
- nama_produk (VARCHAR 255)
- harga_beli (DECIMAL 10,2)
- harga_jual (DECIMAL 10,2)
- stok (INT, DEFAULT 0)
- gambar_produk (VARCHAR 255)
- deskripsi (TEXT)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

---

## 9. COLOR PALETTE SUMMARY

| Usage | Color Code | RGB | Name |
|-------|-----------|-----|------|
| Primary Gradient | #667eea - #764ba2 | 102,126,234 - 118,75,162 | Purple |
| Secondary Gradient 1 | #f093fb - #f5576c | 240,147,251 - 245,87,108 | Pink-Red |
| Secondary Gradient 2 | #4facfe - #00f2fe | 79,172,254 - 0,242,254 | Cyan-Blue |
| Secondary Gradient 3 | #43e97b - #38f9d7 | 67,233,123 - 56,249,215 | Green-Cyan |
| Dark Background | #2c3e50 | 44,62,80 | Charcoal |
| Light Background | #f8f9fa | 248,249,250 | Off-White |
| White | #ffffff | 255,255,255 | White |
| Dark Text | #333333 | 51,51,51 | Dark Gray |
| Medium Text | #666666 | 102,102,102 | Gray |
| Light Text | #999999 | 153,153,153 | Light Gray |
| Border | #dddddd | 221,221,221 | Very Light Gray |
| Border Alt | #ecf0f1 | 236,240,241 | Light Gray |
| Error/Delete | #e74c3c | 231,76,60 | Red |
| Success/Growth | #27ae60 | 39,174,96 | Green |
| Warning/Stock | #fff3cd | 255,243,205 | Light Yellow |
| Info | #4facfe | 79,172,254 | Light Blue |

---

## 10. TYPOGRAPHY

| Element | Font | Size | Weight | Color |
|---------|------|------|--------|-------|
| Page Title | Arial, sans-serif | 28px | bold | #333333 |
| Section Title | Arial, sans-serif | 24px | bold | #333333 |
| Card Title | Arial, sans-serif | 16px | bold | #333333 |
| Label/Button Text | Arial, sans-serif | 14px | bold | varies |
| Body Text | Arial, sans-serif | 14px | normal | #333333 |
| Table Header | Arial, sans-serif | 14px | bold | #ffffff |
| Table Body | Arial, sans-serif | 13px | normal | #333333 |
| Input Text | Arial, sans-serif | 14px | normal | #333333 |
| Placeholder Text | Arial, sans-serif | 14px | normal | #999999 |

---

## 11. SPACING & SIZING

| Element | Value |
|---------|-------|
| Page Padding | 30px |
| Card Padding | 25px |
| Section Margin | 20px |
| Form Group Margin | 20px |
| Gap between flex items | 15px |
| Border Radius (Cards) | 15px |
| Border Radius (Inputs) | 8px |
| Border Radius (Buttons) | 8px |
| Input Height | 45px (normal), 50px (buttons) |
| Button Height | 50px |
| Table Padding | 12-15px |
| Card Box Shadow | 0 5px 20px rgba(0,0,0,0.1) |
| Hover Box Shadow | 0 6px 20px rgba(0,0,0,0.15) |

---

## 12. RESPONSIVE BREAKPOINTS

| Device | Width | Grid Columns | Adjustments |
|--------|-------|--------------|-------------|
| Mobile | < 768px | 1 | Full width, reduced padding |
| Tablet | 768px - 1024px | 2 | Reduced margins, compact cards |
| Desktop | > 1024px | 3 | Full spacing, optimal layout |

---

*Dokumentasi dibuat untuk Sistem Manajemen Produk - versi 1.0*
*Last Updated: 2024*
