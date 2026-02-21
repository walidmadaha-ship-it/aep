<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['admin_login'])) {
  echo '<div class="alert alert-warning">กรุณาล็อกอินแอดมิน</div>';
  return;
}

// ✅ จำนวนสมาชิก
$members_count = 0;
$rMembers = $conn->query("SELECT COUNT(*) AS c FROM users WHERE member_username IS NOT NULL");
if ($rMembers)
  $members_count = (int) $rMembers->fetch_assoc()['c'];

// ✅ จำนวนอุปกรณ์ทั้งหมด
$devices_count = 0;
$rDevicesCount = $conn->query("SELECT COUNT(*) AS c FROM devices");
if ($rDevicesCount)
  $devices_count = (int) $rDevicesCount->fetch_assoc()['c'];

// ✅ จำนวนอุปกรณ์ตามสถานะ
$available = $in_use = $maintenance = 0;
$rDevicesStatus = $conn->query("
  SELECT device_status, COUNT(*) AS c
  FROM devices
  GROUP BY device_status
");
if ($rDevicesStatus) {
  while ($row = $rDevicesStatus->fetch_assoc()) {
    if ($row['device_status'] === 'available')
      $available = (int) $row['c'];
    if ($row['device_status'] === 'in_use')
      $in_use = (int) $row['c'];
    if ($row['device_status'] === 'maintenance')
      $maintenance = (int) $row['c'];
  }
}

// ✅ จำนวนแจ้งเหตุ (คำขอ) ทั้งหมด
$reports_count = 0;
// นับเฉพาะที่ยังไม่เสร็จ (pending, working)
$rReportsCount = $conn->query("SELECT COUNT(*) AS c FROM service_requests WHERE status IN ('pending', 'working')");
if ($rReportsCount)
  $reports_count = (int) $rReportsCount->fetch_assoc()['c'];

// ✅ แจ้งเหตุล่าสุด 5 รายการ
$latest_reports = $conn->query("
  SELECT report_id, reporter_name, status, created_at
  FROM reports
  ORDER BY report_id DESC
  LIMIT 5
");

function status_text($s)
{
  if ($s === 'working')
    return 'กำลังดำเนินการ';
  if ($s === 'done')
    return 'เสร็จสิ้น';
  if ($s === 'cancel')
    return 'ยกเลิก';
  return 'รอการตรวจสอบ';
}
function status_badge($s)
{
  if ($s === 'working')
    return 'bg-primary';
  if ($s === 'done')
    return 'bg-success';
  if ($s === 'cancel')
    return 'bg-danger';
  return 'bg-warning text-dark';
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
    --btn-glass-bg: rgba(255, 255, 255, 0.1);
    --btn-glass-border: rgba(255, 255, 255, 0.3);
    --welcome-bg: linear-gradient(90deg, rgba(255, 215, 0, 0.1), transparent);
  }

  [data-theme="light"] {
    /* Light Theme */
    --bg-color: #f4f6f9;
    /* Overlay to lighten the background image if needed, or just solid color */
    --glass-bg: rgba(255, 255, 255, 0.85);
    --glass-border: rgba(0, 0, 0, 0.1);
    --glass-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    --primary-gradient: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
    --text-color: #333333;
    --card-title: #555555;
    --btn-glass-bg: rgba(0, 0, 0, 0.05);
    --btn-glass-border: rgba(0, 0, 0, 0.1);
    --welcome-bg: linear-gradient(90deg, rgba(255, 215, 0, 0.2), transparent);
  }

  /* Transition for theme switch */
  body,
  .glass-card,
  .btn-glass,
  .stat-number,
  .stat-label,
  .welcome-banner {
    transition: background 0.3s, color 0.3s, border-color 0.3s, box-shadow 0.3s;
  }

  body {
    font-family: 'Prompt', sans-serif;
    color: var(--text-color);
  }

  /* Force background overlay for light mode readability if main BG is dark image */
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
    position: relative;
  }

  .glass-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    border-color: rgba(255, 215, 0, 0.5);
  }

  .card-icon {
    font-size: 3.5rem;
    margin-bottom: 15px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 0 10px rgba(253, 185, 49, 0.5));
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  .glass-card:hover .card-icon {
    transform: scale(1.2) rotate(10deg);
  }

  .stat-number {
    font-size: 3rem;
    font-weight: 700;
    color: var(--text-color);
    /* text-shadow: 0 0 15px rgba(255, 255, 255, 0.3); Remove strong shadow for better light mode */
  }

  .stat-label {
    font-size: 1.1rem;
    color: var(--card-title);
    font-weight: 300;
  }

  .welcome-banner {
    background: var(--welcome-bg);
    border-left: 5px solid #FFD700;
    border-radius: 5px;
    padding: 15px 20px;
    margin-bottom: 30px;
    backdrop-filter: blur(5px);
  }

  .welcome-text {
    color: var(--text-color);
  }

  .welcome-text small {
    color: var(--card-title) !important;
  }

  .btn-glass {
    background: var(--btn-glass-bg);
    border: 1px solid var(--btn-glass-border);
    color: var(--text-color);
    padding: 10px 20px;
    border-radius: 30px;
    backdrop-filter: blur(5px);
  }

  .btn-glass:hover {
    background: #FFD700;
    color: black;
    box-shadow: 0 0 15px rgba(255, 215, 0, 0.6);
    transform: translateY(-2px);
  }

  .status-badge {
    padding: 8px 15px;
    border-radius: 15px;
    font-weight: 500;
    backdrop-filter: blur(4px);
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

  .bg-title-section {
    color: var(--text-color);
    border-bottom: 1px solid var(--glass-border);
  }
</style>

<div class="container mt-4">

  <!-- Welcome Section -->
  <div class="welcome-banner d-flex justify-content-between align-items-center animate__animated animate__fadeInDown">
    <div>
      <h4 class="mb-0 welcome-text">
        <i class="fas fa-user-shield me-2 text-warning"></i>
        ยินดีต้อนรับผู้ดูแลระบบ: <span style="color: #FFD700;"><?= htmlspecialchars($_SESSION['admin_name']); ?></span>
      </h4>
      <small class="text-white-50">จัดการระบบของคุณได้อย่างเต็มประสิทธิภาพ</small>
    </div>

    <div class="d-flex align-items-center gap-3">
      <!-- Theme Toggle -->
      <button class="theme-toggle-btn" id="themeToggle" title="สลับโหมดมืด/สว่าง">
        <i class="fas fa-moon"></i>
      </button>

      <a href="pages/login/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-4">
        <i class="fas fa-sign-out-alt me-1"></i> ออกจากระบบ
      </a>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row g-4 mb-5">

    <!-- Devices Card -->
    <div class="col-md-3">
      <div class="glass-card text-center p-4 h-100">
        <i class="fas fa-bolt card-icon"></i>
        <div class="stat-label">จำนวนอุปกรณ์ทั้งหมด</div>
        <div class="stat-number"><?= $devices_count ?></div>
        <div class="mt-2 small" style="color: var(--card-title)">เครื่อง</div>
      </div>
    </div>

    <!-- Members Card -->
    <div class="col-md-3">
      <div class="glass-card text-center p-4 h-100">
        <i class="fas fa-users card-icon"></i>
        <div class="stat-label">จำนวนสมาชิก</div>
        <div class="stat-number"><?= $members_count ?></div>
        <div class="mt-2 small" style="color: var(--card-title)">คน</div>
      </div>
    </div>

    <!-- Status Card -->
    <div class="col-md-3">
      <div class="glass-card text-center p-4 h-100">
        <i class="fas fa-tools card-icon"></i>
        <div class="stat-label mb-3">สถานะอุปกรณ์</div>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
          <span class="status-badge bg-success bg-opacity-75 shadow-sm text-white">
            <i class="fas fa-check-circle me-1"></i>พร้อม <?= $available ?>
          </span>
          <span class="status-badge bg-primary bg-opacity-75 shadow-sm text-white">
            <i class="fas fa-briefcase me-1"></i>ใช้งาน <?= $in_use ?>
          </span>
          <span class="status-badge bg-warning bg-opacity-75 text-dark shadow-sm">
            <i class="fas fa-wrench me-1"></i>ซ่อม <?= $maintenance ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Reports Card -->
    <div class="col-md-3">
      <div class="glass-card text-center p-4 h-100">
        <i class="fas fa-exclamation-triangle card-icon"></i>
        <div class="stat-label">จำนวนแจ้งเหตุ</div>
        <div class="stat-number text-danger"><?= $reports_count ?></div>
        <div class="mt-3">
          <a href="index.php?page=report" class="btn btn-glass btn-sm w-100">
            ดูรายการ <i class="fas fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>

  </div>

  <!-- Quick Actions -->
  <div class="row mb-5">
    <div class="col-12">
      <h5 class="mb-3 pb-2 d-inline-block bg-title-section">
        <i class="fas fa-rocket me-2 text-warning"></i>เมนูทางลัด
      </h5>
      <div class="d-flex gap-3 flex-wrap">
        <a class="btn btn-glass" href="index.php?page=user">
          <i class="fas fa-address-book me-2"></i>ข้อมูลสมาชิก
        </a>
        <a class="btn btn-glass" href="index.php?page=status">
          <i class="fas fa-chart-line me-2"></i>สถานะการใช้งาน
        </a>
        <a class="btn btn-glass" href="index.php?page=admins">
          <i class="fas fa-user-cog me-2"></i>จัดการ Admin
        </a>
      </div>
    </div>
  </div>
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