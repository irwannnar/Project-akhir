<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigasi dengan Indikator Halaman Aktif</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .nav-active {
            background-color: oklch(0.872 0.01 258.338);
            /* Biru Tailwind */
            color: white;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
        }

        .nav-active:hover {
            background-color: #25oklch(27.8% 0.033 256.848);
            /* Biru lebih gelap saat hover */
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <nav class="bg-gray-100 text-black p-4 transition duration-100 shadow-md hover:shadow-lg ">
        <div class="container">
        </div>
        <ul class="flex items-center gap-6 text-sm justify-between px-10">
            <li>
                <a href="/dashboard" class="flex items-center gap-1 hover:bg-gray-200 p-2 rounded-md">
                    <svg width="20" height="20" viewBox="0 0 40 40" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 40V25.8824H24V40H34V21.1765H40L20 0L0 21.1765H6V40H16Z" fill="currentColor" />
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="/printing" class="flex items-center gap-1 hover:bg-gray-200 p-2 rounded-md">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6 17.9827C4.44655 17.9359 3.51998 17.7626 2.87868 17.1213C2 16.2426 2 14.8284 2 12C2 9.17157 2 7.75736 2.87868 6.87868C3.75736 6 5.17157 6 8 6H16C18.8284 6 20.2426 6 21.1213 6.87868C22 7.75736 22 9.17157 22 12C22 14.8284 22 16.2426 21.1213 17.1213C20.48 17.7626 19.5535 17.9359 18 17.9827"
                            stroke="#1C274C" stroke-width="1.5" />
                        <path d="M9 10H6" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M19 15L5 15" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M17.1213 2.87868L16.591 3.40901V3.40901L17.1213 2.87868ZM6.87868 2.87868L7.40901 3.40901V3.40901L6.87868 2.87868ZM6.87868 21.1213L7.40901 20.591H7.40901L6.87868 21.1213ZM18.75 15C18.75 14.5858 18.4142 14.25 18 14.25C17.5858 14.25 17.25 14.5858 17.25 15H18.75ZM6.75 15C6.75 14.5858 6.41421 14.25 6 14.25C5.58579 14.25 5.25 14.5858 5.25 15H6.75ZM17.25 16C17.25 17.4354 17.2484 18.4365 17.1469 19.1919C17.0482 19.9257 16.8678 20.3142 16.591 20.591L17.6517 21.6517C18.2536 21.0497 18.5125 20.2919 18.6335 19.3918C18.7516 18.5132 18.75 17.393 18.75 16H17.25ZM12 22.75C13.393 22.75 14.5132 22.7516 15.3918 22.6335C16.2919 22.5125 17.0497 22.2536 17.6517 21.6517L16.591 20.591C16.3142 20.8678 15.9257 21.0482 15.1919 21.1469C14.4365 21.2484 13.4354 21.25 12 21.25V22.75ZM12 2.75C13.4354 2.75 14.4365 2.75159 15.1919 2.85315C15.9257 2.9518 16.3142 3.13225 16.591 3.40901L17.6517 2.34835C17.0497 1.74643 16.2919 1.48754 15.3918 1.36652C14.5132 1.24841 13.393 1.25 12 1.25V2.75ZM12 1.25C10.607 1.25 9.48678 1.24841 8.60825 1.36652C7.70814 1.48754 6.95027 1.74643 6.34835 2.34835L7.40901 3.40901C7.68577 3.13225 8.07434 2.9518 8.80812 2.85315C9.56347 2.75159 10.5646 2.75 12 2.75V1.25ZM5.25 16C5.25 17.393 5.24841 18.5132 5.36652 19.3918C5.48754 20.2919 5.74643 21.0497 6.34835 21.6517L7.40901 20.591C7.13225 20.3142 6.9518 19.9257 6.85315 19.1919C6.75159 18.4365 6.75 17.4354 6.75 16H5.25ZM12 21.25C10.5646 21.25 9.56347 21.2484 8.80812 21.1469C8.07435 21.0482 7.68577 20.8678 7.40901 20.591L6.34835 21.6517C6.95027 22.2536 7.70814 22.5125 8.60825 22.6335C9.48678 22.7516 10.607 22.75 12 22.75V21.25ZM18.7323 5.97741C18.6859 4.43521 18.5237 3.22037 17.6517 2.34835L16.591 3.40901C17.0016 3.8196 17.1859 4.4579 17.233 6.02259L18.7323 5.97741ZM6.76698 6.02259C6.81413 4.4579 6.99842 3.8196 7.40901 3.40901L6.34835 2.34835C5.47633 3.22037 5.31413 4.43521 5.26766 5.97741L6.76698 6.02259ZM18.75 16V15H17.25V16H18.75ZM6.75 16V15H5.25V16H6.75Z"
                            fill="#1C274C" />
                        <circle cx="17" cy="10" r="1" fill="#1C274C" />
                    </svg>

                    Service</a>
            </li>
            <li>
                <a href="/product" class="flex items-center gap-1 hover:bg-gray-200 p-2 rounded-md">
                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21.9844 10C21.9473 8.68893 21.8226 7.85305 21.4026 7.13974C20.8052 6.12523 19.7294 5.56066 17.5777 4.43152L15.5777 3.38197C13.8221 2.46066 12.9443 2 12 2C11.0557 2 10.1779 2.46066 8.42229 3.38197L6.42229 4.43152C4.27063 5.56066 3.19479 6.12523 2.5974 7.13974C2 8.15425 2 9.41667 2 11.9415V12.0585C2 14.5833 2 15.8458 2.5974 16.8603C3.19479 17.8748 4.27063 18.4393 6.42229 19.5685L8.42229 20.618C10.1779 21.5393 11.0557 22 12 22C12.9443 22 13.8221 21.5393 15.5777 20.618L17.5777 19.5685C19.7294 18.4393 20.8052 17.8748 21.4026 16.8603C21.8226 16.1469 21.9473 15.3111 21.9844 14"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M21 7.5L17 9.5M12 12L3 7.5M12 12V21.5M12 12C12 12 14.7426 10.6287 16.5 9.75C16.6953 9.65237 17 9.5 17 9.5M17 9.5V13M17 9.5L7.5 4.5"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    Product</a>
            </li>
            <li>
                <a href="/transaction" class="flex items-center gap-1 hover:bg-gray-200 p-2 rounded-md">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.5 18C8.32843 18 9 18.6716 9 19.5C9 20.3284 8.32843 21 7.5 21C6.67157 21 6 20.3284 6 19.5C6 18.6716 6.67157 18 7.5 18Z"
                            stroke="#1C274C" stroke-width="1.5" />
                        <path
                            d="M16.5 18.0001C17.3284 18.0001 18 18.6716 18 19.5001C18 20.3285 17.3284 21.0001 16.5 21.0001C15.6716 21.0001 15 20.3285 15 19.5001C15 18.6716 15.6716 18.0001 16.5 18.0001Z"
                            stroke="#1C274C" stroke-width="1.5" />
                        <path d="M11 10.8L12.1429 12L15 9" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M2 3L2.26121 3.09184C3.5628 3.54945 4.2136 3.77826 4.58584 4.32298C4.95808 4.86771 4.95808 5.59126 4.95808 7.03836V9.76C4.95808 12.7016 5.02132 13.6723 5.88772 14.5862C6.75412 15.5 8.14857 15.5 10.9375 15.5H12M16.2404 15.5C17.8014 15.5 18.5819 15.5 19.1336 15.0504C19.6853 14.6008 19.8429 13.8364 20.158 12.3075L20.6578 9.88275C21.0049 8.14369 21.1784 7.27417 20.7345 6.69708C20.2906 6.12 18.7738 6.12 17.0888 6.12H11.0235M4.95808 6.12H7"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    transaction</a>
            </li>
            <li>
                <a href="/spending" class="flex items-center gap-1 hover:bg-gray-200 p-2 rounded-md">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3.79424 12.0291C4.33141 9.34329 4.59999 8.00036 5.48746 7.13543C5.65149 6.97557 5.82894 6.8301 6.01786 6.70061C7.04004 6 8.40956 6 11.1486 6H12.8515C15.5906 6 16.9601 6 17.9823 6.70061C18.1712 6.8301 18.3486 6.97557 18.5127 7.13543C19.4001 8.00036 19.6687 9.34329 20.2059 12.0291C20.9771 15.8851 21.3627 17.8131 20.475 19.1793C20.3143 19.4267 20.1267 19.6555 19.9157 19.8616C18.7501 21 16.7839 21 12.8515 21H11.1486C7.21622 21 5.25004 21 4.08447 19.8616C3.87342 19.6555 3.68582 19.4267 3.5251 19.1793C2.63744 17.8131 3.02304 15.8851 3.79424 12.0291Z"
                            stroke="#1C274C" stroke-width="1.5" />
                        <circle cx="15" cy="9" r="1" fill="#1C274C" />
                        <circle cx="9" cy="9" r="1" fill="#1C274C" />
                        <path d="M9 6V5C9 3.34315 10.3431 2 12 2C13.6569 2 15 3.34315 15 5V6" stroke="#1C274C"
                            stroke-width="1.5" stroke-linecap="round" />
                    </svg>

                    Spending
                </a>
            </li>
        </ul>
    </nav>

    <script>
        // Fungsi untuk menandai halaman aktif
        function setActivePage() {
            // Dapatkan path URL saat ini
            const currentPath = window.location.pathname;

            // Temukan semua link navigasi
            const navLinks = document.querySelectorAll('nav a');

            // Loop melalui setiap link
            navLinks.forEach(link => {
                // Dapatkan path dari href link
                const linkPath = new URL(link.href).pathname;

                // Jika path link sesuai dengan path saat ini, tambahkan class aktif
                if (linkPath === currentPath) {
                    link.classList.add('nav-active');
                }
            });
        }

        // Panggil fungsi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', setActivePage);
    </script>
</body>

</html>
