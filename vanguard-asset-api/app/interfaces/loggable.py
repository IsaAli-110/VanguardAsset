from abc import ABC, abstractmethod
from app.value_objects.audit_trail_entry import AuditTrailEntry

class Loggable(ABC):
    """
    Interface (Abstract Base Class) untuk objek yang dapat dicatat log auditnya.
    """
    @abstractmethod
    def generate_audit_trail(self, depreciation_amount: float, remaining_value: float) -> AuditTrailEntry:
        """
        Generate an immutable audit trail entry.
        Must be overridden by implementing classes.
        """
        pass
