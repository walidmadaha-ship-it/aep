<style>
    :root {
        --glass-bg: rgba(30, 30, 30, 0.95);
        /* Dark card */
        --glass-border: rgba(255, 215, 0, 0.3);
        --glass-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        --input-bg: rgba(255, 255, 255, 0.1);
        --input-border: rgba(255, 255, 255, 0.2);
        --input-text: #fff;
        --card-title: rgba(255, 255, 255, 0.9);
        --text-color: #333;
        /* Body text dark */
        --primary-gold: #FFD700;
        --admin-red: #ff4757;
        /* Accent for admin */
    }

    body {
        font-family: 'Prompt', sans-serif;
        color: var(--text-color);
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: var(--glass-shadow);
        padding: 2.5rem;
        max-width: 450px;
        margin: 4rem auto;
        animation: zoomIn 0.6s ease;
    }

    .form-label {
        color: var(--primary-gold);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control {
        background: var(--input-bg);
        border: 1px solid var(--input-border);
        color: var(--input-text);
        border-radius: 10px;
        padding: 12px 15px;
        padding-left: 40px;
        /* Space for icon */
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background: var(--input-bg);
        border-color: var(--primary-gold);
        box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25);
        color: var(--input-text);
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .input-group-icon {
        position: absolute;
        left: 15px;
        top: 42px;
        /* Adjust based on label height */
        color: var(--primary-gold);
        z-index: 10;
        opacity: 0.8;
    }

    .btn-submit {
        background: linear-gradient(135deg, #1e1e1e 0%, #333 100%);
        border: 1px solid var(--primary-gold);
        color: var(--primary-gold);
        font-weight: bold;
        padding: 12px;
        border-radius: 10px;
        width: 100%;
        margin-top: 1.5rem;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.2);
        background: var(--primary-gold);
        color: #000;
    }

    .header-icon {
        font-size: 3.5rem;
        color: var(--primary-gold);
        margin-bottom: 1rem;
        filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.3));
    }

    .admin-badge-icon {
        position: absolute;
        top: -10px;
        right: -10px;
        background: var(--admin-red);
        color: white;
        border-radius: 50%;
        padding: 5px;
        font-size: 1rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<div class="glass-card">
    <div class="text-center mb-4 position-relative d-inline-block w-100">
        <div class="mb-3 position-relative d-inline-block">
            <i class="fas fa-user-shield header-icon"></i>
            <i class="fas fa-cog fa-spin admin-badge-icon"></i>
        </div>
        <h3 class="fw-bold text-white mb-1">ผู้ดูแลระบบ</h3>
        <p class="text-white-50 small">Admin Login Portal</p>
    </div>

    <?php if (!empty($_SESSION['login_error'])): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <?= $_SESSION['login_error']; ?>
                <?php unset($_SESSION['login_error']); ?>
            </div>
        </div>
    <?php endif; ?>

    <form action="pages/login/login.php" method="post">
        <div class="mb-3 position-relative">
            <label class="form-label">Admin Username</label>
            <i class="fas fa-user-cog input-group-icon"></i>
            <input class="form-control" name="user_admin" required placeholder="ชื่อผู้ดูแลระบบ">
        </div>

        <div class="mb-4 position-relative">
            <label class="form-label">Password</label>
            <i class="fas fa-key input-group-icon"></i>
            <input class="form-control" type="password" name="password" required placeholder="รหัสผ่านความปลอดภัยสูง">
        </div>

        <button class="btn btn-submit" type="submit">
            <i class="fas fa-unlock-alt me-2"></i> เข้าสู่ระบบ Admin
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="index.php" class="text-white-50 text-decoration-none small opacity-75 hover-opacity-100">
            <i class="fas fa-arrow-left me-1"></i> กลับสู่หน้าหลัก
        </a>
    </div>
</div>