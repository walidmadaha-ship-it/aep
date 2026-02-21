<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ระบบจ่ายไฟฉุกเฉินอัตโนมัติ</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!DOCTYPE html>
<html lang="th">

<head>

    <!-- Google Fonts: Prompt -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <style>
        <?php if (isset($page) && $page == 'home'): ?>body {
                background: url("image/lol.png") no-repeat center center fixed;
                background-size: auto;
                min-height: 100vh;
            }

        <?php else: ?>body {
                background-color: #f4f6f9;
                /* Light gray/white background */
                min-height: 100vh;
                color: #333;
                /* Default text color for light bg */
            }

        <?php endif; ?>
    </style>
</head>

<body>

    <!-- ✅ วางตรงนี้ -->
    <div class="bg-overlay"></div>

    <!-- ===== เนื้อหาเว็บทั้งหมด ===== -->
    <div class="container my-0"></div>

</body>

</html>