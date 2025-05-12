<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel User API

API ini menyediakan endpoint untuk mengelola data pengguna. API mendukung operasi CRUD (Create, Read, Update, Delete) pada resource `User`. API ini menggunakan Laravel sebagai backend, dengan dokumentasi OpenAPI (Swagger) untuk memudahkan integrasi dan pengujian.

## Fitur

- **GET /api/users**: Mendapatkan daftar seluruh pengguna.
- **GET /api/users/{id}**: Mendapatkan detail pengguna berdasarkan ID.
- **POST /api/users**: Membuat pengguna baru.
- **PUT /api/users/{id}**: Memperbarui data pengguna berdasarkan ID.
- **DELETE /api/users/{id}**: Menghapus pengguna berdasarkan ID.

## Prasyarat

Sebelum menjalankan proyek ini, pastikan Anda sudah menginstal:

- PHP (versi 8.3 atau lebih tinggi)
- Composer
- Laravel 12.x atau lebih tinggi
- Database (misalnya MySQL, SQLite, atau PostgreSQL)

## Instalasi

1. **Clone repository ini** ke direktori lokal:

    ```bash
    git clone https://github.com/aldiyusronazhar/usynz.git
    cd repository-name
    ```

2. **Instalasi dependensi** menggunakan Composer:

    ```bash
    composer install
    ```

3. **Salin file `.env.example`** ke `.env` dan sesuaikan konfigurasi database:

    ```bash
    cp .env.example .env
    ```

4. **Generate key aplikasi Laravel**:

    ```bash
    php artisan key:generate
    ```

5. **Migrasi database** (jika menggunakan database yang diperlukan):

    ```bash
    php artisan migrate
    ```

6. **Jalankan server**:

    ```bash
    php artisan serve
    ```

API sekarang dapat diakses melalui `http://localhost:8000`.

## Dokumentasi API

Untuk dokumentasi API lengkap, buka **Swagger UI** yang disediakan pada URL berikut:  
[**API Documentation**](http://localhost:8000/api/documentation)

## Penggunaan API

### 1. Mendapatkan Daftar Pengguna
`GET /api/users`

Mengambil semua pengguna yang ada dalam database.

#### Response:
```json
[
    {
        "id": 1,
        "name": "Azhar",
        "email": "azhar@gmail.com",
        "age": 30,
        "phone_number": "087862811"
    },
    ...
]

