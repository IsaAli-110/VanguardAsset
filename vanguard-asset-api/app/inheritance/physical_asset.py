from datetime import date
from typing import Dict, Any, Optional
from app.abstractions.base_asset import CompanyAsset
from app.strategies.depreciation_strategy import DepreciationStrategy
from app.strategies.straight_line_strategy import StraightLineStrategy

class PhysicalAsset(CompanyAsset):
    """
    Subclass yang mewarisi CompanyAsset.
    Merepresentasikan aset fisik dengan penyusutan yang dapat dikonfigurasi
    menggunakan Strategy Pattern. Default: StraightLineStrategy (20%/tahun).
    """
    def __init__(self, asset_id: str, name: str, purchase_cost: float, purchase_date: date, 
                 serial_number: str, maintenance_interval: int,
                 depreciation_strategy: Optional[DepreciationStrategy] = None):
        # Memanggil konstruktor superclass
        super().__init__(asset_id, name, purchase_cost, purchase_date)
        self.serial_number = serial_number
        self.maintenance_interval = maintenance_interval
        # Strategy Pattern: bisa di-inject dari luar, default StraightLine
        self._depreciation_strategy = depreciation_strategy or StraightLineStrategy()

    def calculate_depreciation(self) -> float:
        """
        Delegasikan perhitungan depresiasi ke Strategy yang di-inject.
        Ini adalah inti dari Strategy Pattern — logika depresiasi
        dapat diubah tanpa mengubah kelas PhysicalAsset.
        """
        return self._depreciation_strategy.calculate(
            purchase_cost=self.purchase_cost,
            purchase_date=self.purchase_date
        )

    def get_audit_details(self) -> Dict[str, Any]:
        """Implementasi spesifik untuk PhysicalAsset."""
        current_date = date.today()
        age_days = (current_date - self.purchase_date).days if current_date > self.purchase_date else 0
        return {
            "serial_number": self.serial_number,
            "maintenance_interval_days": self.maintenance_interval,
            "calculated_age_days": age_days,
            "depreciation_strategy": self._depreciation_strategy.name,
            "strategy_description": self._depreciation_strategy.description
        }
