<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Theta Fornix</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.6.2/dist/dotlottie-wc.js" type="module"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <link rel="stylesheet" href="./style.css">
</head>

<body class="bg-white text-gray-800">

    <!-- Navbar -->
    <header id="site-header" class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Brand -->
                <a href="index.php" class="text-2xl font-extrabold tracking-tight text-blue-600">Theta Fornix</a>

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

    <main class="py-8">
        <section class="py-10 overflow-x-hidden">
            <div
                class="w-full bg-gradient-to-r from-yellow-400 via-pink-400 to-indigo-400 text-white text-center py-2 text-md px-2 font-medium shadow">
                🚀 NEW: Admissions open for 2025! <a href="https://wa.me/919999999999"
                    class="underline font-semibold hover:text-white ml-1">Enquire on WhatsApp</a>
            </div>
            <h2 class="text-4xl font-semibold text-emerald-600 text-center mt-6">Study Materials</h2>
            <p class="text-xl font-medium text-center mt-5 mb-10 italic sm:mb-14">Handpicked by our educators—precise,
                comprehensive, and up-to-date for your success.</p>
            <div class="flex flex-col md:flex-row justify-center mt-8 gap-3 max-w-7xl mx-auto">
                <dotlottie-wc src="https://lottie.host/9d8ef89b-a689-4d13-a3b8-289cf7b1de9e/KE7x9e9bs7.lottie"
                    style="width: 420px; height: 420px" speed="1" autoplay loop></dotlottie-wc>

                <div class="max-w-3xl mx-auto">
                    <div
                        class="faq-card p-5 mb-6 sm:mb-8 rounded-xl shadow-xl border-2 border-orange-200/60 hover:border-orange-400 transition-all duration-300 bg-gradient-to-r from-white via-orange-50 to-amber-50">
                        <h3 class="font-semibold text-xl mb-3">How do mock tests and PYQs boost my performance?</h3>
                        <p>
                            🏆 Our platform features <span class="font-semibold text-orange-700">curated mock tests and
                                Previous Year
                                Question Papers</span> designed to mirror the real exam experience. Each test you take
                            is <span class="font-semibold text-blue-700">thoroughly evaluated</span> by a panel of
                            expert teachers from the
                            respective subject domains.
                            <br><br>
                            Based on your performance, you’ll receive detailed feedback and targeted guidance, helping
                            you turn weaknesses
                            into strengths. This rigorous, <span class="font-semibold text-emerald-700">result-based
                                orientation</span>
                            ensures every assessment becomes a milestone for self-improvement — guiding you step-by-step
                            toward exam
                            excellence.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2">
                        <!-- Collapsible PDF Resource Box -->
                        <div id="resources-container"></div>

                    </div>
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
                    <p class="text-blue-200 mb-3">Empowering learners across India with quality education and mentorship
                        for
                        a brighter future.</p>
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

    <script>

    fetch('php_backend/api/resources.php')
   .then(res => res.json())
   .then(data => {
        const container = document.getElementById('resources-container');
        if (!container) return;
        container.innerHTML = '';
        (data.folders || []).forEach(folder => {
        const folderName = folder.folder_name;
        const resources = folder.resources || [];
        const section = document.createElement('div');
        section.className = "mt-8 max-w-xl mx-auto";
        section.innerHTML = `
          <button class="resource-toggle w-full flex items-center justify-between bg-purple-100 px-6 py-4 rounded-lg shadow transition hover:bg-purple-200 font-semibold text-purple-900 text-lg">
            📚 ${folderName.replace(/_/g, ' ')}
            <svg class="h-6 w-6 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div class="resource-list overflow-hidden max-h-0 transition-all duration-300 ease-in-out bg-white border border-purple-200 rounded-b-lg shadow-inner">
            <ul class="divide-y divide-purple-100">
              ${resources.length
                ? resources.map(res =>
                  `<li class="p-4 flex justify-between items-center">
                    <span class="text-lg font-semibold">${res.title}</span>
                    <a href="/${res.pdf_path}" download class="text-emerald-600 hover:underline font-medium">Download</a>
                  </li>`
                ).join('')
                : `<li class="p-4 text-gray-500">No resources available.</li>`
              }
            </ul>
          </div>
        `;
        container.appendChild(section);
      });

      // Toggle logic for show/hide
      container.querySelectorAll('.resource-toggle').forEach(toggle => {
        const list = toggle.nextElementSibling;
        toggle.addEventListener('click', () => {
          if (list.classList.contains('max-h-0')) {
            list.classList.remove('max-h-0');
            list.classList.add('max-h-[1000px]');
          } else {
            list.classList.remove('max-h-[1000px]');
            list.classList.add('max-h-0');
          }
        });
      });
   });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module" src="./main.js"></script>
</body>

</html>