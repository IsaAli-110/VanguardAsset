# OOP Pilar 5: Interface / Protokol (Interfaces)

## Definisi Konsep
Interface mendefinisikan sebuah "kontrak" perilaku atau metode yang harus diimplementasikan oleh kelas lain, tanpa menyediakan implementasi detail metode tersebut. Di Python, interface dapat diimplementasikan dengan kelas abstrak murni (`abc.ABC` dengan `@abstractmethod`) atau menggunakan `typing.Protocol` (duck typing statis).

## Mengapa Konsep Ini Penting?
Interface memungkinkan pemisahan antara definisi perilaku (apa yang bisa dilakukan objek) dari detail implementasinya (bagaimana cara objek melakukannya). Ini memfasilitasi pemrograman modular yang longgar (loose coupling) dan mendukung prinsip "Program to an interface, not an implementation."

## Contoh Implementasi
1. **`Depreciable`**: Menjamin objek apa pun yang dapat disusutkan memiliki metode `calculate_depreciation()`.
2. **`Loggable`**: Menjamin objek apa pun yang dapat dicatat aktivitasnya memiliki metode `generate_audit_trail()`.
