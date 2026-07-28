"""
Definisi konstanta nama event untuk Observer Pattern.

Menggunakan konstanta string mencegah typo dan memudahkan refactoring.
Setiap event mewakili siklus hidup penting dari sebuah aset.
"""

class AssetEvents:
    """Konstanta nama event yang tersedia dalam sistem Observer."""
    ASSET_CREATED = "asset_created"
    ASSET_DEPRECIATED = "asset_depreciated"
    ASSET_AUDIT_LOGGED = "asset_audit_logged"

# Alias singkat untuk kemudahan import
AssetCreated = AssetEvents.ASSET_CREATED
AssetDepreciated = AssetEvents.ASSET_DEPRECIATED
AssetAuditLogged = AssetEvents.ASSET_AUDIT_LOGGED
