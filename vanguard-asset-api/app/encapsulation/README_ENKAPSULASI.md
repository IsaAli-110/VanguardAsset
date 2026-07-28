# OOP Pilar 2: Enkapsulasi (Encapsulation)

## Definisi Konsep
Enkapsulasi adalah pembungkusan data (atribut) dan metode (fungsi) yang beroperasi pada data tersebut ke dalam satu unit (kelas), serta membatasi akses langsung ke beberapa detail objek. Di Python, pembatasan akses (informasi hiding) dilakukan dengan konvensi nama menggunakan prefix single underscore (`_`) atau double underscore (`__`) untuk atribut privat.

## Mengapa Konsep Ini Penting?
Enkapsulasi melindungi integritas data internal objek dari perubahan ilegal atau tidak sengaja dari luar kelas. Dengan memaksakan akses melalui getter/setter, kita dapat menyisipkan logika validasi (misalnya: memastikan nilai pembelian aset tidak boleh nol atau negatif) untuk memastikan state objek selalu valid.

## Implementasi dalam Code
Kami membuat kelas `CostManager` yang mendefinisikan atribut privat `__cost`. Nilai ini hanya dapat diubah melalui setter `@cost.setter` yang melakukan validasi apakah nilai baru lebih besar dari nol. Kelas `CompanyAsset` juga menerapkan enkapsulasi pada properti `purchase_cost` miliknya.
