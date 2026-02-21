<?php
// pages/admins/view.php
if (session_status() === PHP_SESSION_NONE)
  session_start();
require __DIR__ . "/../../php/config.php";

// เช็กว่าล็อกอินแล้วหรือยัง (ขั้นต่ำ)
if (empty($_SESSION['admin_login'])) {
  echo '<div class="alert alert-warning">กรุณาล็อกอินก่อน</div>';
  return;
}

// ดึงรายชื่อแอดมินทั้งหมด
$result = $conn->query("SELECT id, username, role, is_active, created_at FROM admins ORDER BY id DESC");
?>

<style>
  :root {
    --glass-bg: rgba(30, 30, 30, 0.95);
    /* Dark background for contrast on white page */
    --glass-border: rgba(255, 215, 0, 0.3);
    --glass-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    --text-gold: #FFD700;
    --table-header-bg: rgba(255, 215, 0, 0.15);
  }

  .glass-container {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 15px;
    box-shadow: var(--glass-shadow);
    padding: 20px;
    margin-bottom: 20px;
    color: #fff;
  }

  .section-title {
    color: var(--text-gold);
    text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
    border-left: 4px solid var(--text-gold);
    padding-left: 15px;
    margin-bottom: 20px;
    font-weight: 600;
  }

  .form-control,
  .form-select {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
  }

  .form-control:focus,
  .form-select:focus {
    background: rgba(255, 255, 255, 0.15);
  }

  .btn-gold {
    background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
    border: none;
    color: #000;
    font-weight: bold;
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .btn-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
    background: linear-gradient(135deg, #FDB931 0%, #FFD700 100%);
    color: #000;
  }

  .table-glass {
    color: #fff;
    margin-bottom: 0;
  }

  .table-glass thead th {
    background-color: var(--table-header-bg);
    border-color: rgba(255, 255, 255, 0.1);
    color: var(--text-gold);
    font-weight: 600;
  }

  .table-glass tbody td {
    background-color: transparent;
    border-color: rgba(255, 255, 255, 0.05);
    vertical-align: middle;
  }

  .table-glass tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.05);
  }

  .status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.85rem;
  }

  .status-active {
    background: rgba(25, 135, 84, 0.2);
    color: #75b798;
    border: 1px solid rgba(25, 135, 84, 0.3);
  }

  .status-inactive {
    background: rgba(220, 53, 69, 0.2);
    color: #ea868f;
    border: 1px solid rgba(220, 53, 69, 0.3);
  }
</style>

<div class="container mt-4">

  <h3 class="mb-4 text-dark"><i class="fas fa-users-cog me-2" style="color: #FFD700;"></i>จัดการผู้ดูแลระบบ (Admins)
  </h3>

  <?php if (!empty($_SESSION['admin_msg'])): ?>
    <div class="alert alert-info bg-opacity-25 bg-info text-white border-info mb-4">
      <i class="fas fa-info-circle me-2"></i><?= $_SESSION['admin_msg']; ?>
    </div>
    <?php unset($_SESSION['admin_msg']); ?>
  <?php endif; ?>

  <div class="row">
    <!-- Left Column: Add Admin Form -->
    <div class="col-lg-4 mb-4">
      <div class="glass-container h-100">
        <h5 class="section-title">➕ เพิ่มแอดมินใหม่</h5>

        <?php if (($_SESSION['admin_role'] ?? '') !== 'superadmin'): ?>
          <div class="alert alert-secondary bg-opacity-10 text-white border-secondary mb-0">
            <small><i class="fas fa-lock me-1"></i> เฉพาะ <strong>superadmin</strong> เท่านั้นที่เพิ่มแอดมินได้</small>
          </div>
        <?php else: ?>
          <form action="pages/admins/create.php" method="post">
            <div class="mb-3">
              <label class="form-label text-white-50">Username</label>
              <input class="form-control" name="username" required placeholder="User">
            </div>

            <div class="mb-3">
              <label class="form-label text-white-50">Password</label>
              <input class="form-control" type="password" name="password" required placeholder="Pass">
            </div>

            <div class="mb-4">
              <label class="form-label text-white-50">Role</label>
              <select class="form-select" name="role">
                <option value="admin" selected>admin</option>
                <option value="superadmin">superadmin</option>
              </select>
            </div>

            <button class="btn btn-gold w-100" type="submit">
              <i class="fas fa-plus-circle me-1"></i> เพิ่มแอดมิน
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Column: Admin List Table -->
    <div class="col-lg-8">
      <div class="glass-container">
        <h5 class="section-title">📋 รายชื่อแอดมิน</h5>

        <div class="table-responsive">
          <table class="table table-glass align-middle">
            <thead>
              <tr>
                <th width="5%">ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Active</th>
                <th>Created</th>
                <th width="10%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td class="text-center text-white-50"><?= (int) $row['id'] ?></td>
                  <td class="fw-bold"><?= htmlspecialchars($row['username']) ?></td>
                  <td>
                    <?php if ($row['role'] === 'superadmin'): ?>
                      <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Super</span>
                    <?php else: ?>
                      <span class="badge bg-secondary">Admin</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ((int) $row['is_active'] === 1): ?>
                      <span class="status-badge status-active"><i class="fas fa-check-circle me-1"></i>Active</span>
                    <?php else: ?>
                      <span class="status-badge status-inactive"><i class="fas fa-times-circle me-1"></i>Inactive</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-white-50 small"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                  <td class="text-center">
                    <?php if (($_SESSION['admin_role'] ?? '') === 'superadmin' && (int) $row['id'] !== (int) ($_SESSION['admin_id'] ?? 0)): ?>
                      <form action="pages/admins/delete.php" method="post" class="d-inline"
                        onsubmit="return confirm('ยืนยันที่จะลบแอดมินคนนี้?');">
                        <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                        <button class="btn btn-sm btn-outline-danger border-0" title="ลบ">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    <?php else: ?>
                      <span class="text-white-50 opacity-25"><i class="fas fa-ban"></i></span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <div class="mt-3 text-end">
          <small class="text-white-50" style="font-size: 0.75rem;">
            <i class="fas fa-info-circle me-1"></i> superadmin สามารถเพิ่ม/ลบแอดมินอื่นได้ (ยกเว้นตัวเอง)
          </small>
        </div>
      </div>
    </div>
  </div>

</div>