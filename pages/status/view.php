<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();
require __DIR__ . "/../../php/config.php";

// ต้อง login อย่างใดอย่างหนึ่ง
$is_admin = !empty($_SESSION['admin_login']);
$is_member = !empty($_SESSION['member_login']);

if (!$is_admin && !$is_member) {
  echo '<div class="alert alert-warning">กรุณาล็อกอินก่อน</div>';
  return;
}

$member_id = (int) ($_SESSION['member_id'] ?? 0);

// ดึงรายการคำขอตามสิทธิ์
if ($is_admin) {
  $sql = "
    SELECT r.req_id, r.user_id, r.device_type, r.qty, r.location, r.start_time,
           r.duration_value, r.duration_unit, r.note, r.status, r.created_at,
           u.member_username, u.user_name
    FROM service_requests r
    JOIN users u ON u.user_id = r.user_id
    ORDER BY r.req_id DESC
  ";
  $stmt = $conn->prepare($sql);
} else {
  $sql = "
    SELECT r.req_id, r.user_id, r.device_type, r.qty, r.location, r.start_time,
           r.duration_value, r.duration_unit, r.note, r.status, r.created_at
    FROM service_requests r
    WHERE r.user_id = ?
    ORDER BY r.req_id DESC
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $member_id);
}

$stmt->execute();
$rows = $stmt->get_result();
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

  .badge-secondary {
    background: rgba(108, 117, 125, 0.2);
    color: #adb5bd;
    border: 1px solid rgba(108, 117, 125, 0.3);
  }

  .badge-warning {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
  }

  .badge-primary {
    background: rgba(13, 110, 253, 0.2);
    color: #0d6efd;
    border: 1px solid rgba(13, 110, 253, 0.3);
  }

  .badge-success {
    background: rgba(25, 135, 84, 0.2);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.3);
  }

  .badge-danger {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.3);
  }

  [data-theme="light"] .badge-secondary {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
  }

  [data-theme="light"] .badge-warning {
    background: rgba(255, 193, 7, 0.1);
    color: #d39e00;
  }

  [data-theme="light"] .badge-primary {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
  }

  [data-theme="light"] .badge-success {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
  }

  [data-theme="light"] .badge-danger {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
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
        <i class="fas fa-clipboard-list me-2 text-warning"></i>สถานะการใช้งาน / คำขอเช่า
      </h4>
      <small class="text-white-50"
        style="color: var(--card-title) !important;">ติดตามสถานะและประวัติคำขอใช้บริการ</small>
    </div>

    <div class="d-flex align-items-center gap-2">
      <?php if ($is_member): ?>
        <a href="index.php?page=request" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
          <i class="fas fa-plus me-1"></i> ส่งคำขอใหม่
        </a>
      <?php endif; ?>

      <a href="../member_login/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
        <i class="fas fa-sign-out-alt me-1"></i> ออกจากระบบ
      </a>

      <!-- Theme Toggle -->
      <button class="theme-toggle-btn" id="themeToggle" title="สลับโหมดมืด/สว่าง">
        <i class="fas fa-moon"></i>
      </button>
    </div>
  </div>

  <?php if (!empty($_SESSION['status_msg'])): ?>
    <div class="alert alert-success animate__animated animate__fadeIn">
      <?= htmlspecialchars($_SESSION['status_msg']);
      unset($_SESSION['status_msg']); ?>
    </div>
  <?php endif; ?>

  <div class="glass-card animate__animated animate__fadeInUp">
    <div class="p-0">

      <?php if ($rows->num_rows === 0): ?>
        <div class="p-5 text-center text-white-50">
          <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
          <p>ยังไม่มีรายการคำขอ</p>
        </div>
      <?php else: ?>

        <div class="table-responsive">
          <table class="table glass-table align-middle">
            <thead>
              <tr>
                <th style="width:70px;">#</th>

                <?php if ($is_admin): ?>
                  <th>สมาชิก</th>
                <?php endif; ?>

                <th>อุปกรณ์</th>
                <th style="width:80px;">จำนวน</th>
                <th>สถานที่</th>
                <th style="width:180px;">เริ่มใช้</th>
                <th style="width:140px;">ระยะเวลา</th>
                <th>หมายเหตุ</th>
                <th style="width:130px;">สถานะ</th>
                <th style="width:170px;">วันที่ส่ง</th>

                <?php if ($is_member): ?>
                  <th style="width:150px;">ยกเลิกคำขอ</th>
                <?php endif; ?>

                <?php if ($is_admin): ?>
                  <th style="width:120px;">จัดการ</th>
                <?php endif; ?>
              </tr>
            </thead>

            <tbody>
              <?php while ($r = $rows->fetch_assoc()): ?>
                <?php
                $status = $r['status'] ?? 'pending';
                $badge_class = 'badge-secondary';
                $status_text = $status;
                $icon_class = 'fas fa-circle';

                if ($status === 'pending') {
                  $badge_class = 'badge-warning';
                  $status_text = 'รอตรวจสอบ';
                  $icon_class = 'fas fa-clock';
                } elseif ($status === 'working') {
                  $badge_class = 'badge-primary';
                  $status_text = 'ตรวจสอบแล้ว / กำลังดำเนินการ';
                  $icon_class = 'fas fa-tools';
                } elseif ($status === 'done') {
                  $badge_class = 'badge-success';
                  $status_text = 'เสร็จสิ้น';
                  $icon_class = 'fas fa-check-circle';
                } elseif ($status === 'cancel') {
                  $badge_class = 'badge-danger';
                  $status_text = 'ยกเลิกแล้ว';
                  $icon_class = 'fas fa-times-circle';
                }
                ?>

                <tr>
                  <td>#<?= (int) $r['req_id'] ?></td>

                  <?php if ($is_admin): ?>
                    <td>
                      <div class="fw-bold" style="color: #FFD700;"><?= htmlspecialchars($r['member_username'] ?? '-') ?></div>
                      <div class="small" style="color: var(--card-title);"><?= htmlspecialchars($r['user_name'] ?? '-') ?>
                      </div>
                    </td>
                  <?php endif; ?>

                  <td><span class="fw-medium"><?= htmlspecialchars($r['device_type'] ?? '-') ?></span></td>
                  <td><?= (int) ($r['qty'] ?? 0) ?></td>
                  <td><?= htmlspecialchars($r['location'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($r['start_time'] ?? '-') ?></td>

                  <td>
                    <?= (int) ($r['duration_value'] ?? 0) ?>
                    <?= (($r['duration_unit'] ?? '') === 'day') ? 'วัน' : 'ชม.' ?>
                  </td>

                  <td><small class="text-white-50"><?= nl2br(htmlspecialchars($r['note'] ?? '-')) ?></small></td>

                  <td>
                    <span class="status-badge-custom <?= $badge_class ?>">
                      <i class="<?= $icon_class ?>"></i> <?= htmlspecialchars($status_text) ?>
                    </span>
                  </td>

                  <td><small><?= htmlspecialchars($r['created_at'] ?? '-') ?></small></td>

                  <?php if ($is_member): ?>
                    <td>
                      <?php if ($status === 'pending' || $status === 'working'): ?>
                        <form action="pages/status/cancel.php" method="post" onsubmit="return confirm('ยืนยันยกเลิกคำขอนี้?');">
                          <input type="hidden" name="req_id" value="<?= (int) $r['req_id'] ?>">
                          <button class="btn btn-sm btn-outline-danger w-100 btn-action-glass" type="submit">
                            <i class="fas fa-times me-1"></i> ยกเลิก
                          </button>
                        </form>
                      <?php else: ?>
                        <span class="text-white-50 small">-</span>
                      <?php endif; ?>
                    </td>
                  <?php endif; ?>

                  <?php if ($is_admin): ?>
                    <td>
                      <form action="pages/status/delete.php" method="post" onsubmit="return confirm('ยืนยันลบคำขอนี้?');">
                        <input type="hidden" name="req_id" value="<?= (int) $r['req_id'] ?>">
                        <button class="btn btn-sm btn-outline-danger w-100 btn-action-glass" type="submit">
                          <i class="fas fa-trash-alt me-1"></i> ลบ
                        </button>
                      </form>
                    </td>
                  <?php endif; ?>

                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

      <?php endif; ?>

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