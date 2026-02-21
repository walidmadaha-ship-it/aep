<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();
require __DIR__ . "/../../php/config.php";

if (empty($_SESSION['admin_login'])) {
  echo '<div class="alert alert-warning">กรุณาล็อกอินผู้ดูแลระบบก่อน</div>';
  return;
}

$result = $conn->query("
  SELECT user_id, member_username, user_name, user_phone, user_address, 
         report_detail, service_status, report_time 
  FROM users 
  WHERE member_username IS NOT NULL 
  ORDER BY user_id DESC
");

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
    --table-header-bg: rgba(0, 0, 0, 0.3);
    --table-row-hover: rgba(255, 215, 0, 0.1);
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
    --btn-glass-bg: rgba(0, 0, 0, 0.05);
    --btn-glass-border: rgba(0, 0, 0, 0.1);
    --welcome-bg: linear-gradient(90deg, rgba(255, 215, 0, 0.2), transparent);
    --table-header-bg: rgba(0, 0, 0, 0.05);
    --table-row-hover: rgba(0, 0, 0, 0.02);
  }

  /* Shared Glass Styles */
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

  .glass-table {
    color: var(--text-color);
    margin-bottom: 0;
  }

  .glass-table thead th {
    background-color: var(--table-header-bg);
    border-bottom: 1px solid var(--glass-border);
    color: var(--card-title);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    padding: 15px;
    white-space: nowrap;
  }

  .glass-table tbody td {
    border-bottom: 1px solid var(--glass-border);
    padding: 15px;
    vertical-align: middle;
    font-size: 0.95rem;
  }

  .glass-table tbody tr:hover {
    background-color: var(--table-row-hover);
  }

  .glass-table tbody tr:last-child td {
    border-bottom: none;
  }

  /* Status Badges */
  .status-badge-custom {
    padding: 6px 12px;
    border-radius: 30px;
    font-weight: 500;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }

  .badge-none {
    background: rgba(108, 117, 125, 0.2);
    color: #adb5bd;
    border: 1px solid rgba(108, 117, 125, 0.3);
  }

  .badge-pending {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
  }

  .badge-approved {
    background: rgba(25, 135, 84, 0.2);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.3);
  }

  [data-theme="light"] .badge-none {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
  }

  [data-theme="light"] .badge-pending {
    background: rgba(255, 193, 7, 0.1);
    color: #d39e00;
  }

  [data-theme="light"] .badge-approved {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
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

  /* Form Elements */
  .form-select-glass {
    background-color: rgba(0, 0, 0, 0.2);
    border: 1px solid var(--glass-border);
    color: var(--text-color);
    border-radius: 8px;
  }

  [data-theme="light"] .form-select-glass {
    background-color: rgba(255, 255, 255, 0.5);
  }

  .form-select-glass:focus {
    background-color: rgba(0, 0, 0, 0.3);
    border-color: #FFD700;
    color: var(--text-color);
    box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25);
  }

  .btn-action-glass {
    border-radius: 8px;
    transition: all 0.2s;
  }

  .btn-action-glass:hover {
    transform: translateY(-2px);
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

<div class="container mt-4">

  <!-- Header -->
  <div
    class="page-header-glass d-flex justify-content-between align-items-center animate__animated animate__fadeInDown">
    <div>
      <h4 class="mb-0" style="color: var(--text-color);">
        <i class="fas fa-users me-2 text-warning"></i>จัดการสมาชิก
      </h4>
      <small class="text-white-50" style="color: var(--card-title) !important;">รายชื่อสมาชิกทั้งหมดในระบบ</small>
    </div>
    
    <!-- Theme Toggle -->
    <button class="theme-toggle-btn" id="themeToggle" title="สลับโหมดมืด/สว่าง">
        <i class="fas fa-moon"></i>
    </button>
  </div>

  <?php if (!empty($_SESSION['user_msg'])): ?>
    <div class="alert alert-info animate__animated animate__fadeIn">
      <?= $_SESSION['user_msg']; ?>
    </div>
    <?php unset($_SESSION['user_msg']); ?>
  <?php endif; ?>

  <div class="glass-card table-responsive animate__animated animate__fadeInUp">
    <table class="table glass-table align-middle">
      <thead>
        <tr>
          <th>ลำดับ</th>
          <th>ชื่อผู้ใช้</th>
          <th>ชื่อ-นามสกุล</th>
          <th>เบอร์โทรศัพท์</th>
          <th>ที่อยู่</th>
          <th>รายละเอียด/อีเมล</th>
          <th>วันที่สมัคร</th>
          <th>สถานะ</th>
          <th>จัดการ</th>
        </tr>
      </thead>

      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
          $st = $row['service_status'] ?? 'none';
          $st_text = 'ยังไม่มีคำขอ';
          $st_class = 'badge-none';
          $st_icon = 'fas fa-minus-circle';

          if ($st === 'pending') {
            $st_text = 'รอตรวจสอบ';
            $st_class = 'badge-pending';
            $st_icon = 'fas fa-clock';
          } elseif ($st === 'approved') {
            $st_text = 'ใช้งานได้แล้ว';
            $st_class = 'badge-approved';
            $st_icon = 'fas fa-check-circle';
          }
          ?>

          <tr>
            <td>#<?= (int) $row['user_id'] ?></td>
            <td class="fw-bold" style="color: #FFD700;"><?= htmlspecialchars($row['member_username']) ?></td>
            <td><?= htmlspecialchars($row['user_name']) ?></td>
            <td><?= htmlspecialchars($row['user_phone']) ?></td>
            <td><small><?= nl2br(htmlspecialchars($row['user_address'])) ?></small></td>
            <td><small><?= nl2br(htmlspecialchars($row['report_detail'])) ?></small></td>
            <td><small><?= htmlspecialchars($row['report_time']) ?></small></td>

            <!-- Status Badge -->
            <td>
              <span class="status-badge-custom <?= $st_class ?>">
                <i class="<?= $st_icon ?>"></i> <?= $st_text ?>
              </span>
            </td>

            <!-- Actions -->
            <td style="min-width: 250px;">
              <form action="/aep/pages/user/update_status.php" method="post" class="d-flex align-items-center gap-2 mb-2">
                <input type="hidden" name="user_id" value="<?= (int) $row['user_id'] ?>">
                <select name="service_status" class="form-select form-select-sm form-select-glass">
                  <option value="none" <?= $st == 'none' ? 'selected' : '' ?>>ยังไม่มีคำขอ</option>
                  <option value="pending" <?= $st == 'pending' ? 'selected' : '' ?>>รอตรวจสอบ</option>
                  <option value="approved" <?= $st == 'approved' ? 'selected' : '' ?>>อนุมัติใช้งาน</option>
                </select>
                <button class="btn btn-sm btn-outline-success btn-action-glass" type="submit" title="บันทึกผล">
                  <i class="fas fa-save"></i>
                </button>
              </form>

              <form action="/aep/pages/user/delete.php" method="post"
                onsubmit="return confirm('ยืนยันลบสมาชิกคนนี้? ข้อมูลทั้งหมดจะหายไป!');">
                <input type="hidden" name="user_id" value="<?= (int) $row['user_id'] ?>">
                <button class="btn btn-sm btn-outline-danger w-100 btn-action-glass" type="submit">
                  <i class="fas fa-trash-alt me-1"></i> ลบสมาชิก
                </button>
              </form>
            </td>

          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
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