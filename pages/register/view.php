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
        padding: 2rem;
        max-width: 600px;
        margin: 2rem auto;
        animation: fadeInDown 0.8s ease;
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

    .btn-submit {
        background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
        border: none;
        color: #000;
        font-weight: bold;
        padding: 12px;
        border-radius: 10px;
        width: 100%;
        margin-top: 1rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        background: linear-gradient(135deg, #FDB931 0%, #FFD700 100%);
    }

    .header-icon {
        font-size: 3rem;
        color: var(--primary-gold);
        margin-bottom: 1rem;
        text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="glass-card">
    <div class="text-center mb-4">
        <i class="fas fa-user-plus header-icon"></i>
        <h3 class="fw-bold text-white">สมัครสมาชิก</h3>
        <p class="text-white-50">กรอกข้อมูลเพื่อลงทะเบียนเข้าใช้งานระบบ</p>
    </div>

    <form action="pages/register/create.php" method="post">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-user me-2"></i>Username</label>
                <input type="text" class="form-control" name="member_username" required placeholder="ตั้งชื่อผู้ใช้งาน">
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                <input type="password" class="form-control" name="password" required placeholder="ตั้งรหัสผ่าน">
            </div>

            <div class="col-12">
                <label class="form-label"><i class="fas fa-address-card me-2"></i>ชื่อ-นามสกุล</label>
                <input type="text" class="form-control" name="user_name" required placeholder="ระบุชื่อและนามสกุล">
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-phone me-2"></i>เบอร์โทรศัพท์</label>
                <input type="tel" class="form-control" name="user_phone" required placeholder="0xx-xxx-xxxx">
            </div>

            <div class="col-md-6">
                <label class="form-label"><i class="fas fa-envelope me-2"></i>E-mail</label>
                <input type="email" class="form-control" name="report_detail" placeholder="example@email.com">
            </div>

            <div class="col-12">
                <label class="form-label"><i class="fas fa-map-marker-alt me-2"></i>ที่อยู่</label>
                <textarea class="form-control" name="user_address" rows="3" placeholder="รายละเอียดที่อยู่ / การจัดส่ง"></textarea>
            </div>
        </div>

        <button class="btn btn-submit" type="submit">
            <i class="fas fa-check-circle me-2"></i>ยืนยันการสมัครสมาชิก
        </button>
        
        <div class="text-center mt-3">
            <a href="index.php?page=member_login" class="text-white-50 text-decoration-none small">
                <i class="fas fa-arrow-left me-1"></i> กลับไปหน้าเข้าสู่ระบบ
            </a>
        </div>
    </form>
</div>
