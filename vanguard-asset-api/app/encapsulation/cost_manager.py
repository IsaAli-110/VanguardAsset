class CostManager:
    """
    Menerapkan pilar Enkapsulasi secara ketat.
    Menggunakan atribut privat (__cost) untuk menyembunyikan status internal,
    dan properti getter/setter untuk memberikan akses terkontrol dan validasi.
    """
    def __init__(self, initial_cost: float):
        self.cost = initial_cost

    @property
    def cost(self) -> float:
        """Getter properti untuk mengakses nilai cost privat."""
        return self.__cost

    @cost.setter
    def cost(self, value: float):
        """Setter properti dengan validasi ketat sebelum mengubah nilai privat."""
        if value <= 0:
            raise ValueError("Purchase cost must be greater than zero.")
        self.__cost = value
