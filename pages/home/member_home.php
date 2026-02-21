<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['member_login'])) {
    header("Location: ../../index.php?page=member_login");
    exit();
}
?>
<?php
$member_id = (int) ($_SESSION['member_id'] ?? 0);

$stmt = $conn->prepare("
  SELECT user_id, member_username, user_name, user_phone, user_address, report_detail, service_status, report_time
  FROM users
  WHERE user_id = ?
  LIMIT 1
");

$stmt->bind_param("i", $member_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$st = $user['service_status'] ?? 'none';
$st_text = 'ยังไม่มีคำขอ';
$st_badge = 'secondary';
$st_icon = '📝';

if ($st === 'pending') {
    $st_text = 'กำลังตรวจสอบ';
    $st_badge = 'warning';
    $st_icon = '⏳';
} elseif ($st === 'approved') {
    $st_text = 'ใช้งานได้แล้ว';
    $st_badge = 'success';
    $st_icon = '✅';
}

?>

<style>
    :root {
        /* Dark Theme (Default) */
        --bg-color: transparent;
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
        --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        --primary-gradient: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
        --text-color: #ffffff;
        --card-title: rgba(255, 255, 255, 0.8);
        --input-bg: rgba(0, 0, 0, 0.3);
        --input-border: rgba(255, 255, 255, 0.1);
        --input-text: #fff;
        --btn-glass-bg: rgba(255, 255, 255, 0.1);
        --btn-glass-border: rgba(255, 255, 255, 0.3);
        --welcome-bg: linear-gradient(90deg, rgba(255, 215, 0, 0.1), transparent);
    }

    [data-theme="light"] {
        /* Light Theme */
        --bg-color: #f4f6f9;
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(0, 0, 0, 0.1);
        --glass-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        --primary-gradient: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
        --text-color: #333333;
        --card-title: #555555;
        --input-bg: rgba(255, 255, 255, 0.8);
        --input-border: rgba(0, 0, 0, 0.1);
        --input-text: #333;
        --btn-glass-bg: rgba(0, 0, 0, 0.05);
        --btn-glass-border: rgba(0, 0, 0, 0.1);
        --welcome-bg: linear-gradient(90deg, rgba(255, 215, 0, 0.2), transparent);
    }

    body {
        font-family: 'Prompt', sans-serif;
        color: var(--text-color);
        transition: color 0.3s;
    }

    /* Force background overlay for light mode */
    [data-theme="light"] body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: -1;
        pointer-events: none;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: var(--glass-shadow);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .welcome-banner {
        background: var(--welcome-bg);
        border-left: 5px solid #FFD700;
        border-radius: 5px;
        padding: 15px 20px;
        margin-bottom: 30px;
        backdrop-filter: blur(5px);
        transition: background 0.3s;
    }

    .welcome-text {
        color: var(--text-color);
    }

    /* Form Styles */
    .form-control {
        background: var(--input-bg);
        border: 1px solid var(--input-border);
        color: var(--input-text);
        border-radius: 10px;
        padding: 10px 15px;
    }

    .form-control:focus {
        background: var(--input-bg);
        color: var(--input-text);
        box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25);
        border-color: #FFD700;
    }

    .form-control:disabled {
        background: rgba(0, 0, 0, 0.1);
        opacity: 0.7;
    }

    [data-theme="light"] .form-control:disabled {
        background: rgba(0, 0, 0, 0.05);
    }

    .form-label {
        color: var(--card-title);
        font-weight: 500;
        margin-bottom: 5px;
    }

    /* Buttons */
    .btn-glass {
        background: var(--btn-glass-bg);
        border: 1px solid var(--btn-glass-border);
        color: var(--text-color);
        border-radius: 30px;
        transition: all 0.3s;
    }

    .btn-glass:hover {
        background: #FFD700;
        color: black;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
    }

    .btn-action {
        border-radius: 12px;
        padding: 12px;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .status-badge {
        padding: 8px 15px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Toggle Button */
    .theme-toggle-btn {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: var(--text-color);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: var(--glass-shadow);
    }

    .theme-toggle-btn:hover {
        background: #FFD700;
        color: black;
        transform: rotate(15deg);
    }
</style>

<div class="container mt-4">

    <!-- Welcome Header -->
    <div class="welcome-banner d-flex justify-content-between align-items-center animate__animated animate__fadeInDown">
        <div>
            <h4 class="mb-0 welcome-text">
                <i class="fas fa-user-circle me-2 text-warning"></i>
                ยินดีต้อนรับสมาชิก: <span
                    style="color: #FFD700;"><?= htmlspecialchars($_SESSION['member_name'] ?? $_SESSION['member_username']); ?></span>
            </h4>
            <small class="text-white-50"
                style="color: var(--card-title) !important;">จัดการข้อมูลและตรวจสอบสถานะของคุณ</small>
        </div>

        <div class="d-flex align-items-center gap-3">
            <!-- Theme Toggle -->
            <button class="theme-toggle-btn" id="themeToggle" title="สลับโหมดมืด/สว่าง">
                <i class="fas fa-moon"></i>
            </button>

            <a href="pages/member_login/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-4">
                <i class="fas fa-sign-out-alt me-1"></i> ออกจากระบบ
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Profile Section -->
        <div class="col-lg-8">
            <div class="glass-card h-100">
                <div class="p-4">
                    <div
                        class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
                        <h5 class="fw-bold mb-0 text-warning"><i class="fas fa-id-card me-2"></i>ข้อมูลส่วนตัว</h5>

                        <div class="d-flex gap-2">
                            <button type="button" id="btnEdit" class="btn btn-glass btn-sm px-3">
                                <i class="fas fa-edit me-1"></i> แก้ไข
                            </button>

                            <button type="submit" form="profileForm" id="btnSave"
                                class="btn btn-success btn-sm px-3 d-none shadow">
                                <i class="fas fa-save me-1"></i> บันทึก
                            </button>
                        </div>
                    </div>

                    <?php if (!empty($_SESSION['profile_msg'])): ?>
                        <div class="alert alert-success d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <?= htmlspecialchars($_SESSION['profile_msg']);
                                unset($_SESSION['profile_msg']); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['profile_error'])): ?>
                        <div class="alert alert-danger d-flex align-items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <?= htmlspecialchars($_SESSION['profile_error']);
                                unset($_SESSION['profile_error']); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($user): ?>
                        <form id="profileForm" action="/aep/pages/home/member/update_profile.php" method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">ชื่อผู้ใช้</label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($user['member_username'] ?? '-') ?>" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" name="user_name" class="form-control editable"
                                        value="<?= htmlspecialchars($user['user_name'] ?? '') ?>" disabled required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="text" name="user_phone" class="form-control editable"
                                        value="<?= htmlspecialchars($user['user_phone'] ?? '') ?>" disabled required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">ที่อยู่ / ห้อง</label>
                                    <input type="text" name="user_address" class="form-control editable"
                                        value="<?= htmlspecialchars($user['user_address'] ?? '') ?>" disabled required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">รายละเอียดเพิ่มเติม / อีเมล</label>
                                    <textarea name="report_detail" class="form-control editable" rows="3"
                                        disabled><?= htmlspecialchars($user['report_detail'] ?? '') ?></textarea>
                                </div>

                                <div class="col-12 mt-4">
                                    <div class="d-flex align-items-center gap-2 text-white-50">
                                        <i class="fas fa-clock"></i>
                                        <small>สมัครสมาชิกเมื่อ:
                                            <?= htmlspecialchars($user['report_time'] ?? '-') ?></small>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">ไม่พบข้อมูลสมาชิกในฐานข้อมูล</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right: Menu & Status -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="glass-card mb-4">
                <div class="p-4">
                    <h6 class="fw-bold mb-3 text-white"><i class="fas fa-info-circle me-2"></i>สถานะสมาชิก</h6>

                    <div class="p-3 rounded-3" style="background: rgba(0,0,0,0.2);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-white-50 small">สถานะปัจจุบัน</div>
                            <div class="fs-4"><?= $st_icon ?></div>
                        </div>

                        <span class="badge bg-<?= $st_badge ?> d-block w-100 py-2 fs-6">
                            <?= htmlspecialchars($st_text) ?>
                        </span>

                        <div class="text-white-50 mt-2" style="font-size: 0.8rem;">
                            <i class="fas fa-sync-alt me-1"></i> อัปเดตล่าสุดจากผู้ดูแลระบบ
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Actions -->
            <div class="d-grid gap-3">
                <a href="index.php?page=request" class="btn btn-success btn-action shadow-lg">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-file-signature fs-4"></i>
                        <div class="text-start">
                            <div style="font-size: 0.9rem;">ทำรายการ</div>
                            <div class="fs-6">ขอใช้บริการ / เช่าเครื่อง</div>
                        </div>
                    </div>
                </a>

                <a href="index.php?page=status" class="btn btn-primary btn-action shadow-lg">
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-clipboard-list fs-4"></i>
                        <div class="text-start">
                            <div style="font-size: 0.9rem;">ตรวจสอบ</div>
                            <div class="fs-6">เช็คสถานะการใช้งาน</div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    // Profile Edit Logic
    const btnEdit = document.getElementById('btnEdit');
    const btnSave = document.getElementById('btnSave');
    const editable = document.querySelectorAll('.editable');

    if (btnEdit) {
        btnEdit.addEventListener('click', () => {
            editable.forEach(input => {
                input.disabled = false;
                // Add visual cue
                input.style.borderColor = '#FFD700';
            });
            btnSave.classList.remove('d-none');
            btnSave.classList.add('animate__animated', 'animate__fadeIn');
            btnEdit.classList.add('d-none');
            editable[0]?.focus();
        });
    }

    // Theme Toggle Logic
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('themeToggle');
        const icon = toggleBtn.querySelector('i');
        const html = document.documentElement;

        // Check local storage
        const currentTheme = localStorage.getItem('theme') || 'dark';
        if (currentTheme === 'light') {
            html.setAttribute('data-theme', 'light');
            icon.classList.replace('fa-moon', 'fa-sun');
        }

        toggleBtn.addEventListener('click', () => {
            if (html.getAttribute('data-theme') === 'light') {
                html.removeAttribute('data-theme');
                localStorage.setItem('theme', 'dark');
                icon.classList.replace('fa-sun', 'fa-moon');
            } else {
                html.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
                icon.classList.replace('fa-moon', 'fa-sun');
            }
        });
    });
</script>
<?php
$st = $user['service_status'] ?? 'pending';

$st_text = 'กำลังตรวจสอบ';
$st_badge = 'warning';
$st_icon = '⏳';

if ($st === 'approved') {
    $st_text = 'อนุญาตใช้งานแล้ว';
    $st_badge = 'success';
    $st_icon = '✅';
} elseif ($st === 'rejected') {
    $st_text = 'ไม่อนุญาตใช้งาน';
    $st_badge = 'danger';
    $st_icon = '❌';
} elseif ($st === 'disabled') {
    $st_text = 'ระงับการใช้งาน';
    $st_badge = 'secondary';
    $st_icon = '⛔';
}
?>