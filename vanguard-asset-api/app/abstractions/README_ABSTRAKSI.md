# OOP Pilar 1: Abstraksi (Abstraction)

## Definisi Konsep
Abstraksi adalah proses menyembunyikan detail implementasi internal yang kompleks dan hanya menampilkan fungsionalitas utama atau "kontrak" penting kepada dunia luar. Ini dicapai dengan mendefinisikan kelas abstrak yang tidak dapat diinstansiasi secara langsung tetapi mendefinisikan struktur metode untuk kelas anak.

## Mengapa Konsep Ini Penting?
Abstraksi membatasi kompleksitas dengan membiarkan pengembang fokus pada interaksi tingkat tinggi daripada implementasi spesifik dari setiap jenis aset. Sebagai contoh, sistem hanya perlu tahu bahwa suatu objek adalah `CompanyAsset` dan dapat disusutkan, tanpa perlu tahu detail rumus penyusutan spesifik masing-masing aset fisik atau digital saat memprosesnya.

## Implementasi dalam Code
Kami menggunakan modul bawaan Python `abc.ABC` (Abstract Base Class) dan decorator `@abstractmethod` untuk membuat kelas dasar abstrak `CompanyAsset`. Metode `calculate_depreciation` ditandai sebagai metode abstrak, yang berarti setiap subclass konkret (seperti `PhysicalAsset` atau `DigitalAsset`) **wajib** mengimplementasikan logika penyusutannya sendiri.
