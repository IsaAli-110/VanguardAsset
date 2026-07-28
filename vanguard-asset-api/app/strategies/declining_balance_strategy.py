from datetime import date
from app.strategies.depreciation_strategy import DepreciationStrategy

class DecliningBalanceStrategy(DepreciationStrategy):
    """
    Strategi Depresiasi Saldo Menurun (Declining Balance Method).
    
    Aset disusutkan lebih besar di tahun-tahun awal dan berkurang seiring waktu.
    Formula per tahun: Nilai Buku × 30%
    Kumulatif: Depresiasi = Biaya × (1 - (1 - 0.30)^umur_tahun)
    
    Cocok untuk aset yang kehilangan nilai lebih cepat di awal (misalnya kendaraan, elektronik).
    """

    def calculate(self, purchase_cost: float, purchase_date: date, **kwargs) -> float:
        current_date = date.today()
        if current_date <= purchase_date:
            return 0.0

        age_days = (current_date - purchase_date).days
        age_years = age_days / 365.25

        # Depresiasi saldo menurun 30% per tahun
        # Formula kumulatif: cost * (1 - (1 - rate)^years)
        rate = 0.30
        depreciation_amount = purchase_cost * (1 - (1 - rate) ** age_years)
        return round(min(purchase_cost, max(0.0, depreciation_amount)), 2)

    @property
    def name(self) -> str:
        return "declining_balance"

    @property
    def description(self) -> str:
        return "Saldo Menurun — 30% per tahun dari nilai buku tersisa"
