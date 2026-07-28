from abc import ABC, abstractmethod
from datetime import date
from typing import Dict, Any
from app.interfaces.loggable import Loggable, AuditTrailEntry
from app.interfaces.depreciable import Depreciable

class CompanyAsset(Loggable, Depreciable, ABC):
    """
    Kelas Abstrak (Abstract Class) yang menjadi cetak biru bagi semua aset perusahaan.
    Menerapkan pilar Abstraksi dengan memaksa subclass mendefinisikan metode abstrak.
    """
    def __init__(self, asset_id: str, name: str, purchase_cost: float, purchase_date: date):
        self.asset_id = asset_id
        self.name = name
        # Validasi enkapsulasi dipanggil melalui setter
        self.purchase_cost = purchase_cost
        self.purchase_date = purchase_date

    @property
    def purchase_cost(self) -> float:
        """Getter untuk purchase_cost (Enkapsulasi)."""
        return self._purchase_cost

    @purchase_cost.setter
    def purchase_cost(self, value: float):
        """Setter dengan validasi untuk purchase_cost (Enkapsulasi)."""
        if value <= 0:
            raise ValueError("Purchase cost must be greater than zero.")
        self._purchase_cost = value

    @abstractmethod
    def calculate_depreciation(self) -> float:
        """
        Metode abstrak untuk menghitung nilai penyusutan aset.
        Wajib diimplementasikan oleh subclass (Pewarisan & Polimorfisme).
        """
        pass

    @abstractmethod
    def get_audit_details(self) -> Dict[str, Any]:
        """
        Metode abstrak untuk mendapatkan detail khusus subclass untuk log audit.
        Wajib diimplementasikan oleh subclass.
        """
        pass

    def generate_audit_trail(self, depreciation_amount: float, remaining_value: float) -> AuditTrailEntry:
        """
        Mengimplementasikan interface Loggable untuk menghasilkan entri log audit.
        """
        from datetime import datetime
        return AuditTrailEntry(
            asset_id=self.asset_id,
            name=self.name,
            asset_type=self.__class__.__name__,
            calculated_at=datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
            purchase_cost=self.purchase_cost,
            depreciation_amount=depreciation_amount,
            remaining_value=remaining_value,
            details=self.get_audit_details()
        )
