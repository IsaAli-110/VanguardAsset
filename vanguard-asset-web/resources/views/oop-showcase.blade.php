@extends('layouts.app')

@section('styles')
<style>
    .showcase-grid {
        display: grid;
        grid-template-columns: 1fr 3fr;
        gap: 2rem;
        margin-top: 1.5rem;
    }
    .showcase-menu {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .menu-item {
        background: rgba(30, 41, 59, 0.4);
        border: 1px solid var(--border-dim);
        padding: 1rem 1.25rem;
        border-radius: var(--radius-md);
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-smooth);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .menu-item:hover, .menu-item.active {
        background: rgba(99, 102, 241, 0.1);
        border-color: var(--primary);
        color: var(--text-main);
    }
    .menu-item.active {
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.15);
    }
    .showcase-content {
        min-height: 500px;
    }
    .oop-card {
        display: none;
        animation: fadeIn 0.4s ease-out;
    }
    .oop-card.active {
        display: block;
    }
    .code-block {
        background: #05070c;
        border: 1px solid var(--border-dim);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.9rem;
        color: #e2e8f0;
        overflow-x: auto;
        margin-top: 1rem;
        line-height: 1.5;
    }
    .keyword { color: #f472b6; }
    .class-name { color: #60a5fa; font-weight: bold; }
    .method-name { color: #fbbf24; }
    .comment { color: #64748b; font-style: italic; }
    .string { color: #34d399; }
    .number { color: #fb923c; }
    .decorator { color: #a78bfa; }
</style>
@endsection

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.8rem; margin-bottom: 0.25rem;"><i class="fa-solid fa-graduation-cap" style="color: #c084fc;"></i> OOP Showcase Center</h1>
    <p>Visualisasi dan penjelasan interaktif penerapan Object-Oriented Programming (OOP) tingkat lanjut pada sistem backend Python FastAPI VanguardAsset.</p>
</div>

<div class="showcase-grid">
    <!-- Sidebar Menu -->
    <div class="showcase-menu">
        <div class="menu-item active" onclick="switchTab('abstraction', this)">
            <i class="fa-solid fa-shapes"></i>
            <span>1. Abstraksi</span>
        </div>
        <div class="menu-item" onclick="switchTab('encapsulation', this)">
            <i class="fa-solid fa-box-archive"></i>
            <span>2. Enkapsulasi</span>
        </div>
        <div class="menu-item" onclick="switchTab('inheritance', this)">
            <i class="fa-solid fa-sitemap"></i>
            <span>3. Pewarisan</span>
        </div>
        <div class="menu-item" onclick="switchTab('polymorphism', this)">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            <span>4. Polimorfisme</span>
        </div>
        <div class="menu-item" onclick="switchTab('interfaces', this)">
            <i class="fa-solid fa-plug"></i>
            <span>5. Interface / Protokol</span>
        </div>
        <div class="menu-item" onclick="switchTab('value-objects', this)">
            <i class="fa-solid fa-gem"></i>
            <span>6. Value Objects</span>
        </div>
    </div>

    <!-- Content Area -->
    <div class="showcase-content card" style="margin-bottom: 0;">
        <!-- TAB 1: ABSTRAKSI -->
        <div id="tab-abstraction" class="oop-card active">
            <h2 style="font-size: 1.4rem; color: #818cf8; margin-bottom: 0.5rem;"><i class="fa-solid fa-shapes"></i> Abstraksi (Abstraction)</h2>
            <p style="margin-bottom: 1rem;">Abstraksi menyembunyikan detail implementasi yang kompleks dan hanya mengekspos fungsi penting. Pada Python API kami, ini diterapkan menggunakan <code>abc.ABC</code> dan decorator <code>@abstractmethod</code> pada kelas dasar aset.</p>
            
            <div style="background: rgba(99, 102, 241, 0.05); border-left: 4px solid #818cf8; padding: 1rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 1.5rem;">
                <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.25rem;">Mengapa ini penting?</h4>
                <p style="font-size: 0.88rem;">Dengan mendefinisikan kelas abstrak <code>CompanyAsset</code>, kami menjamin bahwa semua jenis aset (baik fisik maupun digital) harus memiliki metode <code>calculate_depreciation()</code> dan <code>get_audit_details()</code> sendiri tanpa mengizinkan instansiasi langsung dari <code>CompanyAsset</code>.</p>
            </div>

            <h4 style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;">File Implementasi: <code style="color: #60a5fa;">app/abstractions/base_asset.py</code></h4>
            <div class="code-block">
                <span class="keyword">from</span> abc <span class="keyword">import</span> ABC, abstractmethod<br>
                <span class="keyword">from</span> datetime <span class="keyword">import</span> date<br>
                <span class="keyword">from</span> app.interfaces.loggable <span class="keyword">import</span> Loggable<br>
                <span class="keyword">from</span> app.interfaces.depreciable <span class="keyword">import</span> Depreciable<br><br>
                <span class="keyword">class</span> <span class="class-name">CompanyAsset</span>(Loggable, Depreciable, ABC):<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Kelas Abstrak yang menjadi cetak biru bagi semua aset perusahaan."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">__init__</span>(self, asset_id, name, purchase_cost, purchase_date):<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;self.asset_id = asset_id<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;self.name = name<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;self.purchase_cost = purchase_cost<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;self.purchase_date = purchase_date<br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="decorator">@abstractmethod</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">calculate_depreciation</span>(self) -> <span class="keyword">float</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Wajib diimplementasikan oleh subclass."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">pass</span><br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="decorator">@abstractmethod</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">get_audit_details</span>(self) -> Dict[str, Any]:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Wajib diimplementasikan oleh subclass."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">pass</span>
            </div>
        </div>

        <!-- TAB 2: ENKAPSULASI -->
        <div id="tab-encapsulation" class="oop-card">
            <h2 style="font-size: 1.4rem; color: #818cf8; margin-bottom: 0.5rem;"><i class="fa-solid fa-box-archive"></i> Enkapsulasi (Encapsulation)</h2>
            <p style="margin-bottom: 1rem;">Enkapsulasi membatasi akses langsung ke atribut internal objek untuk mencegah modifikasi yang tidak disengaja. Kami menggunakan decorator <code>@property</code> sebagai getter dan setter dengan validasi ketat pada properti <code>purchase_cost</code>.</p>

            <div style="background: rgba(99, 102, 241, 0.05); border-left: 4px solid #818cf8; padding: 1rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 1.5rem;">
                <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.25rem;">Bagaimana Getter & Setter Berperan?</h4>
                <p style="font-size: 0.88rem;">Properti <code>purchase_cost</code> disimpan di atribut privat <code>_purchase_cost</code> (single underscore). Setiap kali ada yang mencoba mengeset nilai baru, setter akan memvalidasi bahwa nilainya harus lebih dari nol. Jika tidak, akan dilempar <code>ValueError</code>.</p>
            </div>

            <h4 style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;">File Implementasi: <code style="color: #60a5fa;">app/abstractions/base_asset.py</code> &amp; <code style="color: #60a5fa;">app/encapsulation/cost_manager.py</code></h4>
            <div class="code-block">
                <span class="comment"># Di dalam CompanyAsset (base_asset.py)</span><br>
                <span class="decorator">@property</span><br>
                <span class="keyword">def</span> <span class="method-name">purchase_cost</span>(self) -> <span class="keyword">float</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Getter untuk purchase_cost (Enkapsulasi)."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> self._purchase_cost<br><br>
                <span class="decorator">@purchase_cost.setter</span><br>
                <span class="keyword">def</span> <span class="method-name">purchase_cost</span>(self, value: <span class="keyword">float</span>):<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Setter dengan validasi untuk purchase_cost."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">if</span> value &lt;= <span class="number">0</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">raise</span> ValueError(<span class="string">"Purchase cost must be greater than zero."</span>)<br>
                &nbsp;&nbsp;&nbsp;&nbsp;self._purchase_cost = value<br><br>
                <span class="comment"># Kelas terpisah: CostManager (cost_manager.py)</span><br>
                <span class="keyword">class</span> <span class="class-name">CostManager</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">__init__</span>(self, initial_cost: <span class="keyword">float</span>):<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;self.cost = initial_cost<br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="decorator">@property</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">cost</span>(self) -> <span class="keyword">float</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> self.__cost <span class="comment"># Atribut privat double-underscore</span><br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="decorator">@cost.setter</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">cost</span>(self, value: <span class="keyword">float</span>):<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">if</span> value &lt;= <span class="number">0</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">raise</span> ValueError(<span class="string">"Purchase cost must be greater than zero."</span>)<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;self.__cost = value
            </div>
        </div>

        <!-- TAB 3: PEWARISAN -->
        <div id="tab-inheritance" class="oop-card">
            <h2 style="font-size: 1.4rem; color: #818cf8; margin-bottom: 0.5rem;"><i class="fa-solid fa-sitemap"></i> Pewarisan (Inheritance)</h2>
            <p style="margin-bottom: 1rem;">Pewarisan memungkinkan pembuatan hierarki kelas di mana kelas anak mewarisi atribut dan perilaku dari kelas induk. Struktur ini mengurangi duplikasi kode secara signifikan.</p>

            <div style="background: rgba(99, 102, 241, 0.05); border-left: 4px solid #818cf8; padding: 1rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 1.5rem;">
                <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.25rem;">Aset Fisik vs Aset Digital</h4>
                <p style="font-size: 0.88rem;"><code>PhysicalAsset</code> mewarisi <code>CompanyAsset</code> dan menambahkan properti khusus fisik seperti <code>serial_number</code> dan <code>maintenance_interval</code>. Sedangkan <code>DigitalAsset</code> menambahkan properti digital seperti <code>license_key</code> dan <code>expiry_date</code>. Keduanya memanggil <code>super().__init__()</code> untuk menjalankan konstruktor induk.</p>
            </div>

            <h4 style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;">File Implementasi: <code style="color: #60a5fa;">app/inheritance/physical_asset.py</code></h4>
            <div class="code-block">
                <span class="keyword">from</span> app.abstractions.base_asset <span class="keyword">import</span> CompanyAsset<br><br>
                <span class="keyword">class</span> <span class="class-name">PhysicalAsset</span>(CompanyAsset):<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Subclass untuk aset fisik dengan penyusutan 20% per tahun."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">__init__</span>(self, asset_id, name, purchase_cost, purchase_date,<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;serial_number, maintenance_interval):<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment"># Memanggil konstruktor superclass</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;super().__init__(asset_id, name, purchase_cost, purchase_date)<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;self.serial_number = serial_number<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;self.maintenance_interval = maintenance_interval<br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">calculate_depreciation</span>(self) -> <span class="keyword">float</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Penyusutan garis lurus 20% per tahun."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;age_days = (date.today() - self.purchase_date).days<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;age_years = age_days / <span class="number">365.25</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;depreciation = self.purchase_cost * <span class="number">0.20</span> * age_years<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> round(min(self.purchase_cost, depreciation), <span class="number">2</span>)
            </div>
        </div>

        <!-- TAB 4: POLIMORFISME -->
        <div id="tab-polymorphism" class="oop-card">
            <h2 style="font-size: 1.4rem; color: #818cf8; margin-bottom: 0.5rem;"><i class="fa-solid fa-wand-magic-sparkles"></i> Polimorfisme (Polymorphism)</h2>
            <p style="margin-bottom: 1rem;">Polimorfisme memungkinkan satu interface/metode berperilaku berbeda tergantung pada jenis kelas objek yang memanggilnya. Dalam API kami, pabrik pembuatan aset menghasilkan objek polimorfik.</p>

            <div style="background: rgba(99, 102, 241, 0.05); border-left: 4px solid #818cf8; padding: 1rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 1.5rem;">
                <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.25rem;">Desain Factory Pattern Polimorfik</h4>
                <p style="font-size: 0.88rem;">Metode <code>AssetFactory.create_asset()</code> mengembalikan objek bertipe <code>CompanyAsset</code>, tetapi di baliknya objek tersebut bisa berupa <code>PhysicalAsset</code> atau <code>DigitalAsset</code>. Kode pemanggil tidak perlu tahu jenis detailnya saat memanggil <code>calculate_depreciation()</code>.</p>
            </div>

            <h4 style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;">File Implementasi: <code style="color: #60a5fa;">app/polymorphism/asset_factory.py</code></h4>
            <div class="code-block">
                <span class="keyword">from</span> app.abstractions.base_asset <span class="keyword">import</span> CompanyAsset<br>
                <span class="keyword">from</span> app.inheritance.physical_asset <span class="keyword">import</span> PhysicalAsset<br>
                <span class="keyword">from</span> app.inheritance.digital_asset <span class="keyword">import</span> DigitalAsset<br><br>
                <span class="keyword">class</span> <span class="class-name">AssetFactory</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="decorator">@staticmethod</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">create_asset</span>(data: <span class="keyword">dict</span>) -> <span class="class-name">CompanyAsset</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;asset_type = data.get(<span class="string">"type"</span>, <span class="string">""</span>).lower()<br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">if</span> asset_type == <span class="string">"physical"</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> PhysicalAsset(<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;asset_id=data.get(<span class="string">"asset_id"</span>),<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;name=data.get(<span class="string">"name"</span>),<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;purchase_cost=<span class="keyword">float</span>(data.get(<span class="string">"purchase_cost"</span>)),<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...)<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">elif</span> asset_type == <span class="string">"digital"</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> DigitalAsset(...)<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">else</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">raise</span> ValueError(<span class="string">f"Unsupported asset type: </span>{asset_type}<span class="string">"</span>)
            </div>
        </div>

        <!-- TAB 5: INTERFACES -->
        <div id="tab-interfaces" class="oop-card">
            <h2 style="font-size: 1.4rem; color: #818cf8; margin-bottom: 0.5rem;"><i class="fa-solid fa-plug"></i> Interface (Abstract Base Class)</h2>
            <p style="margin-bottom: 1rem;">Interface mendefinisikan kontrak perilaku yang harus dipatuhi oleh kelas yang mengimplementasikannya. Di Python, ini diterapkan menggunakan <code>abc.ABC</code> dengan <code>@abstractmethod</code>. <code>CompanyAsset</code> mewarisi dari dua interface sekaligus.</p>

            <div style="background: rgba(99, 102, 241, 0.05); border-left: 4px solid #818cf8; padding: 1rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 1.5rem;">
                <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.25rem;">Interface Loggable & Depreciable</h4>
                <p style="font-size: 0.88rem;">Kami membuat dua interface: <code>Loggable</code> yang mengharuskan metode <code>generate_audit_trail()</code> mengembalikan <code>AuditTrailEntry</code>, dan <code>Depreciable</code> yang mengharuskan metode <code>calculate_depreciation()</code>. Desain ini memungkinkan multiple inheritance yang bersih.</p>
            </div>

            <h4 style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;">File Implementasi: <code style="color: #60a5fa;">app/interfaces/loggable.py</code> &amp; <code style="color: #60a5fa;">app/interfaces/depreciable.py</code></h4>
            <div class="code-block">
                <span class="comment"># loggable.py</span><br>
                <span class="keyword">from</span> abc <span class="keyword">import</span> ABC, abstractmethod<br>
                <span class="keyword">from</span> app.value_objects.audit_trail_entry <span class="keyword">import</span> AuditTrailEntry<br><br>
                <span class="keyword">class</span> <span class="class-name">Loggable</span>(ABC):<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="decorator">@abstractmethod</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">generate_audit_trail</span>(self, depreciation_amount: <span class="keyword">float</span>,<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;remaining_value: <span class="keyword">float</span>) -> <span class="class-name">AuditTrailEntry</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Generate an immutable audit trail entry."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">pass</span><br><br>
                <span class="comment"># depreciable.py</span><br>
                <span class="keyword">from</span> abc <span class="keyword">import</span> ABC, abstractmethod<br><br>
                <span class="keyword">class</span> <span class="class-name">Depreciable</span>(ABC):<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="decorator">@abstractmethod</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">calculate_depreciation</span>(self) -> <span class="keyword">float</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Abstract method to calculate depreciation amount."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">pass</span>
            </div>
        </div>

        <!-- TAB 6: VALUE OBJECTS -->
        <div id="tab-value-objects" class="oop-card">
            <h2 style="font-size: 1.4rem; color: #818cf8; margin-bottom: 0.5rem;"><i class="fa-solid fa-gem"></i> Value Objects</h2>
            <p style="margin-bottom: 1rem;">Value Object adalah objek kecil sederhana yang mewakili nilai tanpa identitas konseptual sendiri. Dua value object dengan nilai properti yang sama dianggap identik dan bersifat tidak dapat diubah (Immutable).</p>

            <div style="background: rgba(99, 102, 241, 0.05); border-left: 4px solid #818cf8; padding: 1rem; border-radius: 0 var(--radius-md) var(--radius-md) 0; margin-bottom: 1.5rem;">
                <h4 style="color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.25rem;">Keamanan Melalui Immutability</h4>
                <p style="font-size: 0.88rem;">Kami menggunakan decorator <code>@dataclass(frozen=True)</code> untuk memastikan bahwa sekali instansi <code>AuditTrailEntry</code> dibuat, nilainya tidak dapat diubah lagi. Ini menjamin integritas data audit — siapapun yang mencoba mengubah field akan mendapat error <code>FrozenInstanceError</code>.</p>
            </div>

            <h4 style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.5rem;">File Implementasi: <code style="color: #60a5fa;">app/value_objects/audit_trail_entry.py</code></h4>
            <div class="code-block">
                <span class="keyword">from</span> dataclasses <span class="keyword">import</span> dataclass, field<br>
                <span class="keyword">from</span> typing <span class="keyword">import</span> Dict, Any<br><br>
                <span class="decorator">@dataclass(frozen=True)</span><br>
                <span class="keyword">class</span> <span class="class-name">AuditTrailEntry</span>:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Frozen dataclass — objek immutable untuk log audit."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;asset_id: <span class="keyword">str</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;name: <span class="keyword">str</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;asset_type: <span class="keyword">str</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;calculated_at: <span class="keyword">str</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;purchase_cost: <span class="keyword">float</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;depreciation_amount: <span class="keyword">float</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;remaining_value: <span class="keyword">float</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;details: Dict[str, Any] = field(default_factory=<span class="keyword">dict</span>)<br><br>
                &nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">def</span> <span class="method-name">to_dict</span>(self) -> Dict[str, Any]:<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="comment">"""Konversi ke dictionary untuk respon JSON."""</span><br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="keyword">return</span> {<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="string">"asset_id"</span>: self.asset_id,<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="string">"name"</span>: self.name,<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchTab(tabId, element) {
        // Remove active class from all menu items
        document.querySelectorAll('.menu-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Hide all cards
        document.querySelectorAll('.oop-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Add active class to clicked menu item
        element.classList.add('active');
        
        // Show selected tab content
        const targetTab = document.getElementById('tab-' + tabId);
        if (targetTab) {
            targetTab.classList.add('active');
        }
    }
</script>
@endsection
