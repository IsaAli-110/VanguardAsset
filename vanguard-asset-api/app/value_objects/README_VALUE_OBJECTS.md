# OOP Bonus: Value Objects (Immutable Objects)

## Definisi Konsep
Value Object adalah objek kecil sederhana yang mewakili nilai atau entitas tanpa identitas konseptualnya sendiri. Dua Value Object dianggap setara (equal) murni jika seluruh nilai properti di dalamnya sama. Mereka dirancang bersifat Immutable (tidak dapat diubah setelah instansiasi selesai).

## Mengapa Konsep Ini Penting?
Immutability (ketidakberubahan) sangat penting dalam pengembangan perangkat lunak, terutama dalam sistem keamanan dan audit. Dengan membekukan objek log audit (`AuditTrailEntry`), kita menjamin bahwa data log tidak dapat dimanipulasi secara tidak sengaja oleh bagian kode lain setelah dihasilkan.

## Implementasi dalam Code
Kami menggunakan decorator bawaan Python `@dataclass(frozen=True)` untuk mengimplementasikan `AuditTrailEntry`. Jika ada bagian program yang mencoba mengubah nilai properti (misalnya `entry.remaining_value = 0.0`), Python akan melempar error `FrozenInstanceError` pada saat runtime.
