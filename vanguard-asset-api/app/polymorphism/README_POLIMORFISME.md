# OOP Pilar 4: Polimorfisme (Polymorphism)

## Definisi Konsep
Polimorfisme ("banyak bentuk") adalah kemampuan objek dari berbagai kelas berbeda untuk merespons pemanggilan metode yang memiliki nama yang sama dengan cara yang berbeda secara unik. Kode pemanggil dapat berinteraksi dengan objek secara seragam tanpa peduli tipe spesifik kelasnya pada saat runtime.

## Mengapa Konsep Ini Penting?
Polimorfisme memungkinkan fleksibilitas yang sangat tinggi. Di FastAPI engine kami, saat menerima data aset, kita memanggil metode tunggal `asset.calculate_depreciation()`. Berkat polimorfisme, program akan menjalankan rumus penyusutan persentase tahunan untuk `PhysicalAsset`, atau rumus pembagian hari aktif lisensi untuk `DigitalAsset`, secara otomatis tanpa menggunakan struktur `if/else` bercabang yang kotor.

## Implementasi dalam Code
Metode `AssetFactory.create_asset()` mengembalikan objek bertipe `CompanyAsset` secara polimorfik. Di `main.py`, kita hanya perlu memanggil:
```python
depreciation_amount = asset.calculate_depreciation()
```
Python akan mencari implementasi metode `calculate_depreciation` yang benar di tingkat kelas konkrit secara dinamis pada saat runtime.
