"""
Test Enkapsulasi: Menguji validasi purchase_cost (negatif, nol, valid).
Membuktikan pilar Enkapsulasi — properti hanya bisa diubah lewat setter dengan validasi.
"""
import pytest
from datetime import date
from app.inheritance.physical_asset import PhysicalAsset
from app.encapsulation.cost_manager import CostManager


def test_valid_purchase_cost():
    """purchase_cost dengan nilai positif harus berhasil."""
    asset = PhysicalAsset(
        asset_id="ENC-001", name="Laptop", purchase_cost=10000000.0,
        purchase_date=date(2024, 1, 1), serial_number="SN123",
        maintenance_interval=90
    )
    assert asset.purchase_cost == 10000000.0


def test_negative_purchase_cost_raises_error():
    """purchase_cost negatif harus raise ValueError."""
    with pytest.raises(ValueError, match="greater than zero"):
        PhysicalAsset(
            asset_id="ENC-002", name="Laptop", purchase_cost=-5000.0,
            purchase_date=date(2024, 1, 1), serial_number="SN456",
            maintenance_interval=90
        )


def test_zero_purchase_cost_raises_error():
    """purchase_cost nol harus raise ValueError."""
    with pytest.raises(ValueError, match="greater than zero"):
        PhysicalAsset(
            asset_id="ENC-003", name="Laptop", purchase_cost=0.0,
            purchase_date=date(2024, 1, 1), serial_number="SN789",
            maintenance_interval=90
        )


def test_setter_updates_value():
    """Setter berhasil mengubah nilai purchase_cost dengan nilai valid."""
    asset = PhysicalAsset(
        asset_id="ENC-004", name="Laptop", purchase_cost=5000000.0,
        purchase_date=date(2024, 1, 1), serial_number="SN010",
        maintenance_interval=90
    )
    asset.purchase_cost = 8000000.0
    assert asset.purchase_cost == 8000000.0


def test_setter_rejects_invalid_value():
    """Setter menolak nilai negatif."""
    asset = PhysicalAsset(
        asset_id="ENC-005", name="Laptop", purchase_cost=5000000.0,
        purchase_date=date(2024, 1, 1), serial_number="SN011",
        maintenance_interval=90
    )
    with pytest.raises(ValueError, match="greater than zero"):
        asset.purchase_cost = -100.0


def test_cost_manager_valid():
    """CostManager menerima nilai positif."""
    cm = CostManager(1000000.0)
    assert cm.cost == 1000000.0


def test_cost_manager_rejects_negative():
    """CostManager menolak nilai negatif."""
    with pytest.raises(ValueError, match="greater than zero"):
        CostManager(-500.0)
