<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shoe Hub SL • Spin & Win</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600&display=swap" rel="stylesheet" />
    <style>
        body {
            margin:0; padding:0;
            font-family: Poppins, sans-serif;
            background:#f7f9fc;
        }
        .modal-wrap {
            width:100%;
            max-width:380px;
            margin:20px auto;
            padding:20px;
            background:#fff;
            border-radius:12px;
            box-shadow:0 6px 20px rgba(0,0,0,.15);
        }
        .logo {
            display:flex; flex-direction:column; align-items:center; gap:6px;
            margin-bottom:16px;
        }
        .logo-icon {
            width:48px; height:48px;
            background: linear-gradient(135deg, #34d399, #60a5fa);
            border-radius:12px;
            display:grid; place-items:center;
            box-shadow:0 4px 12px rgba(0,0,0,.15);
        }
        .logo-icon svg { width:28px; height:28px; fill:#0b1220 }
        .logo strong { font-size:18px; font-weight:600; color:#111; }
        .logo span { font-size:13px; color:#777; }

        h2 { text-align:center; font-size:18px; margin:10px 0 16px; color:#222; }

        form { display:flex; flex-direction:column; gap:14px; }
        .field { display:flex; flex-direction:column; gap:6px; }
        .field label { font-size:14px; font-weight:600; color:#333; }
        .input {
            padding:10px 12px;
            border:1px solid #ccc;
            border-radius:8px;
            font-size:15px;
        }
        .input:focus {
            border-color:#60a5fa; outline:none;
            box-shadow:0 0 0 3px rgba(96,165,250,.25);
        }
        .error-text { color:#e63946; font-size:12px; }
        .btn {
            padding:12px;
            background:linear-gradient(135deg, #ff4b2b, #ff416c);
            border:none; border-radius:8px;
            color:#fff; font-weight:600; font-size:15px;
            cursor:pointer; transition:.18s;
        }
        .btn:hover { filter:brightness(1.05); }

        .rewards {
            margin-top:18px;
            padding:14px;
            border-radius:8px;
            background:#f9fafb;
            border:1px dashed #cfd8dc;
        }
        .rewards h3 {
            font-size:15px;
            margin-bottom:10px;
            text-align:center;
            color:#444;
        }
        .reward-item {
            display:flex; align-items:center; gap:8px;
            font-size:14px; color:#333; margin-bottom:6px;
        }
        .reward-item span {
            display:inline-block;
            width:22px; height:22px;
            background:#ff416c;
            border-radius:50%;
            color:#fff; font-size:13px;
            line-height:22px; text-align:center;
            font-weight:600;
        }
    </style>
</head>
<body>
    <div class="modal-wrap">
        <div class="logo">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24"><path d="M6 7h8a3 3 0 0 1 0 6H9a2 2 0 0 0 0 4h9v2H9a4 4 0 0 1 0-8h5a1 1 0 0 0 0-2H6V7zm13 0h-2v10h2V7z"/></svg>
            </div>
            <strong>Shoe Hub SL</strong>
            <span>Spin & Win</span>
        </div>

        <h2>Enter details to spin</h2>

        <form method="POST" action="{{ route('spin.start') }}">
            @csrf
            <div class="field">
                <label for="name">Name</label>
                <input class="input" id="name" name="name" type="text" 
                       value="{{ old('name') }}" maxlength="255" required>
                @error('name') <div class="error-text">{{ $message }}</div> @enderror
            </div>
            <div class="field">
                <label for="phone">Phone</label>
                <input class="input" id="phone" name="phone" type="tel" 
                       placeholder="0771234567" pattern="[0-9]{10}" maxlength="10"
                       value="{{ old('phone') }}" required>
                @error('phone') <div class="error-text">{{ $message }}</div> @enderror
            </div>
            <button class="btn" type="submit">Start Spin</button>
        </form>

        <!-- Rewards Preview -->
        <div class="rewards">
            <h3>🎁 Possible Rewards</h3>
            <div class="reward-item"><span>1</span> LKR 1000 Rupees</div>
            <div class="reward-item"><span>2</span> Free Delivery</div>
            <div class="reward-item"><span>3</span> 20% OFF Coupon</div>
        </div>
    </div>
</body>
</html>
