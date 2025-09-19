<?php
session_start();
if (!isset($_SESSION['is_admin_logged_in']) || $_SESSION['is_admin_logged_in'] !== true) {
  header('Location: adminLogin.php');
  exit;
}

// Handle logout
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: index.php');
  exit();
}
$admin_name = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | Theta Fornix</title>

  <!-- Inter font and Tailwind CDN -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- GSAP -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

  <!-- Lottie Web Component -->
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

  <link rel="stylesheet" href="./style.css" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(120deg, #f1f5f9 0%, #c7d2fe 100%);
      min-height: 100vh;
    }

    /* Simple pre-submit spinner */
    .spinner {
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255, 255, 255, 0.6);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* Dropzone states */
    .dropzone.dragover {
      border-color: rgb(28, 102, 222);
      background: rgba(55, 125, 237, 0.205);
    }
  </style>
</head>



<body>
  <!-- Navbar -->
  <header id="site-header" class="headerNav fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <!-- Brand -->
        <a href="/" class="text-2xl font-extrabold tracking-tight text-blue-600">Theta Fornix</a>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex items-center gap-8">
          <!-- Home (mega menu trigger) -->
          <div class="relative group">
            <button id="home-trigger" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
              data-anim="nav-link" aria-haspopup="true" aria-expanded="false" aria-controls="mega-home">
              Home
            </button>
            <!-- Mega Menu: Desktop -->
            <div id="mega-home"
              class="invisible opacity-0 pointer-events-none absolute left-1/2 -translate-x-1/2 top-full mt-3 w-[min(1100px,94vw)] rounded-2xl border border-gray-200 bg-white shadow-xl p-6 transition-all duration-200 ease-out">
              <div class="grid grid-cols-12 gap-6">
                <!-- Left: Categories -->
                <aside class="col-span-4">
                  <ul class="space-y-1">
                    <li>
                      <button
                        class="mm-cat flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-medium hover:bg-blue-50 hover:text-blue-700 active:bg-blue-100 data-[active=true]:bg-blue-100 data-[active=true]:text-blue-700"
                        data-target="neet" data-active="true">
                        <span>NEET</span>
                        <span class="text-xs text-gray-400">›</span>
                      </button>
                    </li>
                    <li>
                      <button
                        class="mm-cat flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-medium hover:bg-blue-50 hover:text-blue-700 active:bg-blue-100"
                        data-target="jee">
                        <span>JEE</span>
                        <span class="text-xs text-gray-400">›</span>
                      </button>
                    </li>
                    <li>
                      <button
                        class="mm-cat flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-medium hover:bg-blue-50 hover:text-blue-700 active:bg-blue-100"
                        data-target="nursing">
                        <span>BSC Nursing</span>
                        <span class="text-xs text-gray-400">›</span>
                      </button>
                    </li>
                    <li>
                      <button
                        class="mm-cat flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-medium hover:bg-blue-50 hover:text-blue-700 active:bg-blue-100"
                        data-target="anmgnm">
                        <span>ANM &amp; GNM</span>
                        <span class="text-xs text-gray-400">›</span>
                      </button>
                    </li>
                    <li class="pt-2">
                      <a href="/batch.html"
                        class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:text-blue-700 hover:bg-blue-50">
                        All Courses
                      </a>
                    </li>
                  </ul>
                </aside>
                <!-- Right: Cards Panel -->
                <section class="col-span-8">
                  <!-- NEET -->
                  <div class="mm-panel" id="panel-neet">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                      <a href="/batch.html?track=neet&amp;level=class11"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">NEET</div>
                        <div class="text-gray-900 font-bold">Class 11</div>
                        <div class="text-gray-500 text-sm">Foundation &amp; advanced</div>
                      </a>
                      <a href="/batch.html?track=neet&amp;level=class12"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">NEET</div>
                        <div class="text-gray-900 font-bold">Class 12</div>
                        <div class="text-gray-500 text-sm">Problem solving</div>
                      </a>
                      <a href="/batch.html?track=neet&amp;level=repeater"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">NEET</div>
                        <div class="text-gray-900 font-bold">Repeater</div>
                        <div class="text-gray-500 text-sm">Focused revision</div>
                      </a>
                    </div>
                  </div>
                  <!-- JEE -->
                  <div class="mm-panel hidden" id="panel-jee">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                      <a href="/batch.html?track=jee&amp;level=class11"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">JEE</div>
                        <div class="text-gray-900 font-bold">Class 11</div>
                        <div class="text-gray-500 text-sm">Mathematics track</div>
                      </a>
                      <a href="/batch.html?track=jee&amp;level=class12"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">JEE</div>
                        <div class="text-gray-900 font-bold">Class 12</div>
                        <div class="text-gray-500 text-sm">Advanced prep</div>
                      </a>
                      <a href="/batch.html?track=jee&amp;level=repeater"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">JEE</div>
                        <div class="text-gray-900 font-bold">Repeater</div>
                        <div class="text-gray-500 text-sm">Intensive course</div>
                      </a>
                    </div>
                  </div>
                  <!-- BSC Nursing -->
                  <div class="mm-panel hidden" id="panel-nursing">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                      <a href="/batch.html?track=nursing&amp;level=class11"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">BSC Nursing</div>
                        <div class="text-gray-900 font-bold">Class 11</div>
                        <div class="text-gray-500 text-sm">Early orientation</div>
                      </a>
                      <a href="/batch.html?track=nursing&amp;level=class12"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">BSC Nursing</div>
                        <div class="text-gray-900 font-bold">Class 12</div>
                        <div class="text-gray-500 text-sm">Research skills</div>
                      </a>
                      <a href="/batch.html?track=nursing&amp;level=repeater"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">BSC Nursing</div>
                        <div class="text-gray-900 font-bold">Crash Course</div>
                        <div class="text-gray-500 text-sm">Deep dive</div>
                      </a>
                    </div>
                  </div>
                  <!-- ANM & GNM -->
                  <div class="mm-panel hidden" id="panel-anmgnm">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                      <a href="/batch.html?track=anmgnm&amp;level=class11"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">ANM &amp; GNM</div>
                        <div class="text-gray-900 font-bold">Class 11</div>
                        <div class="text-gray-500 text-sm">Early orientation</div>
                      </a>
                      <a href="/batch.html?track=anmgnm&amp;level=class12"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">ANM &amp; GNM</div>
                        <div class="text-gray-900 font-bold">Class 12</div>
                        <div class="text-gray-500 text-sm">Research skills</div>
                      </a>
                      <a href="/batch.html?track=anmgnm&amp;level=repeater"
                        class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                        <div class="text-blue-600 font-semibold mb-1">ANM &amp; GNM</div>
                        <div class="text-gray-900 font-bold">Crash Course</div>
                        <div class="text-gray-500 text-sm">Deep dive</div>
                      </a>
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </div>

          <a href="batch.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
            data-anim="nav-link">Courses</a>
          <a href="selection.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
            data-anim="nav-link">Selections</a>
          <a href="team.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
            data-anim="nav-link">Our Team</a>
          <a href="resources.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
            data-anim="nav-link">Resources</a>
          <a href="contact.html" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
            data-anim="nav-link">Contact</a>
        </nav>

        <!-- Admin Section & Hamburger -->
        <div class="flex items-center gap-4">
          <!-- Admin Info (Desktop) -->
          <div class="hidden md:flex items-center gap-3">
            <div class="flex items-center gap-2 px-3 py-1 bg-blue-50 rounded-lg">
              <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              <span class="text-sm font-medium text-blue-700">Welcome,
                <?php echo htmlspecialchars($admin_name); ?></span>
            </div>
            <a href="?logout=1"
              class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors"
              onclick="return confirm('Are you sure you want to logout?')">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              Logout
            </a>
          </div>

          <!-- Hamburger -->
          <button id="burger"
            class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50"
            aria-label="Open menu" aria-controls="mobile-menu" aria-expanded="false">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 bg-white">
      <div class="px-4 py-3 space-y-1">
        <!-- Home mobile accordion -->
        <button class="w-full flex items-center justify-between py-2 text-left font-medium text-gray-800"
          data-mobile-toggle="home">
          <span>Home</span>
          <span class="text-gray-400">+</span>
        </button>
        <div class="ml-3 mt-1 hidden space-y-1" data-mobile-panel="home">
          <!-- Categories accordion inside Home -->
          <button class="w-full flex items-center justify-between py-2 text-left text-sm font-medium text-gray-700"
            data-mobile-toggle="home-neet">
            <span>NEET</span><span class="text-gray-300">+</span>
          </button>
          <div class="ml-3 hidden space-y-1" data-mobile-panel="home-neet">
            <a href="/batch.html?track=neet&amp;level=class11" class="block py-1 text-sm text-gray-600">Class 11</a>
            <a href="/batch.html?track=neet&amp;level=class12" class="block py-1 text-sm text-gray-600">Class 12</a>
            <a href="/batch.html?track=neet&amp;level=repeater" class="block py-1 text-sm text-gray-600">Repeater</a>
          </div>

          <button class="w-full flex items-center justify-between py-2 text-left text-sm font-medium text-gray-700"
            data-mobile-toggle="home-jee">
            <span>JEE</span><span class="text-gray-300">+</span>
          </button>
          <div class="ml-3 hidden space-y-1" data-mobile-panel="home-jee">
            <a href="/batch.html?track=jee&amp;level=class11" class="block py-1 text-sm text-gray-600">Class 11</a>
            <a href="/batch.html?track=jee&amp;level=class12" class="block py-1 text-sm text-gray-600">Class 12</a>
            <a href="/batch.html?track=jee&amp;level=repeater" class="block py-1 text-sm text-gray-600">Repeater</a>
          </div>

          <button class="w-full flex items-center justify-between py-2 text-left text-sm font-medium text-gray-700"
            data-mobile-toggle="home-nursing">
            <span>BSC Nursing</span><span class="text-gray-300">+</span>
          </button>
          <div class="ml-3 hidden space-y-1" data-mobile-panel="home-nursing">
            <a href="/batch.html?track=nursing&amp;level=class11" class="block py-1 text-sm text-gray-600">Class 11</a>
            <a href="/batch.html?track=nursing&amp;level=class12" class="block py-1 text-sm text-gray-600">Class 12</a>
            <a href="/batch.html?track=nursing&amp;level=repeater" class="block py-1 text-sm text-gray-600">Crash
              Course</a>
          </div>

          <button class="w-full flex items-center justify-between py-2 text-left text-sm font-medium text-gray-700"
            data-mobile-toggle="home-anmgnm">
            <span>ANM &amp; GNM</span><span class="text-gray-300">+</span>
          </button>
          <div class="ml-3 hidden space-y-1" data-mobile-panel="home-anmgnm">
            <a href="/batch.html?track=anmgnm&amp;level=class11" class="block py-1 text-sm text-gray-600">Class 11</a>
            <a href="/batch.html?track=anmgnm&amp;level=class12" class="block py-1 text-sm text-gray-600">Class 12</a>
            <a href="/batch.html?track=anmgnm&amp;level=repeater" class="block py-1 text-sm text-gray-600">Crash
              Course</a>
          </div>

          <a href="/batch.html" class="block py-2 text-sm font-medium text-blue-700">All Courses</a>
        </div>

        <a href="/batch.html" class="block py-2 font-medium text-gray-800">Courses</a>
        <a href="/selection.html" class="block py-2 font-medium text-gray-800">Selections</a>
        <a href="/team.html" class="block py-2 font-medium text-gray-800">Our Team</a>
        <a href="/resources.html" class="block py-2 font-medium text-gray-800">Resources</a>
        <a href="/contact.html" class="block py-2 font-medium text-gray-800">Contact</a>
      </div>
    </div>
  </header>

  <!-- Hero -->
  <section class="pt-28 pb-8">
    <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-[1.2fr,1fr] items-center gap-6">
      <div>
        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">
          Admin Dashboard
        </h2>
        <p class="mt-3 text-slate-600">
          Update media and content with smooth animations and a friendly UI.
        </p>
      </div>
      <div class="flex justify-end">
        <lottie-player src="https://lottie.host/e8d49616-d3ce-40d0-bfec-1e0a67ec714f/nZ7KUpDekB.json"
          background="transparent" speed="1" class="w-80 h-80" loop autoplay>
        </lottie-player>
      </div>
    </div>
  </section>

  <main class="max-w-6xl mx-auto px-4 pb-20 grid gap-10">
    <!-- Teacher Profile -->
    <?php
    // --- Start of teacher preview grid ---
    $all_teachers = [];
    try {
      require_once __DIR__ . '/php_backend/config/conf.php';
      $stmt = $pdo->query("SELECT id, name, profile_image FROM teachers ORDER BY id DESC");
      if ($stmt) {
        $all_teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
    } catch (Exception $e) {
      error_log('Error fetching teachers: ' . $e->getMessage());
    }
    // --- End of teacher preview grid fetch ---
    ?>
    <section id="teacher-profile-section"
      class="bg-white rounded-2xl shadow-lg p-6 md:p-8 hover:shadow-xl transition-shadow">
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <!-- Left: Show all teacher photos with cross overlay for delete -->
        <div>
          <h3 class="text-xl font-semibold mb-2 text-slate-900">All Teachers</h3>
          <?php if (!empty($all_teachers)): ?>
            <style>
              .teacher-gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; }
              .teacher-img-wrap { position: relative; }
              .teacher-delete-btn {
                position: absolute;
                top: 0.4em; right: 0.4em;
                background: rgba(255,55,55,0.93);
                color: #fff;
                border: none;
                border-radius: 9999px;
                width: 2rem; height: 2rem;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.25rem;
                box-shadow: 0 1px 6px 0 rgba(0,0,0,0.08);
                z-index: 5;
                transition: background 0.18s;
                cursor: pointer;
                opacity: 0.85;
              }
              .teacher-delete-btn:hover {
                background: #d91a20;
                opacity: 1;
              }
            </style>
            <div class="teacher-gallery">
              <?php foreach ($all_teachers as $teacher): ?>
                <div class="teacher-img-wrap bg-slate-50 p-2 rounded-lg shadow text-center">
                  <button class="teacher-delete-btn" title="Delete" data-id="<?php echo $teacher['id']; ?>">
                    &times;
                  </button>
                  <img src="<?php echo htmlspecialchars($teacher['profile_image']); ?>"
                       alt="Photo of <?php echo htmlspecialchars($teacher['name']); ?>"
                       class="w-full h-32 object-cover rounded-md shadow bg-slate-100 mb-2">
                  <p class="text-sm font-medium text-slate-700 truncate" title="<?php echo htmlspecialchars($teacher['name']); ?>">
                    <?php echo htmlspecialchars($teacher['name']); ?>
                  </p>
                </div>
              <?php endforeach; ?>
            </div>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.teacher-delete-btn').forEach(function(btn) {
                  btn.addEventListener('click', function() {
                    if (!confirm('Delete this teacher profile?')) return;
                    const teacherId = this.getAttribute('data-id');
                    const imgWrap = this.closest('.teacher-img-wrap');
                    fetch('php_backend/api/teachers.php?id=' + encodeURIComponent(teacherId), {
                      method: 'DELETE',
                    }).then(async res => {
                      const result = await res.json();
                      if (res.ok) {
                        imgWrap.remove();
                        ToastNotification.show('Teacher profile deleted.');
                      } else {
                        ToastNotification.show(result.error || 'Delete failed.', 'error');
                      }
                    }).catch(() => {
                      ToastNotification.show('Could not reach server.', 'error');
                    });
                  });
                });
              });
            </script>
          <?php else: ?>
            <div class="flex items-center justify-center h-full bg-slate-50 rounded-lg p-8 border border-dashed">
              <p class="text-slate-500 text-center">No teachers to show.<br>Upload one to see it here.</p>
            </div>
          <?php endif; ?>
        </div>
        <div>
          <h3 class="text-xl font-semibold mb-1 text-slate-900">Update Teacher Profile</h3>
          <p class="text-slate-600 mb-4">Enter the teacher's name, bio, and upload a profile picture.</p>

          <!-- The id="teacher-profile-form" is important for the JavaScript handler -->
          <form id="teacher-profile-form" action="php_backend/api/teachers.php" method="POST"
            enctype="multipart/form-data" class="space-y-4 animated-form">
            <div>
              <label for="teacher_name" class="block font-medium text-gray-800 mb-2">Teacher Name</label>
              <input id="teacher_name" name="teacher_name" type="text" required placeholder="Enter full name"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
            </div>

            <!-- New Bio Section -->
            <div>
              <label for="teacher_bio" class="block font-medium text-gray-800 mb-2">Bio</label>
              <textarea id="teacher_bio" name="teacher_bio" rows="3" placeholder="A short bio about the teacher..."
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
            </div>

            <div>
              <label class="block font-medium text-gray-800 mb-2">Profile Image</label>
              <div
                class="dropzone group relative border-2 border-dashed border-slate-300 rounded-xl p-5 text-center cursor-pointer transition">
                <input id="profile_image" type="file" name="profile_image" accept="image/*" required
                  class="absolute inset-0 opacity-0 cursor-pointer" />
                <div class="flex flex-col items-center gap-2 pointer-events-none">
                  <span class="rounded-full bg-blue-50 text-blue-600 px-3 py-1 text-xs font-medium">Click or drag &
                    drop</span>
                  <span class="text-slate-500 text-sm">PNG, JPG, JPEG</span>
                  <div class="file-list mt-1 text-xs text-slate-600"></div>
                </div>
              </div>
            </div>

            <!-- This div is for displaying success or error messages from the server -->
            <div id="form-message" class="text-sm font-medium"></div>

            <button type="submit"
              class="submit-btn inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 active:scale-[0.98] transition">
              <span class="btn-label">Upload Profile</span>
              <span class="btn-spinner hidden spinner"></span>
            </button>
          </form>
        </div>
      </div>
    </section>

    <!-- Team Members -->
    <!-- <section id="team-member-section" class="bg-white rounded-2xl shadow-lg p-6 md:p-8 hover:shadow-xl transition-shadow">
        <div class="grid md:grid-cols-2 gap-8 items-center">
          <div class="flex items-center justify-center order-2 md:order-1">
            <div>
              <h3 class="text-xl font-semibold mb-1 text-slate-900">Update Team Members' Photos</h3>
              <p class="text-slate-600 mb-4">Upload multiple images at once.</p>

              <form action="php_backend/team_upload.php" method="POST" enctype="multipart/form-data" class="space-y-4 animated-form">
                <div>
                  <label class="block font-medium text-gray-800 mb-2">Team Images</label>
                  <div class="dropzone group relative border-2 border-dashed border-slate-300 rounded-xl p-5 text-center cursor-pointer transition">
                    <input id="team_images" type="file" name="team_images[]" accept="image/*" multiple required class="absolute inset-0 opacity-0 cursor-pointer" />
                    <div class="flex flex-col items-center gap-2 pointer-events-none">
                      <span class="rounded-full bg-blue-50 text-blue-600 px-3 py-1 text-xs font-medium">Click or drag & drop</span>
                      <span class="text-slate-500 text-sm">PNG, JPG, JPEG</span>
                      <div class="file-list mt-1 text-xs text-slate-600"></div>
                    </div>
                  </div>
                </div>

                <button type="submit" class="submit-btn inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 active:scale-[0.98] transition">
                  <span class="btn-label">Upload</span>
                  <span class="btn-spinner hidden spinner"></span>
                </button>
              </form>
            </div>
          </div>
          <div class="flex items-center justify-center order-1 md:order-2">
            <lottie-player
              src="https://assets10.lottiefiles.com/packages/lf20_kq5rGs.json"
              background="transparent"
              speed="1"
              class="w-72 h-72"
              loop
              autoplay>
            </lottie-player>
          </div>
        </div>
      </section> -->

    <!-- Founders -->
    <?php
    // --- Start of founders preview grid ---
    $all_founders = [];
    try {
      require_once __DIR__ . '/php_backend/config/conf.php';
      $stmt = $pdo->query("SELECT id, name, image_path FROM founders ORDER BY id DESC");
      if ($stmt) {
        $all_founders = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
    } catch (Exception $e) {
      error_log('Error fetching founders: ' . $e->getMessage());
    }
    // --- End of founders preview grid fetch ---
    ?>
    <section id="founder-section" class="bg-white rounded-2xl shadow-lg p-6 md:p-8 hover:shadow-xl transition-shadow">
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <!-- Left: Show all founder photos with delete cross overlay -->
        <div>
          <h3 class="text-xl font-semibold mb-2 text-slate-900">All Founders</h3>
          <?php if (!empty($all_founders)): ?>
            <style>
              .founder-gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; }
              .founder-img-wrap { position: relative; }
              .founder-delete-btn {
                position: absolute;
                top: 0.4em; right: 0.4em;
                background: rgba(255,55,55,0.93);
                color: #fff;
                border: none;
                border-radius: 9999px;
                width: 2rem; height: 2rem;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.25rem;
                box-shadow: 0 1px 6px 0 rgba(0,0,0,0.08);
                z-index: 5;
                transition: background 0.18s;
                cursor: pointer;
                opacity: 0.85;
              }
              .founder-delete-btn:hover {
                background: #d91a20;
                opacity: 1;
              }
            </style>
            <div class="founder-gallery">
              <?php foreach ($all_founders as $founder): ?>
                <div class="founder-img-wrap bg-slate-50 p-2 rounded-lg shadow text-center">
                  <button class="founder-delete-btn" title="Delete" data-id="<?php echo $founder['id']; ?>">&times;</button>
                  <img src="<?php echo htmlspecialchars($founder['image_path']); ?>"
                       alt="Photo of <?php echo htmlspecialchars($founder['name']); ?>"
                       class="w-full h-32 object-cover rounded-md shadow bg-slate-100 mb-2">
                  <p class="text-sm font-medium text-slate-700 truncate" title="<?php echo htmlspecialchars($founder['name']); ?>">
                    <?php echo htmlspecialchars($founder['name']); ?>
                  </p>
                </div>
              <?php endforeach; ?>
            </div>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.founder-delete-btn').forEach(function(btn) {
                  btn.addEventListener('click', function() {
                    if (!confirm('Delete this founder photo?')) return;
                    const founderId = this.getAttribute('data-id');
                    const imgWrap = this.closest('.founder-img-wrap');
                    fetch('php_backend/api/founders.php?id=' + encodeURIComponent(founderId), {
                      method: 'DELETE',
                    }).then(async res => {
                      const result = await res.json();
                      if (res.ok) {
                        imgWrap.remove();
                        ToastNotification.show('Founder photo deleted.');
                      } else {
                        ToastNotification.show(result.error || 'Delete failed.', 'error');
                      }
                    }).catch(() => {
                      ToastNotification.show('Could not reach server.', 'error');
                    });
                  });
                });
              });
            </script>
          <?php else: ?>
            <div class="flex items-center justify-center h-full bg-slate-50 rounded-lg p-8 border border-dashed">
              <p class="text-slate-500 text-center">No founders to show.<br>Upload one to see it here.</p>
            </div>
          <?php endif; ?>
        </div>
        <div>
          <h3 class="text-xl font-semibold mb-1 text-slate-900">Add or Update a Founder</h3>
          <p class="text-slate-600 mb-4">Provide the founder's name and upload one or more photos.</p>

          <form action="php_backend/api/founders.php" method="POST" enctype="multipart/form-data"
            class="space-y-4 animated-form">
            <div>
              <label for="founder_name" class="block font-medium text-gray-800 mb-2">Founder's Name</label>
              <input type="text" id="founder_name" name="founder_name" required
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="e.g., Jane Doe" />
            </div>

            <div>
              <label for="founder_image" class="block font-medium text-gray-800 mb-2">Founder Images</label>
              <div
                class="dropzone group relative border-2 border-dashed border-slate-300 rounded-xl p-5 text-center cursor-pointer transition">
                <input id="founder_image" type="file" name="founder_image" accept="image/*" multiple required
                  class="absolute inset-0 opacity-0 cursor-pointer" />
                <div class="flex flex-col items-center gap-2 pointer-events-none">
                  <span class="rounded-full bg-blue-50 text-blue-600 px-3 py-1 text-xs font-medium">Click or drag &
                    drop</span>
                  <span class="text-slate-500 text-sm">PNG, JPG, JPEG</span>
                  <div class="file-list mt-1 text-xs text-slate-600"></div>
                </div>
              </div>
            </div>

            <button type="submit"
              class="submit-btn inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 active:scale-[0.98] transition">
              <span class="btn-label">Upload</span>
              <span class="btn-spinner hidden spinner"></span>
            </button>
          </form>
        </div>
      </div>
    </section>

    <!-- Selections -->
    <?php
    // --- Start of new code for candidate preview ---
    
    // Fetch recent candidates for the preview gallery.
    // NOTE: This assumes you have a 'candidates' table with 'id', 'name', and 'photo_path' columns.
    $recent_candidates = [];
    try {
      // This requires your database connection file. Adjust the path if necessary.
      require_once __DIR__ . '/php_backend/config/conf.php';

      // Fetch all candidates for admin preview
      $stmt = $pdo->query("SELECT id, name, image_path FROM candidates ORDER BY id DESC");

      if ($stmt) {
        $recent_candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
    } catch (Exception $e) {
      // This will fail silently if the database connection or table is not found,
      // so the rest of the page still loads.
      error_log('Error fetching recent candidates: ' . $e->getMessage());
    }
    // --- End of new code for candidate preview ---
    ?>
    <section id="selection-section" class="bg-white rounded-2xl shadow-lg p-6 md:p-8 hover:shadow-xl transition-shadow">
      <div class="grid md:grid-cols-2 gap-8 items-start">
        <!-- Left side: Form for uploading -->
        <div>
          <h3 class="text-xl font-semibold mb-1 text-slate-900">Upload Selection Candidate Photos</h3>
          <p class="text-slate-600 mb-4">Add a candidate's name and their photo.</p>

          <form action="php_backend/api/candidate.php" method="POST" enctype="multipart/form-data"
            class="space-y-4 animated-form">
            <div>
              <label for="candidate_name" class="block font-medium text-gray-800 mb-2">Candidate's Name</label>
              <input type="text" id="candidate_name" name="candidate_name" required
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="e.g., John Smith" />
            </div>

            <div>
              <label class="block font-medium text-gray-800 mb-2">Selection Images</label>
              <div
                class="dropzone group relative border-2 border-dashed border-slate-300 rounded-xl p-5 text-center cursor-pointer transition">
                <input id="image" type="file" name="image" accept="image/*" multiple required
                  class="absolute inset-0 opacity-0 cursor-pointer" />
                <div class="flex flex-col items-center gap-2 pointer-events-none">
                  <span class="rounded-full bg-blue-50 text-blue-600 px-3 py-1 text-xs font-medium">Click or drag &
                    drop</span>
                  <span class="text-slate-500 text-sm">PNG, JPG, JPEG</span>
                  <div class="file-list mt-1 text-xs text-slate-600"></div>
                </div>
              </div>
            </div>

            <button type="submit"
              class="submit-btn inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 active:scale-[0.98] transition">
              <span class="btn-label">Upload</span>
              <span class="btn-spinner hidden spinner"></span>
            </button>
          </form>
        </div>

        <!-- Right side: Preview of recently added images -->
        <div>
          <h3 class="text-xl font-semibold mb-4 text-slate-900">All Candidates</h3>
          <?php if (!empty($recent_candidates)): ?>
            <style>
              .candidate-gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; }
              .candidate-img-wrap { position: relative; }
              .candidate-delete-btn {
                position: absolute;
                top: 0.4em; right: 0.4em;
                background: rgba(255,55,55,0.93);
                color: #fff;
                border: none;
                border-radius: 9999px;
                width: 2rem; height: 2rem;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.25rem;
                box-shadow: 0 1px 6px 0 rgba(0,0,0,0.08);
                z-index: 5;
                transition: background 0.18s;
                cursor: pointer;
                opacity: 0.85;
              }
              .candidate-delete-btn:hover {
                background: #d91a20;
                opacity: 1;
              }
            </style>
            <div class="candidate-gallery">
              <?php foreach ($recent_candidates as $candidate): ?>
                <div class="candidate-img-wrap group bg-slate-50 p-2 rounded-lg shadow text-center">
                  <button class="candidate-delete-btn" title="Delete" data-id="<?php echo $candidate['id']; ?>">
                    &times;
                  </button>
                  <img src="<?php echo htmlspecialchars($candidate['image_path']); ?>"
                    alt="Photo of <?php echo htmlspecialchars($candidate['name']); ?>"
                    class="w-full h-32 object-cover rounded-md shadow bg-slate-100 mb-2">
                  <p class="text-sm font-medium text-slate-700 truncate"
                    title="<?php echo htmlspecialchars($candidate['name']); ?>">
                    <?php echo htmlspecialchars($candidate['name']); ?>
                  </p>
                </div>
              <?php endforeach; ?>
            </div>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.candidate-delete-btn').forEach(function(btn) {
                  btn.addEventListener('click', function() {
                    if (!confirm('Delete this candidate photo?')) return;
                    const candidateId = this.getAttribute('data-id');
                    const imgWrap = this.closest('.candidate-img-wrap');
                    fetch('php_backend/api/candidate.php?id=' + encodeURIComponent(candidateId), {
                      method: 'DELETE',
                    }).then(async res => {
                      const result = await res.json();
                      if (res.ok) {
                        imgWrap.remove();
                        ToastNotification.show('Photo deleted.');
                      } else {
                        ToastNotification.show(result.error || 'Delete failed.', 'error');
                      }
                    }).catch(() => {
                      ToastNotification.show('Could not reach server.', 'error');
                    });
                  });
                });
              });
            </script>
          <?php else: ?>
            <div class="flex items-center justify-center h-full bg-slate-50 rounded-lg p-8 border border-dashed">
              <p class="text-slate-500 text-center">No candidates to show.<br>Upload one to see it here.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Batch/Course -->
    <section id="batch-section" class="bg-white rounded-2xl shadow-lg p-6 md:p-8 hover:shadow-xl transition-shadow">
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <div class="flex items-center justify-center">
          <lottie-player src="https://assets4.lottiefiles.com/private_files/lf30_5ttqPi.json" background="transparent"
            speed="1" class="w-72 h-72" loop autoplay>
          </lottie-player>
        </div>
        <div>
          <h3 class="text-xl font-semibold mb-1 text-slate-900">Add/Edit Course Batch</h3>
          <p class="text-slate-600 mb-4">Update batch details with a polished animated form.</p>

          <form action="php_backend/api/courses.php" method="POST" enctype="multipart/form-data"
            class="space-y-5 animated-form">
            <div>
              <label class="block font-medium text-gray-800 mb-2">Batch/Course Image</label>
              <div
                class="dropzone group relative border-2 border-dashed border-slate-300 rounded-xl p-5 text-center cursor-pointer transition">
                <input id="batch_image" type="file" name="batch_image" accept="image/*" required
                  class="absolute inset-0 opacity-0 cursor-pointer" />
                <div class="flex flex-col items-center gap-2 pointer-events-none">
                  <span class="rounded-full bg-blue-50 text-blue-600 px-3 py-1 text-xs font-medium">Click or drag &
                    drop</span>
                  <span class="text-slate-500 text-sm">PNG, JPG, JPEG</span>
                  <div class="file-list mt-1 text-xs text-slate-600"></div>
                </div>
              </div>
            </div>

            <div class="relative">
              <input type="text" id="batch_title" name="batch_title" required placeholder=" "
                class="peer w-full border border-blue-200 px-3 py-3 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-transparent" />
              <label for="batch_title" class="absolute left-3 top-2 px-1 bg-white text-slate-500 text-sm transition-all pointer-events-none
                  peer-placeholder-shown:top-3 peer-placeholder-shown:text-slate-500 peer-placeholder-shown:text-sm
                  peer-focus:-top-3 peer-focus:text-xs peer-focus:text-blue-600">Title</label>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div class="relative">
                <input type="text" id="track" name="track" required placeholder="JEE"
                  class="peer w-full border border-blue-200 px-3 py-3 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-transparent" />
                <label for="track" class="absolute left-3 top-2 px-1 bg-white text-slate-500 text-sm transition-all pointer-events-none
                    peer-placeholder-shown:top-3 peer-placeholder-shown:text-slate-500 peer-placeholder-shown:text-sm
                    peer-focus:-top-3 peer-focus:text-xs peer-focus:text-blue-600">Track</label>
              </div>
              <div class="relative">
                <input type="text" id="level" name="level" required placeholder="Class 11"
                  class="peer w-full border border-blue-200 px-3 py-3 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-transparent" />
                <label for="level" class="absolute left-3 top-2 px-1 bg-white text-slate-500 text-sm transition-all pointer-events-none
                    peer-placeholder-shown:top-3 peer-placeholder-shown:text-slate-500 peer-placeholder-shown:text-sm
                    peer-focus:-top-3 peer-focus:text-xs peer-focus:text-blue-600">Level</label>
              </div>
            </div>

            <div class="relative">
              <textarea id="batch_inclusives" name="batch_inclusives" rows="4" placeholder=" "
                class="peer w-full border border-blue-200 px-3 py-3 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-transparent"></textarea>
              <label for="batch_inclusives" class="absolute left-3 top-2 px-1 bg-white text-slate-500 text-sm transition-all pointer-events-none
                  peer-placeholder-shown:top-3 peer-placeholder-shown:text-slate-500 peer-placeholder-shown:text-sm
                  peer-focus:-top-3 peer-focus:text-xs peer-focus:text-blue-600">Course Includes (one per line)</label>
              <p class="text-xs text-slate-500 mt-1">Example: Live Classes, PDF Notes, Mock Tests</p>
            </div>

            <div class="relative">
              <input type="number" id="current_price" name="current_price" min="0" required placeholder=" "
                class="peer w-full border border-blue-200 px-3 py-3 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-transparent" />
              <label for="current_price" class="absolute left-3 top-2 px-1 bg-white text-slate-500 text-sm transition-all pointer-events-none
                  peer-placeholder-shown:top-3 peer-placeholder-shown:text-slate-500 peer-placeholder-shown:text-sm
                  peer-focus:-top-3 peer-focus:text-xs peer-focus:text-blue-600">Current Price (INR)</label>
            </div>

            <button type="submit"
              class="submit-btn inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 active:scale-[0.98] transition">
              <span class="btn-label">Save Batch</span>
              <span class="btn-spinner hidden spinner"></span>
            </button>
          </form>
        </div>
      </div>
    </section>


    <?php
    // This PHP block should be placed before the HTML section below.
    // It assumes a PDO connection object `$pdo` is available from your conf.php file.
    
    // Fetch distinct tracks for the dropdown
    $track_sql = "SELECT DISTINCT track FROM courses ORDER BY track ASC";
    $track_stmt = $pdo->prepare($track_sql);
    $track_stmt->execute();
    $tracks = $track_stmt->fetchAll(PDO::FETCH_COLUMN);

    // Fetch distinct levels for the dropdown
    $level_sql = "SELECT DISTINCT level FROM courses ORDER BY level ASC";
    $level_stmt = $pdo->prepare($level_sql);
    $level_stmt->execute();
    $levels = $level_stmt->fetchAll(PDO::FETCH_COLUMN);
    ?>

    <!-- Demo Video Section -->
    <section id="demo-video-section"
      class="bg-white rounded-2xl shadow-lg p-6 md:p-8 hover:shadow-xl transition-shadow">
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <div class="flex items-center justify-center order-2 md:order-1">
          <div class="w-full">
            <h3 class="text-xl font-semibold mb-1 text-slate-900">Upload Demo Class Video Link</h3>
            <p class="text-slate-600 mb-4">Select a course track and level, then paste a valid YouTube link.</p>

            <form action="php_backend/api/demo_class_vid.php" method="POST" class="space-y-5 animated-form">

              <!-- Track Selection Dropdown -->
              <div>
                <label for="track" class="block text-sm font-medium text-slate-700 mb-1">Track</label>
                <select id="track" name="track" required
                  class="w-full border border-blue-200 px-3 py-3 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                  <option value="" disabled selected>Please select a track</option>
                  <?php
                  foreach ($tracks as $track) {
                    echo '<option value="' . htmlspecialchars($track) . '">' . htmlspecialchars($track) . '</option>';
                  }
                  ?>
                </select>
              </div>

              <!-- Level Selection Dropdown -->
              <div>
                <label for="level" class="block text-sm font-medium text-slate-700 mb-1">Level</label>
                <select id="level" name="level" required
                  class="w-full border border-blue-200 px-3 py-3 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                  <option value="" disabled selected>Please select a level</option>
                  <?php
                  foreach ($levels as $level) {
                    echo '<option value="' . htmlspecialchars($level) . '">' . htmlspecialchars($level) . '</option>';
                  }
                  ?>
                </select>
              </div>

              <!-- Video Link Input -->
              <div class="relative">
                <input type="url" id="video_link" name="video_link" placeholder=" " required
                  class="peer w-full border border-blue-200 px-3 py-3 rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition placeholder-transparent" />
                <label for="video_link" class="absolute left-3 -top-2.5 px-1 bg-white text-slate-500 text-sm transition-all pointer-events-none
                    peer-placeholder-shown:top-3 peer-placeholder-shown:text-base
                    peer-focus:-top-2.5 peer-focus:text-sm peer-focus:text-blue-600">YouTube Video Link</label>
              </div>

              <button type="submit"
                class="submit-btn inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 active:scale-[0.98] transition">
                <span class="btn-label">Update Video Link</span>
                <span class="btn-spinner hidden spinner"></span>
              </button>
            </form>
          </div>
        </div>
        <div class="flex items-center justify-center order-1 md:order-2">
          <lottie-player src="https://assets3.lottiefiles.com/packages/lf20_sSF6EG.json" background="transparent"
            speed="1" class="w-full max-w-sm h-auto" loop autoplay>
          </lottie-player>
        </div>
      </div>
    </section>

    <!-- Resources -->
    <section id="resources-section" class="bg-white rounded-2xl shadow-lg p-6 md:p-8 hover:shadow-xl transition-shadow">
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <!-- Left: List of uploaded PDFs -->
        <div class="flex items-center justify-center">
          <div class="w-full max-w-xs" id="resource-list-container">
            <h4 class="text-lg font-bold text-blue-700 mb-3">Uploaded Resources</h4>
            <ul id="resource-list" class="space-y-2">
              <!-- AJAX updates here -->
            </ul>
          </div>
        </div>
        <!-- Right: Upload Form -->
        <div>
          <h3 class="text-xl font-semibold mb-1 text-slate-900">Upload Resources (PDF)</h3>
          <p class="text-slate-600 mb-4">Share materials as PDF documents. Enter folder name (or leave as existing), a
            title, and upload a PDF.</p>
          <form id="resource-upload-form" action="php_backend/api/resources.php" method="POST"
            enctype="multipart/form-data" class="space-y-4 animated-form">
            <div>
              <label class="block font-medium text-gray-800 mb-2" for="folder_name">Folder Name</label>
              <input id="folder_name" name="folder_name" type="text" required
                class="w-full px-4 py-2 border border-slate-300 rounded-lg" placeholder="e.g. General" />
            </div>
            <div>
              <label class="block font-medium text-gray-800 mb-2" for="title">Title</label>
              <input id="title" name="title" type="text" required
                class="w-full px-4 py-2 border border-slate-300 rounded-lg" placeholder="Resource title" />
            </div>
            <div>
              <label class="block font-medium text-gray-800 mb-2">PDF File</label>
              <div
                class="dropzone group relative border-2 border-dashed border-slate-300 rounded-xl p-5 text-center cursor-pointer transition">
                <input id="resource_pdf" type="file" name="resource_pdf" accept="application/pdf" required
                  class="absolute inset-0 opacity-0 cursor-pointer" />
                <div class="flex flex-col items-center gap-2 pointer-events-none">
                  <span class="rounded-full bg-blue-50 text-blue-600 px-3 py-1 text-xs font-medium">Click or drag &
                    drop</span>
                  <span class="text-slate-500 text-sm">PDF only</span>
                  <div class="file-list mt-1 text-xs text-slate-600"></div>
                </div>
              </div>
            </div>
            <button type="submit"
              class="submit-btn inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 active:scale-[0.98] transition">
              <span class="btn-label">Upload PDF</span>
              <span class="btn-spinner hidden spinner"></span>
            </button>
          </form>
        </div>
      </div>
    </section>
  </main>

  <footer id="contact" class="bg-blue-950 text-white pt-10 pb-6 mt-12">
    <div class="container mx-auto px-4">
      <!-- Main Footer Sections -->
      <div class="flex flex-col lg:flex-row flex-wrap gap-10 justify-between">
        <!-- Address and About -->
        <div class="flex-1 min-w-[220px] mb-8 lg:mb-0">
          <h4 class="text-xl font-semibold mb-3 text-blue-100">Theta Fornix</h4>
          <p class="text-blue-200 mb-3">Empowering learners across India with quality education and mentorship for a
            brighter future.</p>
          <div class="mb-2 flex items-start">
            <svg class="w-5 h-5 mr-2 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
              <path
                d="M10 2C6.13 2 3 5.14 3 9c0 5.25 7 11 7 11s7-5.75 7-11c0-3.86-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 10 6a2.5 2.5 0 0 1 0 5.5z" />
            </svg>
            <span>Theta Fornix Tower, 123 Knowledge Avenue,<br>Bengaluru, Karnataka, 560001</span>
          </div>
          <div class="mb-2 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-300" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path d="M16 2a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8z" />
              <path d="M9 7h6" />
            </svg>
            <span>Phone: <a href="tel:+919999999999" class="hover:underline hover:text-yellow-200">+91 99999
                99999</a></span>
          </div>
          <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-300" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path d="M4 4h16v16H4z" />
              <path d="M22,6L12,13L2,6" />
            </svg>
            <span>Email: <a href="mailto:info@Theta Fornix.in"
                class="hover:underline hover:text-yellow-200">info@Theta Fornix.in</a></span>
          </div>
          <!-- Social Icons -->
          <div class="flex space-x-4 mt-5">
            <a href="#" aria-label="Facebook" class="hover:text-blue-400 transition">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 5 3.657 9.128 8.438 9.876v-6.988H7.898V12h2.54V9.797c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.462h-1.26c-1.242 0-1.631.771-1.631 1.562V12h2.773l-.443 2.888h-2.33v6.988C18.343 21.128 22 17 22 12" />
              </svg>
            </a>
            <a href="#" aria-label="Twitter" class="hover:text-blue-400 transition">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M24 4.556c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.179-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045C7.691 8.095 4.066 6.13 1.64 3.162c-.35.601-.547 1.293-.547 2.037 0 1.404.676 2.648 1.707 3.377-.628-.02-1.219-.192-1.734-.478v.048c0 1.963 1.397 3.601 3.253 3.97-.34.093-.7.143-1.073.143-.261 0-.515-.025-.762-.072.516 1.611 2.016 2.785 3.797 2.817-1.384 1.085-3.13 1.733-5.025 1.733-.327 0-.651-.019-.971-.057 1.797 1.152 3.93 1.825 6.221 1.825 7.548 0 11.675-6.155 11.675-11.49 0-.175-.004-.349-.012-.522A8.18 8.18 0 0 0 24 4.556z" />
              </svg>
            </a>
            <a href="#" aria-label="LinkedIn" class="hover:text-blue-400 transition">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.761 0 5-2.239 5-5v-14c0-2.761-2.239-5-5-5zm-11.25 19h-2.5v-8.5h2.5v8.5zm-1.25-9.769c-.828 0-1.5-.674-1.5-1.5s.672-1.5 1.5-1.5 1.5.674 1.5 1.5-.672 1.5-1.5 1.5zm13.25 9.769h-2.5v-4.251c0-1.004-.018-2.298-1.4-2.298-1.401 0-1.615 1.093-1.615 2.225v4.324h-2.5v-8.5h2.4v1.161h.033c.334-.633 1.151-1.3 2.373-1.3 2.537 0 3.005 1.671 3.005 3.844v4.795z" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="flex-1 min-w-[180px] mb-8 lg:mb-0">
          <h5 class="text-lg font-semibold mb-4 text-blue-100">Quick Links</h5>
          <ul class="space-y-2">
            <li><a href="#about" class="hover:underline hover:text-yellow-200">About Us</a></li>
            <li><a href="#features" class="hover:underline hover:text-yellow-200">Features</a></li>
            <li><a href="#testimonials" class="hover:underline hover:text-yellow-200">Testimonials</a></li>
            <li><a href="#recognition" class="hover:underline hover:text-yellow-200">Recognition</a></li>
            <li><a href="#contact" class="hover:underline hover:text-yellow-200">Contact</a></li>
          </ul>
        </div>

        <!-- Get in Touch Form -->
        <div class="flex-1 min-w-[250px]">
          <h5 class="text-lg font-semibold mb-4 text-blue-100">Get in Touch</h5>
          <form class="space-y-3" autocomplete="off">
            <input type="text" placeholder="Your Name"
              class="w-full px-3 py-2 rounded outline-none bg-blue-900 text-white placeholder-blue-300 focus:ring focus:ring-blue-300" />
            <input type="email" placeholder="Your Email"
              class="w-full px-3 py-2 rounded outline-none bg-blue-900 text-white placeholder-blue-300 focus:ring focus:ring-blue-300" />
            <textarea placeholder="Message" rows="3"
              class="w-full px-3 py-2 rounded outline-none bg-blue-900 text-white placeholder-blue-300 focus:ring focus:ring-blue-300"></textarea>
            <button type="submit"
              class="w-full bg-yellow-500 text-blue-900 py-2 rounded font-bold hover:bg-yellow-400 transition">Send
              Message</button>
          </form>
        </div>
      </div>

      <!-- Divider -->
      <div class="border-t border-blue-900 my-7"></div>
      <!-- Bottom Bar -->
      <div class="flex flex-col md:flex-row justify-between items-center text-sm text-blue-300 gap-2">
        <span>© 2025 Theta Fornix. All Rights Reserved.</span>
        <span>Made with <span class="text-red-400">♥</span> for Indian learners</span>
      </div>
    </div>
  </footer>

  <!-- Animations -->
  <script>

    const ToastNotification = {
  show(message, type = 'success') {
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const toastEl = document.createElement('div');
    toastEl.className = `fixed top-5 right-5 ${bgColor} text-white py-2 px-4 rounded-lg shadow-lg text-sm z-50`;
    toastEl.textContent = message;

    document.body.appendChild(toastEl);

    // Animate in
    gsap.fromTo(toastEl, {
      opacity: 0,
      y: -20,
      x: 20
    }, {
      opacity: 1,
      y: 0,
      x: 0,
      duration: 0.5,
      ease: 'power3.out'
    });

    // Animate out and remove after a delay
    gsap.to(toastEl, {
      opacity: 0,
      duration: 0.5,
      delay: 3,
      onComplete: () => toastEl.remove()
    });
  }
};

/**
 * Manages the UI interactions for a file dropzone.
 */
class Dropzone {
  constructor(element) {
    this.zone = element;
    this.input = this.zone.querySelector("input[type='file']");
    this.fileList = this.zone.querySelector(".file-list");
    this.allowMultiple = this.input?.hasAttribute("multiple");
  }

  init() {
    if (!this.input) return;
    this.zone.addEventListener("click", () => this.input.click());
    this.input.addEventListener("change", () => this._showFiles(this.input.files));

    ["dragenter", "dragover"].forEach(evt =>
      this.zone.addEventListener(evt, e => this._handleDragOver(e))
    );
    ["dragleave", "drop"].forEach(evt =>
      this.zone.addEventListener(evt, e => this._handleDragLeave(e))
    );
    this.zone.addEventListener("drop", e => this._handleDrop(e));
  }

  _showFiles(files) {
    if (!this.fileList) return;
    if (!files || files.length === 0) {
      this.fileList.textContent = "";
      return;
    }
    const names = Array.from(files).map(f => f.name).slice(0, 3);
    const moreCount = files.length - 3;
    this.fileList.textContent = names.join(", ") + (moreCount > 0 ? ` +${moreCount} more` : "");
  }

  _handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    this.zone.classList.add("dragover");
  }

  _handleDragLeave(e) {
    e.preventDefault();
    e.stopPropagation();
    this.zone.classList.remove("dragover");
  }

  _handleDrop(e) {
    const dt = e.dataTransfer;
    if (!dt || !dt.files || !dt.files.length) return;

    if (!this.allowMultiple && dt.files.length > 1) {
      const firstFile = new DataTransfer();
      firstFile.items.add(dt.files[0]);
      this.input.files = firstFile.files;
    } else {
      this.input.files = dt.files;
    }
    this._showFiles(this.input.files);
  }

  reset() {
    if (this.fileList) {
      this.fileList.textContent = "";
    }
  }
}

/**
 * Handles AJAX form submission, button animations, and toast notifications.
 */
class FormHandler {
  constructor(formElement) {
    this.form = formElement;
    this.btn = this.form.querySelector(".submit-btn");
    this.label = this.btn?.querySelector(".btn-label");
    this.spinner = this.btn?.querySelector(".btn-spinner");
    this.dropzone = this.form.querySelector('.dropzone') ? new Dropzone(this.form.querySelector('.dropzone')) : null;
  }

  init() {
    if (!this.form) return;
    this.form.addEventListener("submit", e => this._handleSubmit(e));
  }

  async _handleSubmit(e) {
    e.preventDefault();
    if (!this.btn) return;

    this._setLoading(true);

    try {
      const formData = new FormData(this.form);
      const response = await fetch(this.form.action, {
        method: this.form.method,
        body: formData,
        headers: { 'Accept': 'application/json' }
      });

      if (!response.ok) {
        // The server returned an error. Let's find out what it was.
        let errorText;
        try {
          // First, try to parse the response as JSON, as our backend should return JSON errors.
          const errorResult = await response.json();
          errorText = errorResult.message || JSON.stringify(errorResult);
        } catch (jsonError) {
          // If parsing as JSON fails, the response is likely HTML (e.g., a PHP error page) or plain text.
          // We'll show a more generic message to the user but the details are in the console.
          throw new Error(`Server returned status ${response.status}. Check console for full response.`);
        }
        throw new Error(errorText);
      }

      // If we get here, the response was successful (status 2xx)
      const result = await response.json();
      ToastNotification.show(result.message || 'Success!', 'success');
      this.form.reset();
      this.dropzone?.reset();

    } catch (error) {
      // This will catch network errors from fetch() itself, or any errors we've thrown above.
      console.error('Form submission error:', error);
      ToastNotification.show(error.message || 'An unexpected error occurred.', 'error');
    } finally {
      this._setLoading(false);
    }
  }

  _setLoading(isLoading) {
    if (!this.btn) return;
    if (isLoading) {
      this.btn.classList.add("pointer-events-none", "opacity-90");
      this.label?.classList.add("opacity-0");
      this.spinner?.classList.remove("hidden");
    } else {
      this.btn.classList.remove("pointer-events-none", "opacity-90");
      this.label?.classList.remove("opacity-0");
      this.spinner?.classList.add("hidden");
    }
  }
}

/**
 * Manages page-level animations like section reveals.
 */
class PageAnimator {
  init() {
    document.querySelectorAll("main > section").forEach((section, idx) => {
      gsap.from(section, {
        opacity: 0,
        y: 40,
        duration: 0.9,
        delay: idx * 0.1 + 0.15,
        ease: "power3.out",
        scrollTrigger: {
          trigger: section,
          start: "top 85%",
        },
      });
    });
  }
}

// Initialize all components on DOM content loaded
document.addEventListener('DOMContentLoaded', () => {
  new PageAnimator().init();

  document.querySelectorAll(".dropzone").forEach(zone => {
    // The Dropzone class is now instantiated and managed by the FormHandler
    // to allow for resetting the file list display on successful form submission.
    // We only initialize it here if it's NOT inside an animated form.
    if (!zone.closest('form.animated-form')) {
      new Dropzone(zone).init();
    }
  });

  document.querySelectorAll("form.animated-form").forEach(form => {
    const handler = new FormHandler(form);
    handler.init();
    // Also initialize the dropzone within the form context
    handler.dropzone?.init();
  });
});
    function fetchResources() {
      fetch('php_backend/api/resources.php')
        .then(res => res.json())
        .then(data => {
          const ul = document.getElementById('resource-list');
          ul.innerHTML = '';
          if (data && data.success && data.resources && data.resources.length) {
            data.resources.forEach(res => {
              ul.innerHTML += `<li class="flex items-center gap-2">
            <span class="font-medium">${res.title || 'Untitled PDF'}</span>
            <a href="/public/${res.pdf_path}" target="_blank" class="px-2 py-1 bg-blue-100 text-blue-700 rounded">View</a>
            <span class="text-xs text-gray-400">${new Date(res.created_at).toLocaleString()}</span>
          </li>`;
            });
          } else {
            ul.innerHTML = '<li class="text-slate-500">No resources uploaded yet.</li>';
          }
        });
    }

    // Initial load
    document.addEventListener('DOMContentLoaded', function () {
      fetchResources();
      // Real-time update after resource form submit
      document.getElementById('resource-upload-form').addEventListener('submit', function (e) {
        // Allow your existing animated-form handler to do its work, then:
        setTimeout(fetchResources, 700); // Wait for upload and refresh list
      });
    });
    

    
  </script>
  <script src="./main.js" defer></script>
</body>

</html>