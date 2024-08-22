<?php

namespace nguyenanhung\Libraries\Filesystem\HeroDoc;

class DefaultHeroDocTemplates
{
    public static function htaccess_deny_all()
    {
        return <<<HTACCESS
Options -Indexes
AddType text/plain .php3 .php4 .php5 .php .cgi .asp .aspx .html .css .js
<IfModule authz_core_module>
    Require all denied
</IfModule>
<IfModule !authz_core_module>
    Deny from all
</IfModule>

HTACCESS;
    }

    public static function nginx_index_html()
    {
        return <<<NGINX_INDEX_HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome to nginx!</title>
    <style>
        body {
            width: 35em;
            margin: 0 auto;
            font-family: Tahoma, Verdana, Arial, sans-serif;
        }
    </style>
</head>
<body>
<h1>Welcome to nginx!</h1>
<p>If you see this page, the nginx web server is successfully installed and working. Further configuration is required.</p>
<p>For online documentation and support please refer to <a href="https://nginx.org/">nginx.org</a>.<br/>Commercial support is available at <a href="https://nginx.com/">nginx.com</a>.</p>
<p><em>Thank you for using nginx.</em></p>
</body>
</html>
<!-- (c) 2024 Powered by Hung Nguyen <dev@nguyenanhung.com> -->
NGINX_INDEX_HTML;
    }

    public static function default_403_simple_html()
    {
        return <<<NGINX_INDEX_HTML
<!DOCTYPE html>
<html lang='en'>
<head>
    <title>403 Forbidden</title>
</head>
<body>
    <p>Directory access is forbidden.</p>
</body>
</html>
<!-- (c) 2024 Powered by Hung Nguyen <dev@nguyenanhung.com> -->
NGINX_INDEX_HTML;
    }

    public static function default_403_html()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Raleway:500,800" rel="stylesheet">
    <title>403 Forbidden</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            background: #233142;

        }

        .whistle {
            width: 20%;
            fill: #f95959;
            margin: 100px 40%;
            text-align: left;
            transform: translate(-50%, -50%);
            transform: rotate(0);
            transform-origin: 80% 30%;
            animation: wiggle .2s infinite;
        }

        @keyframes wiggle {
            0% {
                transform: rotate(3deg);
            }
            50% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(3deg);
            }
        }

        h1 {
            margin-top: -100px;
            margin-bottom: 20px;
            color: #facf5a;
            text-align: center;
            font-family: 'Raleway';
            font-size: 90px;
            font-weight: 800;
        }

        h2 {
            color: #455d7a;
            text-align: center;
            font-family: 'Raleway';
            font-size: 30px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<use>
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
         viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve" class="whistle">
<metadata> Svg Vector Icons : https://www.onlinewebfonts.com/icon </metadata>
        <g><g transform="translate(0.000000,511.000000) scale(0.100000,-0.100000)">
<path d="M4295.8,3963.2c-113-57.4-122.5-107.2-116.8-622.3l5.7-461.4l63.2-55.5c72.8-65.1,178.1-74.7,250.8-24.9c86.2,61.3,97.6,128.3,97.6,584c0,474.8-11.5,526.5-124.5,580.1C4393.4,4001.5,4372.4,4001.5,4295.8,3963.2z"/><path
                d="M3053.1,3134.2c-68.9-42.1-111-143.6-93.8-216.4c7.7-26.8,216.4-250.8,476.8-509.3c417.4-417.4,469.1-463.4,526.5-463.4c128.3,0,212.5,88.1,212.5,224c0,67-26.8,97.6-434.6,509.3c-241.2,241.2-459.5,449.9-488.2,465.3C3181.4,3180.1,3124,3178.2,3053.1,3134.2z"/><path
                d="M2653,1529.7C1644,1445.4,765.1,850,345.8-32.7C62.4-628.2,22.2-1317.4,234.8-1960.8C451.1-2621.3,947-3186.2,1584.6-3500.2c1018.6-501.6,2228.7-296.8,3040.5,515.1c317.8,317.8,561,723.7,670.1,1120.1c101.5,369.5,158.9,455.7,360,553.3c114.9,57.4,170.4,65.1,1487.7,229.8c752.5,93.8,1392,181.9,1420.7,193.4C8628.7-857.9,9900,1250.1,9900,1328.6c0,84.3-67,172.3-147.4,195.3c-51.7,15.3-790.8,19.1-2558,15.3l-2487.2-5.7l-55.5-63.2l-55.5-61.3v-344.6V719.8h-411.7h-411.7v325.5c0,509.3,11.5,499.7-616.5,494C2921,1537.3,2695.1,1533.5,2653,1529.7z"/></g></g>
</svg>
</use>
<h1>403</h1>
<h2>Not this time, access forbidden!</h2>
</body>
</html>
<!-- (c) 2024 Powered by Hung Nguyen <dev@nguyenanhung.com> -->
<!-- a padding to disable MSIE and Chrome friendly error page -->
HTML;
    }

    public static function default_404_html()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            margin: 0px;
            background: linear-gradient(90deg, rgba(47, 54, 64, 1) 23%, rgba(24, 27, 32, 1) 100%);
        }

        .moon {
            background: linear-gradient(90deg, rgba(208, 208, 208, 1) 48%, rgba(145, 145, 145, 1) 100%);
            position: absolute;
            top: -100px;
            left: -300px;
            width: 900px;
            height: 900px;
            content: '';
            border-radius: 100%;
            box-shadow: 0px 0px 30px -4px rgba(0, 0, 0, 0.5);
        }

        .moon__crater {
            position: absolute;
            content: '';
            border-radius: 100%;
            background: linear-gradient(90deg, rgba(122, 122, 122, 1) 38%, rgba(195, 195, 195, 1) 100%);
            opacity: 0.6;
        }

        .moon__crater1 {
            top: 250px;
            left: 500px;
            width: 60px;
            height: 180px;
        }

        .moon__crater2 {
            top: 650px;
            left: 340px;
            width: 40px;
            height: 80px;
            transform: rotate(55deg);
        }

        .moon__crater3 {
            top: -20px;
            left: 40px;
            width: 65px;
            height: 120px;
            transform: rotate(250deg);
        }

        .star {
            background: grey;
            position: absolute;
            width: 5px;
            height: 5px;
            content: '';
            border-radius: 100%;
            transform: rotate(250deg);
            opacity: 0.4;
            animation-name: shimmer;
            animation-duration: 1.5s;
            animation-iteration-count: infinite;
            animation-direction: alternate;
        }

        @keyframes shimmer {
            from {
                opacity: 0;
            }
            to {
                opacity: 0.7;
            }
        }

        .star1 {
            top: 40%;
            left: 50%;
            animation-delay: 1s;
        }

        .star2 {
            top: 60%;
            left: 90%;
            animation-delay: 3s;
        }

        .star3 {
            top: 10%;
            left: 70%;
            animation-delay: 2s;
        }

        .star4 {
            top: 90%;
            left: 40%;
        }

        .star5 {
            top: 20%;
            left: 30%;
            animation-delay: 0.5s;
        }

        .error {
            position: absolute;
            left: 100px;
            top: 400px;
            transform: translateY(-60%);
            font-family: 'Righteous', cursive;
            color: #363e49;
        }

        .error__title {
            font-size: 10em;
        }

        .error__subtitle {
            font-size: 2em;
        }

        .error__description {
            opacity: 0.5;
        }

        .error__button {
            min-width: 7em;
            margin-top: 3em;
            margin-right: 0.5em;
            padding: 0.5em 2em;
            outline: none;
            border: 2px solid #2f3640;
            background-color: transparent;
            border-radius: 8em;
            color: #576375;
            cursor: pointer;
            transition-duration: 0.2s;
            font-size: 0.75em;
            font-family: 'Righteous', cursive;
        }

        .error__button:hover {
            color: #21252c;
        }

        .error__button--active {
            background-color: #e67e22;
            border: 2px solid #e67e22;
            color: white;
        }

        .error__button--active:hover {
            box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.5);
            color: white;
        }

        .astronaut {
            position: absolute;
            width: 185px;
            height: 300px;
            left: 70%;
            top: 50%;
            transform: translate(-50%, -50%) rotate(20deg) scale(1.2);
        }

        .astronaut__head {
            background-color: white;
            position: absolute;
            top: 60px;
            left: 60px;
            width: 60px;
            height: 60px;
            content: '';
            border-radius: 2em;
        }

        .astronaut__head-visor-flare1 {
            background-color: #7f8fa6;
            position: absolute;
            top: 28px;
            left: 40px;
            width: 10px;
            height: 10px;
            content: '';
            border-radius: 2em;
            opacity: 0.5;
        }

        .astronaut__head-visor-flare2 {
            background-color: #718093;
            position: absolute;
            top: 40px;
            left: 38px;
            width: 5px;
            height: 5px;
            content: '';
            border-radius: 2em;
            opacity: 0.3;
        }

        .astronaut__backpack {
            background-color: #bfbfbf;
            position: absolute;
            top: 90px;
            left: 47px;
            width: 86px;
            height: 90px;
            content: '';
            border-radius: 8px;
        }

        .astronaut__body {
            background-color: #e6e6e6;
            position: absolute;
            top: 115px;
            left: 55px;
            width: 70px;
            height: 80px;
            content: '';
            border-radius: 8px;
        }

        .astronaut__body__chest {
            background-color: #d9d9d9;
            position: absolute;
            top: 140px;
            left: 68px;
            width: 45px;
            height: 25px;
            content: '';
            border-radius: 6px;
        }

        .astronaut__arm-left1 {
            background-color: #e6e6e6;
            position: absolute;
            top: 127px;
            left: 9px;
            width: 65px;
            height: 20px;
            content: '';
            border-radius: 8px;
            transform: rotate(-30deg);
        }

        .astronaut__arm-left2 {
            background-color: #e6e6e6;
            position: absolute;
            top: 102px;
            left: 7px;
            width: 20px;
            height: 45px;
            content: '';
            border-radius: 8px;
            transform: rotate(-12deg);
            border-top-left-radius: 8em;
            border-top-right-radius: 8em;
        }

        .astronaut__arm-right1 {
            background-color: #e6e6e6;
            position: absolute;
            top: 113px;
            left: 100px;
            width: 65px;
            height: 20px;
            content: '';
            border-radius: 8px;
            transform: rotate(-10deg);
        }

        .astronaut__arm-right2 {
            background-color: #e6e6e6;
            position: absolute;
            top: 78px;
            left: 141px;
            width: 20px;
            height: 45px;
            content: '';
            border-radius: 8px;
            transform: rotate(-10deg);
            border-top-left-radius: 8em;
            border-top-right-radius: 8em;
        }

        .astronaut__arm-thumb-left {
            background-color: #e6e6e6;
            position: absolute;
            top: 110px;
            left: 21px;
            width: 10px;
            height: 6px;
            content: '';
            border-radius: 8em;
            transform: rotate(-35deg);
        }

        .astronaut__arm-thumb-right {
            background-color: #e6e6e6;
            position: absolute;
            top: 90px;
            left: 133px;
            width: 10px;
            height: 6px;
            content: '';
            border-radius: 8em;
            transform: rotate(20deg);
        }

        .astronaut__wrist-left {
            background-color: #e67e22;
            position: absolute;
            top: 122px;
            left: 6.5px;
            width: 21px;
            height: 4px;
            content: '';
            border-radius: 8em;
            transform: rotate(-15deg);
        }

        .astronaut__wrist-right {
            background-color: #e67e22;
            position: absolute;
            top: 98px;
            left: 141px;
            width: 21px;
            height: 4px;
            content: '';
            border-radius: 8em;
            transform: rotate(-10deg);
        }

        .astronaut__leg-left {
            background-color: #e6e6e6;
            position: absolute;
            top: 188px;
            left: 50px;
            width: 23px;
            height: 75px;
            content: '';
            transform: rotate(10deg);
        }

        .astronaut__leg-right {
            background-color: #e6e6e6;
            position: absolute;
            top: 188px;
            left: 108px;
            width: 23px;
            height: 75px;
            content: '';
            transform: rotate(-10deg);
        }

        .astronaut__foot-left {
            background-color: white;
            position: absolute;
            top: 240px;
            left: 43px;
            width: 28px;
            height: 20px;
            content: '';
            transform: rotate(10deg);
            border-radius: 3px;
            border-top-left-radius: 8em;
            border-top-right-radius: 8em;
            border-bottom: 4px solid #e67e22;
        }

        .astronaut__foot-right {
            background-color: white;
            position: absolute;
            top: 240px;
            left: 111px;
            width: 28px;
            height: 20px;
            content: '';
            transform: rotate(-10deg);
            border-radius: 3px;
            border-top-left-radius: 8em;
            border-top-right-radius: 8em;
            border-bottom: 4px solid #e67e22;
        }

    </style>
    <script type="application/javascript">
        function drawVisor() {
            const canvas = document.getElementById('visor');
            const ctx = canvas.getContext('2d');

            ctx.beginPath();
            ctx.moveTo(5, 45);
            ctx.bezierCurveTo(15, 64, 45, 64, 55, 45);

            ctx.lineTo(55, 20);
            ctx.bezierCurveTo(55, 15, 50, 10, 45, 10);

            ctx.lineTo(15, 10);

            ctx.bezierCurveTo(15, 10, 5, 10, 5, 20);
            ctx.lineTo(5, 45);

            ctx.fillStyle = '#2f3640';
            ctx.strokeStyle = '#f5f6fa';
            ctx.fill();
            ctx.stroke();
        }

        const cordCanvas = document.getElementById('cord');
        const ctx = cordCanvas.getContext('2d');

        let y1 = 160;
        let y2 = 100;
        let y3 = 100;

        let y1Forward = true;
        let y2Forward = false;
        let y3Forward = true;

        function animate() {
            requestAnimationFrame(animate);
            ctx.clearRect(0, 0, innerWidth, innerHeight);

            ctx.beginPath();
            ctx.moveTo(130, 170);
            ctx.bezierCurveTo(250, y1, 345, y2, 400, y3);

            ctx.strokeStyle = 'white';
            ctx.lineWidth = 8;
            ctx.stroke();


            if (y1 === 100) {
                y1Forward = true;
            }

            if (y1 === 300) {
                y1Forward = false;
            }

            if (y2 === 100) {
                y2Forward = true;
            }

            if (y2 === 310) {
                y2Forward = false;
            }

            if (y3 === 100) {
                y3Forward = true;
            }

            if (y3 === 317) {
                y3Forward = false;
            }

            y1Forward ? y1 += 1 : y1 -= 1;
            y2Forward ? y2 += 1 : y2 -= 1;
            y3Forward ? y3 += 1 : y3 -= 1;
        }

        drawVisor();
        animate();
    </script>
</head>

<body>
<div class="moon"></div>
<div class="moon__crater moon__crater1"></div>
<div class="moon__crater moon__crater2"></div>
<div class="moon__crater moon__crater3"></div>

<div class="star star1"></div>
<div class="star star2"></div>
<div class="star star3"></div>
<div class="star star4"></div>
<div class="star star5"></div>

<div class="error">
    <div class="error__title">404</div>
    <div class="error__subtitle">Hmmm...</div>
    <div class="error__description">It looks like one of the system admin fell asleep</div>
    <!--
    <button class="error__button error__button--active"></button>
    <button class="error__button"></button>
    -->
</div>

<div class="astronaut">
    <div class="astronaut__backpack"></div>
    <div class="astronaut__body"></div>
    <div class="astronaut__body__chest"></div>
    <div class="astronaut__arm-left1"></div>
    <div class="astronaut__arm-left2"></div>
    <div class="astronaut__arm-right1"></div>
    <div class="astronaut__arm-right2"></div>
    <div class="astronaut__arm-thumb-left"></div>
    <div class="astronaut__arm-thumb-right"></div>
    <div class="astronaut__leg-left"></div>
    <div class="astronaut__leg-right"></div>
    <div class="astronaut__foot-left"></div>
    <div class="astronaut__foot-right"></div>
    <div class="astronaut__wrist-left"></div>
    <div class="astronaut__wrist-right"></div>

    <div class="astronaut__cord">
        <canvas id="cord" height="500px" width="500px"></canvas>
    </div>

    <div class="astronaut__head">
        <canvas id="visor" width="60px" height="60px"></canvas>
        <div class="astronaut__head-visor-flare1"></div>
        <div class="astronaut__head-visor-flare2"></div>
    </div>
</div>
</body>
</html>
<!-- (c) 2024 Powered by Hung Nguyen <dev@nguyenanhung.com> -->
<!-- a padding to disable MSIE and Chrome friendly error page -->
HTML;
    }

    public static function default_coming_soon_html()
    {
        return <<<HTML


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Coming Soon</title>
    <meta name="author" content="Hung Nguyen" />
    <meta name="keywords" content="404, css3, html5, template" />
    <meta name="description" content="Coming Soon" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!-- Bootstrap CSS -->
    <link type="text/css" media="all" href="https://hungna.github.io/assets/themes/sailor/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Template CSS -->
    <link type="text/css" media="all" href="https://hungna.github.io/assets/themes/sailor/assets/css/style.css" rel="stylesheet" />
    <!-- Responsive CSS -->
    <link type="text/css" media="all" href="https://hungna.github.io/assets/themes/sailor/assets/css/responsive.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic,800italic,800,700italic,700,600italic,600,400italic,300' rel='stylesheet' type='text/css' />
    <!-- Favicon -->
    <link rel="shortcut icon" href="https://hungna.github.io/assets/themes/sailor/assets/img/favicon.png" />
</head>
<body>
<!-- Header -->
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>/</h1>
                <h2>Coming Soon</h2>
                <p>I'll be back...</p>
            </div>
        </div>
    </div>
</section>
<!-- end Header -->

<!-- Illustration -->
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="illustration">
                    <div class="boat"></div>
                    <div class="water1"></div>
                    <div class="water2"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end Illustration -->

<!-- Button -->
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <a href="#"><div class="btn btn-action">Take me out of here</div></a>
            </div>
        </div>
    </div>
</section>
<!-- end Button -->

<!-- Footer -->
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <p>&copy; Copyright 2024 <strong>HungNG Manage Server</strong> All Rights Reserved.</p>
            </div>
        </div>
    </div>
</section>
<!-- end Footer -->

<!-- Scripts -->
<script src="https://hungna.github.io/assets/themes/sailor/assets/js/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="https://hungna.github.io/assets/themes/sailor/assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>

<!-- (c) 2024 Powered by Hung Nguyen <dev@nguyenanhung.com> -->
<!-- a padding to disable MSIE and Chrome friendly error page -->
HTML;
    }
}
