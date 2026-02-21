<style>
    .navbar-custom {
        background: linear-gradient(90deg, #1b1b19, #2c2c2a);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        padding: 1rem 0;
    }

    .navbar-brand {
        font-weight: bold;
        color: #fff !important;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .navbar-brand img {
        filter: drop-shadow(0 0 5px rgba(245, 196, 0, 0.5));
        transition: transform 0.3s;
    }

    .navbar-brand:hover img {
        transform: rotate(-10deg) scale(1.1);
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.85) !important;
        font-weight: 500;
        margin: 0 5px;
        position: relative;
        transition: all 0.3s;
    }

    .nav-link:hover,
    .nav-link.active {
        color: #f5c400 !important;
        text-shadow: 0 0 10px rgba(245, 196, 0, 0.3);
    }

    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 50%;
        background-color: #f5c400;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 80%;
    }

    .btn-logout {
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        transition: all 0.3s;
    }

    .btn-logout:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: #fff;
        box-shadow: 0 0 10px rgba(220, 53, 69, 0.5);
    }

    .admin-badge {
        background: rgba(255, 255, 255, 0.1);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        border: 1px solid rgba(245, 196, 0, 0.3);
        color: #f5c400;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top mb-4">
    <div class="container">
        <a class="navbar-brand" href="?page=home">
            <img src="image/lol.png" alt="Logo" height="40">
            <span>AEP SYSTEM</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($page == 'home') ? 'active' : '' ?>" href="?page=home">หน้าหลัก</a>
                </li>

                <?php if (!empty($_SESSION['admin_login'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'user') ? 'active' : '' ?>" href="?page=user">ข้อมูลสมาชิก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'status') ? 'active' : '' ?>" href="?page=status">สถานะการใช้งาน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($page == 'report') ? 'active' : '' ?>" href="?page=report">ประวัติการแจ้งเหตุ</a>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if (!empty($_SESSION['admin_login'])): ?>
                <div class="d-flex align-items-center gap-3">
                    <div class="admin-badge">
                        <i class="me-1">👤</i> Admin: <strong><?= $_SESSION['admin_name']; ?></strong>
                    </div>
                    <a href="pages/login/logout.php" class="btn btn-sm btn-logout px-3">
                        Logout
                    </a>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['member_login'])): ?>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-white small opacity-75">
                        สวัสดี, สมาชิก
                    </div>
                    <a href="pages/member_login/logout.php" class="btn btn-sm btn-logout px-3">
                        Logout
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</nav>