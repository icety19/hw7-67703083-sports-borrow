<?php
session_start();
require_once 'config/db.php';

$database = new Database();
$db = $database->getConnection();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT id, username, password, full_name, role FROM users WHERE username = :username LIMIT 0,1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($password == $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่คุณสมชาย";
        }
    } else {
        $error = "ไม่พบผู้ใช้งานในฐานระบบของเรา";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Login | Sports Borrow System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Prompt:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #818cf8;
        }
        body { font-family: 'Outfit', 'Prompt', sans-serif; overflow: hidden; }
        
        /* Animated Background */
        .area {
            background: #0f172a;  
            background: -webkit-linear-gradient(to left, #1e1b4b, #0f172a);
            width: 100%;
            height:100vh;
            position: absolute;
            z-index: -1;
        }

        .circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            animation: animate 25s linear infinite;
            bottom: -150px;
        }

        .circles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .circles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .circles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .circles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .circles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .circles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .circles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .circles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .circles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .circles li:nth-child(10){ left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .input-glow:focus {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
            border-color: #3b82f6;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #818cf8 100%);
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.4);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="area">
        <ul class="circles">
            <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
        </ul>
    </div>

    <div class="glass p-10 rounded-[30px] w-full max-w-md mx-4 transform transition-all hover:scale-[1.01]">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-500/10 border border-blue-500/20 mb-6">
                <i class="fas fa-volleyball-ball text-4xl text-blue-400"></i>
            </div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight mb-2">Sports Central</h1>
            <p class="text-blue-200/60 font-medium">Enterprise Equipment Management</p>
        </div>

        <form method="POST" action="" class="space-y-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-blue-200/80 ml-1">Username</label>
                <div class="relative group">
                    <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-blue-300/40 group-focus-within:text-blue-400 transition-colors"></i>
                    <input type="text" name="username" required 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white placeholder-white/20 focus:outline-none input-glow transition-all"
                        placeholder="admin">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-blue-200/80 ml-1">Password</label>
                <div class="relative group">
                    <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-blue-300/40 group-focus-within:text-blue-400 transition-colors"></i>
                    <input type="password" name="password" required 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white placeholder-white/20 focus:outline-none input-glow transition-all"
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit" 
                class="w-full btn-gradient py-4 rounded-2xl text-white font-bold text-lg shadow-xl shadow-blue-500/20">
                Sign In
            </button>
        </form>

        <div class="mt-10 pt-8 border-t border-white/5 text-center">
            <p class="text-blue-200/30 text-xs tracking-widest uppercase">Secured by Enterprise Shield</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <?php if($error): ?>
    <script>
        $(document).ready(function() {
            $.alert({
                title: '<span class="text-red-500 font-bold">Access Denied!</span>',
                content: '<?php echo $error; ?>',
                type: 'red',
                theme: 'material',
                closeIcon: true,
                animation: 'scale',
                typeAnimated: true
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
