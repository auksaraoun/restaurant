# Restaurant App

A simple responsive web application to display a list of restaurants based on a search keyword.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8 |
| Frontend | Vite + React 18, React Bootstrap, Axios |
| Database | MySQL |
| External APIs | Nominatim API, Overpass API (OpenStreetMap) |

---

## Project Structure

```
project-root/
├── backend/
└── frontend/
```

---

## Backend Setup

### 1. ติดตั้ง dependencies

```bash
cd backend
composer install
```

### 2. ตั้งค่า environment

```bash
cp .env.example .env
php artisan key:generate
```
> หากใช้ Apache/Nginx ไม่จำเป็นต้องรัน `php artisan serve` ให้ชี้ Document Root ไปที่ `backend/public` แทน

แก้ไข `.env` ให้ตรงกับ database ของคุณ

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_db
DB_USERNAME=root
DB_PASSWORD=
```

### 3. รัน migration

```bash
php artisan migrate
```

### 4. รัน server

```bash
php artisan serve
```

Backend จะรันที่ `http://localhost:8000`

---

## Frontend Setup

### 1. ติดตั้ง dependencies

```bash
cd frontend
npm install
```

### 2. ตั้งค่า proxy

ไฟล์ `vite.config.js` ตั้งค่า proxy ไปที่ backend แล้ว

```js
server: {
  proxy: {
    '/api': 'http://localhost:8000'
  }
}
```

> ถ้า backend รันที่ port อื่น ให้แก้ URL ใน `vite.config.js` ให้ตรงกัน
> หากใช้ Apache/Nginx ให้แก้ proxy ใน `vite.config.js` ให้ตรงกับ URL ที่ backend รันอยู่ เช่น `http://localhost/backend/public`

### 3. รัน development server

```bash
npm run dev
```

Frontend จะรันที่ `http://localhost:5173`

---

## How to Test

1. ตรวจสอบว่า Backend รันอยู่ที่ `http://localhost:8000`
2. รัน Frontend ด้วย `npm run dev`
3. เปิด browser ที่ `http://localhost:5173`
4. กรอกชื่อเขตหรืออำเภอในช่องค้นหา เช่น `Bang Sue`, `ลาดพร้าว`, `Lat Phrao`
5. กด **ค้นหา** เพื่อแสดงรายการร้านอาหาร

---

## API Endpoint

### `GET /api/restaurants`

| Parameter | Type   | Default    | Description                        |
|-----------|--------|------------|------------------------------------|
| `search`  | string | `Bang Sue` | ชื่อเขตหรืออำเภอ (ภาษาไทย/อังกฤษ) |

**Example Response**

```json
{
  "success": true,
  "data": [
    {
      "osm_id": 123456789,
      "name": "ร้านอาหาร A",
      "name_th": "ร้านอาหาร A",
      "name_en": "Restaurant A",
      "cuisine": "thai",
      "lat": 13.8021,
      "lon": 100.5217
    }
  ]
}
```

---

## Caching

- พิกัดของเขต/อำเภอจะถูก cache ไว้ใน `search_caches` table ไม่เรียก Nominatim API ซ้ำ
- ข้อมูลร้านอาหารจะถูก cache ไว้ใน `restaurants` table และดึงโดยใช้ range ±3 km จากพิกัดกลาง

---

## Known Issues & Limitations

- รองรับเฉพาะการค้นหาระดับ **เขต/อำเภอ** เท่านั้น หากกรอก keyword อื่นจะไม่พบผลลัพธ์
- จำกัดผลลัพธ์ร้านอาหารสูงสุด **50 รายการ** ต่อการค้นหา
- ไม่มีการแสดงผลบนแผนที่ (Map view)