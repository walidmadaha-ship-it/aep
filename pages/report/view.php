<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . "/../../php/config.php";

$is_admin  = !empty($_SESSION['admin_login']);
$is_member = !empty($_SESSION['member_login']);

if (!$is_admin && !$is_member) {
  echo '<div class="alert alert-warning">กรุณาล็อกอินก่อน</div>';
  return;
}

$member_id = (int)($_SESSION['member_id'] ?? 0);

if ($is_admin) {
  $sql = "
    SELECT user_name, user_phone, device_type, qty, duration_value, duration_unit, location, note, created_at
    FROM request_history
    ORDER BY hist_id DESC
  ";
  $stmt = $conn->prepare($sql);
} else {
  $sql = "
    SELECT user_name, user_phone, device_type, qty, duration_value, duration_unit, location, note, created_at
    FROM request_history
    WHERE user_id = ?
    ORDER BY hist_id DESC
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $member_id);
}

$stmt->execute();
$result = $stmt->get_result();
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

    /* Welcome Banner */
    .page-header-glass {
        background: var(--welcome-bg);
        border-left: 5px solid #FFD700;
        border-radius: 5px;
        padding: 20px;
        margin-bottom: 25px;
        backdrop-filter: blur(5px);
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

<div class="container mt-4">

  <!-- Header -->
  <div class="page-header-glass d-flex justify-content-between align-items-center animate__animated animate__fadeInDown">
    <div>
      <h4 class="mb-0" style="color: var(--text-color);">
        <i class="fas fa-history me-2 text-warning"></i>ประวัติการใช้งาน (เก็บถาวร)
      </h4>
      <small class="text-white-50" style="color: var(--card-title) !important;">รายการประวัติคำขอที่ผ่านมาทั้งหมด</small>
    </div>

    <div class="d-flex align-items-center gap-2">
      <a href="index.php?page=home" class="btn btn-glass-outline btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> ย้อนกลับ
      </a>

      <!-- Theme Toggle -->
      <button class="theme-toggle-btn" id="themeToggle" title="สลับโหมดมืด/สว่าง">
          <i class="fas fa-moon"></i>
      </button>
    </div>
  </div>

  <div class="glass-card animate__animated animate__fadeInUp">
    <div class="p-0">
      <div class="table-responsive">
        <table class="table glass-table align-middle">
          <thead>
            <tr>
              <th style="width:220px;">ชื่อ</th>
              <th style="width:170px;">เบอร์โทรศัพท์</th>
              <th>รายละเอียดเครื่องที่ใช้</th>
              <th style="width:150px;">วันที่ทำรายการ</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while($row = $result->fetch_assoc()): ?>
                <?php
                  $unit_th = (($row['duration_unit'] ?? '') === 'day') ? 'วัน' : 'ชม.';
                  $detail = "<strong class='text-warning'>อุปกรณ์:</strong> {$row['device_type']} <span class='mx-1'>|</span> <strong class='text-warning'>จำนวน:</strong> {$row['qty']}";
                  $detail .= " <br> <strong class='text-warning'>ระยะเวลา:</strong> {$row['duration_value']} {$unit_th}";
                  $detail .= " <span class='mx-1'>|</span> <strong class='text-warning'>สถานที่:</strong> {$row['location']}";
                  if (!empty($row['note'])) $detail .= " <br> <span class='text-white-50 small'>Wait Note: {$row['note']}</span>";
                ?>
                <tr>
                  <td>
                    <div class="fw-bold" style="color: #FFD700;"><?= htmlspecialchars($row['user_name'] ?? '-') ?></div>
                  </td>
                  <td><?= htmlspecialchars($row['user_phone'] ?? '-') ?></td>
                  <td style="line-height: 1.6;"><?= $detail ?></td>
                  <td class="small text-white-50"><?= htmlspecialchars($row['created_at'] ?? '-') ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="4" class="text-center py-5">
                    <i class="fas fa-history fa-3x mb-3 opacity-50"></i>
                    <p class="mb-0 text-white-50">ยังไม่มีประวัติการใช้งาน</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
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
