## VietFuture Demo (Laravel)

Dự án demo xây dựng bằng Laravel, sử dụng SQLite để phát triển nhanh, kèm sẵn migrations và seeders (sách, nội dung, phần thưởng…).

### Yêu cầu hệ thống

-   PHP 8.2+ (khuyến nghị cùng bản với `laravel/framework` trong `composer.json`)
-   Composer 2+
-   Node.js 18+ và npm/yarn (để build asset với Vite)
-   SQLite3 (đi kèm hầu hết hệ điều hành)

### Cài đặt

1. Cài dependency PHP

```bash
composer install
```

2. Cài dependency front-end

```bash
npm install
```

3. Tạo file môi trường

```bash
cp .env.example .env
php artisan key:generate
```

### Cấu hình cơ sở dữ liệu (SQLite)

Mặc định repo đã có file `database/database.sqlite` rỗng. Đảm bảo `.env` trỏ đúng tới SQLite:

```env
DB_CONNECTION=sqlite
DB_DATABASE="${BASE_PATH}/database/database.sqlite"
```

Lưu ý Windows: nếu biến trên không hoạt động, dùng đường dẫn tuyệt đối:

```env
DB_CONNECTION=sqlite
DB_DATABASE=D:/vietfuture_demo/vietfuture-demo/database/database.sqlite
```

### Khởi tạo dữ liệu

Chạy migrations và seeders:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Seeders bao gồm: `BookContentSeeder`, `RewardSeeder`, v.v…

Nếu cần làm mới dữ liệu nhanh:

```bash
php artisan migrate:fresh --seed --force
```

### Chạy ứng dụng

Chạy server PHP:

```bash
php artisan serve
```

Build assets và chạy Vite (chế độ dev với HMR):

```bash
npm run dev
```

Build production:

```bash
npm run build
```

### Liên kết storage (nếu cần upload/lưu file)

```bash
php artisan storage:link
```

### Lệnh Artisan hữu ích

-   `php artisan migrate` / `migrate:fresh --seed`
-   `php artisan tinker`
-   `php artisan route:list | cat` (trên Windows PowerShell có thể bỏ `| cat`)

### Kiểm thử

```bash
php artisan test
```

Hoặc dùng PHPUnit trực tiếp:

```bash
vendor/bin/phpunit
```

### Gỡ lỗi nhanh (Windows)

-   Quyền ghi SQLite: đảm bảo quyền ghi thư mục `database/` và file `database.sqlite`.
-   Xóa cache cấu hình: `php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear`.
-   Vite không tải CSS/JS: chạy `npm run dev` và reload trang; kiểm tra `VITE_*` trong `.env` nếu đã tùy chỉnh host/port.

### Cấu trúc chính

-   `app/Http/Controllers`: Controllers (ví dụ `AdminController`, `DemoController`)
-   `app/Models`: Eloquent Models (`Book`, `User`, `Reward`, …)
-   `database/migrations`: Migrations (bảng books, users, sessions, progress_records, community_threads, …)
-   `database/seeders`: Seeders (`DatabaseSeeder`, `BookContentSeeder`, `RewardSeeder`)
-   `resources/views`: Blade templates (admin, auth, community, quiz, …)
-   `public/`: asset công khai

### Góp ý/Phát triển thêm

-   Tạo nhánh mới từ `main`, mở Pull Request.
-   Tuân thủ chuẩn code PHP (Pint): `vendor/bin/pint`.

Chúc bạn chạy dự án vui vẻ!
