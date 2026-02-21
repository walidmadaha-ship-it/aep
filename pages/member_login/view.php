<?php

if (session_status() === PHP_SESSION_NONE) session_start();
?>

<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
        --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        --input-bg: rgba(0, 0, 0, 0.3);
        --input-border: rgba(255, 255, 255, 0.1);
        --input-text: #fff;
        --card-title: rgba(255, 255, 255, 0.9);
        --text-color: #ffffff;
        --primary-gold: #FFD700;
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
        padding-left: 40px; /* Space for icon */
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
        top: 42px; /* Adjust based on label height */
        color: var(--primary-gold);
        z-index: 10;
        opacity: 0.8;
    }

    .btn-submit {
        background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
        border: none;
        color: #000;
        font-weight: bold;
        padding: 12px;
        border-radius: 10px;
        width: 100%;
        margin-top: 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        background: linear-gradient(135deg, #FDB931 0%, #FFD700 100%);
    }

    .header-icon {
        font-size: 3.5rem;
        color: var(--primary-gold);
        margin-bottom: 1rem;
        filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.3));
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
    
    .register-link {
        color: var(--primary-gold);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .register-link:hover {
        color: #fff;
        text-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
    }
</style>

<div class="glass-card">
    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fas fa-user-circle header-icon"></i>
        </div>
        <h3 class="fw-bold text-white mb-1">เข้าสู่ระบบสมาชิก</h3>
        <p class="text-white-50 small">Member Login</p>
    </div>

    <?php if (!empty($_SESSION['member_error'])): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <?= $_SESSION['member_error']; ?>
                <?php unset($_SESSION['member_error']); ?>
            </div>
        </div>
    <?php endif; ?>

    <form action="pages/member_login/login.php" method="post">
        <div class="mb-3 position-relative">
            <label class="form-label">Username</label>
            <i class="fas fa-user input-group-icon"></i>
            <input class="form-control" name="member_username" required placeholder="ชื่อผู้ใช้งาน">
        </div>

        <div class="mb-4 position-relative">
            <label class="form-label">Password</label>
            <i class="fas fa-lock input-group-icon"></i>
            <input class="form-control" type="password" name="password" required placeholder="รหัสผ่าน">
        </div>

        <button class="btn btn-submit" type="submit">
            <i class="fas fa-sign-in-alt me-2"></i> เข้าสู่ระบบ
        </button>
    </form>

    <div class="mt-4 text-center">
        <span class="text-white-50">ยังไม่มีบัญชีสมาชิก?</span>
        <a href="index.php?page=register" class="register-link ms-1">
            สมัครสมาชิก <i class="fas fa-arrow-right small"></i>
        </a>
    </div>
</div>
