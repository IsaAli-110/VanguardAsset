from abc import ABC, abstractmethod
from datetime import date

class DepreciationStrategy(ABC):
    """
    Kelas Abstrak (Strategy Pattern) yang mendefinisikan kontrak untuk semua
    algoritma perhitungan depresiasi aset.
    
    Setiap subclass mengimplementasikan metode calculate() dengan formula yang berbeda,
    memungkinkan pemilihan metode depresiasi secara dinamis saat runtime.
    """

    @abstractmethod
    def calculate(self, purchase_cost: float, purchase_date: date, **kwargs) -> float:
        """
        Hitung nilai depresiasi berdasarkan strategi yang diimplementasikan.
        
        Args:
            purchase_cost: Biaya pembelian aset.
            purchase_date: Tanggal pembelian aset.
            **kwargs: Parameter tambahan spesifik strategi.
        
        Returns:
            Nilai depresiasi dalam float (sudah dibulatkan 2 desimal).
        """
        pass

    @property
    @abstractmethod
    def name(self) -> str:
        """Nama strategi untuk keperluan logging dan audit."""
        pass

    @property
    @abstractmethod
    def description(self) -> str:
        """Deskripsi singkat formula yang digunakan."""
        pass
