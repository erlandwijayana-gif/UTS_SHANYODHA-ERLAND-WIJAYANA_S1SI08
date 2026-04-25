<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Donasi Hewan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $snapUrl = config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp

    <script src="{{ $snapUrl }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: white;
            min-height: 100vh;
            background:
                linear-gradient(
                    90deg,
                    rgba(0, 0, 0, 0.86) 0%,
                    rgba(0, 0, 0, 0.68) 45%,
                    rgba(0, 0, 0, 0.35) 100%
                ),
                url("https://images.unsplash.com/photo-1583337130417-3346a1be7dee?auto=format&fit=crop&w=1800&q=80");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
            width: min(1180px, 92%);
            margin: auto;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 28px 0;
        }

        .logo {
            font-size: 26px;
            font-weight: 900;
        }

        .logo span {
            color: #86efac;
        }

        .nav-btn {
            background: #22c55e;
            color: white;
            padding: 13px 22px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: bold;
        }

        .hero {
            min-height: calc(100vh - 90px);
            display: grid;
            grid-template-columns: 1fr 430px;
            gap: 50px;
            align-items: center;
            padding: 40px 0 70px;
        }

        .badge {
            display: inline-block;
            background: rgba(34, 197, 94, 0.18);
            border: 1px solid rgba(134, 239, 172, 0.5);
            color: #bbf7d0;
            padding: 10px 16px;
            border-radius: 999px;
            font-weight: bold;
            margin-bottom: 20px;
            backdrop-filter: blur(8px);
        }

        h1 {
            font-size: clamp(44px, 6vw, 76px);
            line-height: 1;
            margin: 0 0 22px;
            letter-spacing: -3px;
        }

        h1 span {
            color: #86efac;
        }

        .subtitle {
            font-size: 19px;
            line-height: 1.7;
            color: #e5e7eb;
            max-width: 620px;
            margin-bottom: 30px;
        }

        .cta-row {
            display: flex;
            gap: 14px;
            align-items: center;
            margin-bottom: 35px;
            flex-wrap: wrap;
        }

        .primary-btn {
            background: #22c55e;
            color: white;
            border: none;
            padding: 15px 24px;
            border-radius: 14px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 18px 40px rgba(34, 197, 94, 0.28);
        }

        .outline-btn {
            color: white;
            border: 1px solid rgba(255,255,255,.35);
            padding: 14px 22px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: bold;
            backdrop-filter: blur(8px);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            max-width: 720px;
            background: rgba(0, 0, 0, 0.46);
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 22px;
            overflow: hidden;
            backdrop-filter: blur(12px);
        }

        .stat {
            padding: 22px;
            border-right: 1px solid rgba(255,255,255,.16);
        }

        .stat:last-child {
            border-right: none;
        }

        .stat strong {
            display: block;
            font-size: 30px;
            margin-bottom: 5px;
        }

        .stat span {
            color: #d1d5db;
            font-size: 14px;
        }

        .help-title {
            margin-top: 34px;
            font-weight: bold;
            font-size: 18px;
        }

        .help-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-top: 18px;
            max-width: 740px;
        }

        .help-item {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 18px;
            padding: 18px;
            backdrop-filter: blur(10px);
        }

        .help-item .icon {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .help-item strong {
            display: block;
            margin-bottom: 6px;
        }

        .help-item p {
            margin: 0;
            color: #d1d5db;
            font-size: 14px;
            line-height: 1.5;
        }

        .donation-card {
            background: rgba(255,255,255,.96);
            color: #111827;
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 30px 90px rgba(0,0,0,.35);
        }

        .donation-card h2 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .donation-card .desc {
            color: #6b7280;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        input,
        textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 13px;
            padding: 14px 15px;
            font-size: 15px;
            outline: none;
        }

        input:focus,
        textarea:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34,197,94,.14);
        }

        textarea {
            min-height: 85px;
            resize: vertical;
        }

        .amount-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 12px;
        }

        .amount-btn {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #166534;
            border-radius: 13px;
            padding: 13px;
            font-weight: bold;
            cursor: pointer;
        }

        .amount-btn:hover {
            background: #dcfce7;
        }

        .submit-btn {
            width: 100%;
            border: none;
            background: #22c55e;
            color: white;
            border-radius: 15px;
            padding: 16px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 8px;
        }

        .submit-btn:hover {
            background: #16a34a;
        }

        .submit-btn:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        .secure {
            text-align: center;
            color: #6b7280;
            font-size: 13px;
            margin-top: 16px;
        }

        .alert {
            display: none;
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        @media (max-width: 950px) {
            .hero {
                grid-template-columns: 1fr;
            }

            .stats,
            .help-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 560px) {
            .navbar {
                flex-direction: column;
                gap: 16px;
            }

            .stats,
            .help-grid,
            .amount-grid {
                grid-template-columns: 1fr;
            }

            h1 {
                letter-spacing: -1px;
            }
        }
    </style>
</head>

<body>
<div class="container">
    <nav class="navbar">
        <div class="logo">🐾 Care<span>Paws</span></div>
        <a href="#donasi" class="nav-btn">Donasi Sekarang</a>
    </nav>

    <section class="hero">
        <div>
            <div class="badge">🐕 Mereka butuh bantuan kita</div>

            <h1>
                Bantu mereka hidup
                <span>lebih layak.</span>
            </h1>

            <p class="subtitle">
                Banyak hewan terlantar hidup dalam kondisi tidak layak:
                kelaparan, sakit, terluka, dan tidak memiliki tempat aman.
                Donasi Anda membantu mereka mendapatkan makanan, perawatan,
                dan perlindungan.
            </p>

            <div class="cta-row">
                <a href="#donasi" class="primary-btn">❤️ Donasi Sekarang</a>
                <a href="#bantuan" class="outline-btn">Lihat Bantuan</a>
            </div>

            <div class="stats">
                <div class="stat">
                    <strong>1.245+</strong>
                    <span>Hewan terbantu</span>
                </div>
                <div class="stat">
                    <strong>{{ $donations->count() }}+</strong>
                    <span>Donatur baik</span>
                </div>
                <div class="stat">
                    <strong>Rp {{ number_format($totalDonation, 0, ',', '.') }}</strong>
                    <span>Total donasi</span>
                </div>
            </div>

            <div id="bantuan" class="help-title">
                Bantuan Anda akan digunakan untuk:
            </div>

            <div class="help-grid">
                <div class="help-item">
                    <div class="icon">🍖</div>
                    <strong>Makanan</strong>
                    <p>Memberi makan hewan yang kelaparan.</p>
                </div>

                <div class="help-item">
                    <div class="icon">💊</div>
                    <strong>Perawatan</strong>
                    <p>Obat, vaksin, dan pengobatan darurat.</p>
                </div>

                <div class="help-item">
                    <div class="icon">🏠</div>
                    <strong>Tempat Aman</strong>
                    <p>Shelter sementara yang lebih layak.</p>
                </div>

                <div class="help-item">
                    <div class="icon">💚</div>
                    <strong>Penyelamatan</strong>
                    <p>Menolong hewan dari kondisi berbahaya.</p>
                </div>
            </div>
        </div>

        <div class="donation-card" id="donasi">
            <h2>Berdonasi Sekarang</h2>
            <p class="desc">Pilih nominal dan lanjutkan pembayaran melalui Midtrans.</p>

            <div id="alertBox" class="alert"></div>

            <form id="donationForm">
                @csrf

                <div class="form-group">
                    <label>Nominal Donasi</label>

                    <div class="amount-grid">
                        <button type="button" class="amount-btn" data-amount="25000">Rp25.000</button>
                        <button type="button" class="amount-btn" data-amount="50000">Rp50.000</button>
                        <button type="button" class="amount-btn" data-amount="100000">Rp100.000</button>
                    </div>

                    <input type="number" name="amount" id="amount" min="10000" placeholder="Minimal Rp10.000" required>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" placeholder="Masukkan nama Anda" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="nama@email.com">
                </div>

                <div class="form-group">
                    <label>Nomor WhatsApp</label>
                    <input type="text" name="phone" placeholder="08xxxxxxxxxx">
                </div>

                <div class="form-group">
                    <label>Pesan Dukungan</label>
                    <textarea name="message" placeholder="Tulis pesan untuk mereka..."></textarea>
                </div>

                <button type="submit" id="submitBtn" class="submit-btn">
                    Lanjutkan ke Pembayaran →
                </button>

                <div class="secure">🔒 Donasi aman melalui Midtrans</div>
            </form>
        </div>
    </section>
</div>

<script>
    const form = document.getElementById('donationForm');
    const amountInput = document.getElementById('amount');
    const amountButtons = document.querySelectorAll('.amount-btn');
    const submitBtn = document.getElementById('submitBtn');
    const alertBox = document.getElementById('alertBox');

    amountButtons.forEach(button => {
        button.addEventListener('click', function () {
            amountInput.value = this.dataset.amount;
        });
    });

    function showAlert(message, type = 'error') {
        alertBox.style.display = 'block';
        alertBox.className = type === 'success'
            ? 'alert alert-success'
            : 'alert alert-error';
        alertBox.innerText = message;
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        submitBtn.disabled = true;
        submitBtn.innerText = 'Memproses...';
        alertBox.style.display = 'none';

        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('donations.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                showAlert(result.message || 'Gagal membuat transaksi.');
                submitBtn.disabled = false;
                submitBtn.innerText = 'Lanjutkan ke Pembayaran →';
                return;
            }

            window.snap.pay(result.snap_token, {
                onSuccess: function () {
                    showAlert('Pembayaran berhasil. Terima kasih atas donasinya.', 'success');
                    window.location.href = "{{ route('donations.success') }}";
                },
                onPending: function () {
                    showAlert('Pembayaran masih pending. Silakan selesaikan pembayaran.', 'success');
                    window.location.href = "{{ route('donations.pending') }}";
                },
                onError: function (result) {
                    console.log(result);
                    showAlert('Pembayaran gagal atau ditolak oleh bank.');
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Lanjutkan ke Pembayaran →';
                },
                onClose: function () {
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Lanjutkan ke Pembayaran →';
                }
            });

        } catch (error) {
            console.error(error);
            showAlert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            submitBtn.disabled = false;
            submitBtn.innerText = 'Lanjutkan ke Pembayaran →';
        }
    });
</script>
</body>
</html>