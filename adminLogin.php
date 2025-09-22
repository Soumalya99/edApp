<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION['is_admin_logged_in']) && $_SESSION['is_admin_logged_in'] === true) {
    header('Location: adminDashboard.php');
    exit();
}

$error_message = '';
if (isset($_GET['error'])) {
    $error_message = urldecode($_GET['error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login & Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" onerror="this.onerror=null;this.href='https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css';">
    <style>
        .tab-active { background-color: #3B82F6; color: white; }
        .tab-inactive { background: white; color: #374151; border:1px solid #e5e7eb; }
        .tab-btn { transition: all 0.2s; }
        .spinner {border: 2px solid #f3f3f3;border-top: 2px solid #3498db;border-radius: 50%;width: 20px;height: 20px;animation: spin 1s linear infinite;display: inline-block;margin-right: 8px;}@keyframes spin {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}
        .loading.show { display:inline-block;}
        .loading { display:none;}
        .otp-input { width:2.5rem; text-align:center; font-size: 1.5rem; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
</head>
<body class="bg-gradient-to-r from-purple-400 to-blue-400 flex items-center justify-center h-screen">

<div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md flex flex-col min-w-[360px]">
    <div class="flex mb-8">
        <button id="tab-login" class="tab-btn w-1/2 py-2 rounded-l tab-active">Login</button>
        <button id="tab-register" class="tab-btn w-1/2 py-2 rounded-r tab-inactive">Register</button>
    </div>
    <!-- Error/success messages -->
    <div id="message-box" style="display:none;"></div>
    <?php if ($error_message): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <span><?php echo htmlspecialchars($error_message); ?></span>
    </div>
    <?php endif; ?>
    <!-- TAB CONTENTS -->
    <div id="tab-content-login">
        <form id="loginForm">
            <div class="mb-4">
                <label for="login-username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                <input id="login-username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" type="text" name="username" placeholder="Enter username" required>
            </div>
            <div class="mb-2">
                <label for="login-password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input id="login-password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" type="password" name="password" placeholder="Enter password" required>
            </div>
            <div class="flex items-center justify-between">
                <a href="#" id="forgot-link" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
            </div>
            <button id="loginBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full mt-6 transition duration-200" type="submit">
                <span class="loading"><div class="spinner"></div></span>
                <span id="login-btn-text">Login</span>
            </button>
        </form>
        <!-- FORGOT PASSWORD MULTISTEP -->
        <div id="forgot-steps" style="display:none;">
            <form id="forgotForm" class="mt-6">
                <p class="mb-4 text-gray-700">Enter your username and associated phone to reset password.</p>
                <div class="mb-4">
                    <label for="forgot-username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                    <input id="forgot-username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" type="text" name="username" placeholder="Enter username" required>
                </div>
                <div class="mb-6">
                    <label for="forgot-phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number:</label>
                    <input id="forgot-phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" type="text" name="phone" placeholder="Enter your phone" required>
                </div>
                <button id="forgotBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-200" type="submit">
                    <span class="loading"><div class="spinner"></div></span>
                    <span id="forgot-btn-text">Send OTP</span>
                </button>
                <button type="button" id="forgot-back-btn" class="mt-4 w-full text-blue-600 underline text-sm">Go back to login</button>
            </form>
            <form id="otpForm" class="mt-6" style="display:none;">
                <p class="mb-4 text-gray-700">Enter 4-digit OTP sent to your phone:</p>
                <div class="flex justify-between mb-6">
                    <input type="text" maxlength="1" class="otp-input border rounded mx-1" inputmode="numeric" />
                    <input type="text" maxlength="1" class="otp-input border rounded mx-1" inputmode="numeric" />
                    <input type="text" maxlength="1" class="otp-input border rounded mx-1" inputmode="numeric" />
                    <input type="text" maxlength="1" class="otp-input border rounded mx-1" inputmode="numeric" />
                </div>
                <button id="otpBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-200" type="submit" >
                    <span class="loading"><div class="spinner"></div></span>
                    <span id="otp-btn-text">Verify OTP</span>
                </button>
                <button type="button" id="otp-back-btn" class="mt-4 w-full text-blue-600 underline text-sm">Back</button>
            </form>
            <form id="resetPwForm" class="mt-6" style="display:none;">
                <p class="mb-4 text-gray-700">Enter your new password:</p>
                <div class="mb-4">
                    <label for="reset-new-password" class="block text-gray-700 text-sm font-bold mb-2">New Password:</label>
                    <input id="reset-new-password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" type="password" name="password" required placeholder="New password">
                </div>
                <div class="mb-6">
                    <label for="reset-confirm-password" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password:</label>
                    <input id="reset-confirm-password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" type="password" name="confirm_password" required placeholder="Confirm password">
                </div>
                <button id="resetPwBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-200" type="submit" >
                    <span class="loading"><div class="spinner"></div></span>
                    <span id="resetPw-btn-text">Reset Password</span>
                </button>
                <button type="button" id="reset-back-btn" class="mt-4 w-full text-blue-600 underline text-sm">Back</button>
            </form>
        </div>
    </div>
    <div id="tab-content-register" style="display:none;">
        <form id="registerForm">
            <div class="mb-4">
                <label for="reg-username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                <input id="reg-username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" type="text" name="username" required placeholder="Username">
            </div>
            <div class="mb-4">
                <label for="reg-phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number:</label>
                <input id="reg-phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" type="text" name="phone" required placeholder="Phone">
            </div>
            <div class="mb-4">
                <label for="reg-password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input id="reg-password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" type="password" name="password" required placeholder="Password">
            </div>
            <div class="mb-6">
                <label for="reg-confirm-password" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password:</label>
                <input id="reg-confirm-password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500" type="password" name="confirm_password" required placeholder="Confirm Password">
            </div>
            <button id="registerBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-200" type="submit">
                <span class="loading"><div class="spinner"></div></span>
                <span id="register-btn-text">Register</span>
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script src="./main.js"></script>
</body>
</html>
