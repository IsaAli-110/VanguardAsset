"""
Test Abstraksi: Menguji bahwa CompanyAsset tidak bisa diinstansiasi langsung.
Membuktikan pilar Abstraksi — kelas abstrak hanya bisa dipakai lewat subclass.
"""
import pytest
from datetime import date
from app.abstractions.base_asset import CompanyAsset


def test_cannot_instantiate_company_asset_directly():
    """CompanyAsset adalah kelas abstrak, tidak bisa diinstansiasi langsung."""
    with pytest.raises(TypeError):
        CompanyAsset(
            asset_id="TEST-001",
            name="Test Asset",
            purchase_cost=1000000.0,
            purchase_date=date(2024, 1, 1)
        )


def test_subclass_must_implement_calculate_depreciation():
    """Subclass yang tidak mengimplementasikan calculate_depreciation() harus gagal."""
    class IncompleteAsset(CompanyAsset):
        def get_audit_details(self):
            return {}

    with pytest.raises(TypeError):
        IncompleteAsset(
            asset_id="TEST-002",
            name="Incomplete",
            purchase_cost=500000.0,
            purchase_date=date(2024, 1, 1)
        )


def test_subclass_must_implement_get_audit_details():
    """Subclass yang tidak mengimplementasikan get_audit_details() harus gagal."""
    class IncompleteAsset(CompanyAsset):
        def calculate_depreciation(self):
            return 0.0

    with pytest.raises(TypeError):
        IncompleteAsset(
            asset_id="TEST-003",
            name="Incomplete",
            purchase_cost=500000.0,
            purchase_date=date(2024, 1, 1)
        )
