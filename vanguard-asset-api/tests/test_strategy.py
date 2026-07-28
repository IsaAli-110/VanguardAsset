"""
Test Strategy Pattern: Menguji semua 3 strategi depresiasi dengan nilai yang diketahui.
Membuktikan bahwa setiap strategi menghasilkan nilai depresiasi yang berbeda
untuk aset yang sama.
"""
import pytest
from datetime import date, timedelta
from app.strategies.straight_line_strategy import StraightLineStrategy
from app.strategies.declining_balance_strategy import DecliningBalanceStrategy
from app.strategies.sum_of_years_strategy import SumOfYearsStrategy
from app.strategies.depreciation_strategy import DepreciationStrategy


def test_strategies_are_abstract():
    """DepreciationStrategy adalah kelas abstrak, tidak bisa diinstansiasi langsung."""
    with pytest.raises(TypeError):
        DepreciationStrategy()


def test_straight_line_future_date():
    """Aset di masa depan = depresiasi 0."""
    strategy = StraightLineStrategy()
    result = strategy.calculate(10000000.0, date(2099, 1, 1))
    assert result == 0.0


def test_straight_line_one_year():
    """Aset 1 tahun lalu dengan straight line 20% = ~20% dari cost."""
    strategy = StraightLineStrategy()
    purchase_date = date.today() - timedelta(days=365)
    result = strategy.calculate(10000000.0, purchase_date)
    # 20% dari 10 juta = 2 juta (dengan toleransi kecil karena 365.25)
    assert 1900000 <= result <= 2100000


def test_straight_line_name():
    """StraightLineStrategy punya nama 'straight_line'."""
    assert StraightLineStrategy().name == "straight_line"


def test_declining_balance_future_date():
    """Aset di masa depan = depresiasi 0."""
    strategy = DecliningBalanceStrategy()
    result = strategy.calculate(10000000.0, date(2099, 1, 1))
    assert result == 0.0


def test_declining_balance_one_year():
    """Aset 1 tahun: declining balance 30% = ~30% dari cost."""
    strategy = DecliningBalanceStrategy()
    purchase_date = date.today() - timedelta(days=365)
    result = strategy.calculate(10000000.0, purchase_date)
    # 30% declining: 10jt * (1 - 0.7^1) = 3 juta
    assert 2800000 <= result <= 3200000


def test_declining_balance_higher_than_straight_line():
    """Declining balance harus menghasilkan depresiasi lebih tinggi di tahun awal."""
    sl = StraightLineStrategy()
    db = DecliningBalanceStrategy()
    purchase_date = date.today() - timedelta(days=365)
    cost = 10000000.0

    sl_result = sl.calculate(cost, purchase_date)
    db_result = db.calculate(cost, purchase_date)

    # Declining balance 30% > Straight line 20% di tahun pertama
    assert db_result > sl_result


def test_sum_of_years_future_date():
    """Aset di masa depan = depresiasi 0."""
    strategy = SumOfYearsStrategy()
    result = strategy.calculate(10000000.0, date(2099, 1, 1))
    assert result == 0.0


def test_sum_of_years_exceeds_useful_life():
    """Aset yang melebihi umur ekonomis = depresiasi penuh (cost)."""
    strategy = SumOfYearsStrategy(useful_life_years=3)
    purchase_date = date.today() - timedelta(days=365 * 5)
    result = strategy.calculate(10000000.0, purchase_date)
    assert result == 10000000.0


def test_sum_of_years_name():
    """SumOfYearsStrategy menampilkan umur ekonomis di deskripsi."""
    strategy = SumOfYearsStrategy(useful_life_years=7)
    assert "7" in strategy.description


def test_different_strategies_different_results():
    """Strategi berbeda menghasilkan nilai depresiasi berbeda untuk aset yang sama."""
    purchase_date = date.today() - timedelta(days=365 * 2)
    cost = 10000000.0

    sl = StraightLineStrategy().calculate(cost, purchase_date)
    db = DecliningBalanceStrategy().calculate(cost, purchase_date)
    syd = SumOfYearsStrategy().calculate(cost, purchase_date)

    # Semua harus menghasilkan nilai yang berbeda
    assert sl != db
    assert sl != syd
    assert db != syd
