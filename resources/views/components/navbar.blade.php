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
                    <svg width="20" height="20" viewBox="0 0 40 40" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M27.21 7.52672L21.9117 0.0983887L2.43 15.6617L1.35 15.6501V15.6667H0.5V35.6667H35.5V15.6667H33.8967L30.7067 6.33506L27.21 7.52672ZM30.375 15.6667H13.6617L26.11 11.4234L28.6467 10.6117L30.375 15.6667ZM23.9167 8.65005L11.0667 13.0301L21.2433 4.90006L23.9167 8.65005ZM3.83333 29.2817V22.0484C4.53696 21.8001 5.17608 21.3974 5.70384 20.8699C6.2316 20.3424 6.63462 19.7035 6.88333 19.0001H29.1167C29.3652 19.7039 29.7681 20.3431 30.2959 20.8709C30.8236 21.3986 31.4629 21.8015 32.1667 22.0501V29.2834C31.4629 29.5319 30.8236 29.9348 30.2959 30.4626C29.7681 30.9904 29.3652 31.6296 29.1167 32.3334H6.88667C6.63697 31.6295 6.23327 30.9903 5.70503 30.4623C5.17679 29.9344 4.53734 29.531 3.83333 29.2817Z"
                            fill="black" />
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
