<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VanguardAsset - Masuk</title>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

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
            padding: 1.5rem;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            animation: fadeIn 0.6s ease-out;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-weight: 800;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand span {
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand i {
            color: var(--primary);
            text-shadow: 0 0 15px var(--primary-glow);
        }

        .auth-card {
            background: var(--bg-card);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.6);
            transition: var(--transition-smooth);
            position: relative;
        }

        .auth-card:hover {
            border-color: var(--border-glow);
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.15);
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }

        .auth-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .auth-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.05rem;
            transition: var(--transition-smooth);
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.75rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-dim);
            border-radius: var(--radius-md);
            color: var(--text-main);
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition-smooth);
        }

        .form-control:focus {
            border-color: var(--primary);
            background: rgba(15, 23, 42, 0.9);
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.15);
        }

        .form-control:focus + i {
            color: var(--primary);
        }

        .error-message {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.35rem;
            font-weight: 500;
        }

        .btn-submit {
            width: 100%;
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.85rem;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: var(--radius-md);
            cursor: pointer;
            box-shadow: 0 4px 14px var(--primary-glow);
            transition: var(--transition-smooth);
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            opacity: 0.95;
            box-shadow: 0 6px 20px var(--primary-glow);
        }

        .auth-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition-smooth);
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <a href="#" class="brand">
            <i class="fa-solid fa-shield-halved"></i> Vanguard<span>Asset</span>
        </a>
        
        <div class="auth-card">
            <h2 class="auth-title">Selamat Datang Kembali</h2>
            <p class="auth-subtitle">Masuk untuk mengelola aset perusahaan</p>
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <div class="input-group">
                        <input type="email" id="email" name="email" class="form-control" placeholder="nama@vanguard.com" required value="{{ old('email') }}">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn-submit">Masuk</button>
            </form>
            
            <div class="auth-footer">
                Belum punya akun? <a href="{{ route('register') }}">Buat akun</a>
            </div>
        </div>
    </div>

</body>
</html>
