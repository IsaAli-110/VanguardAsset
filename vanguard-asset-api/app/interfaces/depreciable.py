from abc import ABC, abstractmethod

class Depreciable(ABC):
    @abstractmethod
    def calculate_depreciation(self) -> float:
        """
        Abstract method to calculate and return the depreciation amount.
        Must be overridden by subclasses.
        """
        pass
