<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VanguardAsset - Buat Akun</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-deep: #090d16;
            --bg-card: rgba(30, 41, 59, 0.45);
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.35);
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-dim: rgba(255, 255, 255, 0.06);
            --border-glow: rgba(99, 102, 241, 0.25);
            --radius-lg: 16px;
            --radius-md: 10px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body {
            background-color: var(--bg-deep);
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.08) 0px, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }
        .auth-container { width: 100%; max-width: 460px; animation: fadeIn 0.6s ease-out; }
        .brand { display: flex; align-items: center; justify-content: center; gap: 0.75rem; font-weight: 800; font-size: 1.8rem; margin-bottom: 2rem; text-decoration: none; color: var(--text-main); }
        .brand span { background: linear-gradient(to right, #818cf8, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .brand i { color: var(--primary); text-shadow: 0 0 15px var(--primary-glow); }
        .auth-card { background: var(--bg-card); backdrop-filter: blur(16px); border: 1px solid var(--border-dim); border-radius: var(--radius-lg); padding: 2.5rem; box-shadow: 0 15px 35px -10px rgba(0,0,0,0.6); position: relative; }
        .auth-card:hover { border-color: var(--border-glow); }
        .auth-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(135deg, #c084fc, #6366f1); border-radius: var(--radius-lg) var(--radius-lg) 0 0; }
        .auth-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 0.4rem; text-align: center; }
        .auth-subtitle { color: var(--text-muted); font-size: 0.9rem; text-align: center; margin-bottom: 1.75rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-group { position: relative; }
        .input-group i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1rem; }
        .form-control { width: 100%; padding: 0.8rem 1rem 0.8rem 2.75rem; background: rgba(15, 23, 42, 0.6); border: 1px solid var(--border-dim); border-radius: var(--radius-md); color: var(--text-main); font-size: 0.95rem; outline: none; transition: var(--transition-smooth); }
        .form-control:focus { border-color: var(--primary); background: rgba(15, 23, 42, 0.9); box-shadow: 0 0 10px rgba(99, 102, 241, 0.15); }
        select.form-control { cursor: pointer; }
        select.form-control option { background: #1e293b; color: var(--text-main); }
        .error-message { color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem; font-weight: 500; }
        .role-hint { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.4rem; }
        .btn-submit { width: 100%; background: var(--primary-gradient); color: white; border: none; padding: 0.85rem; font-size: 0.95rem; font-weight: 700; border-radius: var(--radius-md); cursor: pointer; box-shadow: 0 4px 14px var(--primary-glow); transition: var(--transition-smooth); margin-top: 1rem; }
        .btn-submit:hover { transform: translateY(-2px); opacity: 0.95; box-shadow: 0 6px 20px var(--primary-glow); }
        .auth-footer { margin-top: 1.25rem; text-align: center; font-size: 0.85rem; color: var(--text-muted); }
        .auth-footer a { color: var(--primary); text-decoration: none; font-weight: 600; }
        .auth-footer a:hover { text-decoration: underline; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <div class="auth-container">
        <a href="#" class="brand"><i class="fa-solid fa-shield-halved"></i> Vanguard<span>Asset</span></a>
        <div class="auth-card">
            <h2 class="auth-title">Buat Akun Baru</h2>
            <p class="auth-subtitle">Bergabung dengan VanguardAsset untuk mengelola aset perusahaan</p>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <div class="input-group">
                        <input type="text" id="name" name="name" class="form-control" placeholder="Nama Lengkap Anda" value="{{ old('name') }}" required>
                        <i class="fa-solid fa-user"></i>
                    </div>
                    @error('name')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <div class="input-group">
                        <input type="email" id="email" name="email" class="form-control" placeholder="nama@vanguard.com" value="{{ old('email') }}" required>
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    @error('email')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="background: rgba(99, 102, 241, 0.05); padding: 1rem; border-radius: var(--radius-md); border: 1px dashed rgba(99, 102, 241, 0.2); margin-bottom: 1.25rem;">
                    <p style="font-size: 0.85rem; color: #a5b4fc; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                        <i class="fa-solid fa-shield-halved" style="color: #818cf8; text-shadow: none; font-size: 1rem;"></i>
                        <strong>Keamanan Akses: Role Otomatis</strong>
                    </p>
                    <p style="font-size: 0.8rem; color: var(--text-muted); line-height: 1.4;">
                        Untuk mencegah <em>Privilege Escalation</em>, setiap akun baru didaftarkan sebagai <strong>Staf / Karyawan</strong>. Hubungi Admin IT untuk menaikkan wewenang Anda.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div id="password-strength-container" style="margin-top: 0.6rem; display: none;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.3rem;">
                            <span id="strength-label" style="font-size: 0.78rem; font-weight: 600; color: var(--text-muted);">Kekuatan: Lemah</span>
                            <span id="strength-percent" style="font-size: 0.78rem; font-weight: 600; color: var(--text-muted);">0%</span>
                        </div>
                        <div style="width: 100%; height: 6px; background: rgba(255, 255, 255, 0.1); border-radius: 3px; overflow: hidden;">
                            <div id="strength-bar" style="width: 0%; height: 100%; background: #ef4444; transition: var(--transition-smooth);"></div>
                        </div>
                        <ul id="strength-requirements" style="list-style: none; margin-top: 0.5rem; padding-left: 0.1rem; display: flex; flex-direction: column; gap: 0.25rem;">
                            <li id="req-length" style="font-size: 0.75rem; color: #ef4444; display: flex; align-items: center; gap: 0.35rem;"><i class="fa-solid fa-circle-xmark"></i> Minimal 8 karakter</li>
                            <li id="req-case" style="font-size: 0.75rem; color: #ef4444; display: flex; align-items: center; gap: 0.35rem;"><i class="fa-solid fa-circle-xmark"></i> Huruf besar & kecil</li>
                            <li id="req-number" style="font-size: 0.75rem; color: #ef4444; display: flex; align-items: center; gap: 0.35rem;"><i class="fa-solid fa-circle-xmark"></i> Angka (0-9)</li>
                            <li id="req-symbol" style="font-size: 0.75rem; color: #ef4444; display: flex; align-items: center; gap: 0.35rem;"><i class="fa-solid fa-circle-xmark"></i> Karakter khusus (@$!%*?&#)</li>
                        </ul>
                    </div>
                    @error('password')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required>
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>

                <button type="submit" class="btn-submit"><i class="fa-solid fa-user-plus"></i> Buat Akun</button>
            </form>

            <div class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const strengthContainer = document.getElementById('password-strength-container');
        const strengthLabel = document.getElementById('strength-label');
        const strengthPercent = document.getElementById('strength-percent');
        const strengthBar = document.getElementById('strength-bar');

        const reqLength = document.getElementById('req-length');
        const reqCase = document.getElementById('req-case');
        const reqNumber = document.getElementById('req-number');
        const reqSymbol = document.getElementById('req-symbol');

        passwordInput.addEventListener('input', function() {
            const val = passwordInput.value;
            if (val.length === 0) {
                strengthContainer.style.display = 'none';
                return;
            }
            strengthContainer.style.display = 'block';

            let score = 0;
            const checks = {
                length: val.length >= 8,
                case: /[a-z]/.test(val) && /[A-Z]/.test(val),
                number: /\d/.test(val),
                symbol: /[@$!%*?&#+\-_]/.test(val)
            };

            if (checks.length) score += 25;
            if (checks.case) score += 25;
            if (checks.number) score += 25;
            if (checks.symbol) score += 25;

            // Update indicators
            updateRequirement(reqLength, checks.length);
            updateRequirement(reqCase, checks.case);
            updateRequirement(reqNumber, checks.number);
            updateRequirement(reqSymbol, checks.symbol);

            // Update progress bar
            strengthPercent.innerText = score + '%';
            strengthBar.style.width = score + '%';

            if (score <= 25) {
                strengthBar.style.backgroundColor = '#ef4444';
                strengthLabel.innerText = 'Kekuatan: Sangat Lemah';
                strengthLabel.style.color = '#ef4444';
            } else if (score <= 50) {
                strengthBar.style.backgroundColor = '#f97316';
                strengthLabel.innerText = 'Kekuatan: Lemah';
                strengthLabel.style.color = '#f97316';
            } else if (score <= 75) {
                strengthBar.style.backgroundColor = '#eab308';
                strengthLabel.innerText = 'Kekuatan: Sedang';
                strengthLabel.style.color = '#eab308';
            } else {
                strengthBar.style.backgroundColor = '#22c55e';
                strengthLabel.innerText = 'Kekuatan: Sangat Kuat';
                strengthLabel.style.color = '#22c55e';
            }
        });

        function updateRequirement(element, passed) {
            const icon = element.querySelector('i');
            if (passed) {
                element.style.color = '#22c55e';
                icon.className = 'fa-solid fa-circle-check';
            } else {
                element.style.color = '#ef4444';
                icon.className = 'fa-solid fa-circle-xmark';
            }
        }
    </script>
</body>
</html>
