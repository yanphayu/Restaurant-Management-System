<?php
$pageTitle = 'Login';
$pageDepth = 2;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KitchenFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/auth.js"></script>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: var(--surface);
        }
    </style>
</head>
<body class="font-body-md text-on-surface">

    <div class="w-full max-w-md px-4">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-xl">
            <div class="flex items-center justify-center gap-3 mb-2">
                <div class="w-12 h-12 rounded-xl bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-2xl">restaurant</span>
                </div>
            </div>
            <h1 class="text-headline-lg font-headline-lg font-bold text-on-surface text-center mb-1">KitchenFlow</h1>
            <p class="text-body-sm text-on-surface-variant text-center mb-8">Sign in to your account</p>

            <form id="loginForm" class="space-y-5">
                <div>
                    <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Username</label>
                    <input type="text" id="loginUsername" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md placeholder:text-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Enter your username">
                </div>
                <div>
                    <label class="text-label-caps font-label-caps text-on-surface-variant block mb-2">Password</label>
                    <input type="password" id="loginPassword" required class="w-full py-3 px-4 bg-surface-container-low border border-outline-variant rounded-lg text-body-md placeholder:text-on-surface-variant focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" placeholder="Enter your password">
                </div>
                <button  type="submit" class="w-full py-2.5 px-5 bg-primary text-on-primary rounded-xl font-semibold flex items-center justify-center gap-2 hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined">login</span>
                    Sign In
                </button>
            </form>

            <p class="text-body-sm text-on-surface-variant text-center mt-6">Don't have an account? <a href="register.php" class="text-primary font-semibold hover:underline">Sign up</a></p>
        </div>
    </div>

</body>
</html>