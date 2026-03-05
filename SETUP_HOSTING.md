# Panduan Hosting ke Vercel + Aiven

Projek ini telah disiapkan untuk dihosting menggunakan Vercel (Frontend/Logic) dan Aiven (MySQL Database).

## 1. Persiapan Database (Aiven)
1. Buat akun di [Aiven.io](https://aiven.io/).
2. Buat layanan baru **MySQL**. Pilih plan gratis (Free Tier) jika tersedia.
3. Tunggu hingga status layanan menjadi `Running`.
4. Buka tab **Overview** pada dashboard Aiven untuk mendapatkan detail koneksi:
   - Host
   - Port
   - User (`avnadmin`)
   - Password
   - Database Name (`defaultdb` atau nama lain yang Anda buat)
5. Import file `database.sql` ke Aiven:
   - Gunakan MySQL Workbench, DBeaver, atau Command Line.
   - **Catatan**: Jika muncul error pada baris `CREATE DATABASE`, hapus baris `CREATE DATABASE` dan `USE` di awal file `database.sql` dan import langsung ke database yang sudah disediakan Aiven.

## 2. Persiapan Vercel
1. Install [Vercel CLI](https://vercel.com/cli) atau hubungkan repository GitHub Anda ke Vercel.
2. Masukkan **Environment Variables** di Dashboard Vercel (Settings -> Environment Variables):
   - `DB_HOST`: *Host dari Aiven*
   - `DB_USER`: `avnadmin`
   - `DB_PASS`: *Password dari Aiven*
   - `DB_NAME`: `defaultdb` (atau sesuai yang ada di Aiven)
   - `DB_PORT`: *Port dari Aiven (biasanya 10xxx - 2xxxx)*
   - `DB_SSL`: `true`
   - `BASEURL`: `https://nama-projek-anda.vercel.app`

## 3. Deploy
Jika menggunakan Vercel CLI:
```bash
vercel --prod
```
Atau cukup push ke GitHub jika sudah terhubung.

## 4. Troubleshooting
- **Database Connection Error**: Pastikan alamat host Aiven dan port sudah benar. Periksa apakah IP Vercel perlu di-whitelist di Aiven (biasanya di Aiven set `0.0.0.0/0` untuk allow all sementara jika perlu).
- **Session Issues**: Vercel menggunakan serverless functions, session PHP standar (file-based) mungkin hilang jika function melakukan cold start. Disarankan menggunakan Database Session jika aplikasi sangat bergantung pada session yang persisten lama.
