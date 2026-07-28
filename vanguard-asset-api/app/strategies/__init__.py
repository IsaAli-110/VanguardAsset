from app.strategies.depreciation_strategy import DepreciationStrategy
from app.strategies.straight_line_strategy import StraightLineStrategy
from app.strategies.declining_balance_strategy import DecliningBalanceStrategy
from app.strategies.sum_of_years_strategy import SumOfYearsStrategy

__all__ = [
    "DepreciationStrategy",
    "StraightLineStrategy",
    "DecliningBalanceStrategy",
    "SumOfYearsStrategy",
]
