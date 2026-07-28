# OOP Pilar 3: Pewarisan (Inheritance)

## Definisi Konsep
Pewarisan adalah mekanisme di mana sebuah kelas baru (subclass / kelas anak) dapat mengadopsi struktur data dan perilaku (metode) dari kelas lain yang sudah ada (superclass / kelas induk). Kelas anak dapat menambahkan data/metode baru atau memodifikasi metode yang diwarisi.

## Mengapa Konsep Ini Penting?
Pewarisan mempromosikan pemanfaatan kembali kode (code reusability) dan merancang model domain dunia nyata secara hierarkis. Menghindari penulisan berulang atribut dasar seperti `asset_id`, `name`, dan `purchase_cost` untuk setiap jenis aset baru yang ditambahkan ke sistem di masa depan.

## Implementasi dalam Code
Kami merancang `PhysicalAsset` dan `DigitalAsset` sebagai kelas anak yang mewarisi `CompanyAsset`. Keduanya secara otomatis memiliki semua atribut finansial dan metode pembuatan audit log dari `CompanyAsset`, sementara masing-masing menambahkan atribut spesifiknya (seperti `serial_number` untuk fisik, dan `license_key` untuk digital).
