<?php 
session_start();

// Language selection logic
$available_languages = ['en', 'fr', 'es'];
$default_language = 'en';

if (isset($_GET['lang']) && in_array($_GET['lang'], $available_languages)) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
    setcookie('lang', $lang, time() + (3600 * 24 * 30), '/');
} elseif (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $available_languages)) {
    $lang = $_SESSION['lang'];
} elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $available_languages)) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = $default_language;
}

// language path
$lang_file = "./lang/lang.{$lang}.php";

if (!file_exists($lang_file)) {
    $lang = $default_language;
    $lang_file = "./lang/lang.{$lang}.php";
}


include_once $lang_file;

// error hangling if the file is missing in the directery
if (!isset($lang) || !is_array($lang)) {
    die('Language file is missing or incorrect.');
}

include './php_scripts/connection.php'; 

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// user data
$user = $_SESSION['user'];



?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ceo Dashboard</title>
        <link rel="shortcut icon" href="./images/icon.png" type="image/x-icon">
        <link rel="stylesheet" href="./styles/home.css">

        <!-- tailwind css link -->
        <!-- <link href="./output.css" rel="stylesheet"> -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="./styles/footer.css">

        <!-- toastify -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <!-- font - kanit -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <body class="bg-white">
        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= $labels ?>,
                    datasets: [{
                        label: 'Revenue',
                        data: <?= $dataPoints ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <header class="bg-white shadow-md">
            <nav class="container mx-auto py-3">
                <div class="flex flex-wrap items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <img src="./images/logo.png" alt="donor_king" class="w-16 h-auto">
                    </div>

                    <!-- Mobile menu button -->
                    <div class="lg:hidden">
                        <button id="mobile-menu-button"
                            class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                            <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation Links -->
                    <div id="menu" class="hidden w-full lg:flex lg:items-center lg:w-auto mt-4 lg:mt-0">
                        <div class="text-sm lg:flex-grow">
                            <a href="#"
                                class="active block mt-4 lg:inline-block lg:mt-0 text-lg font-normal text-red-400 hover:text-red-500 mr-4">
                                <?php echo $lang['MENU_HOME'] ?>
                            </a>
                            <a href="#" onclick='FutureFeature()'
                                class="block mt-4 lg:inline-block lg:mt-0 text-lg font-normal text-black hover:text-red-500 mr-4">
                                <?php echo $lang['messages'] ?>
                            </a>
                            <a href="#" onclick="FutureFeature()"
                                class="block mt-4 lg:inline-block lg:mt-0 text-lg font-normal text-black hover:text-red-500">
                                <?php echo $lang['notes'] ?>
                            </a>
                        </div>
                    </div>

                    <!-- Language Select and Buttons -->
                    <div
                        class="w-full lg:w-auto mt-4 lg:mt-0 flex flex-wrap items-center justify-between lg:justify-end">
                        <div class="w-full lg:w-auto mb-4 lg:mb-0 lg:mr-4">
                            <label for="language-select" class="block text-sm font-medium text-gray-700 mb-1">Select
                                Language:</label>
                            <select name="language-select" class="select_lang w-full lg:w-auto border-2 p-2 rounded"
                                id="language-select">
                                <option value="op" class="language_opt">select</option>
                                <option value="en" class="language_opt" <?php echo $lang=='en' ? 'selected' : '' ; ?>
                                    >English</option>
                                <option value="fr" class="language_opt" <?php echo $lang=='fr' ? 'selected' : '' ; ?>
                                    >Français (French)</option>
                                <option value="es" class="language_opt" <?php echo $lang=='es' ? 'selected' : '' ; ?>
                                    >Español (Spanish)</option>
                            </select>
                        </div>
                        <div class="w-full lg:w-auto flex justify-between lg:justify-end">
                            <form action="./php_scripts/log_out.php" method="post">
                                <button
                                    class="w-1/2 lg:w-auto bg-red-400 text-white px-3 py-2 rounded-xl shadow-sm shadow-red-700 hover:shadow transition-shadow delay-75 hover:bg-red-500 mr-2">
                                    <?php echo $lang['logout'] ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <section class="py-12 mt-2">
            <div class="container mx-auto px-4">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-red-500 mb-2">
                        <?php echo $lang['welcome_staff']; ?>,
                        <?php echo ($user['name']); ?>!
                    </h1>
                    <p class="text-xl text-gray-400">
                        You are logged in as a CEO!!
                    </p>

                    <button type="button"
                        class="btn btn-primary mt-4 open-modal w-full sm:w-auto bg-red-400 text-white px-3 py-2 rounded-xl shadow-sm shadow-red-700 hover:shadow transition-shadow delay-75 hover:bg-red-500"
                        data-modal="modal3">
                        <?php echo $lang['need_help'] ?? 'Need Help?'; ?>
                    </button>
                    <!-- helping popup -->
                    <div class="modal-overlay hidden" data-modal="modal3">
                        <div class="modal-container w-100 mx-20 ">
                            <div class="flex justify-between items-center pb-4 border-b">
                                <h4 class="text-sm font-medium">
                                    <?php echo $lang['need_help'] ?? 'Need Help?'; ?>
                                </h4>
                                <button class="close-modal" data-modal="modal3">
                                    <svg width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M7.75732 7.75739L16.2426 16.2427M16.2426 7.75739L7.75732 16.2427"
                                            stroke="black" stroke-width="1.6" stroke-linecap="round"></path>
                                    </svg>
                                </button>
                            </div>
                            <span class="font-bold text-red-500 text-lg block mb-2">
                                <?php echo $lang['need_help_title']; ?>
                            </span>

                            <ol class="list-decimal text-black font-normal list-inside space-y-4">
                                <li><strong>
                                        <?php echo $lang['need_help_step1_title']; ?>
                                    </strong>
                                    <br>
                                    <?php echo $lang['need_help_step1_desc']; ?>
                                </li>

                                <li><strong>
                                        <?php echo $lang['need_help_step2_title']; ?>
                                    </strong>
                                    <br>
                                    <?php echo $lang['need_help_step2_desc']; ?>
                                </li>

                                <li><strong>
                                        <?php echo $lang['need_help_step3_title']; ?>
                                    </strong>
                                    <br>
                                    <?php echo $lang['need_help_step3_desc']; ?>
                                </li>

                                <li><strong>
                                        <?php echo $lang['need_help_step4_title']; ?>
                                    </strong>
                                    <br>
                                    <?php echo $lang['need_help_step4_desc']; ?>
                                </li>

                                <li><strong>
                                        <?php echo $lang['need_help_step5_title']; ?>
                                    </strong>
                                    <br>
                                    <?php echo $lang['need_help_step5_desc']; ?>
                                </li>

                                <li><strong>
                                        <?php echo $lang['need_help_step6_title']; ?>
                                    </strong>
                                    <br>
                                    <?php echo $lang['need_help_step6_desc']; ?>
                                </li>

                                <li><strong>
                                        <?php echo $lang['need_help_step7_title']; ?>
                                    </strong>
                                    <br>
                                    <?php echo $lang['need_help_step7_desc']; ?>
                                </li>
                            </ol>
                            <div class="flex items-center justify-end pt-4 border-t space-x-4">
                                <button type="button"
                                    class="btn btn-primary close-modal bg-red-400 text-white px-3 py-2 rounded-xl shadow-sm shadow-red-700 hover:shadow transition-shadow delay-75 hover:bg-red-500"
                                    data-modal="modal3">
                                    <?php echo $lang['hygiene_ok'] ?? 'Okay, got it'; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- script for popup -->
                    <script>
                        // Function to open a modal
                        function openModal(modalId) {
                            const modal = document.querySelector(`.modal-overlay[data-modal="${modalId}"]`);
                            modal.classList.remove('hidden');
                            document.body.classList.add('modal-open'); // Disable body scroll
                            setTimeout(() => modal.querySelector('.modal-container').classList.add('active'), 100);
                        }

                        // Function to close a modal
                        function closeModal(modalId) {
                            const modal = document.querySelector(`.modal-overlay[data-modal="${modalId}"]`);
                            modal.querySelector('.modal-container').classList.remove('active');
                            setTimeout(() => {
                                modal.classList.add('hidden');
                                document.body.classList.remove('modal-open'); // Re-enable body scroll
                            }, 500);
                        }

                        // Event listeners for opening and closing modals
                        document.querySelectorAll('.open-modal').forEach(button => {
                            button.addEventListener('click', function () {
                                const modalId = this.getAttribute('data-modal');
                                openModal(modalId);
                            });
                        });

                        document.querySelectorAll('.close-modal').forEach(button => {
                            button.addEventListener('click', function () {
                                const modalId = this.getAttribute('data-modal');
                                closeModal(modalId);
                            });
                        });

                        // Close modal on outside click
                        document.querySelectorAll('.modal-overlay').forEach(overlay => {
                            overlay.addEventListener('click', function (e) {
                                if (e.target === overlay) {
                                    const modalId = overlay.getAttribute('data-modal');
                                    closeModal(modalId);
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 px-4" id="dashboard">
            <!-- Company Overview -->
            <div class="col-span-1 lg:col-span-3 bg-gray-50 p-6 rounded-lg shadow-lg text-center">
                <h1 class="text-3xl font-bold text-blue-600">Company Overview</h1>
                <p class="text-gray-500">High-level insights into company performance</p>
            </div>

            <!-- Key Performance Indicators (KPIs) -->
            <div class="col-span-1 md:col-span-2 lg:col-span-1 bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold text-green-500">Total Revenue</h2>
                <p class="text-gray-700 text-xl">$10,000,000</p>
            </div>
            <div class="col-span-1 md:col-span-2 lg:col-span-1 bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold text-blue-500">Net Profit</h2>
                <p class="text-gray-700 text-xl">$2,000,000</p>
            </div>
            <div class="col-span-1 md:col-span-2 lg:col-span-1 bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold text-yellow-500">Cash Flow</h2>
                <p class="text-gray-700 text-xl">$1,500,000</p>
            </div>

            <!-- Financial Performance Chart -->
            <div class="col-span-1 md:col-span-2 bg-gray-50 p-6 rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold text-blue-600">Revenue Trend</h2>
                <canvas id="revenueChart"></canvas> <!-- Placeholder for chart -->
            </div>

            <!-- Additional Metrics Here... -->
        </div>

        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    datasets: [{
                        label: 'Monthly Revenue',
                        data: [1200000, 1150000, 1300000, 1250000, 1400000, 1350000, 1450000, 1400000, 1500000, 1550000, 1600000, 1650000],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `$${context.raw.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return `$${value.toLocaleString()}`;
                                }
                            }
                        }
                    }
                }
            });
        </script>


        <footer class="pt-8 pb-6 flex flex-col items-center">
            <div class="footer-links flex flex-row flex-wrap justify-center items-center mb-4 space-x-2">
                <a class="active px-2 mx-1 text-sm sm:text-base hover:text-red-500 hover:font-bold py-1 hover:transition-bg delay-50 hover:transition-colors rounded-lg"
                    href="index.php">
                    <?php echo $lang['MENU_HOME'] ?>
                </a>
                <a class="px-2 mx-1 text-sm sm:text-base cursor-pointer text-black hover:text-red-500 hover:font-bold py-1 hover:transition-bg delay-50 hover:transition-colors rounded-lg"
                    onclick="FutureFeature()">
                    <?php echo $lang['MENU_ABOUT_US'] ?>
                </a>
                <a class="px-2 mx-1 text-sm sm:text-base cursor-pointer text-black hover:text-red-500 hover:font-bold py-1 hover:transition-bg delay-50 hover:transition-colors rounded-lg"
                    onclick="FutureFeature()">
                    <?php echo $lang['MENU_CONTACT_US'] ?>
                </a>
            </div>

            <div class="my-4 text-center">
                <h3 class="font-bold text-lg sm:text-xl">
                    <?php echo $lang['footer_heading'] ?>
                </h3>
                <p class="mt-2 font-light text-sm sm:text-base text-gray-700">
                    <?php echo $lang['footer_subheading'] ?>
                </p>
            </div>

            <div class="flex flex-row justify-center space-x-3">
                <button class="button mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 51 51" fill="none">
                        <path
                            d="M17.4456 25.7808C17.4456 21.1786 21.1776 17.4468 25.7826 17.4468C30.3875 17.4468 34.1216 21.1786 34.1216 25.7808C34.1216 30.383 30.3875 34.1148 25.7826 34.1148C21.1776 34.1148 17.4456 30.383 17.4456 25.7808ZM12.9377 25.7808C12.9377 32.8708 18.6883 38.618 25.7826 38.618C32.8768 38.618 38.6275 32.8708 38.6275 25.7808C38.6275 18.6908 32.8768 12.9436 25.7826 12.9436C18.6883 12.9436 12.9377 18.6908 12.9377 25.7808ZM36.1342 12.4346C36.1339 13.0279 36.3098 13.608 36.6394 14.1015C36.9691 14.595 37.4377 14.9797 37.9861 15.2069C38.5346 15.4342 39.1381 15.4939 39.7204 15.3784C40.3028 15.2628 40.8378 14.9773 41.2577 14.5579C41.6777 14.1385 41.9638 13.6041 42.0799 13.0222C42.1959 12.4403 42.1367 11.8371 41.9097 11.2888C41.6828 10.7406 41.2982 10.2719 40.8047 9.94202C40.3112 9.61218 39.7309 9.436 39.1372 9.43576H39.136C38.3402 9.43613 37.5771 9.75216 37.0142 10.3144C36.4514 10.8767 36.1349 11.6392 36.1342 12.4346ZM15.6765 46.1302C13.2377 46.0192 11.9121 45.6132 11.0311 45.2702C9.86323 44.8158 9.02993 44.2746 8.15381 43.4002C7.27768 42.5258 6.73536 41.6938 6.28269 40.5266C5.93928 39.6466 5.53304 38.3214 5.42217 35.884C5.3009 33.2488 5.27668 32.4572 5.27668 25.781C5.27668 19.1048 5.3029 18.3154 5.42217 15.678C5.53324 13.2406 5.94248 11.918 6.28269 11.0354C6.73736 9.86816 7.27888 9.03536 8.15381 8.15976C9.02873 7.28416 9.86123 6.74216 11.0311 6.28976C11.9117 5.94656 13.2377 5.54056 15.6765 5.42976C18.3133 5.30856 19.1054 5.28436 25.7826 5.28436C32.4598 5.28436 33.2527 5.31056 35.8916 5.42976C38.3305 5.54076 39.6539 5.94976 40.537 6.28976C41.7049 6.74216 42.5382 7.28536 43.4144 8.15976C44.2905 9.03416 44.8308 9.86816 45.2855 11.0354C45.6289 11.9154 46.0351 13.2406 46.146 15.678C46.2673 18.3154 46.2915 19.1048 46.2915 25.781C46.2915 32.4572 46.2673 33.2466 46.146 35.884C46.0349 38.3214 45.6267 39.6462 45.2855 40.5266C44.8308 41.6938 44.2893 42.5266 43.4144 43.4002C42.5394 44.2738 41.7049 44.8158 40.537 45.2702C39.6565 45.6134 38.3305 46.0194 35.8916 46.1302C33.2549 46.2514 32.4628 46.2756 25.7826 46.2756C19.1024 46.2756 18.3125 46.2514 15.6765 46.1302ZM15.4694 0.932162C12.8064 1.05336 10.9867 1.47536 9.39755 2.09336C7.75177 2.73156 6.35853 3.58776 4.9663 4.97696C3.57406 6.36616 2.71955 7.76076 2.08097 9.40556C1.46259 10.9948 1.04034 12.8124 0.919069 15.4738C0.795795 18.1394 0.767578 18.9916 0.767578 25.7808C0.767578 32.57 0.795795 33.4222 0.919069 36.0878C1.04034 38.7494 1.46259 40.5668 2.08097 42.156C2.71955 43.7998 3.57426 45.196 4.9663 46.5846C6.35833 47.9732 7.75177 48.8282 9.39755 49.4682C10.9897 50.0862 12.8064 50.5082 15.4694 50.6294C18.138 50.7506 18.9893 50.7808 25.7826 50.7808C32.5759 50.7808 33.4265 50.7506 36.0973 50.6294C38.7604 50.5082 40.5781 50.0862 42.1661 49.4682C43.8119 48.8282 45.2075 47.9732 46.5972 46.5846C47.987 45.196 48.8427 43.7998 49.4813 42.156C50.0996 40.5668 50.5228 38.7494 50.644 36.0878C50.7653 33.4222 50.7935 32.57 50.7935 25.7808C50.7935 18.9916 50.7653 18.1394 50.644 15.4738C50.5228 12.8124 50.0996 10.9948 49.4813 9.40556C48.8427 7.76076 47.987 6.36616 46.5972 4.97696C45.2075 3.58776 43.8119 2.73156 42.1661 2.09336C40.5781 1.47536 38.7604 1.05336 36.0973 0.932162C33.4265 0.811562 32.5759 0.781162 25.7826 0.781162C18.9893 0.781162 18.138 0.811562 15.4694 0.932162Z" />
                    </svg>
                    <div class="bg-effect"></div>
                </button>
                <button class="button_fb mr-3 ">

                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 72 72">
                        <path
                            d="M46.4927 38.6403L47.7973 30.3588H39.7611V24.9759C39.7611 22.7114 40.883 20.4987 44.4706 20.4987H48.1756V13.4465C46.018 13.1028 43.8378 12.9168 41.6527 12.8901C35.0385 12.8901 30.7204 16.8626 30.7204 24.0442V30.3588H23.3887V38.6403H30.7204V58.671H39.7611V38.6403H46.4927Z" />
                    </svg>
                    <div class="bg-effect"></div>
                </button>
                <button class="button_whatsapp">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 71 72">
                        <path
                            d="M12.5762 56.8405L15.8608 44.6381C13.2118 39.8847 12.3702 34.3378 13.4904 29.0154C14.6106 23.693 17.6176 18.952 21.9594 15.6624C26.3012 12.3729 31.6867 10.7554 37.1276 11.1068C42.5685 11.4582 47.6999 13.755 51.5802 17.5756C55.4604 21.3962 57.8292 26.4844 58.2519 31.9065C58.6746 37.3286 57.1228 42.7208 53.8813 47.0938C50.6399 51.4668 45.9261 54.5271 40.605 55.7133C35.284 56.8994 29.7125 56.1318 24.9131 53.5513L12.5762 56.8405ZM25.508 48.985L26.2709 49.4365C29.7473 51.4918 33.8076 52.3423 37.8191 51.8555C41.8306 51.3687 45.5681 49.5719 48.4489 46.7452C51.3298 43.9185 53.1923 40.2206 53.7463 36.2279C54.3002 32.2351 53.5143 28.1717 51.5113 24.6709C49.5082 21.1701 46.4003 18.4285 42.6721 16.8734C38.9438 15.3184 34.8045 15.0372 30.8993 16.0736C26.994 17.11 23.5422 19.4059 21.0817 22.6035C18.6212 25.801 17.2903 29.7206 17.2963 33.7514C17.293 37.0937 18.2197 40.3712 19.9732 43.2192L20.4516 44.0061L18.6153 50.8167L25.508 48.985Z"
                            fill="" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M44.0259 36.8847C43.5787 36.5249 43.0549 36.2716 42.4947 36.1442C41.9344 36.0168 41.3524 36.0186 40.793 36.1495C39.9524 36.4977 39.4093 37.8134 38.8661 38.4713C38.7516 38.629 38.5833 38.7396 38.3928 38.7823C38.2024 38.8251 38.0028 38.797 37.8316 38.7034C34.7543 37.5012 32.1748 35.2965 30.5122 32.4475C30.3704 32.2697 30.3033 32.044 30.325 31.8178C30.3467 31.5916 30.4555 31.3827 30.6286 31.235C31.2344 30.6368 31.6791 29.8959 31.9218 29.0809C31.9756 28.1818 31.7691 27.2863 31.3269 26.5011C30.985 25.4002 30.3344 24.42 29.4518 23.6762C28.9966 23.472 28.4919 23.4036 27.9985 23.4791C27.5052 23.5546 27.0443 23.7709 26.6715 24.1019C26.0242 24.6589 25.5104 25.3537 25.168 26.135C24.8256 26.9163 24.6632 27.7643 24.6929 28.6165C24.6949 29.0951 24.7557 29.5716 24.8739 30.0354C25.1742 31.1497 25.636 32.2144 26.2447 33.1956C26.6839 33.9473 27.163 34.6749 27.6801 35.3755C29.3607 37.6767 31.4732 39.6305 33.9003 41.1284C35.1183 41.8897 36.42 42.5086 37.7799 42.973C39.1924 43.6117 40.752 43.8568 42.2931 43.6824C43.1711 43.5499 44.003 43.2041 44.7156 42.6755C45.4281 42.1469 45.9995 41.4518 46.3795 40.6512C46.6028 40.1675 46.6705 39.6269 46.5735 39.1033C46.3407 38.0327 44.9053 37.4007 44.0259 36.8847Z"
                            fill="" />
                    </svg>
                    <div class="bg_effect_whatsapp_button"></div>
                </button>
            </div>

            <div class="mx-4 my-3 w-full max-w-screen-2xl border-b-2 border-gray-300"></div>

            <div class="mb-3">
                <p class="text-center font-light text-sm sm:text-base text-gray-700">
                    <?php echo $lang['footer_copyright'] ?>
                </p>
            </div>
        </footer>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/pagedone@1.2.2/src/js/pagedone.js"></script>
    <script>
        function FutureFeature() {
            Toastify({
                text: "<?php echo $lang['future_function'] ?? 'This feature is coming soon!!'; ?>",
                duration: 3000,
                gravity: "top",
                position: "left",
                backgroundColor: "#1f5a0e",
            }).showToast();
        }

    </script>
    <script>
        document.getElementById('language-select').addEventListener('change', function () {
            var selectedLang = this.value;
            window.location.href = window.location.pathname + '?lang=' + selectedLang;
        });
    </script>

</html>