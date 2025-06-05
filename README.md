# Laravel Product Management System

Sistem manajemen produk lengkap yang dibangun dengan Laravel, menggunakan Eloquent ORM, Blade templates, dan Bootstrap untuk antarmuka pengguna yang modern dan responsif.

## 📋 Deskripsi

Aplikasi web CRUD (Create, Read, Update, Delete) untuk mengelola data produk dan kategori. Sistem ini memungkinkan pengguna untuk:
- Mengelola produk dengan kategori
- Pencarian dan filter produk
- Pagination dengan navigasi custom
- Dashboard homepage dengan statistik
- Interface yang responsif dan user-friendly
- Sistem alert dan notifikasi

## 🚀 Fitur Utama

### ✨ Manajemen Produk
- **CRUD Operations**: Create, Read, Update, Delete produk
- **Kategori Produk**: Sistem kategorisasi dengan warna
- **Search & Filter**: Pencarian berdasarkan nama/deskripsi, filter kategori, range harga
- **Sorting**: Pengurutan berdasarkan nama, harga, atau tanggal
- **Stock Management**: Tracking stok produk
- **Status Management**: Active/inactive products

### 🎯 Dashboard & Analytics
- **Homepage Dashboard**: Statistik lengkap produk
- **Quick Statistics**: Total produk, kategori, rata-rata harga, total stok
- **Featured Products**: Produk unggulan di homepage
- **Category Overview**: Browse berdasarkan kategori

### 🎨 UI/UX Features
- **Responsive Design**: Bootstrap 5 untuk semua device
- **Custom Components**: Alert, button, pagination yang reusable
- **Interactive Elements**: Hover effects, smooth transitions
- **Icon Integration**: Bootstrap Icons terintegrasi
- **Color-coded Categories**: Setiap kategori memiliki warna unik
- **Modern Grid Layout**: Card-based product display

### 🔧 Technical Features
- **Eloquent ORM**: Database relationships dan query optimization
- **Blade Components**: Komponen yang dapat digunakan ulang
- **Flash Messages**: Sistem notifikasi otomatis
- **Form Validation**: Validasi input dengan error handling
- **SEO-friendly URLs**: Route naming convention
- **Database Seeding**: Data dummy untuk development

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 10+
- **Database**: MySQL/PostgreSQL dengan Eloquent ORM
- **Frontend**: Bootstrap 5.3, Custom CSS
- **Template Engine**: Blade with Components
- **Icons**: Bootstrap Icons 1.11+
- **JavaScript**: Vanilla JS untuk interaktivitas

## 📊 Database Schema

### Products Table
```php
- id (bigint, primary key)
- name (string, required)
- description (text, nullable)
- price (decimal:2, required)
- category_id (foreign key to categories)
- stock (integer, required)
- is_active (boolean, default: true)
- created_at, updated_at (timestamps)
```

### Categories Table
```php
- id (bigint, primary key)
- name (string, required)
- description (text, nullable)
- color (string, hex color)
- created_at, updated_at (timestamps)
```

### Users Table
```php
- id (bigint, primary key)
- name (string)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string, hashed)
- remember_token (string, nullable)
- created_at, updated_at (timestamps)
```

## 📁 Struktur Project

```
├── app/
│   ├── Http/Controllers/
│   │   └── ProductController.php          # Controller utama untuk produk
│   └── Models/
│       ├── Product.php                    # Model Product dengan relationships
│       ├── Category.php                   # Model Category
│       └── User.php                       # Model User
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php         # Migration users
│   │   ├── create_cache_table.php         # Migration cache
│   │   └── create_jobs_table.php          # Migration jobs
│   └── seeders/
│       └── DatabaseSeeder.php             # Seeder dengan 30+ produk dummy
├── routes/
│   └── web.php                            # Definisi routes dengan grouping
├── resources/views/
│   ├── components/
│   │   ├── template.blade.php             # Layout template utama
│   │   ├── alert.blade.php               # Alert component
│   │   └── button.blade.php              # Button component
│   ├── pagination/
│   │   └── custom.blade.php              # Custom pagination view
│   ├── products/
│   │   ├── list.blade.php                # Halaman daftar produk
│   │   ├── form.blade.php                # Form create/edit produk
│   │   └── show.blade.php                # Detail produk
│   └── home.blade.php                    # Homepage dashboard
└── README.md
```

## ⚙️ Instalasi dan Setup

### Prerequisites
- PHP 8.1+
- Composer
- MySQL/PostgreSQL
- Laravel 10+

### Langkah Instalasi

1. **Clone atau download project**
   ```bash
   git clone https://github.com/Yasminingrum/laravel 
   cd laravel
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi database di `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=product_management
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Jalankan migration dan seeding**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Jalankan server**
   ```bash
   php artisan serve
   ```

7. **Akses aplikasi**
   - Homepage: `http://localhost:8000`
   - Products: `http://localhost:8000/products`

## 🗺️ Routes

| Method | URI | Name | Controller@Method | Deskripsi |
|--------|-----|------|------------------|-----------|
| GET | `/` | home | ProductController@home | Dashboard homepage |
| GET | `/products` | products | ProductController@index | Daftar produk dengan filter |
| GET | `/products/create` | products.create | ProductController@create | Form tambah produk |
| GET | `/products/edit/{id}` | products.edit | ProductController@edit | Form edit produk |
| POST | `/products/store` | products.store | ProductController@store | Simpan produk baru |
| POST | `/products/update/{id}` | products.update | ProductController@update | Update produk |
| GET | `/products/show/{id}` | products.show | ProductController@show | Detail produk |
| DELETE | `/products/delete/{id}` | products.destroy | ProductController@destroy | Hapus produk |

## 📄 Halaman Aplikasi

### 🏠 Homepage Dashboard (`/`)
- **Hero Section**: Search bar utama dengan branding
- **Statistics Cards**: Total produk, kategori, rata-rata harga, total stok
- **Categories Section**: Browse produk berdasarkan kategori
- **Featured Products**: 6 produk unggulan
- **Quick Actions**: Shortcut ke fungsi utama

### 📋 Daftar Produk (`/products`)
- **Advanced Search**: Filter berdasarkan nama, kategori, range harga
- **Sorting Options**: Nama, harga, tanggal (ascending/descending)
- **Grid Layout**: Card-based responsive design
- **Pagination**: Custom pagination dengan info hasil
- **Product Cards**: Nama, kategori (color-coded), harga, stok, actions

### ➕ Form Produk (`/products/create` dan `/products/edit/{id}`)
- **Dynamic Form**: Sama untuk create dan edit
- **Fields**: Name, Category, Description, Price, Stock
- **Validation**: Client-side dan server-side validation
- **Error Handling**: Individual field error messages
- **Auto-fill**: Old input values pada error

### 👁️ Detail Produk (`/products/show/{id}`)
- **Complete Info**: ID, nama, kategori, deskripsi, harga, stok, status
- **Visual Elements**: Color-coded badges, icons
- **Actions**: Edit button, back to list
- **Timestamps**: Created date information

## 🧩 Blade Components

### Template Component (`x-template`)
```php
<x-template title="Page Title" bodyClass="bg-light">
    <x-slot name="header">
        <h1>Page Header</h1>
        <x-button href="/action">Action</x-button>
    </x-slot>
    
    <!-- Page content -->
</x-template>
```

**Props:**
- `title`: Judul halaman (default: Laravel Application)
- `bodyClass`: CSS class untuk body element
- `containerClass`: Container class (default: container)

**Slots:**
- `header`: Header section dengan title dan actions
- `navigation`: Custom navigation (optional)
- `footer`: Custom footer (optional)

**Auto Features:**
- Flash message handling (success, error, warning, info)
- Validation error display
- Active navigation highlighting
- Search form di navbar
- CSRF token meta tag

### Alert Component (`x-alert`)
```php
<x-alert type="success" :dismissible="false">
    <strong>Success!</strong> Product saved successfully.
</x-alert>
```

**Props:**
- `type`: success, danger, warning, info, primary, secondary (default: info)
- `dismissible`: true/false (default: true)
- `icon`: true/false (default: true)

### Button Component (`x-button`)
```php
<x-button href="/products/create" type="success" size="lg">
    <i class="bi bi-plus-circle me-1"></i>Add Product
</x-button>
```

**Props:**
- `href`: URL tujuan (required)
- `type`: primary, secondary, success, danger, warning, info (default: primary)
- `size`: sm, md, lg (default: md)

### Custom Pagination (`pagination.custom`)
- Bootstrap-styled pagination
- Previous/Next dengan icons
- Page numbers dengan ellipsis
- Results summary
- Responsive (icons only di mobile)

## 📊 Model Features

### Product Model
```php
// Relationships
$product->category          // belongsTo Category

// Accessors
$product->formatted_price   // "Rp 1.500.000"

// Scopes
Product::active()                           // hanya produk aktif
Product::search($keyword)                   // search nama/deskripsi
Product::priceRange($min, $max)            // filter range harga
```

### Category Model
```php
// Relationships
$category->products         // hasMany Product

// Accessors
$category->products_count   // jumlah produk dalam kategori
```

## 🎨 Styling & UI

### Color System
- **Primary**: Bootstrap blue (#007bff)
- **Success**: Green untuk harga dan stok
- **Warning**: Yellow untuk edit actions
- **Info**: Blue untuk view actions
- **Custom**: Setiap kategori memiliki warna unik

### Interactive Elements
- **Hover Effects**: Card transforms, smooth transitions
- **Color-coded Categories**: Visual distinction
- **Badge System**: Status, stock, categories
- **Icon Integration**: Meaningful icons untuk semua actions

### Responsive Design
- **Mobile-first**: Bootstrap grid system
- **Adaptive UI**: Forms, cards, navigation
- **Touch-friendly**: Button sizes, spacing

## 💾 Data & Seeding

### Included Categories
1. **Elektronik** (#007bff) - 8 produk
2. **Fashion** (#e83e8c) - 8 produk  
3. **Rumah Tangga** (#28a745) - 8 produk
4. **Olahraga** (#fd7e14) - 6 produk

### Sample Products (30 total)
- **Elektronik**: Smartphone, Laptop, Headphone, Smart TV, Kamera, dll
- **Fashion**: Kemeja Batik, Dress, Sneakers, Tas Kulit, dll
- **Rumah Tangga**: Rice Cooker, Blender, Vacuum Cleaner, dll
- **Olahraga**: Sepeda Gunung, Treadmill, Dumbell Set, dll

Setiap produk memiliki:
- Nama dan deskripsi yang realistis
- Harga dalam format Rupiah (85.000 - 9.800.000)
- Stok yang bervariasi (3-50 items)
- Status aktif secara default

## 🔄 Development Roadmap

### Phase 1 - Core Features ✅
- [x] CRUD operations
- [x] Database dengan relationships
- [x] Search dan filtering
- [x] Pagination
- [x] Dashboard homepage

### Phase 2 - Enhanced Features 🚧
- [ ] Image upload untuk produk
- [ ] User authentication & authorization
- [ ] Role-based access (admin, user)
- [ ] Product categories management
- [ ] Bulk operations

### Phase 3 - Advanced Features 📋
- [ ] REST API endpoints
- [ ] Product variants (size, color)
- [ ] Inventory management
- [ ] Sales tracking
- [ ] Reporting dashboard
- [ ] Export/Import functionality

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
```bash
# Check database configuration
php artisan config:clear
php artisan cache:clear

# Test database connection
php artisan tinker
> DB::connection()->getPdo();
```

**Migration Errors**
```bash
# Reset database
php artisan migrate:fresh --seed

# Specific table issues
php artisan migrate:rollback
php artisan migrate
```

**Component Not Found**
- Pastikan file component ada di `resources/views/components/`
- Nama file harus kebab-case (template.blade.php untuk `x-template`)
- Clear view cache: `php artisan view:clear`

**Pagination Links Error**
- Pastikan custom pagination view ada di `resources/views/pagination/custom.blade.php`
- Check AppServiceProvider untuk pagination view binding

## 🚀 Performance Tips

### Database Optimization
```php
// Eager loading untuk menghindari N+1 queries
Product::with('category')->paginate(20);

// Index pada kolom yang sering di-query
Schema::table('products', function (Blueprint $table) {
    $table->index(['category_id', 'is_active']);
});
```

### Caching
```php
// Cache statistics untuk homepage
Cache::remember('product_stats', 3600, function () {
    return [
        'total_products' => Product::count(),
        // ... other stats
    ];
});
```

## 📝 Contributing Guidelines

1. Fork repository
2. Create feature branch: `git checkout -b feature/new-feature`
3. Commit changes: `git commit -m 'Add new feature'`
4. Push branch: `git push origin feature/new-feature`
5. Submit Pull Request

### Code Standards
- Follow PSR-12 coding standards
- Use meaningful variable and method names
- Add comments untuk logic yang kompleks
- Write tests untuk new features

## 📄 License

Project ini dibuat untuk keperluan edukasi dan pembelajaran Laravel.

## 👨‍💻 Credits

Dibuat sebagai sistem manajemen produk lengkap dengan fitur-fitur modern:
- ✅ Complete CRUD operations
- ✅ Database relationships (Eloquent ORM)
- ✅ Advanced search & filtering
- ✅ Custom pagination
- ✅ Dashboard dengan statistik
- ✅ Responsive design dengan Bootstrap 5
- ✅ Blade components architecture
- ✅ Seeding dengan data realistis
- ✅ Modern UI/UX dengan hover effects

---
