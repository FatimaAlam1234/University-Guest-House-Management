<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Web Title -->
    <title>MPUAT Guest House</title>

    <!-- Google Fonts: Poppins & Merriweather -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/11f841f55d.js" crossorigin="anonymous"></script>
    
    @stack('styles')
    
    <!-- Main Styles -->
    <link rel="stylesheet" href="{{ asset('css/client/main.css') }}">
</head>
<body>
    <!-- Nav -->
    <nav id="nav" class="nav">
        <div class="nav-top">
            <div class="nav-top--left">
                <div class="box box--email">
                    <i class="fas fa-envelope icon"></i>
                    <span>mpuat@info.com</span>
                </div>
                <div class="box box--phone">
                    <i class="fas fa-phone icon"></i>
                    <span>(+12) 345 6789</span>
                </div>
            </div>
        </div>
        <div class="nav-bottom">
            <div class="nav-bottom--left">
                <div class="box box--title">
                    <h1><a href="#">University Guest House</a></h1>
                </div>
            </div>
            <div class="nav-bottom--right">
                {{-- <ul class="nav-link">
                    <li class="nav-item" data-link="/"><a href="/">Home</a></li>
                    <li class="nav-item" data-link="/our-rooms"><a href="/our-rooms">Rooms</a></li>
                    <li class="nav-item" data-link="/about-us"><a href="/about-us">About Us</a></li>
                    <li class="nav-item" data-link="/contact-us"><a href="/contact-us">Contact Us</a></li>
                </ul>
                <div class="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
                <ul class="hamburger-link">
                    <i class="fas fa-times hamburger-close"></i>
                    <li class="hamburger-item active"><a href="/">Home</a></li>
                    <li class="hamburger-item"><a href="/our-rooms">Rooms</a></li>
                    <li class="hamburger-item"><a href="/about-us">About Us</a></li>
                    <li class="hamburger-item"><a href="/contact-us">Contact Us</a></li>
                </ul> --}}
            </div>
        </div>
    </nav>
    <!-- /.nav -->

    <header id="header" class="header">
        @yield('header')
    </header>

    <main id="main" class="main">
        @yield('content')
    </main>

    <footer id="footer" class="footer">
        <div class="footer-top">
            <div class="footer--quick-links">
                <h2>Quick Links</h2>
                <ul>
                    <li><a href="#">Rooms</a></li>
                    <li><a href="#">Book Room</a></li>
                    <li><a href="#">About Us</a></li>
                </ul>
            </div>
            <div class="footer--contact-us">
                <h2>Contact Us</h2>
                <ul>
                    <li>
                        <span>Maharana Pratap University
                            of Agricluture & Technology,
                            Udaipur(Rajasthan) 313001</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>registrar@mpuat.ac.in</span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <span>(0294-2471302 (o)</span>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Shakti Nagar, Udaipur, Rajasthan 313001</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div>
                All right reserved © 2021 MPUAT. For website updation & queries contact: web.mpuat@mpuat.ac.in, web.mpuat@gmail.com
            </div>
        </div>
    </footer>
    
    @stack('scripts')
    <script>
        // Hamburger Menu
        const hamburger = document.querySelector('.hamburger')
        const hamburgerLink = document.querySelector('.hamburger-link')
        const hamburgerClose = document.querySelector('.hamburger-close')

        hamburger.addEventListener('click', () => {
            hamburgerLink.classList.add('show')
        })
        hamburgerClose.addEventListener('click', () => {
            hamburgerLink.classList.remove('show')
        })
    </script>
</body>
</html>