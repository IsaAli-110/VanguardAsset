from datetime import date
from app.strategies.depreciation_strategy import DepreciationStrategy

class SumOfYearsStrategy(DepreciationStrategy):
    """
    Strategi Depresiasi Jumlah Angka Tahun (Sum-of-the-Years'-Digits / SYD).
    
    Metode depresiasi dipercepat yang memberikan beban depresiasi lebih tinggi
    di tahun-tahun awal dan menurun di tahun-tahun berikutnya.
    
    Formula:
        Total umur ekonomis = N tahun (default 5 tahun)
        Jumlah angka tahun (SYD) = N × (N + 1) / 2
        Depresiasi tahun ke-k = Biaya × (N - k + 1) / SYD
    
    Cocok untuk aset teknologi yang cepat usang (misalnya server, perangkat lunak).
    """

    def __init__(self, useful_life_years: int = 5):
        """
        Args:
            useful_life_years: Umur ekonomis aset dalam tahun (default 5).
        """
        self._useful_life = useful_life_years

    def calculate(self, purchase_cost: float, purchase_date: date, **kwargs) -> float:
        current_date = date.today()
        if current_date <= purchase_date:
            return 0.0

        age_days = (current_date - purchase_date).days
        age_years = age_days / 365.25

        # Jangan depresiasi melebihi umur ekonomis
        if age_years >= self._useful_life:
            return round(purchase_cost, 2)

        # Hitung Sum of Years' Digits
        n = self._useful_life
        syd = n * (n + 1) / 2  # misal 5 tahun: 5+4+3+2+1 = 15

        # Hitung depresiasi kumulatif tahun per tahun
        total_depreciation = 0.0
        full_years = int(age_years)

        for year in range(1, full_years + 1):
            # Bobot tahun ke-k: (N - k + 1) / SYD
            weight = (n - year + 1) / syd
            total_depreciation += purchase_cost * weight

        # Tambahkan proporsi tahun berjalan (partial year)
        partial_year = age_years - full_years
        if partial_year > 0 and (full_years + 1) <= n:
            next_year_weight = (n - (full_years + 1) + 1) / syd
            total_depreciation += purchase_cost * next_year_weight * partial_year

        return round(min(purchase_cost, max(0.0, total_depreciation)), 2)

    @property
    def name(self) -> str:
        return "sum_of_years"

    @property
    def description(self) -> str:
        return f"Jumlah Angka Tahun (SYD) — umur ekonomis {self._useful_life} tahun"
