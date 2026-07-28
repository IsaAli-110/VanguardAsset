from datetime import date
from app.strategies.depreciation_strategy import DepreciationStrategy

class StraightLineStrategy(DepreciationStrategy):
    """
    Strategi Depresiasi Garis Lurus (Straight-Line Method).
    
    Aset disusutkan secara merata setiap tahun dengan tarif tetap 20% dari biaya perolehan.
    Formula: Depresiasi = Biaya × 20% × (Umur dalam tahun)
    
    Cocok untuk aset fisik yang penurunan nilainya linear seiring waktu.
    """

    def calculate(self, purchase_cost: float, purchase_date: date, **kwargs) -> float:
        current_date = date.today()
        if current_date <= purchase_date:
            return 0.0

        age_days = (current_date - purchase_date).days
        age_years = age_days / 365.25

        # Depresiasi 20% per tahun (garis lurus)
        depreciation_amount = purchase_cost * 0.20 * age_years
        return round(min(purchase_cost, depreciation_amount), 2)

    @property
    def name(self) -> str:
        return "straight_line"

    @property
    def description(self) -> str:
        return "Garis Lurus — 20% per tahun dari biaya perolehan"
