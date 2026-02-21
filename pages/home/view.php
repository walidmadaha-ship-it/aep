<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();

if (!empty($_SESSION['admin_login'])) {
  require __DIR__ . '/admin_home.php';
  return;
}

if (!empty($_SESSION['member_login'])) {
  require __DIR__ . '/member_home.php';
  return;

}
?>

<?php
if (session_status() === PHP_SESSION_NONE)
  session_start();
?>

<style>
  body {
    background-color: #0f0f0f;
    background-image:
      radial-gradient(circle at 10% 20%, rgba(245, 196, 0, 0.05) 0%, transparent 20%),
      radial-gradient(circle at 90% 80%, rgba(245, 196, 0, 0.05) 0%, transparent 20%);
    color: #e0e0e0;
    font-family: 'Prompt', sans-serif;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    color: #fff;
    font-weight: 600;
  }

  /* Luxury Text Utilities */
  .text-gold-gradient {
    background: linear-gradient(135deg, #FFD700 0%, #FDB931 50%, #FFD700 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 2px 10px rgba(255, 215, 0, 0.2);
  }

  .text-luxury-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
  }



  /* Fix: Force dark text on cards inputs (Specific override) */


  /* Fix: Force dark text on cards and inputs to prevent white-on-white issues */
  /* Global Dark Mode Overrides */
  .card {
    background-color: #1e1e1e;
    color: #FFD700;
    /* Default text gold for cards */
    border: 1px solid #333;
  }

  .form-control {
    background-color: #2a2a2a;
    border: 1px solid #444;
    color: #FFD700 !important;
    /* Input text Gold */
  }

  .form-control::placeholder {
    color: rgba(255, 215, 0, 0.5) !important;
    /* Placeholder Gold dim */
  }

  .form-control:focus {
    background-color: #333;
    color: #FFD700 !important;
    border-color: #f5c400;
    box-shadow: 0 0 0 0.25rem rgba(245, 196, 0, 0.25);
  }

  /* Nav Pills Gold */
  .nav-pills .nav-link.active,
  .nav-pills .show>.nav-link {
    background-color: #FFD700;
    color: #000;
    font-weight: bold;
  }

  .nav-pills .nav-link {
    color: #FFD700;
  }

  /* Link colors */
  a {
    color: #FFD700;
    text-decoration: none;
  }

  a:hover {
    color: #FDB931;
    text-decoration: underline;
  }

  /* Button Gold Overrides */
  .btn-dark {
    background-color: #1e1e1e;
    color: #FFD700;
    border: 1px solid #FFD700;
  }

  .btn-dark:hover {
    background-color: #FFD700;
    color: #000;
    border-color: #FFD700;
    box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
  }

  .btn-outline-primary {
    color: #FFD700;
    border-color: #FFD700;
  }

  .btn-outline-primary:hover {
    background-color: #FFD700;
    color: #000;
    border-color: #FFD700;
  }

  .hero {
    background: linear-gradient(90deg, #1b1b19, #fad81b, #ff8c00);
    background-size: 200% 200%;
    animation: heroGradient 6s ease infinite;
    color: #fff;
    padding: 22px;
    border-radius: 16px;
    position: relative;
    overflow: hidden;
  }

  @keyframes heroGradient {
    0% {
      background-position: 0% 50%;
    }

    50% {
      background-position: 100% 50%;
    }

    100% {
      background-position: 0% 50%;
    }
  }

  .hero-icon {
    animation: electricGlow 2s infinite alternate;
  }

  @keyframes electricGlow {
    from {
      filter: drop-shadow(0 0 2px rgba(255, 215, 0, 0.4));
    }

    to {
      filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.9));
    }
  }

  .card-soft {
    border: 0;
    border-radius: 16px;
  }

  .tool-card {
    border: 0;
    border-radius: 16px;
    transition: transform .25s ease, box-shadow .25s ease;
  }

  .tool-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 36px rgba(0, 0, 0, .15);
  }

  .tool-icon {
    width: 80px;
    height: 80px;
    color: #f5c400;
    transition: transform .25s ease;
  }

  .tool-card:hover .tool-icon {
    transform: scale(1.1) rotate(-3deg);
  }

  /* Login Enhancements */
  .form-control:focus {
    box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    /* Gold Glow */
    border-color: #f5c400;
  }

  @keyframes shake {

    0%,
    100% {
      transform: translateX(0);
    }

    10%,
    30%,
    50%,
    70%,
    90% {
      transform: translateX(-5px);
    }

    20%,
    40%,
    60%,
    80% {
      transform: translateX(5px);
    }
  }

  .shake-input {
    animation: shake 0.5s;
    border-color: #dc3545 !important;
    box-shadow: 0 0 10px rgba(220, 53, 69, 0.5) !important;
  }

  /* Electric Loader Overlay */
  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s;
  }

  .loading-overlay.show {
    opacity: 1;
    visibility: visible;
  }

  .electric-loader {
    width: 60px;
    height: 60px;
    border: 4px solid transparent;
    border-top-color: #f5c400;
    border-radius: 50%;
    animation: spin 1s linear infinite, glow 1.5s ease-in-out infinite alternate;
  }

  @keyframes spin {
    100% {
      transform: rotate(360deg);
    }
  }

  @keyframes glow {
    from {
      box-shadow: 0 0 5px #f5c400;
    }

    to {
      box-shadow: 0 0 20px #ff8c00;
    }
  }

  /* Success Checkmark */
  .checkmark-wrapper {
    display: none;
    text-align: center;
  }

  .checkmark {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #4bb71b;
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px #4bb71b;
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    margin: 0 auto 20px;
  }

  .checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #4bb71b;
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
  }

  .checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
  }

  @keyframes stroke {
    100% {
      stroke-dashoffset: 0;
    }
  }

  @keyframes scale {

    0%,
    100% {
      transform: none;
    }

    50% {
      transform: scale3d(1.1, 1.1, 1);
    }
  }

  @keyframes fill {
    100% {
      box-shadow: inset 0px 0px 0px 50px #4bb71b;
    }

    /* Nav Pills Custom Styling */
    .nav-pills .nav-link {
      color: #ccc !important;
      /* Light text */
      background-color: #1a1a1a !important;
      /* Dark background */
      margin-right: 8px;
      border-radius: 50px;
      padding: 8px 20px;
      transition: all 0.3s ease;
      border: 1px solid #333 !important;
      font-weight: 500;
    }

    .nav-pills .nav-link:hover {
      background-color: #333 !important;
      color: #fff !important;
      transform: translateY(-1px);
      border-color: #555 !important;
    }

    .nav-pills .nav-link.active {
      background-color: #f5c400 !important;
      color: #000 !important;
      font-weight: bold;
      box-shadow: 0 4px 10px rgba(245, 196, 0, 0.4);
      border-color: #f5c400 !important;
    }

    /* Custom Accordion - Modern Light Gold with Animations */
    .accordion-item {
      border: 1px solid #333 !important;
      margin-bottom: 12px;
      border-radius: 12px !important;
      background-color: #1a1a1a !important;

      /* Fix: Remove overflow hidden to allow shadow/pop to show */
      overflow: visible !important;
      position: relative;

      /* Initial Shadow and Transition */
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);

      /* Entrance Animation */
      opacity: 0;
      animation: slideInUp 0.6s ease-out forwards;
    }

    /* Staggered Delay for items */
    .accordion-item:nth-child(1) {
      animation-delay: 0.1s;
    }

    .accordion-item:nth-child(2) {
      animation-delay: 0.2s;
    }

    .accordion-item:nth-child(3) {
      animation-delay: 0.3s;
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Hover Effect - Force Pop */
    .accordion-item:hover {
      transform: translateY(-8px) scale(1.02) !important;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15) !important;
      border-color: #f5c400 !important;
      z-index: 10;
      cursor: pointer;
    }

    .accordion-button {
      background-color: #1a1a1a !important;
      color: #e0e0e0 !important;
      font-weight: 600;
      border-bottom: 1px solid #333;
      padding: 16px 20px;
      border-radius: 12px !important;
      /* Ensure rounded since item isn't clipping */
      transition: all 0.3s ease;
    }

    /* Active State - Gold Gradient & Pop */
    /* Active styling adjustments for radius */
    .accordion-button:not(.collapsed) {
      background: linear-gradient(135deg, #f5c400, #ff8c00) !important;
      color: #fff !important;
      border-bottom: none;
      box-shadow: 0 5px 15px rgba(245, 196, 0, 0.4);
      padding-left: 28px;

      /* Only round top when open */
      border-bottom-left-radius: 0 !important;
      border-bottom-right-radius: 0 !important;
    }

    .accordion-body {
      background-color: #1a1a1a !important;
      color: #ccc !important;
      border-top: 1px solid #333;
      line-height: 1.6;
      border-bottom-left-radius: 12px;
      border-bottom-right-radius: 12px;
      animation: fadeIn 0.5s ease 0.2s forwards;
      /* Add delay so it fades in after expand */
      opacity: 0;
      /* Start hidden for fade in */
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    /* Icon Animation */
    .accordion-button::after {
      filter: grayscale(100%);
      transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55), filter 0.3s ease;
      /* Bouncy rotate */
    }

    /* Icon Rotate & Color on Active */
    .accordion-button:not(.collapsed)::after {
      filter: brightness(0) invert(1) !important;
      transform: rotate(360deg) scale(1.2);
    }

    /* System Knowledge Card New Design */
    .knowledge-card {
      background: linear-gradient(145deg, #1a1a1a, #262626) !important;
      border: 1px solid #444;
      color: #e0e0e0;
    }

    .knowledge-icon-box {
      width: 50px;
      height: 50px;
      background: rgba(245, 196, 0, 0.1);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      border: 1px solid rgba(245, 196, 0, 0.2);
    }

    .knowledge-title {
      color: #f5c400;
      font-weight: 700;
      margin-bottom: 6px;
    }

    .knowledge-text {
      color: #d0d0d0 !important;
      font-size: 0.95rem;
      line-height: 1.6;
    }

    .k-step {
      position: relative;
      padding-left: 20px;
      color: #d0d0d0 !important;
    }

    .k-step::before {
      content: "•";
      color: #f5c400;
      position: absolute;
      left: 0;
      font-weight: bold;
    }

    /* Effects & Animations */
    .knowledge-card {
      transition: all 0.3s ease-in-out;
      border: 1px solid #444;
    }

    .knowledge-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(245, 196, 0, 0.15) !important;
      border-color: #f5c400;
      background: linear-gradient(145deg, #262626, #1a1a1a) !important;
    }

    .knowledge-icon-box {
      transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
      position: relative;
      overflow: hidden;
      z-index: 1;
    }

    /* Hover effect */
    .knowledge-item:hover .knowledge-icon-box {
      transform: scale(1.1) rotate(10deg);
      background: #f5c400;
      color: #000;
      box-shadow: 0 0 20px rgba(245, 196, 0, 0.6);
      border-color: #f5c400;
    }

    /* Interactive Collapsible Items */
    .knowledge-item {
      cursor: pointer;
      border-radius: 12px;
      padding: 10px;
      transition: background 0.3s;
      border: 1px solid transparent;
      animation: slideInRight 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) backwards;
    }

    .knowledge-item:hover {
      background: rgba(255, 255, 255, 0.05);
      border-color: rgba(245, 196, 0, 0.2);
    }

    .knowledge-item.active {
      background: rgba(245, 196, 0, 0.1);
      border-color: #f5c400;
    }

    .k-body {
      max-height: 0;
      overflow: hidden;
      opacity: 0;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      padding: 0 !important;
      margin: 0 !important;
    }

    .knowledge-item.active .k-body {
      max-height: 500px !important;
      /* increased max-height to be safe */
      opacity: 1;
      margin-top: 10px !important;
    }

    /* Chevron Icon */
    .k-chevron {
      transition: transform 0.3s;
      color: #666;
    }

    .knowledge-item:hover .k-chevron {
      color: #f5c400;
    }

    .knowledge-item.active .k-chevron {
      transform: rotate(180deg);
      color: #f5c400;
    }

    .knowledge-item:nth-child(1) {
      animation-delay: 0.1s;
    }

    .knowledge-item:nth-child(2) {
      animation-delay: 0.3s;
    }

    .knowledge-item:nth-child(3) {
      animation-delay: 0.5s;
    }

    @keyframes slideInRight {
      0% {
        opacity: 0;
        transform: translateX(-30px);
      }

      100% {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* Running Light Border Animation - ROBUST V2 */
    .border-effect {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 9999;
      border-radius: 12px;
      overflow: hidden;
      /* Keep overflow hidden ONLY on the mask, not the card */
      animation: rainbow-hue 3s linear infinite;
    }

    @keyframes rainbow-hue {
      from {
        filter: hue-rotate(0deg);
      }

      to {
        filter: hue-rotate(360deg);
      }
    }

    .border-effect span {
      position: absolute;
      display: block;
    }

    .border-effect span:nth-child(1) {
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, transparent, #ff0000);
      animation: animate1 2s linear infinite;
      box-shadow: 0 0 10px #ff0000;
    }

    @keyframes animate1 {
      0% {
        left: -100%;
      }

      50%,
      100% {
        left: 100%;
      }
    }

    .border-effect span:nth-child(2) {
      top: -100%;
      right: 0;
      width: 4px;
      height: 100%;
      background: linear-gradient(180deg, transparent, #ff0000);
      animation: animate2 2s linear infinite;
      animation-delay: 0.5s;
      box-shadow: 0 0 10px #ff0000;
    }

    @keyframes animate2 {
      0% {
        top: -100%;
      }

      50%,
      100% {
        top: 100%;
      }
    }

    .border-effect span:nth-child(3) {
      bottom: 0;
      right: -100%;
      width: 100%;
      height: 4px;
      background: linear-gradient(270deg, transparent, #ff0000);
      animation: animate3 2s linear infinite;
      animation-delay: 1s;
      box-shadow: 0 0 10px #ff0000;
    }

    @keyframes animate3 {
      0% {
        right: -100%;
      }

      50%,
      100% {
        right: 100%;
      }
    }

    .border-effect span:nth-child(4) {
      bottom: -100%;
      left: 0;
      width: 4px;
      height: 100%;
      background: linear-gradient(360deg, transparent, #ff0000);
      animation: animate4 2s linear infinite;
      animation-delay: 1.5s;
      box-shadow: 0 0 10px #ff0000;
    }

    @keyframes animate4 {
      0% {
        bottom: -100%;
      }

      50%,
      100% {
        bottom: 100%;
      }
    }
</style>

<div class="container my-4">

  <!-- HERO -->
  <div class="hero shadow-sm d-flex align-items-center mb-4">
    <img src="image/lol.png" height="64" class="me-3 hero-icon" alt="">
    <div>
      <h3 class="fw-bold mb-1 text-gold-gradient">ระบบจ่ายไฟฉุกเฉินอัตโนมัติ</h3>
      <div class="opacity-75 text-luxury-shadow">
        กรณีไฟฟ้าขัดข้อง ระบบจะสลับไปใช้ไฟสำรองอัตโนมัติ เพื่อความต่อเนื่องและความปลอดภัย
      </div>
    </div>
  </div>

  <div class="row g-4">

    <!-- LEFT: เนื้อหา + อุปกรณ์ -->
    <div class="col-lg-8">

      <!-- Knowledge - Glass Cards Design -->
      <style>
        .glass-row {
          display: flex;
          gap: 1.5rem;
          flex-wrap: wrap;
        }

        .glass-col {
          flex: 1;
          min-width: 250px;
        }

        .glass-card {
          background: rgba(30, 30, 30, 0.6);
          /* Dark mode base */
          backdrop-filter: blur(12px);
          -webkit-backdrop-filter: blur(12px);
          border: 1px solid rgba(255, 255, 255, 0.08);
          border-radius: 16px;
          padding: 1.5rem;
          height: 100%;
          transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
          position: relative;
          /* overflow: hidden; Removed to ensure border is visible */
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* Subtle animated glow background */
        .glass-card::before {
          content: '';
          position: absolute;
          top: -50%;
          left: -50%;
          width: 200%;
          height: 200%;
          background: radial-gradient(circle, rgba(245, 196, 0, 0.1) 0%, transparent 60%);
          opacity: 0;
          transform: scale(0.5);
          transition: opacity 0.5s, transform 0.5s;
          pointer-events: none;
        }

        .glass-card:hover {
          transform: translateY(-8px) scale(1.02);
          background: rgba(255, 255, 255, 0.9);
          border-color: #f5c400;
          box-shadow: 0 15px 35px rgba(245, 196, 0, 0.2);
        }

        .glass-card:hover::before {
          opacity: 1;
          transform: scale(1);
        }

        .glass-icon-box {
          width: 60px;
          height: 60px;
          background: #333;
          border-radius: 14px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 28px;
          margin-bottom: 1rem;
          color: #f5c400;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
          border: 1px solid #444;
          transition: transform 0.4s ease;
        }

        .glass-card:hover .glass-icon-box {
          transform: rotate(-10deg) scale(1.1);
          background: #f5c400;
          color: #111;
        }

        .glass-title {
          font-weight: 700;
          font-size: 1.25rem;
          margin-bottom: 0.75rem;
          color: #f0f0f0;
        }

        .glass-text {
          font-size: 0.95rem;
          color: #ccc;
          line-height: 1.6;
        }
      </style>

      <div class="mb-5">
        <h4 class="fw-bold mb-4 border-start border-4 border-warning ps-3 text-gold-gradient">ความรู้เกี่ยวกับระบบ
          AEPS</h4>

        <div class="glass-row">
          <!-- Card 1 -->
          <div class="glass-col">
            <div class="glass-card">
              <div class="border-effect">
                <span></span><span></span><span></span><span></span>
              </div>
              <div class="glass-icon-box">⚡</div>
              <h5 class="glass-title">AEPS คืออะไร?</h5>
              <p class="glass-text">
                ระบบจ่ายไฟฉุกเฉินอัตโนมัติ สลับแหล่งจ่ายไฟทันทีเมื่อเกิดเหตุขัดข้อง เพื่อความต่อเนื่องของระบบไฟฟ้า 24
                ชม.
              </p>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="glass-col">
            <div class="glass-card">
              <div class="border-effect">
                <span></span><span></span><span></span><span></span>
              </div>
              <div class="glass-icon-box">⚙️</div>
              <h5 class="glass-title">ทำงานอย่างไร?</h5>
              <p class="glass-text">
                <span class="d-block mb-1">1. <strong>ตรวจจับ:</strong> เฝ้าระวังไฟดับ</span>
                <span class="d-block mb-1">2. <strong>สลับไฟ:</strong> ATS ทำงานอัตโนมัติ</span>
                <span class="d-block">3. <strong>คืนค่า:</strong> กลับสู่ปกติเมื่อไฟมา</span>
              </p>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="glass-col">
            <div class="glass-card">
              <div class="border-effect">
                <span></span><span></span><span></span><span></span>
              </div>
              <div class="glass-icon-box">✅</div>
              <h5 class="glass-title">ดียังไง?</h5>
              <div class="glass-text">
                <span class="badge bg-dark text-warning border border-warning me-1 mb-1">ลดความเสี่ยง</span>
                <span class="badge bg-dark text-warning border border-warning me-1 mb-1">ทำงานต่อเนื่อง</span>
                <span class="badge bg-dark text-warning border border-warning">ปลอดภัยสูง</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Equipment - Tech Glass Design -->
      <style>
        .tech-card {
          background: linear-gradient(145deg, #1e1e1e, #252525);
          border: 1px solid #333;
          border-radius: 12px;
          padding: 2rem 1rem;
          text-align: center;
          transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
          position: relative;
          /* overflow: hidden; Removed */
        }

        .tech-card::after {
          content: '';
          position: absolute;
          bottom: 0;
          left: 0;
          width: 100%;
          height: 4px;
          background: #444;
          transition: height 0.3s, background 0.3s;
        }

        .tech-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
          border-color: rgba(245, 196, 0, 0.5);
        }

        .tech-card:hover::after {
          height: 4px;
          background: #f5c400;
        }

        .tech-icon-wrapper {
          width: 80px;
          height: 80px;
          margin: 0 auto 1.5rem;
          display: flex;
          align-items: center;
          justify-content: center;
          background: #2a2a2a;
          border-radius: 50%;
          box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
          position: relative;
          z-index: 1;
          transition: all 0.4s ease;
          border: 2px solid transparent;
        }

        .tech-card:hover .tech-icon-wrapper {
          border-color: #f5c400;
          transform: scale(1.1);
          box-shadow: 0 0 20px rgba(245, 196, 0, 0.3);
        }

        .tech-icon {
          width: 40px;
          height: 40px;
          color: #ccc;
          transition: color 0.3s;
        }

        .tech-card:hover .tech-icon {
          color: #f5c400;
        }

        .tech-title {
          font-weight: 800;
          letter-spacing: 0.5px;
          margin-bottom: 0.5rem;
          color: #f0f0f0;
        }

        .tech-badge {
          font-size: 0.75rem;
          text-transform: uppercase;
          letter-spacing: 1px;
          color: #aaa;
          background: #333;
          padding: 4px 12px;
          border-radius: 20px;
          display: inline-block;
          border: 1px solid #444;
        }
      </style>

      <div class="mb-5">
        <h4 class="fw-bold mb-4 border-start border-4 border-warning ps-3 text-gold-gradient">อุปกรณ์หลัก (System
          Components)</h4>

        <div class="row g-4">
          <!-- ATS -->
          <div class="col-md-4">
            <div class="tech-card h-100">
              <div class="border-effect">
                <span></span><span></span><span></span><span></span>
              </div>
              <div class="tech-icon-wrapper">
                <svg class="tech-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                </svg>
              </div>
              <h5 class="tech-title">ATS</h5>
              <p class="text-muted small mb-3">Automatic Transfer Switch</p>
              <div class="tech-badge">Smart Switch</div>
            </div>
          </div>

          <!-- Battery/UPS -->
          <div class="col-md-4">
            <div class="tech-card h-100">
              <div class="border-effect">
                <span></span><span></span><span></span><span></span>
              </div>
              <div class="tech-icon-wrapper">
                <svg class="tech-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="2" y="7" width="20" height="10" rx="2" ry="2"></rect>
                  <line x1="6" y1="7" x2="6" y2="17"></line>
                  <line x1="18" y1="7" x2="18" y2="17"></line>
                  <path d="M10 12h4"></path>
                </svg>
              </div>
              <h5 class="tech-title">UPS / Battery</h5>
              <p class="text-muted small mb-3">Uninterruptible Power Supply</p>
              <div class="tech-badge">Zero Latency</div>
            </div>
          </div>

          <!-- Generator -->
          <div class="col-md-4">
            <div class="tech-card h-100">
              <div class="border-effect">
                <span></span><span></span><span></span><span></span>
              </div>
              <div class="tech-icon-wrapper">
                <svg class="tech-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 2v20"></path>
                  <path d="M2 12h20"></path>
                  <circle cx="12" cy="12" r="10"></circle>
                  <path d="M12 2a10 10 0 0 1 10 10"></path>
                </svg>
              </div>
              <h5 class="tech-title">Generator</h5>
              <p class="text-muted small mb-3">Diesel / Gas Engine</p>
              <div class="tech-badge">Long Runtime</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Detailed System Breakdown -->
      <div class="mt-5">
        <h3 class="fw-bold mb-4 border-start border-4 border-warning ps-3 text-gold-gradient">รายละเอียดระบบและอุปกรณ์
        </h3>

        <!-- ATS Section -->
        <div class="card card-soft shadow-sm mb-4 overflow-hidden">
          <div class="row g-0 align-items-center">
            <div class="col-md-5">
              <img src="image/ats_unit.png" class="img-fluid h-100 object-fit-cover" alt="ATS Unit"
                style="min-height: 300px;">
            </div>
            <div class="col-md-7">
              <div class="card-body p-4 p-lg-5">
                <h4 class="fw-bold mb-3">⚡ ATS (Automatic Transfer Switch)</h4>
                <p class="text-secondary mb-4">
                  หัวใจหลักของระบบที่ทำหน้าที่ตรวจสอบสถานะไฟฟ้าและสั่งการสลับแหล่งจ่ายไฟอย่างแม่นยำ
                  เมื่อไฟฟ้าหลักดับลง ATS จะสั่งสตาร์ทเครื่องกำเนิดไฟฟ้าและสลับไปใช้ไฟฟ้าสำรองภายในเวลาไม่กี่วินาที
                </p>
                <ul class="list-unstyled mb-0">
                  <li class="mb-2 d-flex align-items-center">
                    <span class="badge bg-warning text-dark me-2">Fast</span>
                    สลับไฟรวดเร็ว ลดช่วงเวลาไฟดับ
                  </li>
                  <li class="mb-2 d-flex align-items-center">
                    <span class="badge bg-warning text-dark me-2">Smart</span>
                    ระบบประมวลผลแม่นยำ สั่งการอัตโนมัติ
                  </li>
                  <li class="d-flex align-items-center">
                    <span class="badge bg-warning text-dark me-2">Safe</span>
                    ป้องกันการชนกันของกระแสไฟ
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Battery Section -->
        <div class="card card-soft shadow-sm mb-4 overflow-hidden">
          <div class="row g-0 align-items-center flex-md-row-reverse">
            <div class="col-md-5">
              <img src="image/battery_ups.png" class="img-fluid h-100 object-fit-cover" alt="Battery System"
                style="min-height: 300px;">
            </div>
            <div class="col-md-7">
              <div class="card-body p-4 p-lg-5">
                <h4 class="fw-bold mb-3">🔋 Battery & UPS System</h4>
                <p class="text-secondary mb-4">
                  แหล่งพลังงานสำรองที่พร้อมจ่ายไฟทันทีที่เกิดเหตุขัดข้อง (Zero Transfer Time)
                  เหมาะสำหรับอุปกรณ์อิเล็กทรอนิกส์ที่ละเอียดอ่อน เช่น เซิร์ฟเวอร์และเครื่องมือแพทย์
                </p>
                <div class="row g-3">
                  <div class="col-6">
                    <div class="border rounded p-3 text-center bg-white">
                      <div class="h3 fw-bold text-warning mb-0">0ms</div>
                      <small class="text-muted">Transfer Time</small>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="border rounded p-3 text-center bg-white">
                      <div class="h3 fw-bold text-warning mb-0">100%</div>
                      <small class="text-muted">Protection</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Generator Section -->
        <div class="card card-soft shadow-sm mb-4 overflow-hidden">
          <div class="row g-0 align-items-center">
            <div class="col-md-5">
              <img src="image/diesel_generator.png" class="img-fluid h-100 object-fit-cover" alt="Generator"
                style="min-height: 300px;">
            </div>
            <div class="col-md-7">
              <div class="card-body p-4 p-lg-5">
                <h4 class="fw-bold mb-3">⚙️ Generator (เครื่องกำเนิดไฟฟ้า)</h4>
                <p class="text-secondary mb-4">
                  พลังงานสำรองระยะยาวที่รองรับโหลดขนาดใหญ่ สามารถทำงานต่อเนื่องได้ตราบเท่าที่มีเชื้อเพลิง
                  ทำงานร่วมกับ ATS เพื่อรับช่วงต่อจากแบตเตอรี่เมื่อไฟดับเป็นเวลานาน
                </p>
                <div class="d-flex gap-2 flex-wrap">
                  <span class="badge bg-light text-dark border">Heavy Duty</span>
                  <span class="badge bg-light text-dark border">Diesel/Gas</span>
                  <span class="badge bg-light text-dark border">Long Run</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

    <!-- RIGHT: Login/สมัคร/ติดต่อ (จะอยู่ขวาเหมือนเดิม) -->
    <div class="col-lg-4">

      <div class="card card-soft shadow-sm">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-3 text-gold-gradient">🔐 เข้าสู่ระบบ</h5>

          <ul class="nav nav-pills mb-3" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabAdmin"
                type="button">Admin</button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabMember" type="button">Member</button>
            </li>
          </ul>

          <div class="tab-content">
            <div class="tab-pane fade show active" id="tabAdmin">
              <form id="formAdminLogin" action="pages/login/login.php" method="post">
                <div class="mb-2"><input class="form-control" name="user_admin" placeholder="Admin Username" required>
                </div>
                <div class="mb-3"><input class="form-control" type="password" name="password" placeholder="Password"
                    required></div>
                <button class="btn btn-dark w-100" type="submit">Login (Admin)</button>
              </form>
            </div>

            <div class="tab-pane fade" id="tabMember">
              <form id="formMemberLogin" action="pages/member_login/login.php" method="post">
                <div class="mb-2"><input class="form-control" name="member_username" placeholder="Member Username"
                    required></div>
                <div class="mb-3"><input class="form-control" type="password" name="password" placeholder="Password"
                    required></div>
                <button class="btn btn-dark w-100" type="submit">Login (Member)</button>
              </form>
            </div>
          </div>

          <hr class="my-4">
          <a href="index.php?page=register" class="btn btn-success w-100">🧑‍💼 สมัครสมาชิก</a>
          <div class="small mt-2 text-center" style="color: #FFD700;">สมัครแล้วสามารถใช้บริการและติดตามสถานะได้</div>
        </div>
      </div>

      <!-- ติดต่อ -->
      <div class="card card-soft shadow-sm mt-3">
        <div class="card-body p-4 text-center">
          <h6 class="fw-bold mb-2 text-gold-gradient">ติดต่อเจ้าหน้าที่</h6>
          <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#contactModal">
            📞 ติดต่อเจ้าหน้าที่
          </button>
          <div class="small mt-2" style="color: #FFD700;">กดเพื่อดูเบอร์โทร</div>
        </div>
      </div>

    </div>

  </div>
</div>

<!-- Modal เบอร์โทร -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">📞 ติดต่อเจ้าหน้าที่</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <div class="fs-4 fw-bold mb-2">081-234-5678</div>
        <div class="text-muted">เวลาทำการ 08:00 - 17:00</div>
      </div>
      <div class="modal-footer justify-content-center">
        <a href="tel:0812345678" class="btn btn-primary">โทรเลย</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
      </div>
    </div>
  </div>
</div>

<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success mt-3">
    ✅ ส่งแจ้งเหตุเรียบร้อยแล้ว เจ้าหน้าที่จะติดต่อกลับ
  </div>
<?php endif; ?>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loginOverlay">
  <div id="loaderBox">
    <div class="electric-loader mb-3"></div>
    <div class="fs-5 fw-bold">กำลังเชื่อมต่อระบบ...</div>
  </div>
  <div class="checkmark-wrapper" id="successCheck">
    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
      <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
      <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
    </svg>
    <div class="fs-5 fw-bold text-success mt-3">เข้าสู่ระบบสำเร็จ!</div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {

    function handleLogin(formId) {
      const form = document.getElementById(formId);
      if (!form) return;

      form.addEventListener('submit', function (e) {
        e.preventDefault();

        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(i => i.classList.remove('shake-input'));

        // Show Overlay
        const overlay = document.getElementById('loginOverlay');
        const loaderBox = document.getElementById('loaderBox');
        const successCheck = document.getElementById('successCheck');

        loaderBox.style.display = 'block';
        successCheck.style.display = 'none';
        overlay.classList.add('show');

        const formData = new FormData(form);

        setTimeout(() => {
          fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          })
            .then(async response => {
              const text = await response.text();
              try {
                return JSON.parse(text);
              } catch (err) {
                console.error("Invalid JSON response:", text);
                throw new Error("Invalid JSON: " + text.substring(0, 100)); // Show part of error
              }
            })
            .then(data => {
              if (data.status === 'success') {
                // Success Animation
                loaderBox.style.display = 'none';
                successCheck.style.display = 'block';
                setTimeout(() => {
                  window.location.reload();
                }, 1500); // Wait for checkmark animation
              } else {
                // Error
                console.error("Login Error:", data.message);
                overlay.classList.remove('show');
                inputs.forEach(i => {
                  i.classList.add('shake-input');
                  // Remove class after animation to allow replay
                  setTimeout(() => i.classList.remove('shake-input'), 500);
                });
                if (data.message) alert(data.message);
              }
            })
            .catch(err => {
              console.error("Fetch/System Error:", err);
              overlay.classList.remove('show');
              alert('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + err.message);
            });
        }, 800);
      });
    }

    handleLogin('formAdminLogin');
    handleLogin('formMemberLogin');

    // Trigger Entrance Animations
    const items = document.querySelectorAll('.knowledge-item');
    items.forEach((item, index) => {
      setTimeout(() => {
        item.classList.add('show');
      }, 300 + (index * 200));
    });
  });

  // Toggle Function for Knowledge Items (Global Scope)
  window.toggleK = function (element) {
    element.classList.toggle('active');
  }
</script>