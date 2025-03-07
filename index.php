<?php
include("config/database.php");
session_start();
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
    <link rel="shortcut icon"
        href="https://r.mobirisesite.com/908256/assets/images/photo-1527772482340-7895c3f2b3f7.jpeg"
        type="image/x-icon">
    <meta name="description"
        content="ระบบการจัดการหอพักสำหรับเจ้าของหอที่ต้องการลงทะเบียนผู้เช่าและติดตามสถานะการชำระเงิน.">
    <title>หอพักออนไลน์</title>
    <link rel="stylesheet"
        href="https://r.mobirisesite.com/908256/assets/web/assets/mobirise-icons2/mobirise2.css?rnd=1731837499227">
    <link rel="stylesheet"
        href="https://r.mobirisesite.com/908256/assets/bootstrap/css/bootstrap.min.css?rnd=1731837499227">
    <link rel="stylesheet"
        href="https://r.mobirisesite.com/908256/assets/bootstrap/css/bootstrap-grid.min.css?rnd=1731837499227">
    <link rel="stylesheet"
        href="https://r.mobirisesite.com/908256/assets/bootstrap/css/bootstrap-reboot.min.css?rnd=1731837499227">
    <link rel="stylesheet" href="https://r.mobirisesite.com/908256/assets/parallax/jarallax.css?rnd=1731837499227">
    <link rel="stylesheet" href="https://r.mobirisesite.com/908256/assets/dropdown/css/style.css?rnd=1731837499227">
    <link rel="stylesheet" href="https://r.mobirisesite.com/908256/assets/socicon/css/styles.css?rnd=1731837499227">
    <link rel="stylesheet" href="https://r.mobirisesite.com/908256/assets/theme/css/style.css?rnd=1731837499227">
    <link rel="stylesheet" href="https://r.mobirisesite.com/908256/assets/recaptcha.css?rnd=1731837499227">
    <link rel="preload"
        href="https://fonts.googleapis.com/css2?family=Roboto+Flex:wght@400;700&display=swap&display=swap" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Roboto+Flex:wght@400;700&display=swap&display=swap">
    </noscript>
    <link rel="stylesheet" href="https://r.mobirisesite.com/908256/assets/css/mbr-additional.css?rnd=1731837499227"
        type="text/css">






    <style>
    .navbar-fixed-top {
        top: auto;
    }

    #mobiriseBanner.container-banner {
        height: 8rem;
        opacity: 1;
        -webkit-animation: 4s linear animationHeight;
        -moz-animation: 4s linear animationHeight;
        -o-animation: 4s linear animationHeight;
        animation: 4s linear animationHeight;
        transition: all 0.5s;
    }

    #mobiriseBanner.container-banner.container-banner-closing {
        pointer-events: none;
        height: 0;
        opacity: 0;
        -webkit-animation: 0.5s linear animationClosing;
        -moz-animation: 0.5s linear animationClosing;
        -o-animation: 0.5s linear animationClosing;
        animation: 0.5s linear animationClosing;
    }

    #mobiriseBanner .banner {
        min-height: 8rem;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: #fff;
        padding: 10px;
        opacity: 1;
        -webkit-animation: 4s linear animationBanner;
        -moz-animation: 4s linear animationBanner;
        -o-animation: 4s linear animationBanner;
        animation: 4s linear animationBanner;
        z-index: 1031;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    #mobiriseBanner .banner p {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        animation: none;
        visibility: visible;
    }

    #mobiriseBanner .buy-license {
        text-decoration: underline;
    }

    #mobiriseBanner .banner .btn {
        margin: 0.3rem 0.5rem;
        animation: none;
        visibility: visible;
    }

    .navbar.opened {
        z-index: 1032;
    }

    @-webkit-keyframes animationBanner {
        0% {
            opacity: 0;
            top: -8rem;
        }

        75% {
            opacity: 0;
            top: -8rem;
        }

        100% {
            opacity: 1;
            top: 0;
        }
    }

    @-moz-keyframes animationBanner {
        0% {
            opacity: 0;
            top: -8rem;
        }

        75% {
            opacity: 0;
            top: -8rem;
        }

        100% {
            opacity: 1;
            top: 0;
        }
    }

    @-o-keyframes animationBanner {
        0% {
            opacity: 0;
            top: -8rem;
        }

        75% {
            opacity: 0;
            top: -8rem;
        }

        100% {
            opacity: 1;
            top: 0;
        }
    }

    @keyframes animationBanner {
        0% {
            opacity: 0;
            top: -8rem;
        }

        75% {
            opacity: 0;
            top: -8rem;
        }

        100% {
            opacity: 1;
            top: 0;
        }
    }

    @-webkit-keyframes animationHeight {
        0% {
            height: 0;
        }

        75% {
            height: 0;
        }

        100% {
            height: 8rem;
        }
    }

    @-moz-keyframes animationHeight {
        0% {
            height: 0;
        }

        75% {
            height: 0;
        }

        100% {
            height: 8rem;
        }
    }

    @-o-keyframes animationHeight {
        0% {
            height: 0;
        }

        75% {
            height: 0;
        }

        100% {
            height: 8rem;
        }
    }

    @keyframes animationHeight {
        0% {
            height: 0;
        }

        75% {
            height: 0;
        }

        100% {
            height: 8rem;
        }
    }

    @-webkit-keyframes animationClosing {
        0% {
            height: 8rem;
            opacity: 1;
        }

        30% {
            height: 8rem;
            opacity: 0.5;
        }

        100% {
            height: 0;
            opacity: 0;
        }
    }

    @-moz-keyframes animationClosing {
        0% {
            height: 8rem;
            opacity: 1;
        }

        30% {
            height: 8rem;
            opacity: 0.5;
        }

        100% {
            height: 0;
            opacity: 0;
        }
    }

    @-o-keyframes animationClosing {
        0% {
            height: 8rem;
            opacity: 1;
        }

        30% {
            height: 8rem;
            opacity: 0.5;
        }

        100% {
            height: 0;
            opacity: 0;
        }
    }

    @keyframes animationClosing {
        0% {
            height: 8rem;
            opacity: 1;
        }

        30% {
            height: 8rem;
            opacity: 0.5;
        }

        100% {
            height: 0;
            opacity: 0;
        }
    }

    @media(max-width: 767px) {
        #mobiriseBanner.container-banner {
            height: 12rem;
        }

        #mobiriseBanner .banner {
            min-height: 12rem;
        }

        @-webkit-keyframes animationBanner {
            0% {
                opacity: 0;
                top: -12rem;
            }

            75% {
                opacity: 0;
                top: -12rem;
            }

            100% {
                opacity: 1;
                top: 0;
            }
        }

        @-moz-keyframes animationBanner {
            0% {
                opacity: 0;
                top: -12rem;
            }

            75% {
                opacity: 0;
                top: -12rem;
            }

            100% {
                opacity: 1;
                top: 0;
            }
        }

        @-o-keyframes animationBanner {
            0% {
                opacity: 0;
                top: -12rem;
            }

            75% {
                opacity: 0;
                top: -12rem;
            }

            100% {
                opacity: 1;
                top: 0;
            }
        }

        @keyframes animationBanner {
            0% {
                opacity: 0;
                top: -12rem;
            }

            75% {
                opacity: 0;
                top: -12rem;
            }

            100% {
                opacity: 1;
                top: 0;
            }
        }

        @-webkit-keyframes animationHeight {
            0% {
                height: 0;
            }

            75% {
                height: 0;
            }

            100% {
                height: 12rem;
            }
        }

        @-moz-keyframes animationHeight {
            0% {
                height: 0;
            }

            75% {
                height: 0;
            }

            100% {
                height: 12rem;
            }
        }

        @-o-keyframes animationHeight {
            0% {
                height: 0;
            }

            75% {
                height: 0;
            }

            100% {
                height: 12rem;
            }
        }

        @keyframes animationHeight {
            0% {
                height: 0;
            }

            75% {
                height: 0;
            }

            100% {
                height: 12rem;
            }
        }

        @-webkit-keyframes animationClosing {
            0% {
                height: 12rem;
                opacity: 1;
            }

            30% {
                height: 12rem;
                opacity: 0.5;
            }

            100% {
                height: 0;
                opacity: 0;
            }
        }

        @-moz-keyframes animationClosing {
            0% {
                height: 12rem;
                opacity: 1;
            }

            30% {
                height: 12rem;
                opacity: 0.5;
            }

            100% {
                height: 0;
                opacity: 0;
            }
        }

        @-o-keyframes animationClosing {
            0% {
                height: 12rem;
                opacity: 1;
            }

            30% {
                height: 12rem;
                opacity: 0.5;
            }

            100% {
                height: 0;
                opacity: 0;
            }
        }

        @keyframes animationClosing {
            0% {
                height: 12rem;
                opacity: 1;
            }

            30% {
                height: 12rem;
                opacity: 0.5;
            }

            100% {
                height: 0;
                opacity: 0;
            }
        }
    }
    </style>
</head>

<body>

    <section data-bs-version="5.1" class="menu menu2 cid-uunpiZsd02" once="menu" id="menu-5-uunpiZsd02">


        <nav class="navbar navbar-dropdown navbar-fixed-top navbar-expand-lg">
            <div class="container">
                <div class="navbar-brand">
                    <span class="navbar-logo">
                        <a href="#">
                            <img src="https://i.ibb.co/yPs6VDz/image-2024-11-24-205553373.png" alt=""
                                style="height: 4.3rem;">
                        </a>
                    </span>
                    <span class="navbar-caption-wrap"><a class="navbar-caption text-black display-4"
                            href="#">หอพักบ้านพุทธชาติ</a></span>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse"
                    data-target="#navbarSupportedContent" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- <ul class="navbar-nav nav-dropdown" data-app-modern-menu="true"> -->
                    <!-- <li class="nav-item">
						<a class="nav-link link text-black display-4" href="#">ลงทะเบียน</a>
					</li>
					<li class="nav-item">
						<a class="nav-link link text-black display-4" href="#" aria-expanded="false">เข้าสู่ระบบ</a>
					</li>
					<li class="nav-item">
						<a class="nav-link link text-black display-4" href="#">ผู้ดูแล</a>
					</li> -->
                    <!-- </ul> -->

                    <div class="navbar-buttons mbr-section-btn">
                        <a class="btn btn-primary display-4" href="public/login.php">ล็อกอิน</a>
                    </div>
                </div>
            </div>
        </nav>
    </section>

    <section data-bs-version="5.1" class="header18 cid-uunpiZuq0K mbr-fullscreen"
        style="background-image: url('https://i.ibb.co/yPs6VDz/image-2024-11-24-205553373.png'); background-size: cover; background-position: center;"
        id="hero-15-uunpiZuq0K">


        <div class="mbr-overlay" style="opacity: 0.5; background-color: rgb(0, 0, 0);"></div>
        <div class="container-fluid">
            <div class="row">
                <div class="content-wrap col-12 col-md-12">
                    <h1 class="mbr-section-title mbr-fonts-style mbr-white mb-4 display-1">
                        <strong>ยินดีต้อนรับ</strong>
                    </h1>

                    <!-- <p class="mbr-fonts-style mbr-text mbr-white mb-4 display-7">ที่นี่คือที่ที่คุณจะเริ่มต้นการผจญภัยใหม่!</p> -->
                    <div class="mbr-section-btn">
                        <a class="btn btn-white-outline display-7" href="public/login.php">เข้าสุู่ระบบ</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section data-bs-version="5.1" class="gallery07 cid-uunpiZuLJ7" id="gallery-16-uunpiZuLJ7">



        <div class="container-fluid gallery-wrapper">
            <div class="row justify-content-center">
                <div class="col-12 content-head">
                    <div class="mbr-section-head mb-5">


                    </div>
                </div>
            </div>
            <div class="grid-container">
                <div class="grid-container-3 moving-left" style="transform: translate3d(-200px, 0px, 0px);">
                    <div class="grid-item">
                        <img src="https://i.ibb.co/yPs6VDz/image-2024-11-24-205553373.png" alt="">
                    </div>
                    <div class="grid-item">
                        <img src="https://i.ibb.co/q0hYSzj/image-2024-11-24-205741429.png" alt="">
                    </div>
                    <div class="grid-item">
                        <img src="https://i.ibb.co/Gd6GRPJ/image-2024-11-24-205814904.png" alt="">
                    </div>
                    <div class="grid-item">
                        <img src="https://i.ibb.co/3kgXfXj/image-2024-11-24-205854713.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    // ดึงจำนวนห้องทั้งหมด
    $queryTotal = "SELECT COUNT(*) as total_rooms FROM rooms";
    $resultTotal = $conn->query($queryTotal);
    $totalRooms = $resultTotal->fetch_assoc()['total_rooms'];

    // ดึงจำนวนห้องที่มีคนอยู่ (status = 'occupied')
    $queryOccupied = "SELECT COUNT(*) as occupied_rooms FROM rooms WHERE status = 'occupied'";
    $resultOccupied = $conn->query($queryOccupied);
    $occupiedRooms = $resultOccupied->fetch_assoc()['occupied_rooms'];

    // ดึงจำนวนห้องว่าง (status = 'available')
    $queryAvailable = "SELECT COUNT(*) as available_rooms FROM rooms WHERE status = 'available'";
    $resultAvailable = $conn->query($queryAvailable);
    $availableRooms = $resultAvailable->fetch_assoc()['available_rooms'];
    ?>

    <section data-bs-version="5.1" class="features023 cid-uunpiZuOK7" id="metrics-1-uunpiZuOK7">
        <div class="container">
            <div class="row content-row justify-content-center">
                <div class="item features-without-image col-12 col-md-6 col-lg-4 item-mb">
                    <div class="item-wrapper">
                        <div class="title mb-2 mb-md-3">
                            <span class="num mbr-fonts-style display-1">
                                <strong><?php echo $totalRooms; ?></strong>
                            </span>
                        </div>
                        <h4 class="card-title mbr-fonts-style display-5">
                            <strong>ห้องทั้งหมด</strong>
                        </h4>
                    </div>
                </div>
                <div class="item features-without-image col-12 col-md-6 col-lg-4 item-mb">
                    <div class="item-wrapper">
                        <div class="title mb-2 mb-md-3">
                            <span class="num mbr-fonts-style display-1">
                                <strong><?php echo $occupiedRooms; ?></strong>
                            </span>
                        </div>
                        <h4 class="card-title mbr-fonts-style display-5">
                            <strong>ห้องที่มีคนอยู่</strong>
                        </h4>
                    </div>
                </div>
                <div class="item features-without-image col-12 col-md-6 col-lg-4 item-mb">
                    <div class="item-wrapper">
                        <div class="title mb-2 mb-md-3">
                            <span class="num mbr-fonts-style display-1">
                                <strong><?php echo $availableRooms; ?></strong>
                            </span>
                        </div>
                        <h4 class="card-title mbr-fonts-style display-5">
                            <strong>ห้องว่าง</strong>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section data-bs-version="5.1" class="list05 cid-uunpiZuvhj" id="faq-3-uunpiZuvhj">


    </section>


    <section data-bs-version="5.1" class="header18 cid-uunpiZvmaw mbr-fullscreen"
        data-bg-video="https://www.youtube.com/embed/QUqhgZjrrsE?autoplay=1&amp;loop=1&amp;playlist=QUqhgZjrrsE&amp;t=20&amp;mute=1&amp;playsinline=1&amp;controls=0&amp;showinfo=0&amp;autohide=1&amp;allowfullscreen=true&amp;mode=transparent"
        id="video-5-uunpiZvmaw">


        <div class="mbr-overlay" style="opacity: 0.3; background-color: rgb(0, 0, 0);"></div>
        <div class="container-fluid">
            <div class="row">
            </div>
        </div>
    </section>

    <section data-bs-version="5.1" class="people03 cid-uunpiZv0Ag" id="team-1-uunpiZv0Ag">

        <?php
            require ("assets\assets\calendar.php");
        ?>

    </section>

    <section data-bs-version="5.1" class="image02 cid-uunpiZwBR7 mbr-fullscreen mbr-parallax-background"
        id="image-13-uunpiZwBR7">
        <div class="container">
            <div class="row">

            </div>
        </div>
    </section>

    <section data-bs-version="5.1" class="header14 cid-uunpiZwk0T mbr-parallax-background"
        id="call-to-action-1-uunpiZwk0T">

    </section>

    <section data-bs-version="5.1" class="social4 cid-uunpiZymln" id="follow-us-1-uunpiZymln">

    </section>

    <section data-bs-version="5.1" class="form5 cid-uunpiZy2fV" id="contact-form-2-uunpiZy2fV">

    </section>

    <section data-bs-version="5.1" class="contacts02 map1 cid-uunpiZys8q" id="contacts-2-uunpiZys8q">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 content-head">
                    <div class="mbr-section-head mb-5">
                        <h3 class="mbr-section-title mbr-fonts-style align-center mb-0 display-2">
                            <strong>ข้อมูลติดต่อ</strong>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="card col-12 col-md-12 col-lg-6">
                    <div class="card-wrapper">
                        <div class="text-wrapper">

                            <ul class="list mbr-fonts-style display-7">
                                <li class="mbr-text item-wrap">
                                    โทร:
                                    <a href="tel:012-345-6789" class="text-black"> 06-5329-9452
                                    </a>
                                </li>
                                <li class="mbr-text item-wrap">
                                    ที่อยู่:
                                    Nakhon Pathom Thailand
                                </li>
                                <li class="mbr-text item-wrap">
                                    เวลาทำการ:
                                    จันทร์ - ศุกร์: 9:00 - 17:00
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="map-wrapper col-md-12 col-lg-6">
                    <div class="google-map"><iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d691.4646695002218!2d100.0227830309071!3d13.836666137040652!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e2e55b88496abd%3A0x9fb2e98eac2b7d7a!2z4LiB4Lit4Lil4LmM4LifIOC4iuC4tOC5ieC4meC4quC5iOC4p-C4meC4q-C4oeC4uQ!5e0!3m2!1sth!2sth!4v1732452703157!5m2!1sth!2sth"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe></div>
                </div>
            </div>
        </div>
    </section>

    <section data-bs-version="5.1" class="footer3 cid-uunpiZzWT8" once="footers" id="footer-3-uunpiZzWT8">

        <div class="container">
            <div class="row">
                <div class="row-links">
                    <p class="mbr-fonts-style copyright display-7">หอพักบ้านพุทธชาติ นครปฐม</p>
                </div>

                <div class="col-12 mt-5">
                    <p class="mbr-fonts-style copyright display-7">© 2024 ทุกสิทธิ์สงวนไว้</p>
                </div>
                <div class="col-12 mt-5">
                    <p class="mbr-fonts-style copyright display-7">ผู้จัดทำ</p>
                    <p class="mbr-fonts-style copyright display-7">654230004</p>
                    <p class="mbr-fonts-style copyright display-7">654230005</p>
                    <p class="mbr-fonts-style copyright display-7">654230017</p>
                    <p class="mbr-fonts-style copyright display-7">654230018</p>
                </div>
            </div>
        </div>
    </section>


    <script src="https://r.mobirisesite.com/908256/assets/web/assets/jquery/jquery.min.js?rnd=1731837499227"></script>
    <script src="https://r.mobirisesite.com/908256/assets/bootstrap/js/bootstrap.bundle.min.js?rnd=1731837499227">
    </script>
    <script src="https://r.mobirisesite.com/908256/assets/parallax/jarallax.js?rnd=1731837499227"></script>
    <script src="https://r.mobirisesite.com/908256/assets/smoothscroll/smooth-scroll.js?rnd=1731837499227"></script>
    <script src="https://r.mobirisesite.com/908256/assets/ytplayer/index.js?rnd=1731837499227"></script>
    <script src="https://r.mobirisesite.com/908256/assets/dropdown/js/navbar-dropdown.js?rnd=1731837499227"></script>
    <script src="https://r.mobirisesite.com/908256/assets/vimeoplayer/player.js?rnd=1731837499227"></script>
    <script src="https://r.mobirisesite.com/908256/assets/scrollgallery/scroll-gallery.js?rnd=1731837499227"></script>
    <script src="https://r.mobirisesite.com/908256/assets/theme/js/script.js?rnd=1731837499227"></script>
    <script src="https://r.mobirisesite.com/908256/assets/formoid.min.js?rnd=1731837499227"></script>




</body>

</html>