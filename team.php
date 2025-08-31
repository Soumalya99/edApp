<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EduConnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.6.2/dist/dotlottie-wc.js" type="module"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <link rel="stylesheet" href="./style.css">
</head>

<body class="bg-white text-gray-800 overflow-x-hidden">


    <!-- Navbar -->
    <header id="site-header" class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Brand -->
                <a href="index.php" class="text-2xl font-extrabold tracking-tight text-blue-600">EduTech</a>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-8">
                    <!-- Home (mega menu trigger) -->
                    <div class="relative group">
                        <button id="home-trigger"
                            class="text-gray-700 hover:text-blue-600 transition-colors font-medium" data-anim="nav-link"
                            aria-haspopup="true" aria-expanded="false" aria-controls="mega-home">
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
                                            <a href="batch.php"
                                                class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:text-blue-700 hover:bg-blue-50">
                                                All Courses
                                            </a>
                                        </li>
                                    </ul>
                                </aside>

                                <!-- Right: Cards Panel -->
                                <section class="col-span-8">
                                    <!-- NEET-->
                                    <div class="mm-panel" id="panel-neet">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <a href="batch.php?track=neet&amp;level=class11"
                                                class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                                                <div class="text-blue-600 font-semibold mb-1">NEET</div>
                                                <div class="text-gray-900 font-bold">Class 11</div>
                                                <div class="text-gray-500 text-sm">Foundation &amp; advanced</div>
                                            </a>
                                            <a href="batch.php?track=neet&amp;level=class12"
                                                class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                                                <div class="text-blue-600 font-semibold mb-1">NEET</div>
                                                <div class="text-gray-900 font-bold">Class 12</div>
                                                <div class="text-gray-500 text-sm">Problem solving</div>
                                            </a>
                                            <a href="batch.php?track=neet&amp;level=repeater"
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
                                            <a href="batch.php?track=jee&amp;level=class11"
                                                class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                                                <div class="text-blue-600 font-semibold mb-1">JEE</div>
                                                <div class="text-gray-900 font-bold">Class 11</div>
                                                <div class="text-gray-500 text-sm">Mathematics track</div>
                                            </a>
                                            <a href="batch.php?track=jee&amp;level=class12"
                                                class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                                                <div class="text-blue-600 font-semibold mb-1">JEE</div>
                                                <div class="text-gray-900 font-bold">Class 12</div>
                                                <div class="text-gray-500 text-sm">Advanced prep</div>
                                            </a>
                                            <a href="batch.php?track=jee&amp;level=repeater"
                                                class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                                                <div class="text-blue-600 font-semibold mb-1">JEE</div>
                                                <div class="text-gray-900 font-bold">Repeater</div>
                                                <div class="text-gray-500 text-sm">Intensive course</div>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Nursing -->
                                    <div class="mm-panel hidden" id="panel-nursing">
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                            <a href="batch.php?track=nursing&amp;level=class11"
                                                class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                                                <div class="text-blue-600 font-semibold mb-1">BSC Nursing</div>
                                                <div class="text-gray-900 font-bold">Class 11</div>
                                                <div class="text-gray-500 text-sm">Early orientation</div>
                                            </a>
                                            <a href="batch.php?track=nursing&amp;level=class12"
                                                class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                                                <div class="text-blue-600 font-semibold mb-1">BSC Nursing</div>
                                                <div class="text-gray-900 font-bold">Class 12</div>
                                                <div class="text-gray-500 text-sm">Research skills</div>
                                            </a>
                                            <a href="batch.php?track=nursing&amp;level=repeater"
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
                                            <a href="batch.php?track=anmgnm&amp;level=class11"
                                                class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                                                <div class="text-blue-600 font-semibold mb-1">ANM &amp; GNM</div>
                                                <div class="text-gray-900 font-bold">Class 11</div>
                                                <div class="text-gray-500 text-sm">Early orientation</div>
                                            </a>
                                            <a href="batch.php?track=anmgnm&amp;level=class12"
                                                class="mm-card group rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition">
                                                <div class="text-blue-600 font-semibold mb-1">ANM &amp; GNM</div>
                                                <div class="text-gray-900 font-bold">Class 12</div>
                                                <div class="text-gray-500 text-sm">Research skills</div>
                                            </a>
                                            <a href="batch.php?track=anmgnm&amp;level=repeater"
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

                    <!-- <a href="batch.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                        data-anim="nav-link">Courses</a> -->
                    <a href="selection.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                        data-anim="nav-link">Selections</a>
                    <a href="team.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                        data-anim="nav-link">Our Team</a>
                    <a href="resources.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                        data-anim="nav-link">Resources</a>
                    <a href="contact.php" class="text-gray-700 hover:text-blue-600 transition-colors font-medium"
                        data-anim="nav-link">Contact</a>
                </nav>

                <!-- Hamburger -->
                <button id="burger"
                    class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50"
                    aria-label="Open menu" aria-controls="mobile-menu" aria-expanded="false">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
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
                    <button
                        class="w-full flex items-center justify-between py-2 text-left text-sm font-medium text-gray-700"
                        data-mobile-toggle="home-neet">
                        <span>NEET</span><span class="text-gray-300">+</span>
                    </button>
                    <div class="ml-3 hidden space-y-1" data-mobile-panel="home-neet">
                        <a href="/batch.php?track=neet&amp;level=class11" class="block py-1 text-sm text-gray-600">Class
                            11</a>
                        <a href="/batch.php?track=neet&amp;level=class12" class="block py-1 text-sm text-gray-600">Class
                            12</a>
                        <a href="/batch.php?track=neet&amp;level=repeater"
                            class="block py-1 text-sm text-gray-600">Repeater</a>
                    </div>

                    <button
                        class="w-full flex items-center justify-between py-2 text-left text-sm font-medium text-gray-700"
                        data-mobile-toggle="home-jee">
                        <span>JEE</span><span class="text-gray-300">+</span>
                    </button>
                    <div class="ml-3 hidden space-y-1" data-mobile-panel="home-jee">
                        <a href="/batch.php?track=jee&amp;level=class11" class="block py-1 text-sm text-gray-600">Class
                            11</a>
                        <a href="/batch.php?track=jee&amp;level=class12" class="block py-1 text-sm text-gray-600">Class
                            12</a>
                        <a href="/batch.php?track=jee&amp;level=repeater"
                            class="block py-1 text-sm text-gray-600">Repeater</a>
                    </div>

                    <button
                        class="w-full flex items-center justify-between py-2 text-left text-sm font-medium text-gray-700"
                        data-mobile-toggle="home-nursing">
                        <span>BSC Nursing</span><span class="text-gray-300">+</span>
                    </button>
                    <div class="ml-3 hidden space-y-1" data-mobile-panel="home-nursing">
                        <a href="/batch.php?track=nursing&amp;level=class11"
                            class="block py-1 text-sm text-gray-600">Class
                            11</a>
                        <a href="/batch.php?track=nursing&amp;level=class12"
                            class="block py-1 text-sm text-gray-600">Class
                            12</a>
                        <a href="/batch.php?track=nursing&amp;level=repeater"
                            class="block py-1 text-sm text-gray-600">Crash Course</a>
                    </div>

                    <button
                        class="w-full flex items-center justify-between py-2 text-left text-sm font-medium text-gray-700"
                        data-mobile-toggle="home-anmgnm">
                        <span>ANM &amp; GNM</span><span class="text-gray-300">+</span>
                    </button>
                    <div class="ml-3 hidden space-y-1" data-mobile-panel="home-anmgnm">
                        <a href="/batch.php?track=anmgnm&amp;level=class11"
                            class="block py-1 text-sm text-gray-600">Class
                            11</a>
                        <a href="/batch.php?track=anmgnm&amp;level=class12"
                            class="block py-1 text-sm text-gray-600">Class
                            12</a>
                        <a href="/batch.php?track=anmgnm&amp;level=repeater"
                            class="block py-1 text-sm text-gray-600">Crash Course</a>
                    </div>

                    <a href="/batch.php" class="block py-2 text-sm font-medium text-blue-700">All Courses</a>
                </div>

                <!-- <a href="/batch.html" class="block py-2 font-medium text-gray-800">Courses</a> -->
                <a href="/selection.php" class="block py-2 font-medium text-gray-800">Selections</a>
                <a href="/team.php" class="block py-2 font-medium text-gray-800">Our Team</a>
                <a href="/resources.php" class="block py-2 font-medium text-gray-800">Resources</a>
                <a href="/contact.php" class="block py-2 font-medium text-gray-800">Contact</a>
            </div>
        </div>
    </header>


    <section class="py-16 max-w-8xl mx-auto">
        <div
            class="w-full bg-gradient-to-r from-yellow-400 via-pink-400 to-indigo-400 text-white text-center py-2 text-md px-2 font-medium shadow">
            🚀 NEW: Admissions open for 2025! <a href="https://wa.me/919999999999"
                class="underline font-semibold hover:text-white ml-1">Enquire on WhatsApp</a>
        </div>
        <h2 class="text-4xl font-semibold text-purple-600 text-center mt-8 mb-6">Meet ur Mentors</h2>
        <h3 class="team-mentors-h3 text-2xl font-medium text-center mb-6">
            Inspiring Excellence in Physics, Math, Chemistry &amp; Biology
        </h3>
        <div id="mentors-grid" class="mentors-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-8 px-6">
            <!-- Dynamically injected -->
        </div>
    </section>


    <section class="max-w-5xl mx-auto py-12 px-4 mb-8">
        <h2 class="text-4xl font-semibold mb-6 text-purple-700 text-center">About Our Team &amp; Founder</h2>
        <div class="team-intro-flex flex flex-col sm:flex-row items-center justify-center gap-8 relative">
            <!-- Lottie Animation Container -->
            <div id="lottie-team" class="w-64 h-64 sm:w-96 sm:h-96 shrink-0 flex items-center justify-center">
                <dotlottie-wc src="https://lottie.host/95afefff-94d2-4ef5-8e49-430decdbd2d1/K4fTX35Fw3.lottie"
                    style="width: 100%; height: 100%;" speed="1" autoplay loop>
                </dotlottie-wc>
            </div>
            <!-- Typing Animated Paragraph -->
            <p id="team-typewriter"
                class="text-xl sm:text-xl md:text-2xl text-gray-700 text-center leading-relaxed font-medium min-h-[90px] font-mono mt-6"
                style="white-space: pre-line;">
                <!-- The paragraph will be animated in by GSAP script -->
            </p>
        </div>
    </section>

    <section>
        <div class="team-member flex flex-col items-center">
            <h2 class="text-4xl font-semibold text-purple-700 mt-8 mb-8">Our Founder Circle</h2>
            <div id="founders-grid" class="founders-grid flex flex-col lg:flex-row justify-center items-center flex-wrap gap-12 mt-8 px-6">
                <!-- Dynamically injected -->
            </div>
        </div>
    </section>




    <footer id="contact" class="bg-blue-950 text-white pt-10 pb-6 mt-12">
        <div class="container mx-auto px-4">
            <!-- Main Footer Sections -->
            <div class="flex flex-col lg:flex-row flex-wrap gap-10 justify-between">
                <!-- Address and About -->
                <div class="flex-1 min-w-[220px] mb-8 lg:mb-0">
                    <h4 class="text-xl font-semibold mb-3 text-blue-100">EduConnect</h4>
                    <p class="text-blue-200 mb-3">Empowering learners across India with quality education and mentorship
                        for
                        a brighter future.</p>
                    <div class="mb-2 flex items-start">
                        <svg class="w-5 h-5 mr-2 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 2C6.13 2 3 5.14 3 9c0 5.25 7 11 7 11s7-5.75 7-11c0-3.86-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 10 6a2.5 2.5 0 0 1 0 5.5z" />
                        </svg>
                        <span>EduConnect Tower, 123 Knowledge Avenue,<br>Bengaluru, Karnataka, 560001</span>
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
                        <span>Email: <a href="mailto:info@educonnect.in"
                                class="hover:underline hover:text-yellow-200">info@educonnect.in</a></span>
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
                <span>© 2025 EduConnect. All Rights Reserved.</span>
                <span>Made with <span class="text-red-400">♥</span> for Indian learners</span>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const grid = document.getElementById('mentors-grid');
            if (!grid) return;
            // Clear existing placeholder mentor cards
            grid.innerHTML = '';

            fetch('php_backend/api/teachers.php')
                .then(r => r.json())
                .then(resp => {
                    const teachers = Array.isArray(resp) ? resp : (resp.teachers || []);
                    if (!teachers.length) {
                        grid.innerHTML = '<p class="text-gray-600 col-span-full text-center">No mentors found.</p>';
                        return;
                    }
                    const baseSeg = window.location.pathname.split('/')[1] || '';
                    const basePrefix = baseSeg ? `/${baseSeg}/` : '/';

                    const html = teachers.map(t => {
                        const name = t.name || 'Mentor';
                        const bio = t.bio || '';
                        const parts = bio.split(',').map(s => s && s.trim()).filter(Boolean);
                        const subject = parts[0] || 'Mentor';
                        const university = parts[1] || '';
                        const imgPath = t.profile_image || t.image || '';
                        const imgSrc = /^https?:/i.test(imgPath) ? imgPath : (basePrefix + String(imgPath || '').replace(/^\\+/, ''));
                        return `
                <div class="mentor-card group relative flex flex-col items-center rounded-xl border border-purple-300 shadow-lg bg-gradient-to-tr from-blue-100 via-gray-100 to-amber-100 p-6 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                  <span class="pointer-events-none absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition duration-300 bg-[radial-gradient(ellipse_at_top_left,rgba(255,255,255,0.5),transparent_60%)]"></span>
                  <img src="${imgSrc}" alt="${name}"
                       class="w-40 h-40 sm:w-44 sm:h-44 rounded-full border-4 border-purple-300 shadow object-cover mb-4 transform transition-transform duration-300 group-hover:scale-105"
                       onerror="this.src='https://via.placeholder.com/176?text=Mentor'">
                  <h3 class="text-xl font-bold text-purple-800">${name}</h3>
                  <p class="text-gray-600 text-center mt-1">
                    ${subject}
                    ${university ? `<br/><span class="text-gray-500">${university}</span>` : ''}
                  </p>
                </div>`;
                    }).join('');

                    grid.innerHTML = html;
                })
                .catch(() => {
                    grid.innerHTML = '<p class="text-gray-600 col-span-full text-center">Failed to load mentors.</p>';
                });
        });


        document.addEventListener('DOMContentLoaded', function () {
            const grid = document.getElementById('founders-grid');
            if (!grid) return;
            // Clear existing placeholder founder cards
            grid.innerHTML = '';

            fetch('php_backend/api/founders.php')
                .then(r => r.json())
                .then(resp => {
                    const founders = Array.isArray(resp) ? resp : (resp.founders || []);
                    if (!founders.length) {
                        grid.innerHTML = '<p class="text-gray-600 col-span-full text-center">No founders found.</p>';
                        return;
                    }
                    const baseSeg = window.location.pathname.split('/')[1] || '';
                    const basePrefix = baseSeg ? `/${baseSeg}/` : '/';

                    const html = founders.map(f => {
                        const name = f.name || 'Founder';
                        const imgPath = f.profile_image || f.image || f.image_path || '';
                        const imgSrc = /^https?:/i.test(imgPath) ? imgPath : (imgPath ? (basePrefix + String(imgPath).replace(/^\\+/, '')) : 'https://via.placeholder.com/400x300?text=Founder');
                        return `
                    <div class="founder-card founder-shiny group flex flex-col items-center justify-center w-full max-w-md shadow-2xl bg-gradient-to-br from-[#201c2b] via-[#25213a] to-[#812fe8] p-0 pt-0 pb-6 rounded-2xl border-0 transition-transform duration-300 hover:scale-[1.08] hover:shadow-2xl" style="overflow: hidden;">
                        <div class="w-full bg-black relative flex justify-center items-center">
                            <img src="${imgSrc}" alt="${name}" class="w-full h-auto object-cover" style="aspect-ratio: 4/3; background: #181c2a; min-height:235px; max-height:340px;" loading="lazy" onerror="this.onerror=null;this.src='https://via.placeholder.com/400x300?text=Founder'">
                            <span class="founder-shine pointer-events-none absolute inset-0"></span>
                        </div>
                        <h3 class="text-xl md:text-2xl font-bold text-center mt-5 text-purple-100 tracking-tight drop-shadow founder-name">${name}</h3>
                    </div>`;
                    }).join('');

                    grid.innerHTML = html;
                })
                .catch(() => {
                    grid.innerHTML = '<p class="text-gray-600 col-span-full text-center">Failed to load founders.</p>';
                });
        });

    </script>
    <script>
        // Mentor Card Renderer (Unchanged)
        function renderMentorCard(item) {
            const name = item.name || 'Mentor';
            const bio = item.bio || '';
            const parts = bio.split(',').map(s => s && s.trim()).filter(Boolean);
            const subject = parts[0] || name;
            const university = parts[1] || '';
            const imgPath = item.profile_image || item.image || item.image_path || '';
            const baseSeg = window.location.pathname.split('/')[1] || '';
            const basePrefix = baseSeg ? `/${baseSeg}/` : '/';
            const imgSrc = /^https?:/i.test(imgPath) ? imgPath : (imgPath ? (basePrefix + String(imgPath).replace(/^\\+/, '')) : 'https://via.placeholder.com/176?text=Mentor');
            return `
          <div class="mentor-card group relative flex flex-col items-center rounded-xl border border-purple-300 shadow-lg bg-gradient-to-tr from-blue-100 via-gray-100 to-amber-100 p-6 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
            <span class="pointer-events-none absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition duration-300 bg-[radial-gradient(ellipse_at_top_left,rgba(255,255,255,0.5),transparent_60%)]"></span>
            <img src="${imgSrc}" alt="${name}" class="w-40 h-40 sm:w-44 sm:h-44 rounded-full border-4 border-purple-300 shadow object-cover mb-4 transform transition-transform duration-300 group-hover:scale-105" onerror="this.src='https://via.placeholder.com/176?text=Mentor'">
            <h3 class="text-xl font-bold text-purple-800">${name}</h3>
            <p class="text-gray-600 text-center mt-1">
              ${subject}
              ${university ? `<br/><span class='text-gray-500'>${university}</span>` : ''}
            </p>
          </div>`;
        }

        // Founder Card Renderer - new: only image and name, full image, shiny effect
        function renderFounderCard(item) {
            const name = item.name || 'Founder';
            const imgPath = item.profile_image || item.image || item.image_path || '';
            const baseSeg = window.location.pathname.split('/')[1] || '';
            const basePrefix = baseSeg ? `/${baseSeg}/` : '/';
            const imgSrc = /^https?:/i.test(imgPath) ? imgPath : (imgPath ? (basePrefix + String(imgPath).replace(/^\\+/, '')) : 'https://via.placeholder.com/400x300?text=Founder');
            return `
          <div class="founder-card founder-shiny group flex flex-col items-center justify-center w-full max-w-xs shadow-xl bg-gradient-to-br from-[#201c2b] via-[#25213a] to-[#812fe8] p-0 pt-0 pb-4 rounded-xl border-0 transition-transform duration-300 hover:scale-[1.08] hover:shadow-2xl" style="overflow: hidden;">
            <div class="w-full bg-black relative flex justify-center items-center"><img src="${imgSrc}" alt="${name}" class="w-full h-auto object-cover" style="aspect-ratio: 4/3; background: #181c2a; min-height:180px;" loading="lazy" onerror="this.src='https://via.placeholder.com/400x300?text=Founder'">
              <span class="founder-shine pointer-events-none absolute inset-0"></span>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-center mt-3 text-purple-200 tracking-tight drop-shadow founder-name">${name}</h3>
          </div>`;
        }

        function loadGrid(gridId, apiEndpoint, renderCardFn, arrKey) {
            const grid = document.getElementById(gridId);
            if (!grid) return;
            grid.innerHTML = '';
            fetch(apiEndpoint)
                .then(r => r.json())
                .then(resp => {
                    const list = Array.isArray(resp) ? resp : (resp[arrKey] || []);
                    if (!list.length) {
                        grid.innerHTML = `<p class='text-gray-600 col-span-full text-center'>No ${arrKey} found.</p>`;
                        return;
                    }
                    grid.innerHTML = list.map(renderCardFn).join('');
                })
                .catch(() => {
                    grid.innerHTML = `<p class='text-gray-600 col-span-full text-center'>Failed to load ${arrKey}.</p>`;
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadGrid('mentors-grid', 'php_backend/api/teachers.php', renderMentorCard, 'teachers');
            loadGrid('founders-grid', 'php_backend/api/founders.php', renderFounderCard, 'founders');
        });
    </script>
    <style>
        .founder-shiny {
            background: linear-gradient(135deg, #181e2d 0%, #322054 60%, #a259f7 100%);
            box-shadow: 0 6px 32px rgba(50, 24, 94, 0.31), 0 2px 12px 0 rgba(20, 10, 46, 0.07);
            position: relative;
        }

        .founder-shine::before {
            content: "";
            position: absolute;
            left: -60%;
            top: -110%;
            width: 180%;
            height: 180%;
            background: linear-gradient(120deg, rgba(194, 143, 255, 0.10) 10%, rgba(67, 38, 146, 0.10) 60%, rgba(124, 58, 237, 0.18) 100%);
            pointer-events: none;
            z-index: 2;
            opacity: 0.65;
            filter: blur(3.5px) saturate(1.15);
        }

        .founder-card:hover .founder-shine::before {
            opacity: 0.92;
            filter: blur(2.3px) saturate(1.38);
            animation: founder-shine-anim 1.05s linear;
        }

        @keyframes founder-shine-anim {
            0% {
                top: -110%;
                left: -60%;
            }

            100% {
                top: 85%;
                left: 70%;
            }
        }

        .founder-name {
            letter-spacing: 0.01em;
            font-family: 'Zilla Slab', serif;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module" src="./main.js"></script>
</body>

</html>