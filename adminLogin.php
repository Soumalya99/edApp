<?php
session_start();

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is already logged in
if (isset($_SESSION['is_admin_logged_in']) && $_SESSION['is_admin_logged_in'] === true) {
    header('Location: adminDashboard.php');
    exit();
}

// Handle error messages from URL parameters
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
    <title>Admin Login</title>
    
    <!-- Tailwind CSS with fallback -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" 
          onerror="this.onerror=null;this.href='https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css';">
    
    <!-- GSAP with fallback and error handling -->
    <script src="https://unpkg.com/gsap@3.9.1/dist/gsap.min.js" 
            onerror="console.warn('GSAP failed to load from primary CDN, trying fallback...'); this.src='https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js';">
    </script>
    
    <style>
        .loading {
            display: none;
        }

        .loading.show {
            display: inline-block;
        }

        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 8px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Fallback styles in case Tailwind doesn't load */
        .fallback-container {
            background: linear-gradient(to right, #9f7aea, #60a5fa);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        .fallback-form {
            background: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }

        /* Stable animation that doesn't cause flickering */
        .login-form-container {
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.6s ease-out;
        }

        .login-form-container.loaded {
            opacity: 1;
            transform: translateY(0);
        }

        /* Fallback CSS animation */
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(-20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        /* Ensure form is always visible as fallback */
        .login-form-container {
            min-height: 400px;
            display: block !important;
        }

        /* Hide initially only if JavaScript is enabled */
        .js-enabled .login-form-container {
            opacity: 0;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-purple-400 to-blue-400 flex items-center justify-center h-screen fallback-container">
    <div id="login-form" class="login-form-container bg-white shadow-lg rounded-lg p-8 w-full max-w-sm fallback-form">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Admin Login</h2>

        <!-- Success Message -->
        <div id="success-message"
            class="flex items-center bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
            role="alert" style="display: none;">
            <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20">
                <path d="M10 15l-5-5 1.41-1.41L10 12.17l7.59-7.58L19 6l-9 9z" />
            </svg>
            <span id="success-text"></span>
        </div>

        <!-- Error Message -->
        <div id="error-message"
            class="flex items-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
            role="alert" style="display: none;">
            <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path
                    d="M10 15l-2-2h4l-2 2zm0-12c-1.1 0-2 .9-2 2v4l1 1v-5h2v1l1 1V5.04A2 2 0 0 0 10 3zm-9 9v2h3l10 10 2-2-10-10h-3l-2-2zm10 8a1 1 0 0 1-1-1v-4a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1z" />
            </svg>
            <span id="error-text"></span>
        </div>

        <!-- Show PHP error message if present -->
        <?php if ($error_message): ?>
        <div id="php-error-message" class="flex items-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M10 15l-2-2h4l-2 2zm0-12c-1.1 0-2 .9-2 2v4l1 1v-5h2v1l1 1V5.04A2 2 0 0 0 10 3zm-9 9v2h3l10 10 2-2-10-10h-3l-2-2zm10 8a1 1 0 0 1-1-1v-4a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1z" />
            </svg>
            <span><?php echo htmlspecialchars($error_message); ?></span>
        </div>
        <?php endif; ?>

        <form id="loginForm">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                <input id="username"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500"
                    type="text" name="username" placeholder="Enter username" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input id="password"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500"
                    type="password" name="password" placeholder="Enter password" required>
            </div>
            <button id="submitButton"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-200"
                type="submit">
                <span class="loading">
                    <div class="spinner"></div>
                </span>
                <span id="button-text">Login</span>
            </button>
        </form>
    </div>

    <script>
        // Mark that JavaScript is enabled
        document.documentElement.classList.add('js-enabled');

        // Stable animation initialization
        function initializeAnimations() {
            const loginForm = document.getElementById('login-form');
            
            // Ensure form is visible first
            if (loginForm) {
                // Use CSS transition for stable animation
                setTimeout(() => {
                    loginForm.classList.add('loaded');
                }, 100);

                // Try GSAP animation if available, but don't rely on it
                if (typeof gsap !== 'undefined') {
                    try {
                        // Set initial state
                        gsap.set(loginForm, { opacity: 0, y: -30 });
                        // Animate to visible state
                        gsap.to(loginForm, { 
                            duration: 0.8, 
                            opacity: 1, 
                            y: 0, 
                            ease: "power2.out",
                            delay: 0.1
                        });
                    } catch (error) {
                        console.warn('GSAP animation failed, using CSS fallback:', error);
                        // Ensure CSS animation takes over
                        loginForm.classList.add('animate-fade-in');
                    }
                } else {
                    console.log('GSAP not available, using CSS animations');
                    loginForm.classList.add('animate-fade-in');
                }
            }
        }

        // Initialize animations safely
        function safeInitialize() {
            try {
                initializeAnimations();
            } catch (error) {
                console.error('Animation initialization failed:', error);
                // Ensure form is visible even if animations fail
                const loginForm = document.getElementById('login-form');
                if (loginForm) {
                    loginForm.style.opacity = '1';
                    loginForm.style.transform = 'translateY(0)';
                    loginForm.classList.add('loaded');
                }
            }
        }

        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', safeInitialize);
        } else {
            safeInitialize();
        }

        // Utility functions
        function showMessage(type, message) {
            const messageDiv = document.getElementById(`${type}-message`);
            const textSpan = document.getElementById(`${type}-text`);
            if (messageDiv && textSpan) {
                textSpan.textContent = message;
                messageDiv.style.display = 'block';

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 5000);
            }
        }

        function setLoading(isLoading) {
            const submitButton = document.getElementById('submitButton');
            const loading = submitButton.querySelector('.loading');
            const buttonText = document.getElementById('button-text');

            if (isLoading) {
                loading.classList.add('show');
                buttonText.textContent = 'Logging in...';
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                loading.classList.remove('show');
                buttonText.textContent = 'Login';
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        // Main login form handler      
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            // Client-side validation
            if (!username || !password) {
                showMessage('error', 'Please enter both username and password');
                return;
            }

            if (username.length < 3) {
                showMessage('error', 'Username must be at least 3 characters long');
                return;
            }

            if (password.length < 6) {
                showMessage('error', 'Password must be at least 6 characters long');
                return;
            }

            setLoading(true);

            const formData = new URLSearchParams();
            formData.append('username', username);
            formData.append('password', password);

            console.log('Sending login request...');

            // API path
            const apiPath = 'php_backend/api/admin_Login.php';

            fetch(apiPath, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData,
                credentials: 'same-origin' // Include cookies/session
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response URL:', response.url);

                // Check if we were redirected (which might indicate success)
                if (response.redirected) {
                    console.log('Redirected to:', response.url);
                    
                    // Check if redirected to dashboard
                    if (response.url.includes('adminDashboard.php')) {
                        showMessage('success', 'Login successful! Redirecting...');
                        setTimeout(() => {
                            window.location.href = 'adminDashboard.php';
                        }, 1000);
                        return;
                    }
                    
                    // Check if redirected back to login with error
                    if (response.url.includes('adminLogin.php') && response.url.includes('error=')) {
                        const urlParams = new URLSearchParams(response.url.split('?')[1]);
                        const errorMsg = urlParams.get('error');
                        if (errorMsg) {
                            showMessage('error', decodeURIComponent(errorMsg));
                            return;
                        }
                    }
                }

                // Handle non-redirect responses
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                return response.text();
            })
            .then(text => {
                if (!text) return; // Skip if already handled redirect

                console.log('Response text:', text);

                // Try to parse as JSON
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed JSON:', data);

                    if (data.success) {
                        showMessage('success', 'Login successful! Redirecting...');
                        setTimeout(() => {
                            window.location.href = data.redirect_url || 'adminDashboard.php';
                        }, 1000);
                    } else {
                        const errorMsg = data.error || data.message || 'Login failed';
                        showMessage('error', errorMsg);
                    }
                } catch (e) {
                    // Not JSON, check if it's HTML with redirect
                    if (text.includes('adminDashboard.php')) {
                        showMessage('success', 'Login successful! Redirecting...');
                        setTimeout(() => {
                            window.location.href = 'adminDashboard.php';
                        }, 1000);
                    } else if (text.includes('<!DOCTYPE html>')) {
                        showMessage('error', 'Server returned HTML instead of expected response');
                    } else {
                        showMessage('error', 'Unexpected response from server');
                    }
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                showMessage('error', 'Connection error: ' + error.message);
            })
            .finally(() => {
                setLoading(false);
            });
        });

        // Auto-hide PHP error message
        <?php if ($error_message): ?>
        setTimeout(() => {
            const phpErrorDiv = document.getElementById('php-error-message');
            if (phpErrorDiv) {
                phpErrorDiv.style.display = 'none';
            }
        }, 5000);
        <?php endif; ?>
    </script>
</body>

</html>