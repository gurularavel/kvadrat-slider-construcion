# Kvadrat Slider Constructor — Laravel Package

[![Laravel](https://img.shields.io/badge/Laravel-10%2B%20%7C%2011%2B%20%7C%2012%2B-red?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue?logo=php)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

**Drag & Drop Slider Constructor** — Laravel üçün hazır paket.
Admin paneldə vizual slider qura, frontend-də isə avtomatik embed ol.

---

## Screenshotlar

| Admin Editor | Frontend |
|---|---|
| Drag & drop ilə slide, layer əlavə et | Slider avtomatik səhifəyə embed olur |

---

## Xüsusiyyətlər

- **Drag & Drop** — Backbone.js əsaslı vizual editor
- **Layers** — Mətn, şəkil, ikon, video layerləri
- **Animasiyalar** — 40+ slide keçid + 40+ layer animasiya effekti
- **Auto-play**, **Ox düymələri**, **Nöqtə naviqasiyası**
- **Şəkil yükləmə** — PHP GD ilə avtomatik resize
- **Server-side HTML rendering** — SEO dostu, sürətli frontend
- **MySQL** — Bütün slider data JSON formatda saxlanılır
- **Konfiqurə edilə bilən** — URL prefiksi, middleware, ölçülər
- **Laravel 10 / 11 / 12** dəstəyi

---

## Quraşdırma

### 1. Paketi yüklə

```bash
composer require msc/slider-constructor
```

### 2. Assets publish et

```bash
php artisan vendor:publish --tag=slider-constructor-assets
php artisan storage:link
```

### 3. Migration çalışdır

```bash
php artisan migrate
```

> Bu addımlar ilə `sliders` cədvəli yaranır və JS/CSS faylları `public/vendor/slider/` qovluğuna kopyalanır.

### 4. (İxtiyari) Konfiqurasiyanı publish et

```bash
php artisan vendor:publish --tag=slider-constructor-config
```

Bu `config/slider-constructor.php` faylını yaradır.

---

## İstifadə

Quraşdırmadan sonra bu URL-lər avtomatik aktivləşir:

| URL | Açıqlama |
|-----|----------|
| `GET /{prefix}/` | **Frontend** — slider embedded göstərilir |
| `GET /{prefix}/admin/` | **Admin Editor** — Drag & Drop constructor |
| `ANY /{prefix}/admin/api` | **JS API** — slider data CRUD |
| `POST /{prefix}/admin/upload` | **Şəkil yükləmə** |

> Default prefix: `slider`
> Yəni: `http://your-app.test/slider/` və `http://your-app.test/slider/admin/`

---

## Konfiqurasiya

`config/slider-constructor.php`:

```php
return [
    // URL prefiksi
    // Frontend: /slider/    Admin: /slider/admin/
    'prefix' => 'slider',

    // Frontend middleware
    'middleware' => ['web'],

    // Admin middleware — auth əlavə etmək üçün:
    // 'admin_middleware' => ['web', 'auth'],
    'admin_middleware' => ['web'],

    // Şəkil yükləmə
    'upload_disk'   => 'public',
    'upload_folder' => 'slider-uploads',

    // Default slider ölçüsü (ilk dəfə yarananda)
    'default_width'  => 1200,
    'default_height' => 500,
];
```

### Admin üçün auth əlavə et

```php
// config/slider-constructor.php
'admin_middleware' => ['web', 'auth'],
```

---

## Necə işləyir

```
Kullanıcı          Admin Editor              DB (MySQL)         Frontend
   │                    │                        │                  │
   │──── /slider/admin/ ──>                      │                  │
   │                    │── openFile(id) ────>   │                  │
   │                    │<── slider data ─────   │                  │
   │   [Slide düzəlt]   │                        │                  │
   │                    │── POST api?a=save ──>  │                  │
   │                    │                        │── UPDATE sliders │
   │──── /slider/ ─────────────────────────────────────────────>    │
   │                                             │<─ SELECT slider  │
   │<──────── Server-side HTML (SEO dostu) ─────────────────────    │
```

**Slider data axışı:**
1. Admin editor-da slider qur → `?a=save` ilə MySQL-ə yazılır
2. Frontend-də `Slider::getDefault()` DB-dən oxuyur
3. Blade template server-side HTML render edir
4. `new MySlider(containerId, options)` JS naviqasiya/animasiya əlavə edir

---

## Fayl Strukturu

```
src/
├── SliderConstructorServiceProvider.php
├── Models/
│   └── Slider.php                  # getDefault(), toSliderArray()
└── Http/Controllers/
    ├── AdminController.php          # GET /admin/ → editor
    ├── FrontController.php          # GET / → frontend
    ├── SliderApiController.php      # ?a=getfiles|save|item|jsLang
    └── UploadController.php         # POST /admin/upload
config/
└── slider-constructor.php
database/migrations/
└── 2024_01_01_000000_create_sliders_table.php
resources/views/
├── admin/editor.blade.php          # Drag & Drop editor (Backbone.js)
└── front/index.blade.php           # Frontend embed view
routes/
└── web.php
public/                             # JS/CSS assets (vendor:publish ilə kopyalanır)
├── bundle/
│   ├── css/myslider_constructor.min.css
│   └── js/myslider_constructor.min.js
├── js/myslider.js
├── css/
│   ├── bootstrap-custom/
│   ├── icomoon/
│   ├── icomoon2/
│   └── icomoon-custom/
├── img/
├── config/
│   ├── icons.json
│   └── icons2.json
└── lib/jquery/
```

---

## View-ları Override Et

```bash
php artisan vendor:publish --tag=slider-constructor-views
```

Bu `resources/views/vendor/slider-constructor/` qovluğunu yaradır.
Oradakı view-ları öz dizaynına uyğun dəyişə bilərsən.

---

## API Endpoint-ləri

`GET/POST /{prefix}/admin/api?a={action}`

| Action | Method | Açıqlama |
|--------|--------|----------|
| `getfiles` | GET | Default slider məlumatı |
| `save` | POST | Slider saxla/yenilə |
| `item` | GET | Slider data al |
| `item` | DELETE | Slider data sıfırla |
| `upload` | POST | Şəkil yüklə |
| `jsLang` | GET | JS dil obyekti |

---

## Tələblər

- PHP 8.1+
- Laravel 10 / 11 / 12
- MySQL 5.7+ (JSON column dəstəyi üçün)
- PHP GD extension (şəkil resize üçün)

---

## Lisenziya

MIT — istədiyin kimi istifadə et.
