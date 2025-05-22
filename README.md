# Laravel Product Management System

Sistem manajemen produk sederhana yang dibangun dengan Laravel, menggunakan Blade templates dan Bootstrap untuk antarmuka pengguna.

## 📋 Deskripsi

Aplikasi web CRUD (Create, Read, Update, Delete) untuk mengelola data produk. Sistem ini memungkinkan pengguna untuk:
- Melihat daftar produk
- Menambah produk baru
- Mengedit produk yang sudah ada
- Melihat detail produk
- Navigasi yang mudah dengan antarmuka yang responsif

## 🚀 Fitur

- **CRUD Operations**: Create, Read, Update untuk produk
- **Responsive Design**: Bootstrap 5 untuk tampilan yang responsif
- **Blade Components**: Component yang dapat digunakan ulang
- **Flash Messages**: Notifikasi sukses/error otomatis
- **Form Validation**: Validasi input form
- **Clean URLs**: Route yang SEO friendly

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 10+
- **Frontend**: Bootstrap 5.3
- **Template Engine**: Blade
- **Icons**: Bootstrap Icons
- **Styling**: CSS via Bootstrap CDN

## 📁 Struktur Project

```
├── app/Http/Controllers/
│   └── ProductController.php          # Controller utama untuk produk
├── routes/
│   └── web.php                        # Definisi routes
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php             # Layout utama (opsional)
│   ├── components/
│   │   ├── alert.blade.php           # Component alert
│   │   ├── button.blade.php          # Component button
│   │   └── template.blade.php        # Template component
│   └── products/
│       ├── list.blade.php            # Halaman daftar produk
│       ├── form.blade.php            # Form create/edit produk
│       └── show.blade.php            # Detail produk
└── README.md
```

## ⚙️ Instalasi dan Setup

### Prerequisites
- PHP 8.1+
- Composer
- Laravel 10+

### Langkah Instalasi

1. **Clone atau download project**
   ```bash
   git clone <repository-url>
   cd laravel
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan server**
   ```bash
   php artisan serve
   ```

6. **Akses aplikasi**
   Buka browser dan akses: `http://localhost:8000/products`

## 🗺️ Routes

| Method | URI | Name | Controller | Deskripsi |
|--------|-----|------|------------|-----------|
| GET | `/products` | products | ProductController@index | Daftar semua produk |
| GET | `/products/create` | products.create | ProductController@create | Form tambah produk |
| GET | `/products/edit/{id}` | products.edit | ProductController@edit | Form edit produk |
| POST | `/products/store` | products.store | ProductController@store | Simpan produk baru |
| POST | `/products/update/{id}` | products.update | ProductController@update | Update produk |
| GET | `/products/show/{id}` | products.show | ProductController@show | Detail produk |

## 📄 Halaman

### 1. Daftar Produk (`/products`)
- Menampilkan 20 produk dummy dalam bentuk card grid
- Tombol "Add new product" untuk menambah produk baru
- Tombol "View" dan "Edit" untuk setiap produk
- Alert info yang menampilkan jumlah produk

### 2. Form Produk (`/products/create` dan `/products/edit/{id}`)
- Form dengan field: Name, Description, Price
- Validasi input required
- Tombol submit dan cancel
- Sama untuk create dan edit (form dinamis)

### 3. Detail Produk (`/products/show/{id}`)
- Menampilkan informasi lengkap produk
- Tombol untuk edit dan kembali ke list
- Layout yang rapi dengan card

## 🧩 Components

### Alert Component (`x-alert`)
```php
<x-alert type="success">
    Product saved successfully!
</x-alert>
```

**Props:**
- `type`: success, danger, warning, info (default: info)
- `dismissible`: true/false (default: true)
- `icon`: true/false (default: true)

### Button Component (`x-button`)
```php
<x-button href="/products" type="primary" size="sm">
    Click Me
</x-button>
```

**Props:**
- `href`: URL tujuan
- `type`: primary, secondary, success, danger, warning, info (default: primary)
- `size`: sm, md, lg (default: md)

### Template Component (`x-template`)
```php
<x-template title="Page Title">
    <x-slot name="header">
        <h1>Header Content</h1>
    </x-slot>
    
    <!-- Page content -->
</x-template>
```

**Props:**
- `title`: Judul halaman (default: Laravel Application)
- `bodyClass`: CSS class untuk body
- `containerClass`: CSS class untuk container (default: container)

**Slots:**
- `header`: Konten header halaman
- `navigation`: Custom navigation (opsional)
- `footer`: Custom footer (opsional)

## 💡 Fitur Template Component

- **Auto Flash Messages**: Otomatis menampilkan session flash messages
- **Error Handling**: Menampilkan validation errors
- **Active Navigation**: Menu navigation otomatis active
- **Bootstrap Integration**: Fully integrated dengan Bootstrap 5
- **Icon Support**: Bootstrap Icons terintegrasi

## 🎨 Styling

- Menggunakan **Bootstrap 5.3** via CDN
- **Bootstrap Icons** untuk ikon
- **Responsive grid system** untuk layout
- **Card components** untuk tampilan produk
- **Form styling** dengan Bootstrap classes

## 📊 Data

Saat ini menggunakan **dummy data** (array) untuk simulasi. Data produk meliputi:
- ID (auto-generated)
- Name (string)
- Description (text)
- Price (number, format Rupiah)

## 🔄 Development Notes

### Untuk Development Selanjutnya:
1. **Database Integration**: Tambahkan model dan migration untuk data persisten
2. **Image Upload**: Fitur upload gambar produk
3. **Search & Filter**: Pencarian dan filter produk
4. **Pagination**: Untuk handling data yang banyak
5. **User Authentication**: Login/register system
6. **API Endpoints**: RESTful API untuk mobile app

### Customization:
- Ubah warna tema di Bootstrap variables
- Tambah field produk sesuai kebutuhan
- Modifikasi layout template component
- Tambah validation rules

## 🐛 Troubleshooting

### Error 500
- Pastikan Laravel key sudah di-generate: `php artisan key:generate`
- Cek file `.env` sudah dikonfigurasi dengan benar

### Tampilan Tidak Muncul
- Pastikan route sudah benar
- Cek nama file view sesuai dengan yang dipanggil controller

### Component Tidak Bekerja
- Pastikan file component ada di `resources/views/components/`
- Nama file harus sesuai dengan nama component yang dipanggil

## 📝 License

Project ini dibuat untuk keperluan edukasi dan pembelajaran Laravel.

## 👨‍💻 Developer

Dibuat sebagai tugas progress report Laravel dengan requirement:
- ✅ ProductController dengan semua method CRUD
- ✅ Routes dengan group dan naming convention
- ✅ Blade templates dan components
- ✅ Bootstrap framework integration
- ✅ Form handling dan validation
- ✅ 20 produk dummy dengan directives

---
