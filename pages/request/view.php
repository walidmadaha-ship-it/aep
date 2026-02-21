<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['member_login'])) {
  header("Location: ../../index.php?page=member_login");
  exit();
}

$member_id = (int) ($_SESSION['member_id'] ?? 0);

// ดึงข้อมูลสมาชิกมาเติมฟอร์ม (optional)
$user = null;
if ($member_id > 0) {
  $stmt = $conn->prepare("
    SELECT user_name, user_phone, user_address
    FROM users
    WHERE user_id = ?
    LIMIT 1
  ");
  $stmt->bind_param("i", $member_id);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();
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

  /* Glass Card */
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

  /* Welcome Banner */
  .page-header-glass {
    background: var(--welcome-bg);
    border-left: 5px solid #FFD700;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 25px;
    backdrop-filter: blur(5px);
  }

  /* Form Styles */
  .form-control,
  .form-select {
    background: var(--input-bg);
    border: 1px solid var(--input-border);
    color: var(--input-text);
    border-radius: 10px;
    padding: 10px 15px;
  }

  .form-control:focus,
  .form-select:focus {
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
  .btn-glass-outline {
    background: transparent;
    border: 1px solid var(--btn-glass-border);
    color: var(--text-color);
    border-radius: 30px;
    transition: all 0.3s;
  }

  .btn-glass-outline:hover {
    background: rgba(255, 215, 0, 0.1);
    border-color: #FFD700;
    color: var(--text-color);
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
    margin-left: 10px;
  }

  .theme-toggle-btn:hover {
    background: #FFD700;
    color: black;
    transform: rotate(15deg);
  }
</style>

<div class="container mt-4" style="max-width: 900px;">
  <!-- Last Updated: <?= date('Y-m-d H:i:s') ?> -->

  <!-- Header -->
  <div
    class="page-header-glass d-flex justify-content-between align-items-center animate__animated animate__fadeInDown">
    <div>
      <h4 class="mb-0" style="color: var(--text-color);">
        <i class="fas fa-edit me-2 text-warning"></i>ขอใช้บริการ / เช่าเครื่อง
      </h4>
      <small class="text-white-50" style="color: var(--card-title) !important;">กรอกรายละเอียดเพื่อส่งคำขออนุญาต</small>
    </div>

    <div class="d-flex align-items-center gap-2">
      <a href="index.php?page=home" class="btn btn-glass-outline btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> ย้อนกลับ
      </a>
      <a href="index.php?page=status" class="btn btn-outline-warning btn-sm rounded-pill px-3">
        <i class="fas fa-list-alt me-1"></i> สถานะ
      </a>

      <!-- Theme Toggle -->
      <button class="theme-toggle-btn" id="themeToggle" title="สลับโหมดมืด/สว่าง">
        <i class="fas fa-moon"></i>
      </button>
    </div>
  </div>

  <?php if (!empty($_SESSION['req_error'])): ?>
    <div class="alert alert-danger animate__animated animate__fadeIn">
      <?= htmlspecialchars($_SESSION['req_error']);
      unset($_SESSION['req_error']); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($_SESSION['req_success'])): ?>
    <div class="alert alert-success animate__animated animate__fadeIn">
      <?= htmlspecialchars($_SESSION['req_success']);
      unset($_SESSION['req_success']); ?>
    </div>
  <?php endif; ?>

  <form action="pages/request/create.php" method="post" class="glass-card animate__animated animate__fadeInUp">
    <div class="p-4">

      <div class="row g-3">

        <div class="col-md-6">
          <label class="form-label">ประเภทอุปกรณ์</label>
          <select name="device_type" class="form-select" required>
            <option value="">-- เลือก --</option>
            <option value="ATS">ATS (ชุดสลับไฟอัตโนมัติ)</option>
            <option value="UPS">UPS / Battery (ไฟสำรองทันที)</option>
            <option value="Generator">Generator (ไฟสำรองระยะยาว)</option>
          </select>
          <div class="text-white-50 small mt-1" style="opacity: 0.7;">เลือกอุปกรณ์ที่ต้องการใช้งาน</div>
        </div>

        <div class="col-md-3">
          <label class="form-label">จำนวน (เครื่อง)</label>
          <input type="number" name="qty" class="form-control" min="1" value="1" required>
        </div>

        <div class="col-md-3">
          <label class="form-label">เริ่มใช้งาน (ถ้ามี)</label>
          <input type="datetime-local" name="start_time" class="form-control">
        </div>

        <div class="col-md-6">
          <label class="form-label">สถานที่ / บ้านเลขที่ / ห้อง</label>
          <input type="text" name="location" class="form-control"
            value="<?= htmlspecialchars($user['user_address'] ?? '') ?>" placeholder="อาคาร A ห้อง 205" required>
        </div>

        <div class="col-md-3">
          <label class="form-label">ระยะเวลา</label>
          <input type="number" name="duration_value" class="form-control" min="1" value="1" required>
        </div>

        <div class="col-md-3">
          <label class="form-label">หน่วย</label>
          <select name="duration_unit" class="form-select" required>
            <option value="hour">ชั่วโมง</option>
            <option value="day">วัน</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">ชื่อผู้ขอ</label>
          <input type="text" class="form-control"
            value="<?= htmlspecialchars($user['user_name'] ?? ($_SESSION['member_name'] ?? '')) ?>" disabled>
        </div>

        <div class="col-md-6">
          <label class="form-label">เบอร์โทรศัพท์</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($user['user_phone'] ?? '') ?>" disabled>
        </div>

        <div class="col-12">
          <label class="form-label">รายละเอียดเพิ่มเติม / หมายเหตุ</label>
          <textarea name="note" class="form-control" rows="3"
            placeholder="เช่น ใช้สำหรับระบบกล้องวงจรปิด / ห้องเซิร์ฟเวอร์ / เหตุฉุกเฉิน ฯลฯ"></textarea>
        </div>

      </div>

      <hr class="my-4" style="border-color: var(--glass-border);">

      <button class="btn btn-success w-100 shadow-lg py-2 rounded-pill fw-bold" type="submit"
        style="font-size: 1.1rem;">
        <i class="fas fa-paper-plane me-2"></i> ส่งคำขอเช่า/ใช้บริการ
      </button>

      <div class="text-white-50 small mt-3 text-center">
        หลังส่งคำขอแล้ว สามารถติดตามผลได้ที่หน้า “สถานะการใช้งาน”
      </div>

    </div>
  </form>
</div>

<script>
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