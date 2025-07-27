# Laravel Inventory Analytics System

Цей проєкт об'єднує дані з двох умовних систем — **Firma** та **Linker**, і виводить зведену інформацію у вигляді таблиці з фільтрами, пагінацією та аналітикою по товарах.

## 🔧 Встановлення та розгортання

### 1. Клонуйте репозиторій
```
git clone https://github.com/sbabikov/inventory-analytics.git
cd inventory-analytics
```

### 2. Встановіть залежності
```
composer install

```

### 3. Створіть .env файл
```
cp .env.example .env

```

Змініть параметри підключення до БД у .env:
```
DB_DATABASE=firma_linker_analytics
DB_USERNAME=analytics_user
DB_PASSWORD=
```

### 4. Запустіть міграції
```
php artisan migrate
```

### 5. Запустіть команду для генерації данних
```
php artisan external:refresh
```

### 6. Запустіть локальний сервер
```
php artisan serve
```

### 7. Увійдіть у систему
```
http://127.0.0.1:8000
```
