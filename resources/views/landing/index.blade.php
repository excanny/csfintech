<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title>{{ env('APP_NAME') }}</title>
    <meta name="title" content="SageCloud">
    <meta name="description"
          content="SageCloud is a fintech and VAS leader, providing deep expertise in building a wide range of API infrastructures and complete digital financial solutions that reduce cost, enhance the customer experience, and increase revenue for African Businesses.">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://sagecloud.ng/">
    <meta property="og:title" content="SageCloud NG">
    <meta property="og:description"
          content="SageCloud is a fintech and VAS leader, providing deep expertise in building a wide range of API infrastructures and complete digital financial solutions that reduce cost, enhance the customer experience, and increase revenue for African Businesses.">
    <meta property="og:image" content="">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://sagecloud.ng/">
    <meta property="twitter:title" content="SageCloud NG">
    <meta property="twitter:description"
          content="SageCloud is a fintech and VAS leader, providing deep expertise in building a wide range of API infrastructures and complete digital financial solutions that reduce cost, enhance the customer experience, and increase revenue for African Businesses.">
    <meta property="twitter:image" content="">

{{--    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/landing/icons/apple-icon-57x57.png') }}">--}}
{{--    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/landing/icons/apple-icon-60x60.png') }}">--}}
{{--    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/landing/icons/apple-icon-72x72.png') }}">--}}
{{--    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/landing/icons/apple-icon-76x76.png') }}">--}}
{{--    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/landing/icons/apple-icon-114x114.png') }}">--}}
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/sage_icon.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/sage_icon.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/sage_icon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/sage_icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/sage_icon.png') }}">
    <link rel="manifest" href="{{ asset('assets/landing/icons/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('assets/landing/icons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/landing/stylesheet/main.css') }}">
    <title>SageCloud NG</title>
</head>

<body data-spy="scroll" data-target="#navbar" data-offset="150">
<main>
    <!-- navbar -->
    <nav id="navbar" class="navbar navbar-expand-md navbar-dark fixed-top" data-aos="fade">
    <div style="position: absolute;z-index: 3;left:73%;right:0;top:7rem">
        @include('partials.message')
    </div>
    <div class="container d-flex align-items-center">
        <a class="navbar-brand mr-5" href="#"><img src="{{ asset('images/sage_cloud.png') }}" width="180"></a>
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <img src="{{ asset('assets/landing/img/ham.svg') }}" alt="">
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav pb-5 pb-md-0">
                <a class="nav-link" href="#about-us">About Us</a>
                <a class="nav-link" href="#wwd">What We Do</a>
                <a class="nav-link" href="#why-us">Why Us</a>
                <a class="nav-link" href="#contact-us">Contact Us</a>
                <a class="nav-link" href="#contact-us">Support</a>
{{--                <a class="nav-link" href="{{ uri(env('API_DOC_URL')) }}">API Docs</a>--}}
                <a class="nav-link" href="https://lawrecks.gitbook.io/sagecloud/">API Docs</a>
            @if(auth()->guest())
                    <a class="nav-link  cta" href="{{ route('login') }}">Sign In</a>
                    <a class="nav-link  cta" href="{{ route('register') }}">Become a Merchant</a>
                @else
                    <a class="nav-link  cta" href="{{ route('dashboard') }}">Account</a>
                @endif
            </div>
        </div>
    </div>
    </nav>
    <!-- navbar end -->

    <!-- header -->
    <header id="header" class="header d-flex" data-aos="fade">
        <img class="wave" src="{{ asset('assets/landing/img/header-wave-2.png') }}" alt="">
        <div class="container d-flex outer">
            <div class="inner text-center text-lg-left">
                <h1>AGILE SOLUTIONS THAT TRANSFORM YOU</h1>
                <p>SageCloud is a value-added service (VAS) solution hub providing deep expertise in delivering
                    a wide range of APIs infrastructure and complete digital financial solutions that reduce cost,
                    enhance users experience, and increase revenue for African businesses.</p>
                <div class="cta">
                    <a href="#about-us">Learn More <svg width="30" class="img-fluid" viewBox="0 0 30 30" fill="none"
                                                        xmlns="https://www.w3.org/2000/svg">
                            <g id="arrow-right">
                                <path id="Vector" d="M6.25 15H23.75" stroke="#FDFDFD" stroke-width="2" stroke-linecap="round"
                                      stroke-linejoin="round" />
                                <path id="Vector_2" d="M15 6.25L23.75 15L15 23.75" stroke="#FDFDFD" stroke-width="2"
                                      stroke-linecap="round" stroke-linejoin="round" />
                            </g>
                        </svg></a>
                </div>
            </div>
            <div class="img">
                <img src="{{ asset('assets/landing/img/black-map.svg') }}" alt="" class="img-fluid">
            </div>
        </div>
    </header>
    <!-- header end -->

    <!-- company img -->
{{--    <div id="companies">--}}
{{--        <div class="container" data-aos="fade">--}}
{{--            <img src="{{ asset('assets/landing/img/comp.svg') }}" alt="" class="img-fluid">--}}
{{--        </div>--}}
{{--    </div>--}}
    <!-- company img end -->

    <!-- abouts-us -->
    <section id="about-us">
        <div class="container">
            <div class="inner">
                <div class="left p-3" data-aos="zoom-out" data-aos-delay="500">
                    <svg class="img-fluid" viewBox="0 0 542 591" fill="none" xmlns="https://www.w3.org/2000/svg">
                        <g id="abt-us-illus">
                            <g id="g12">
                                <g id="g14">
                                    <g id="g20">
                                        <g id="g34">
                                            <g id="g32" opacity="0.699997">
                                                <g id="g30" opacity="0.699997">
                                                    <path id="path28" opacity="0.699997" d="M14.0398 183.961C14.0398 183.961 -23.1295 279.944 29.1025 376.632C81.3331 473.321 188.941 524.368 275.061 568.03C361.181 611.692 450.609 590.429 493.106 518.913C535.602 447.397 477.313 396.341 477.244 294.354C477.176 192.368 492.506 165.366 437.293 79.2025C382.08 -6.96021 246.548 -26.0309 144.094 37.8745C41.6385 101.78 14.0398 183.961 14.0398 183.961Z" fill="#E0E0E0"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="g36">
                                        <path id="path38" d="M22.0586 212.729C69.2812 165.524 109.267 205.128 126.115 141.74C164.172 -1.43994 321.999 -0.0452648 342.753 101.804C380.149 285.331 543.005 168.78 541.795 300.227C540.503 440.567 437.3 549.869 252.204 560.793C86.2666 570.587 -56.1828 290.943 22.0586 212.729Z" fill="#001E89"/>
                                    </g>
                                </g>
                            </g>
                            <g id="g232">
                                <g id="g234">
                                    <g id="g240">
                                        <path id="path242" d="M193.84 212.589C188.77 208.06 183.518 210.047 180.829 211.637C180.288 208.56 178.37 203.273 171.634 202.367C164.32 201.393 158.337 208.531 160.296 218.464C161.714 225.611 166.278 232.155 168.728 235.253C169.018 235.625 169.292 235.949 169.516 236.211C169.86 236.211 170.284 236.208 170.757 236.187C174.702 236.033 182.642 235.245 188.982 231.671C197.814 226.704 199.336 217.513 193.84 212.589Z" stroke="#0197F6" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g244">
                                        <path id="path246" d="M165.368 240.854C165.368 240.854 165.012 241.518 164.53 242.663" stroke="#0197F6" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g248">
                                        <path id="path250" d="M163.208 246.36C161.259 252.929 160.099 263.861 170.566 270.533C196.858 287.297 205.706 247.753 185.572 251.308C171.774 253.744 186.278 290.152 207.864 283.09" stroke="#0197F6" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="3.93 3.93"/>
                                    </g>
                                    <g id="g252">
                                        <path id="path254" d="M209.712 282.386C210.306 282.126 210.906 281.834 211.509 281.506" stroke="#0197F6" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                </g>
                            </g>
                            <g id="g256">
                                <g id="g258">
                                    <g id="g264">
                                        <path id="path266" d="M177.404 523.973C177.404 523.973 162.564 534.072 156.585 533.398C155.842 533.325 155.26 533.09 154.826 532.654C150.926 528.63 147.794 521.568 147.794 521.568C147.794 521.568 149.649 518.99 155.381 525.824L159.024 522.481L151.505 514.926C151.505 514.926 155.737 511.564 162.921 515.84C162.682 515.326 158.044 505.237 161.541 491.501L144.024 474.628L159.529 461.118L160.724 460.08C160.724 460.08 161.702 461.042 163.28 462.534C164.808 463.984 166.91 465.92 169.196 467.925C174.665 472.729 181.278 477.937 184.145 477.889C189.45 477.784 177.404 523.973 177.404 523.973Z" fill="white"/>
                                    </g>
                                </g>
                            </g>
                            <g id="g268">
                                <g id="g270">
                                    <g id="g276">
                                        <g id="g290">
                                            <g id="g288" opacity="0.399994">
                                                <g id="g286" opacity="0.399994">
                                                    <path id="path284" opacity="0.399994" d="M165.928 517.356C165.928 517.356 157.475 526.073 156.586 533.398C155.842 533.325 155.259 533.09 154.826 532.654C150.926 528.63 147.794 521.568 147.794 521.568C147.794 521.568 149.65 518.99 155.382 525.824L159.024 522.481L151.504 514.926C151.504 514.926 155.736 511.564 162.922 515.84C162.682 515.326 158.043 505.237 161.54 491.501L144.023 474.628L159.528 461.118L163.28 462.534C164.807 463.984 166.911 465.92 169.196 467.925C165.915 472.741 158.887 482.685 158.887 482.685L164.959 490.924C164.959 490.924 161.278 511.694 165.928 517.356Z" fill="#0A263E"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                            <g id="g292">
                                <g id="g294">
                                    <g id="g300">
                                        <path id="path302" d="M177.404 523.973C177.404 523.973 162.564 534.072 156.585 533.398C155.842 533.325 155.26 533.09 154.826 532.654C150.926 528.63 147.794 521.568 147.794 521.568C147.794 521.568 149.649 518.99 155.381 525.824L159.024 522.481L151.505 514.926C151.505 514.926 155.737 511.564 162.921 515.84C162.682 515.326 158.044 505.237 161.541 491.501L144.024 474.628L159.529 461.118L160.724 460.08C160.724 460.08 161.702 461.042 163.28 462.534C164.808 463.984 166.91 465.92 169.196 467.925C174.665 472.729 181.278 477.937 184.145 477.889C189.45 477.784 177.404 523.973 177.404 523.973Z" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g304">
                                        <path id="path306" d="M159.025 522.485C159.025 522.485 160.316 518.433 162.937 515.851" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g308">
                                        <path id="path310" d="M435.962 406.395C433.857 409.593 430.99 412.612 427.561 415.451C427.561 415.451 427.547 415.449 427.546 415.463C400.254 438.06 337.329 449.227 337.329 449.227C337.329 449.227 335.226 452.387 331.985 456.885C326.165 464.961 316.635 477.364 308.867 483.581H308.853C305.575 486.216 302.602 487.741 300.362 487.331L312.162 530.937L317.446 549.867C317.446 549.867 263.191 573.537 170.385 546.465C170.385 546.465 169.335 534.355 170.121 531.769C170.157 531.651 170.195 531.521 170.246 531.391C171.11 529.308 173.881 526.817 173.881 526.817C173.881 526.817 173.363 519.031 173.098 511.103C172.889 504.945 172.834 498.707 173.287 495.935C173.946 491.935 176.873 482.273 178.342 477.524C178.866 475.84 179.207 474.771 179.207 474.771L174.589 431.523L173.259 419.067C173.259 419.067 173.238 418.837 173.191 418.42C173.186 418.324 173.181 418.231 173.163 418.109L172.435 411.428L148.566 418.719C148.566 418.719 149.838 421.511 151.539 425.435C154.675 432.605 159.285 443.535 160.311 447.86C160.39 448.213 160.495 448.556 160.615 448.9C162.831 455.207 171.235 460.933 171.235 460.933L148.634 487.351L141.95 482.687C141.95 482.687 140.979 483.451 139.258 484.38C134.863 486.765 125.643 490.271 115.577 484.919C111.897 482.96 108.11 479.829 104.407 475.033C86.5902 451.969 78.9395 433.181 81.4448 411.071C83.9528 388.948 183.239 320.939 202.941 321.644L232.246 328.119C232.877 327.593 234.593 326.236 235.911 326.303C241.239 326.576 241.379 332.767 241.379 332.767L307.051 343.427C307.051 343.427 313.857 340.021 315.043 340.077C317.947 340.219 319.189 342.715 319.189 342.715C319.189 342.715 336.203 332.527 341.683 334.897C359.673 342.66 449.255 386.192 435.962 406.395Z" fill="#0197F6"/>
                                    </g>
                                    <g id="g312">
                                        <g id="g326">
                                            <g id="g324" opacity="0.199997">
                                                <g id="g322" opacity="0.199997">
                                                    <path id="path320" opacity="0.199997" d="M207.818 514.326C207.818 514.326 197.624 526.848 179.397 529.74C175.941 530.29 172.9 530.844 170.246 531.391C171.11 529.307 173.881 526.818 173.881 526.818C173.881 526.818 173.364 519.03 173.098 511.103C180.809 516.143 194.232 522.01 207.818 514.326Z" fill="black"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="g328">
                                        <g id="g342">
                                            <g id="g340" opacity="0.199997">
                                                <g id="g338" opacity="0.199997">
                                                    <path id="path336" opacity="0.199997" d="M178.342 477.524C178.867 475.84 179.207 474.772 179.207 474.772L174.589 431.522C181.49 441.42 206.106 475.152 223.079 478.79C240.816 482.586 193.384 489.301 178.342 477.524Z" fill="black"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="g344">
                                        <g id="g358">
                                            <g id="g356" opacity="0.199997">
                                                <g id="g354" opacity="0.199997">
                                                    <path id="path352" opacity="0.199997" d="M312.163 530.938C293.632 528.83 267.417 524.158 264.795 514.46C260.715 499.306 280.583 490.219 308.853 483.58C305.576 486.216 302.603 487.742 300.361 487.331L312.163 530.938Z" fill="black"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="g360">
                                        <g id="g374">
                                            <g id="g372" opacity="0.199997">
                                                <g id="g370" opacity="0.199997">
                                                    <path id="path368" opacity="0.199997" d="M427.547 415.462C400.253 438.06 337.329 449.226 337.329 449.226C337.329 449.226 335.227 452.386 331.984 456.885C326.668 444.454 319.004 418.749 327.964 387.157C327.964 387.157 332.815 413.41 352.936 419.648C352.936 419.648 337.535 408.176 342.908 380.56C342.908 380.56 348.16 413.065 382.359 422.038C398.993 426.4 415.239 421.566 427.547 415.462Z" fill="black"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="g376">
                                        <g id="g390">
                                            <g id="g388" opacity="0.199997">
                                                <g id="g386" opacity="0.199997">
                                                    <path id="path384" opacity="0.199997" d="M173.192 418.42L172.436 411.429L148.567 418.72C148.567 418.72 149.837 421.51 151.539 425.436C142.412 424.493 125.095 432.972 122.073 423.736C118.431 412.592 140.847 416.433 140.847 416.433L161.992 404.132C157.991 387.032 168.172 374.173 168.172 374.173L170.105 386.104C171.033 363.421 179.904 351.016 179.904 351.016C172.524 368.952 172.728 413.097 173.192 418.42Z" fill="black"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="g392">
                                        <g id="g406">
                                            <g id="g404" opacity="0.199997">
                                                <g id="g402" opacity="0.199997">
                                                    <path id="path400" opacity="0.199997" d="M171.236 460.934L148.634 487.35L141.95 482.686C141.95 482.686 140.98 483.451 139.258 484.38C134.864 486.766 125.644 490.271 115.577 484.918C122.902 474.784 138.026 459.867 156.032 454.107C156.032 454.107 148.624 464.942 144.024 474.628L148.668 479.206C148.668 479.206 160.256 465.994 171.236 460.934Z" fill="black"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="g408">
                                        <g id="g422">
                                            <g id="g420" opacity="0.199997">
                                                <g id="g418" opacity="0.199997">
                                                    <path id="path416" opacity="0.199997" d="M226.136 344.585C226.129 346.91 224.664 373.342 243.889 380.326C263.115 387.309 280.212 375.465 280.212 375.465C280.212 375.465 250.723 403.901 232.783 388.705C214.844 373.508 226.136 344.585 226.136 344.585Z" fill="black"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="g424">
                                        <path id="path426" d="M170.385 546.46C170.385 546.46 169.342 534.349 170.115 531.769C170.763 529.608 173.877 526.82 173.877 526.82C173.877 526.82 172.245 502.268 173.289 495.94C174.185 490.517 179.211 474.765 179.211 474.765L172.439 411.428L148.569 418.723C148.569 418.723 158.731 441.173 160.305 447.864C161.878 454.553 171.238 460.937 171.238 460.937L148.63 487.347L141.951 482.692C141.951 482.692 122.226 498.113 104.407 475.04C86.5874 451.964 78.9407 433.183 81.4434 411.065C83.9461 388.949 183.246 320.937 202.946 321.639L232.247 328.116C232.882 327.595 234.594 326.236 235.913 326.304C241.242 326.576 241.381 332.768 241.381 332.768L307.047 343.429C307.047 343.429 313.862 340.021 315.046 340.081C317.95 340.225 319.194 342.711 319.194 342.711C319.194 342.711 336.201 332.528 341.689 334.895C359.67 342.655 449.251 386.192 435.961 406.393C417.094 435.072 337.333 449.223 337.333 449.223C337.333 449.223 310.803 489.229 300.357 487.324L317.443 549.871" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g428">
                                        <g id="path430">
                                            <path d="M366.565 410.772C396.701 430.682 420.99 426.994 440.006 401.38C440.006 401.38 455.836 380.976 454.714 358.958C453.593 336.939 448.334 265.136 379.73 283.496C368.44 286.518 342.085 321.811 339.865 331.51C338.849 335.943 328.269 358.507 318.001 381.86" fill="#0197F6"/>
                                            <path d="M366.565 410.772C396.701 430.682 420.99 426.994 440.006 401.38C440.006 401.38 455.836 380.976 454.714 358.958C453.593 336.939 448.334 265.136 379.73 283.496C368.44 286.518 342.085 321.811 339.865 331.51C338.849 335.943 328.269 358.507 318.001 381.86" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                    </g>
                                    <g id="g432">
                                        <path id="path434" d="M285.03 199.874C285.03 199.874 304.223 196.961 317.565 208.903C334.431 223.999 353.362 264.691 326.393 332.134C326.393 332.134 286.559 305.686 218.574 323.374C218.574 323.374 224.427 307.551 214.151 257.755C209.505 235.239 221.187 183.327 285.03 199.874Z" fill="#1A2E35"/>
                                    </g>
                                    <g id="g436">
                                        <path id="path438" d="M255.922 369.111C284.335 373.718 314.53 342.264 314.53 342.264C314.53 342.264 299.081 340.36 297.697 333.107C293.265 310.226 311.091 281.963 311.091 281.963C311.091 281.963 242.099 283.872 254.165 299.483C256.694 302.759 258.437 305.898 259.621 308.84C263.803 319.118 260.902 326.871 258.675 328.762C258.515 328.899 258.331 329.01 258.174 329.096C253.999 331.004 236.853 328.163 236.853 328.163C236.853 328.163 229.998 364.908 255.922 369.111Z" fill="white"/>
                                    </g>
                                    <g id="g440">
                                        <path id="path442" d="M255.922 369.111C284.335 373.718 314.53 342.264 314.53 342.264C314.53 342.264 299.081 340.36 297.697 333.107C293.265 310.226 311.091 281.963 311.091 281.963C311.091 281.963 242.099 283.872 254.165 299.483C256.694 302.759 258.437 305.898 259.621 308.84C263.803 319.118 260.902 326.871 258.675 328.762C258.515 328.899 258.331 329.01 258.174 329.096C253.999 331.004 236.853 328.163 236.853 328.163C236.853 328.163 229.998 364.908 255.922 369.111Z" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g444">
                                        <path id="path446" d="M304.747 350.973C304.747 350.973 291.647 356.214 272.453 354.655L263.807 358.89" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g448">
                                        <path id="path450" d="M255.203 358.192L254.569 352.517C254.569 352.517 240.419 348.086 236.038 339.67" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g452">
                                        <path id="path454" d="M258.676 328.762C279.805 334.286 289.726 307.187 289.726 307.187C289.726 307.187 272.822 302.147 259.621 308.841C263.804 319.118 260.902 326.871 258.676 328.762Z" fill="#1A2E35"/>
                                    </g>
                                    <g id="g456">
                                        <g id="path458">
                                            <path d="M312.311 267.707C317.45 238.855 311.737 204.231 267.825 207.098C236.451 209.149 236.709 243.547 238.786 253.814C242.279 271.082 237.273 277.882 242.653 292.387C246.519 302.811 255.503 314.469 266.075 318.837C272.579 321.523 298.911 303.046 300.858 294.597" fill="white"/>
                                            <path d="M312.311 267.707C317.45 238.855 311.737 204.231 267.825 207.098C236.451 209.149 236.709 243.547 238.786 253.814C242.279 271.082 237.273 277.882 242.653 292.387C246.519 302.811 255.503 314.469 266.075 318.837C272.579 321.523 298.911 303.046 300.858 294.597" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                    </g>
                                    <g id="g460">
                                        <path id="path462" d="M257.131 259.201C256.867 262.44 256.487 270.464 249.627 276.383C245.19 280.212 255.376 284.129 255.376 284.129" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g464">
                                        <path id="path466" d="M249.841 247.754C249.841 247.754 240.15 243.033 233.602 250.823" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g468">
                                        <path id="path470" d="M266.096 247.142C266.096 247.142 275.46 243.783 286.812 247.957" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g472">
                                        <path id="path474" d="M276.162 260.763C276.463 263.545 278.336 265.627 280.347 265.409C282.356 265.192 283.743 262.759 283.442 259.975C283.14 257.192 281.267 255.111 279.256 255.328C277.246 255.545 275.86 257.979 276.162 260.763Z" fill="#1A2E35"/>
                                    </g>
                                    <g id="g476">
                                        <path id="path478" d="M255.855 293.246C268.042 291.768 277.527 285.33 277.527 285.33C277.527 285.33 278.431 297.436 268.294 299.952C260.519 301.884 257.507 293.009 257.507 293.009" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g480">
                                        <path id="path482" d="M284.354 218.092C284.354 218.092 281.576 252.281 311.758 264.703L325.896 231.84L304.506 208.148L272.276 198.837C224.128 201.84 203.397 231.763 230.132 311.472C230.132 311.472 222.656 216.536 284.354 218.092Z" fill="#1A2E35"/>
                                    </g>
                                    <g id="g484">
                                        <g id="path486">
                                            <path d="M305.159 266.154C304.743 262.311 314.461 252.523 317.751 258.805C323.357 269.509 319.273 278.842 313.475 282.498C310.877 284.135 307.588 287.202 305.275 284.309" fill="white"/>
                                            <path d="M305.159 266.154C304.743 262.311 314.461 252.523 317.751 258.805C323.357 269.509 319.273 278.842 313.475 282.498C310.877 284.135 307.588 287.202 305.275 284.309" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                        </g>
                                    </g>
                                    <g id="g488">
                                        <path id="path490" d="M246.777 262.035C247.079 264.819 248.952 266.899 250.963 266.681C252.972 266.464 254.359 264.031 254.057 261.248C253.756 258.464 251.883 256.384 249.872 256.601C247.861 256.819 246.476 259.252 246.777 262.035Z" fill="#1A2E35"/>
                                    </g>
                                    <g id="g492">
                                        <g id="g542">
                                            <g id="g540" opacity="0.199997">
                                                <g id="g502" opacity="0.199997">
                                                    <path id="path500" opacity="0.199997" d="M225.862 256.422C225.501 258.742 225.254 261.081 225.111 263.425" stroke="white" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <g id="g506" opacity="0.199997">
                                                    <path id="path504" opacity="0.199997" d="M279.353 211.872C254.961 211.228 232.294 226.776 226.898 251.189" stroke="white" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <g id="g510" opacity="0.199997">
                                                    <path id="path508" opacity="0.199997" d="M274.997 204.903C274.997 204.903 239.824 196.629 226.635 219.961" stroke="white" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <g id="g514" opacity="0.199997">
                                                    <path id="path512" opacity="0.199997" d="M303.534 245.549C305.686 248.538 308.184 251.302 311.116 253.544" stroke="white" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <g id="g518" opacity="0.199997">
                                                    <path id="path516" opacity="0.199997" d="M289.583 209.46C291.469 220.454 295.089 231.619 300.742 241.25" stroke="white" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <g id="g522" opacity="0.199997">
                                                    <path id="path520" opacity="0.199997" d="M324.927 315.274C324.302 318.075 323.568 320.852 322.735 323.598" stroke="white" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <g id="g526" opacity="0.199997">
                                                    <path id="path524" opacity="0.199997" d="M322.893 268.793C328.6 281.462 328.208 297.225 325.865 310.559" stroke="white" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <g id="g530" opacity="0.199997">
                                                    <path id="path528" opacity="0.199997" d="M318.703 290.856C318.703 290.856 320.451 304.994 308.787 316.925" stroke="white" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <g id="g534" opacity="0.199997">
                                                    <path id="path532" opacity="0.199997" d="M302.484 213.54C308.47 221.633 312.911 230.309 319.847 237.7C327.151 245.48 334.454 254.396 335.824 265.357" stroke="white" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                                <g id="g538" opacity="0.199997">
                                                    <path id="path536" opacity="0.199997" d="M294.053 205.128C295.771 206.242 297.331 207.586 298.747 209.064" stroke="white" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                    <g id="g544">
                                        <path id="path546" d="M414.387 392.87C413.692 393.027 412.987 393.143 412.269 393.246C393.389 395.939 372.213 380.293 365.073 361.493C359.429 346.654 364.369 329.811 367.055 322.606C367.908 320.346 368.524 319.033 368.524 319.033C379.904 302.767 446.105 334.579 446.873 352.114C447.644 369.633 433.781 388.626 414.387 392.87Z" fill="#1A2E35"/>
                                    </g>
                                    <g id="g548">
                                        <path id="path550" d="M414.387 392.87C413.692 393.027 412.987 393.143 412.269 393.246C393.389 395.939 372.213 380.293 365.073 361.493C359.429 346.654 364.369 329.811 367.055 322.606C367.908 320.346 368.524 319.033 368.524 319.033C379.904 302.767 446.105 334.579 446.873 352.114C447.644 369.633 433.781 388.626 414.387 392.87Z" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g552">
                                        <path id="path554" d="M435.096 354.487C435.096 354.487 431.335 382.342 412.27 393.246C393.39 395.939 372.214 380.293 365.072 361.493C359.43 346.654 364.37 329.811 367.055 322.606C367.056 322.591 367.068 322.593 367.068 322.593C389.374 309.865 435.096 354.487 435.096 354.487Z" fill="white"/>
                                    </g>
                                </g>
                            </g>
                            <g id="g556">
                                <g id="g558">
                                    <g id="g564">
                                        <g id="g578">
                                            <g id="g576" opacity="0.399994">
                                                <g id="g574" opacity="0.399994">
                                                    <path id="path572" opacity="0.399994" d="M435.096 354.487C435.096 354.487 431.335 382.342 412.27 393.246C393.39 395.939 372.214 380.293 365.072 361.493C359.43 346.654 364.37 329.811 367.055 322.606C367.056 322.591 367.068 322.593 367.068 322.593C389.374 309.865 435.096 354.487 435.096 354.487Z" fill="#0A263E"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                            <g id="g580">
                                <g id="g582">
                                    <g id="g588">
                                        <path id="path590" d="M435.096 354.487C435.096 354.487 431.335 382.342 412.27 393.246C393.39 395.939 372.214 380.293 365.072 361.493C359.43 346.654 364.37 329.811 367.055 322.606C367.056 322.591 367.068 322.593 367.068 322.593C389.374 309.865 435.096 354.487 435.096 354.487Z" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g592">
                                        <path id="path594" d="M357.04 369.156C357.04 369.156 378.535 400.01 403.634 402.578C428.735 405.147 444.788 384.004 445.831 368.059" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="phone-palm">
                                        <g id="Group 22">
                                            <g id="g596">
                                                <path id="path598" d="M344.948 229.889C346.84 224.6 360.399 226.375 364.697 242.863C368.993 259.352 385.959 299.403 385.959 299.403C385.959 299.403 453.331 200.26 465.417 196.439C477.503 192.617 472.713 209.796 456.579 233.635C456.579 233.635 471.692 230.972 475.659 233.645C479.627 236.319 477.696 252.324 479.809 257.936C483.351 267.341 475.477 269.328 475.477 269.328C475.477 269.328 481.673 269.273 486.001 273.328C490.328 277.384 488.157 294.153 489.393 298.895C490.629 303.637 482.368 307.647 482.368 307.647C482.368 307.647 502.235 318.481 497.749 321.193C490.187 325.763 485.869 321.081 485.869 321.081C485.869 321.081 455.353 359.417 432.335 367.948C409.316 376.479 364.5 342.557 359.684 324.079C354.867 305.599 360.616 306.739 356.963 275.989C353.309 245.24 344.948 229.889 344.948 229.889Z" fill="white"/>
                                            </g>
                                            <g id="g600">
                                                <path id="path602" d="M344.948 229.889C346.84 224.6 360.399 226.375 364.697 242.863C368.993 259.352 385.959 299.403 385.959 299.403C385.959 299.403 453.331 200.26 465.417 196.439C477.503 192.617 472.713 209.796 456.579 233.635C456.579 233.635 471.692 230.972 475.659 233.645C479.627 236.319 477.696 252.324 479.809 257.936C483.351 267.341 475.477 269.328 475.477 269.328C475.477 269.328 481.673 269.273 486.001 273.328C490.328 277.384 488.157 294.153 489.393 298.895C490.629 303.637 482.368 307.647 482.368 307.647C482.368 307.647 502.235 318.481 497.749 321.193C490.187 325.763 485.869 321.081 485.869 321.081C485.869 321.081 455.353 359.417 432.335 367.948C409.316 376.479 364.5 342.557 359.684 324.079C354.867 305.599 360.616 306.739 356.963 275.989C353.309 245.24 344.948 229.889 344.948 229.889Z" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                        </g>
                                        <g id="Group 23">
                                            <g id="g604">
                                                <path id="path606" d="M443.236 169.005L354.477 192.137C351.324 192.959 349.434 196.181 350.257 199.333L392.208 360.305C393.03 363.457 396.252 365.347 399.405 364.525L488.164 341.392C491.317 340.571 493.206 337.349 492.384 334.197L450.43 173.225C449.609 170.073 446.388 168.184 443.236 169.005Z" fill="white"/>
                                            </g>
                                            <g id="g608">
                                                <path id="path610" d="M443.236 169.005L354.477 192.137C351.324 192.959 349.434 196.181 350.257 199.333L392.208 360.305C393.03 363.457 396.252 365.347 399.405 364.525L488.164 341.392C491.317 340.571 493.206 337.349 492.384 334.197L450.43 173.225C449.609 170.073 446.388 168.184 443.236 169.005Z" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                            <g id="g636">
                                                <path id="path638" d="M447.759 174.848L489.368 334.504C489.854 336.371 488.735 338.276 486.87 338.763L399.248 361.597C397.383 362.085 395.476 360.967 394.991 359.1L353.38 199.445C352.895 197.579 354.012 195.672 355.879 195.185L373.867 190.497C375.051 190.189 376.302 190.649 377.006 191.652L378.304 193.505C379.007 194.507 380.258 194.967 381.442 194.657L420.93 184.367C422.115 184.057 422.982 183.045 423.106 181.828L423.336 179.576C423.459 178.359 424.327 177.347 425.511 177.039L443.5 172.349C445.366 171.864 447.272 172.983 447.759 174.848Z" fill="#FF7A00"/>
                                            </g>
                                            <g id="g612">
                                                <path id="path614" d="M392.384 187.47C392.586 188.242 392.123 189.03 391.352 189.231C390.579 189.432 389.791 188.97 389.59 188.198C389.388 187.426 389.851 186.636 390.623 186.436C391.395 186.235 392.184 186.698 392.384 187.47Z" fill="#FF7A00"/>
                                            </g>
                                            <g id="g620">
                                                <path id="path622" d="M410.667 182.705C410.793 183.189 410.503 183.683 410.019 183.81C409.535 183.935 409.04 183.646 408.915 183.162C408.788 182.677 409.079 182.183 409.561 182.057C410.045 181.93 410.54 182.221 410.667 182.705Z" fill="#FF7A00"/>
                                            </g>
                                            <g id="g624">
                                                <path id="path626" d="M410.667 182.705C410.793 183.189 410.503 183.683 410.019 183.81C409.535 183.935 409.04 183.646 408.915 183.162C408.788 182.677 409.079 182.183 409.561 182.057C410.045 181.93 410.54 182.221 410.667 182.705Z" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                            <g id="g628">
                                                <path id="path630" d="M405.902 184.474L395.256 187.249C395.029 187.307 394.798 187.171 394.738 186.946L394.696 186.778C394.636 186.551 394.772 186.319 394.998 186.259L405.646 183.485C405.873 183.426 406.102 183.562 406.162 183.789L406.206 183.957C406.265 184.183 406.129 184.415 405.902 184.474Z" fill="#FF7A00"/>
                                            </g>
                                            <g id="g632">
                                                <path id="path634" d="M405.902 184.474L395.256 187.249C395.029 187.307 394.798 187.171 394.738 186.946L394.696 186.778C394.636 186.551 394.772 186.319 394.998 186.259L405.646 183.485C405.873 183.426 406.102 183.562 406.162 183.789L406.206 183.957C406.265 184.183 406.129 184.415 405.902 184.474Z" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                            <g id="g616">
                                                <path id="path618" d="M392.384 187.47C392.586 188.242 392.123 189.03 391.352 189.231C390.579 189.432 389.791 188.97 389.59 188.198C389.388 187.426 389.851 186.636 390.623 186.436C391.395 186.235 392.184 186.698 392.384 187.47Z" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </g>
                                        </g>
                                        <g id="Group 37">
                                            <path id="POWERED BY" d="M389.756 262.504L388.014 255.518L390.566 254.882C391.019 254.769 391.404 254.728 391.72 254.759C392.044 254.788 392.312 254.872 392.526 255.012C392.745 255.149 392.917 255.33 393.042 255.553C393.171 255.768 393.268 256.005 393.332 256.264C393.429 256.652 393.428 256.992 393.329 257.285C393.237 257.576 393.083 257.827 392.868 258.039C392.651 258.244 392.388 258.416 392.079 258.555C391.777 258.692 391.464 258.801 391.141 258.882L389.646 259.254L390.416 262.34L389.756 262.504ZM391.02 258.314C391.33 258.237 391.604 258.148 391.84 258.048C392.076 257.948 392.265 257.825 392.407 257.679C392.555 257.526 392.651 257.347 392.696 257.144C392.747 256.932 392.735 256.677 392.661 256.38C392.551 255.94 392.322 255.643 391.975 255.489C391.634 255.334 391.149 255.334 390.522 255.491L388.814 255.916L389.506 258.691L391.02 258.314ZM394.577 257.602C394.449 257.091 394.398 256.596 394.422 256.116C394.453 255.634 394.563 255.194 394.753 254.796C394.941 254.392 395.211 254.043 395.564 253.749C395.916 253.455 396.355 253.243 396.879 253.112C397.422 252.977 397.922 252.955 398.378 253.048C398.838 253.132 399.246 253.308 399.602 253.577C399.962 253.838 400.267 254.177 400.516 254.596C400.769 255.007 400.96 255.468 401.087 255.979C401.213 256.484 401.26 256.977 401.229 257.459C401.203 257.932 401.096 258.371 400.908 258.775C400.719 259.173 400.445 259.52 400.086 259.815C399.726 260.111 399.278 260.326 398.741 260.46C398.205 260.594 397.708 260.614 397.252 260.522C396.796 260.429 396.391 260.252 396.038 259.99C395.682 259.721 395.381 259.384 395.136 258.978C394.889 258.565 394.702 258.107 394.577 257.602ZM398.611 259.894C399.064 259.782 399.431 259.601 399.712 259.352C399.999 259.095 400.21 258.798 400.346 258.462C400.488 258.124 400.561 257.756 400.565 257.356C400.575 256.955 400.529 256.551 400.427 256.144C400.306 255.658 400.137 255.24 399.919 254.889C399.706 254.53 399.452 254.243 399.158 254.028C398.864 253.813 398.537 253.674 398.178 253.613C397.819 253.551 397.439 253.57 397.038 253.67C396.592 253.781 396.226 253.965 395.939 254.222C395.659 254.478 395.449 254.777 395.308 255.122C395.172 255.458 395.099 255.827 395.089 256.228C395.086 256.627 395.135 257.03 395.236 257.438C395.34 257.852 395.486 258.234 395.677 258.585C395.874 258.935 396.112 259.226 396.39 259.459C396.674 259.69 396.999 259.85 397.364 259.937C397.736 260.023 398.152 260.009 398.611 259.894ZM408.519 256.837L408.538 256.832L408.552 250.397L409.231 250.228L409.139 257.671L408.411 257.853L405.252 252.086L405.233 252.091L405.151 258.666L404.423 258.847L400.877 252.311L401.537 252.147L404.55 257.826L404.57 257.821L404.68 251.363L405.418 251.179L408.519 256.837ZM411.891 256.985L410.15 249.999L414.797 248.84L414.938 249.403L410.95 250.397L411.579 252.92L415.179 252.023L415.319 252.585L411.719 253.483L412.411 256.258L416.496 255.239L416.636 255.802L411.891 256.985ZM419.599 251.096C419.845 251.034 420.065 250.952 420.259 250.849C420.459 250.744 420.623 250.617 420.751 250.468C420.878 250.313 420.964 250.137 421.011 249.94C421.058 249.742 421.051 249.521 420.99 249.275C420.925 249.016 420.824 248.815 420.685 248.671C420.552 248.525 420.393 248.424 420.207 248.368C420.028 248.309 419.829 248.286 419.613 248.299C419.403 248.31 419.187 248.343 418.968 248.398L416.872 248.921L417.542 251.609L419.599 251.096ZM417.682 252.171L418.473 255.344L417.813 255.509L416.072 248.523L418.808 247.84C419.578 247.648 420.203 247.647 420.683 247.837C421.161 248.02 421.481 248.435 421.642 249.081C421.752 249.521 421.749 249.893 421.632 250.197C421.522 250.499 421.289 250.791 420.933 251.072C421.158 251.092 421.345 251.155 421.495 251.262C421.646 251.369 421.772 251.502 421.874 251.662C421.975 251.822 422.057 251.998 422.118 252.189C422.186 252.378 422.246 252.562 422.297 252.742C422.37 253.005 422.434 253.223 422.491 253.394C422.554 253.564 422.609 253.702 422.656 253.807C422.709 253.91 422.76 253.99 422.808 254.047C422.855 254.097 422.905 254.133 422.958 254.154L422.975 254.222L422.248 254.403C422.148 254.304 422.055 254.156 421.971 253.957C421.894 253.756 421.817 253.545 421.741 253.324C421.672 253.1 421.604 252.884 421.538 252.673C421.478 252.462 421.419 252.291 421.359 252.162C421.273 251.984 421.172 251.851 421.054 251.764C420.941 251.668 420.815 251.607 420.678 251.579C420.54 251.551 420.392 251.547 420.232 251.567C420.078 251.584 419.92 251.613 419.759 251.654L417.682 252.171ZM424.295 253.893L422.553 246.907L427.201 245.748L427.341 246.311L423.353 247.305L423.982 249.828L427.582 248.93L427.722 249.493L424.123 250.39L424.814 253.165L428.899 252.147L429.04 252.71L424.295 253.893ZM430.227 252.414L428.485 245.428L430.872 244.832C431.066 244.784 431.258 244.743 431.449 244.709C431.638 244.669 431.828 244.646 432.019 244.639C432.208 244.626 432.396 244.635 432.582 244.664C432.774 244.691 432.966 244.743 433.156 244.819C433.431 244.93 433.674 245.089 433.884 245.298C434.092 245.5 434.272 245.726 434.424 245.977C434.581 246.22 434.71 246.476 434.812 246.746C434.918 247.008 435.001 247.256 435.059 247.488C435.117 247.721 435.159 247.961 435.187 248.209C435.221 248.455 435.234 248.702 435.227 248.951C435.219 249.194 435.187 249.435 435.13 249.676C435.078 249.909 434.993 250.133 434.875 250.348C434.758 250.569 434.617 250.759 434.45 250.918C434.29 251.074 434.111 251.212 433.914 251.33C433.721 251.439 433.515 251.535 433.295 251.618C433.074 251.694 432.846 251.761 432.614 251.819L430.227 252.414ZM430.746 251.686L432.328 251.292C432.625 251.218 432.899 251.132 433.15 251.036C433.406 250.931 433.651 250.773 433.888 250.563C434.092 250.389 434.243 250.183 434.342 249.945C434.44 249.707 434.503 249.462 434.529 249.208C434.56 248.946 434.559 248.681 434.527 248.415C434.501 248.146 434.459 247.892 434.399 247.653C434.336 247.401 434.256 247.149 434.159 246.899C434.061 246.641 433.937 246.408 433.789 246.197C433.638 245.981 433.461 245.795 433.258 245.639C433.053 245.477 432.811 245.362 432.533 245.294C432.298 245.236 432.035 245.226 431.742 245.265C431.449 245.303 431.158 245.359 430.866 245.432L429.285 245.826L430.746 251.686ZM440.449 249.267L442.128 248.849C442.49 248.758 442.8 248.664 443.057 248.566C443.32 248.465 443.527 248.345 443.678 248.205C443.827 248.058 443.921 247.883 443.96 247.681C444.003 247.471 443.987 247.214 443.912 246.91C443.839 246.618 443.732 246.398 443.592 246.247C443.45 246.09 443.276 245.986 443.071 245.934C442.865 245.882 442.632 245.872 442.372 245.902C442.11 245.926 441.824 245.977 441.513 246.054L439.757 246.492L440.449 249.267ZM439.617 245.929L441.286 245.513C441.629 245.428 441.909 245.327 442.128 245.211C442.346 245.094 442.513 244.964 442.627 244.818C442.741 244.666 442.806 244.502 442.824 244.326C442.847 244.142 442.832 243.943 442.779 243.729C442.724 243.509 442.642 243.334 442.534 243.203C442.424 243.065 442.28 242.971 442.102 242.919C441.923 242.86 441.702 242.843 441.44 242.867C441.183 242.883 440.873 242.937 440.511 243.027L438.988 243.407L439.617 245.929ZM439.93 249.995L438.188 243.008L440.711 242.379C441.118 242.278 441.478 242.233 441.79 242.244C442.102 242.256 442.368 242.317 442.588 242.427C442.814 242.535 442.997 242.689 443.136 242.888C443.273 243.08 443.374 243.306 443.438 243.565C443.5 243.811 443.52 244.032 443.501 244.229C443.48 244.42 443.433 244.59 443.36 244.739C443.285 244.881 443.189 245.004 443.071 245.109C442.958 245.206 442.833 245.286 442.697 245.347L442.702 245.366C443.205 245.316 443.612 245.418 443.922 245.67C444.239 245.921 444.455 246.279 444.571 246.745C444.662 247.107 444.68 247.429 444.627 247.71C444.579 247.99 444.475 248.233 444.313 248.438C444.151 248.643 443.937 248.817 443.67 248.959C443.408 249.093 443.112 249.201 442.782 249.283L439.93 249.995ZM447.643 244.227L448.909 240.335L449.676 240.144L448.126 244.715L448.885 247.762L448.226 247.926L447.478 244.928L443.99 241.562L444.747 241.373L447.643 244.227Z" fill="#FDFFFC"/>
                                            <path id="SageCloud" d="M389.147 277.377C389.32 277.809 389.525 278.112 389.761 278.288C390.193 278.608 390.812 278.667 391.616 278.467C392.098 278.346 392.477 278.196 392.751 278.015C393.27 277.669 393.461 277.22 393.324 276.667C393.243 276.344 393.039 276.13 392.712 276.023C392.386 275.922 391.904 275.88 391.265 275.898L390.172 275.924C389.098 275.948 388.333 275.873 387.876 275.7C387.105 275.414 386.6 274.79 386.36 273.831C386.142 272.955 386.279 272.149 386.771 271.411C387.264 270.673 388.127 270.15 389.36 269.842C390.391 269.585 391.337 269.641 392.199 270.008C393.064 270.37 393.644 271.066 393.938 272.096L391.975 272.586C391.793 272.007 391.432 271.653 390.893 271.524C390.534 271.44 390.118 271.457 389.644 271.575C389.118 271.706 388.725 271.917 388.464 272.207C388.203 272.498 388.119 272.833 388.214 273.214C388.301 273.563 388.521 273.785 388.874 273.881C389.101 273.946 389.56 273.975 390.248 273.968L392.034 273.952C392.816 273.945 393.432 274.043 393.882 274.245C394.581 274.56 395.038 275.152 395.256 276.023C395.478 276.917 395.321 277.745 394.783 278.508C394.249 279.266 393.358 279.801 392.111 280.112C390.838 280.429 389.764 280.389 388.89 279.992C388.015 279.59 387.45 278.881 387.197 277.864L389.147 277.377ZM400.438 274.034C400.337 274.139 400.231 274.231 400.119 274.31C400.011 274.384 399.856 274.468 399.653 274.561L399.249 274.746C398.868 274.916 398.605 275.073 398.458 275.218C398.209 275.463 398.129 275.767 398.22 276.13C398.3 276.452 398.447 276.665 398.66 276.767C398.877 276.863 399.114 276.879 399.37 276.815C399.777 276.714 400.121 276.501 400.402 276.177C400.687 275.853 400.758 275.373 400.613 274.737L400.438 274.034ZM399.07 273.438C399.409 273.306 399.645 273.189 399.779 273.085C400.02 272.903 400.11 272.69 400.049 272.447C399.975 272.151 399.821 271.973 399.585 271.914C399.353 271.85 399.038 271.868 398.64 271.967C398.194 272.078 397.905 272.268 397.775 272.535C397.683 272.732 397.649 272.975 397.675 273.264L395.851 273.719C395.733 273.077 395.78 272.513 395.993 272.028C396.333 271.267 397.112 270.735 398.328 270.431C399.119 270.234 399.862 270.216 400.555 270.376C401.248 270.537 401.703 271.053 401.92 271.924L402.747 275.241C402.804 275.471 402.878 275.748 402.968 276.073C403.043 276.317 403.123 276.476 403.207 276.549C403.291 276.622 403.402 276.672 403.54 276.698L403.609 276.977L401.553 277.49C401.459 277.358 401.385 277.231 401.331 277.108C401.277 276.985 401.223 276.844 401.169 276.684C400.979 277.032 400.738 277.348 400.447 277.632C400.099 277.967 399.668 278.199 399.155 278.327C398.501 278.49 397.913 278.44 397.391 278.175C396.873 277.905 396.528 277.427 396.358 276.742C396.136 275.853 396.318 275.124 396.904 274.555C397.226 274.245 397.74 273.952 398.447 273.678L399.07 273.438ZM407.607 274.332C408.049 274.222 408.381 273.963 408.604 273.555C408.825 273.143 408.845 272.572 408.663 271.842C408.492 271.157 408.216 270.671 407.835 270.386C407.458 270.099 407.026 270.017 406.54 270.138C405.877 270.303 405.497 270.729 405.4 271.416C405.35 271.78 405.386 272.206 405.507 272.692C405.612 273.112 405.776 273.468 405.999 273.76C406.407 274.307 406.943 274.497 407.607 274.332ZM405.677 268.599C406.04 268.509 406.37 268.485 406.667 268.528C407.174 268.604 407.633 268.861 408.043 269.298L407.783 268.257L409.62 267.799L411.33 274.657C411.563 275.59 411.581 276.333 411.385 276.884C411.049 277.832 410.115 278.497 408.585 278.879C407.661 279.109 406.862 279.116 406.188 278.899C405.514 278.681 405.056 278.22 404.814 277.515L406.871 277.002C406.979 277.21 407.105 277.348 407.248 277.415C407.495 277.537 407.859 277.538 408.341 277.418C409.022 277.248 409.421 276.907 409.537 276.394C409.615 276.065 409.567 275.555 409.395 274.865L409.28 274.401C409.175 274.756 409.039 275.036 408.87 275.243C408.565 275.624 408.113 275.889 407.511 276.039C406.583 276.271 405.759 276.131 405.039 275.62C404.323 275.103 403.827 274.288 403.549 273.174C403.281 272.099 403.323 271.13 403.677 270.267C404.029 269.4 404.696 268.844 405.677 268.599ZM414.584 268.013C414.146 268.122 413.84 268.344 413.665 268.679C413.495 269.012 413.437 269.421 413.492 269.905L416.43 269.173C416.275 268.681 416.028 268.341 415.69 268.153C415.355 267.959 414.986 267.912 414.584 268.013ZM414.195 266.454C414.797 266.304 415.367 266.282 415.905 266.387C416.443 266.492 416.93 266.749 417.365 267.157C417.755 267.516 418.063 267.967 418.288 268.512C418.419 268.832 418.558 269.305 418.705 269.93L413.756 271.164C413.964 271.883 414.318 272.334 414.82 272.519C415.126 272.635 415.463 272.648 415.83 272.556C416.219 272.459 416.507 272.27 416.695 271.988C416.798 271.836 416.874 271.641 416.923 271.403L418.853 270.922C418.911 271.363 418.797 271.854 418.509 272.396C418.065 273.253 417.286 273.821 416.172 274.098C415.252 274.328 414.367 274.234 413.516 273.817C412.665 273.399 412.073 272.523 411.74 271.188C411.428 269.936 411.505 268.898 411.971 268.072C412.441 267.246 413.183 266.706 414.195 266.454ZM419.284 267.961C418.859 266.254 418.989 264.825 419.673 263.672C420.269 262.669 421.181 262.014 422.41 261.707C424.055 261.297 425.393 261.537 426.422 262.426C426.993 262.927 427.37 263.479 427.553 264.082L425.511 264.591C425.261 264.155 425.003 263.844 424.735 263.657C424.258 263.325 423.654 263.25 422.925 263.432C422.182 263.617 421.671 264.064 421.392 264.772C421.112 265.476 421.109 266.377 421.382 267.473C421.655 268.57 422.086 269.336 422.674 269.772C423.265 270.202 423.91 270.33 424.608 270.156C425.325 269.978 425.812 269.607 426.071 269.045C426.216 268.741 426.295 268.32 426.306 267.781L428.329 267.277C428.418 268.387 428.181 269.366 427.618 270.216C427.059 271.065 426.211 271.631 425.075 271.914C423.669 272.265 422.451 272.089 421.421 271.388C420.39 270.682 419.678 269.54 419.284 267.961ZM432.502 269.773L430.612 270.245L428.174 260.468L430.065 259.996L432.502 269.773ZM437.264 267.17C437.817 267.032 438.192 266.729 438.39 266.262C438.588 265.795 438.597 265.198 438.416 264.473C438.235 263.748 437.948 263.228 437.555 262.912C437.161 262.593 436.687 262.502 436.134 262.64C435.582 262.777 435.204 263.081 435.001 263.549C434.802 264.012 434.793 264.606 434.974 265.331C435.155 266.057 435.442 266.579 435.837 266.899C436.235 267.217 436.711 267.308 437.264 267.17ZM440.399 263.979C440.664 265.04 440.585 266.025 440.164 266.933C439.741 267.837 438.908 268.444 437.666 268.754C436.423 269.063 435.403 268.918 434.605 268.319C433.807 267.715 433.275 266.882 433.01 265.821C432.75 264.777 432.83 263.797 433.249 262.88C433.669 261.963 434.5 261.349 435.742 261.039C436.985 260.73 438.007 260.881 438.808 261.494C439.609 262.107 440.139 262.935 440.399 263.979ZM442.738 259.542L443.825 263.9C443.927 264.311 444.053 264.608 444.202 264.792C444.466 265.116 444.85 265.215 445.354 265.089C446 264.928 446.377 264.557 446.486 263.976C446.539 263.662 446.508 263.273 446.392 262.809L445.411 258.875L447.328 258.397L449.131 265.627L447.294 266.085L447.039 265.064C447.027 265.09 446.999 265.168 446.956 265.296C446.913 265.424 446.853 265.542 446.777 265.651C446.546 265.99 446.304 266.243 446.05 266.409C445.8 266.575 445.489 266.704 445.118 266.797C444.048 267.064 443.231 266.859 442.668 266.182C442.354 265.809 442.094 265.209 441.888 264.382L440.801 260.025L442.738 259.542ZM451.782 257.104C452.22 256.995 452.633 256.995 453.022 257.104C453.41 257.21 453.753 257.406 454.05 257.694L453.19 254.245L455.107 253.767L457.541 263.53L455.704 263.988L455.454 262.987C455.292 263.483 455.062 263.871 454.766 264.152C454.469 264.432 454.064 264.637 453.551 264.765C452.707 264.975 451.91 264.812 451.161 264.276C450.415 263.734 449.909 262.927 449.642 261.857C449.335 260.624 449.376 259.582 449.765 258.734C450.159 257.884 450.832 257.341 451.782 257.104ZM453.698 263.037C454.234 262.904 454.591 262.603 454.77 262.136C454.949 261.668 454.96 261.119 454.803 260.486C454.582 259.602 454.201 259.025 453.66 258.756C453.328 258.595 452.981 258.559 452.619 258.649C452.066 258.787 451.711 259.099 451.555 259.584C451.402 260.063 451.403 260.613 451.558 261.232C451.724 261.9 451.988 262.402 452.349 262.739C452.714 263.071 453.163 263.17 453.698 263.037Z" fill="#FDFFFC"/>
                                        </g>
                                    </g>
                                    <g id="g712">
                                        <path id="path714" d="M337.333 449.223C337.333 449.223 322.177 413.954 329.909 371.186" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g716">
                                        <path id="path718" d="M300.357 487.324C300.357 487.324 269.719 496.691 266.611 503.742C263.503 510.794 280.353 504.859 280.353 504.859" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g720">
                                        <path id="path722" d="M319.194 342.71C319.194 342.71 290.979 370.669 272.105 378.131" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g724">
                                        <path id="path726" d="M226.747 335.198C226.747 335.198 221.608 372.948 240.793 380.074" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g728">
                                        <path id="path730" d="M172.439 411.427C172.439 411.427 172.283 361.933 182.115 346.63" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g732">
                                        <path id="path734" d="M168.775 361.003C168.775 361.003 166.009 391.738 172.208 401.767" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g736">
                                        <path id="path738" d="M148.569 418.723C148.569 418.723 127.11 411.332 122.661 420.496C118.213 429.66 135.458 421.536 135.458 421.536" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g740">
                                        <path id="path742" d="M161.475 450.817C161.475 450.817 140.402 462.687 139.321 469.479" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g744">
                                        <path id="path746" d="M195.897 417.981C195.897 417.981 236.23 478.942 258.496 473.342" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g748">
                                        <path id="path750" d="M223.457 503.482C223.457 503.482 191.829 526.667 187.225 524.706" stroke="#1A2E35" stroke-width="1.33333" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    <g id="g752">
                                        <g id="g766">
                                            <g id="g764" opacity="0.199997">
                                                <g id="g762" opacity="0.199997">
                                                    <path id="path760" opacity="0.199997" d="M444.98 404.559C447.116 402.807 448.52 401.65 448.979 401.286C448.504 401.702 447.122 402.9 444.98 404.559Z" fill="black"/>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class="right p-3" data-aos="fade">
                    <h2 class="watermark d-none d-lg-block">ABOUT US</h2>
                    <h2 class="title text-center text-lg-left">About <span>Us</span></h2>
                    <p class="text-dark text-justify text-lg-left">We are the foremost solutions provider of digital financial
                        services out of Africa. Annexing the power of agile proprietary API technologies, we provide end-to-end
                        financial automation and integration that swiftly transforms how people and businesses interact seamlessly
                        and profitably.</p> <br>
                    <p class="second-p text-dark text-justify text-lg-left">Led by a relentless and innovative team,
                        SageCloud is focused on retailing financial and VAS gateway infrastructures that help you
                        capture a new stream of customers and create a wholesome digital customer experience for your business growth.
                    </p>
                    <div class="links mt-5 text-center text-lg-left">
{{--                        <a href="#" class="cta mr-3">Get Started Now</a>--}}
                        <a href="#wwd" class="cta-2 text-red">Learn More <img src="{{ asset('assets/landing/img/arrow-right-2.svg') }}"></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- abouts-us end -->

    <!-- what we do -->
    <section id="wwd" class="mt-5 p-2 p-lg-5">
        <div class="container outer">
            <div class="header">
                <h2 class="watermark d-none d-lg-block">WHAT WE DO</h2>
                <h2 class="text-center text-dark" data-aos="fade">What We <span class="text-red">Do</span></h2>
                <p class="text-dark text-center" data-aos="fade">SageCloud is a one-stop-shop for designing and implementing
                    the full pool of transaction and payment value-added services for your business, startup, and community.</p>
            </div>
            <div class="inner h-100 d-flex justify-content-center align-items-center mt-2 p-0 p-lg-3">
                <div class="kard text-center m-3" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/smartphone 1.svg') }}" alt="phone">
                    <h3>Purchase Airtime & Data</h3>
                    <p>We offer a better way to integrate virtual mobile airtime and data transactions fast on your platform
                        regardless of the service provider.</p>
                </div>
                <div class="kard text-center m-3" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/television 1.svg') }}" alt="phone">
                    <h3>Pay Electricity & TV Bills</h3>
                    <p>Seamless, safe, versatile, and secure are how we describe our payment gateway solutions. We help you
                        treat your customers to realtime and quick transactions.</p>
                </div>
                <div class="kard text-center m-3" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/Group.svg') }}" alt="phone">
                    <h3>Funds Transfer API</h3>
                    <p>SageCloud's Funds Transfer API enables instant, responsive, fund transfers to any bank.</p>
                </div>
                <div class="kard text-center m-3" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/dial 1.svg') }}" alt="phone">
                    <h3>USSD</h3>
                    <p>Either on a feature phone or smartphone, give your customers a hassle-free offline service accessible
                        anywhere with our USSD API.</p>
                </div>
                <div class="kard text-center m-3" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/cashmore.svg') }}" alt="phone">
                    <h3>Recharge and Earn(Cash More)</h3>
                    <p>Cashmore is our multilevel business platform where every action is an opportunity for a reward. With our
                        profit-sharing recharge value-added service, everyone is a winner.</p>
                </div>
{{--                <div class="kard text-center m-3" data-aos="fade">--}}
{{--                    <img src="{{ asset('assets/landing/img/debit.svg') }}" alt="phone">--}}
{{--                    <h3>Direct Debit</h3>--}}
{{--                    <p>Your wish is our command! We manage your remittance at your own pace, anytime, any amount.</p>--}}
{{--                </div>--}}
                <div class="kard text-center m-3" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/wallet(5) 1.svg') }}" alt="phone">
                    <h3>Account Opening (E-Wallet)</h3>
                    <p>SageCloud's E-wallet API enables instant, responsive, and cross-platforms transaction, connecting
                        e-wallets, service providers, and businesses.</p>
                </div>
                <div class="kard text-center m-3" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/credit-card-iss.svg') }}" alt="phone">
                    <h3>Debit Card Issuance</h3>
                    <p>Coming Soon...</p>
                </div>
            </div>
        </div>
    </section>
    <!-- what we do end -->

    <!-- why-us -->
    <section id="why-us" class="p-3 d-flex">
        <div class="container outer">
            <div class="row flex-column align-items-center text-center">
                <h2 data-aos="fade">Why Choose <span>Us</span></h2>
                <p class="text-dark" data-aos="fade" data-aos-delay="0">Still on The fence about Us? Let me tell you more</p>
            </div>
            <div class="inner mt-3">
                <div class="kard" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/cloud-computing 1.svg') }}" alt="cloud">
                    <div class="text d-flex flex-column">
                        <h3 class="text-dark">Flexible OnBoarding</h3>
                        <p class="pt-2">We don't get the point of ridiculous tonnes of paperwork, so we don't do it. We only
                            require essential documentation to get you started to get on value-added service (VAS).</p>
                    </div>
                </div>
                <div class="kard" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/cloud-computing 1.svg') }}" alt="cloud">
                    <div class="text d-flex flex-column">
                        <h3 class="text-dark">Online Collation of transactions</h3>
                        <p class="pt-2">When we say seamless, we mean it. Our services are truly digital with the same point of
                            transaction and collection.</p>
                    </div>
                </div>
                <div class="kard" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/cloud-computing 1.svg') }}" alt="cloud">
                    <div class="text d-flex flex-column">
                        <h3 class="text-dark">Instant Settlement of Discounted Values</h3>
                        <p class="mt-2">Every day is a settlement day. No stories. No waiting on your hand.</p>
                    </div>
                </div>
                <div class="kard" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/cloud-computing 1.svg') }}" alt="cloud">
                    <div class="text d-flex flex-column">
                        <h3 class="text-dark">Competitive Percentage Based Discount on Transaction</h3>
                        <p class="pt-2">SageCloud has the best rate you can get anywhere on all transactions.</p>
                    </div>
                </div>
                <div class="kard" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/cloud-computing 1.svg') }}" alt="cloud">
                    <div class="text d-flex flex-column">
                        <h3 class="text-dark">Wide Range of Bill Payments</h3>
                        <p class="pt-2">Our APIs are highly compatible with various brands of cards and allows many transaction
                            options you desire. We deliver complete peace of mind.</p>
                    </div>
                </div>
                <div class="kard" data-aos="fade">
                    <img src="{{ asset('assets/landing/img/cloud-computing 1.svg') }}" alt="cloud">
                    <div class="text d-flex flex-column">
                        <h3 class="text-dark">24/7 Customer Support</h3>
                        <p class="pt-2">Unmatched customer service support across all project phases and beyond.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- why-us end -->

    <!-- highlight -->
{{--    <section id="highlight" class="p-3 d-flex flex-column justify-content-evenly" data-aos="fade">--}}
{{--        <div class="container d-fle">--}}
{{--            <hr>--}}
{{--            <div class="inner">--}}
{{--                <div class="text text-center text-lg-left mt-2 mb-2">--}}
{{--                    <h3>What are you waiting for <span>?</span></h3>--}}
{{--                    <p>Let???s get you started as a merchant right away</p>--}}
{{--                </div>--}}
{{--                <img id="double-arrow" src="./assets/img/double-arrow.svg" alt="arrow">--}}
{{--                <a href="#" class="cta">Create Free Account</a>--}}
{{--            </div>--}}
{{--            <hr>--}}
{{--        </div>--}}
{{--    </section>--}}
    <!-- highlight end -->

    <!-- contact -->
    <section id="contact-us" class="p-3 d-flex">
        <div class="container outer">
            <div class="header">
                <h2 class="text-center text-dark" data-aos="fade">Need Help? Contact <span class="text-red">Us</span></h2>
{{--                <p class="text-center text-dark" data-aos="fade">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut--}}
{{--                    aliquam, purus sit--}}
{{--                    amet luctus venenatis</p>--}}
            </div>
            <div class="row mt-5">
                <div class="col-12 col-lg-6">
                    <img class="img-fluid" src="{{ asset('assets/landing/img/contact-illus.svg') }}" alt="contact" data-aos="zoom-out"
                         data-aos-delay="500">
                </div>
                <div class="col-12 col-lg-6">
                    <form data-aos="fade" method="post" action="{{ route('support.mail') }}">
                        @csrf
                        <div class="form-group">
                            <label for="form-email">Email address</label>
                            <input type="email" name="email" class="form-control" id="form-email" aria-describedby="emailHelp" required>
                        </div>
                        <div class="form-group">
                            <label for="form-name">Full Name</label>
                            <input type="text" name="name" class="form-control" id="form-name" required>
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label for="form-name">Phone Number (<small>optional</small>)</label>--}}
{{--                            <input type="number" name="" class="form-control" id="form-name">--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <label for="form-message">Message</label>
                            <textarea class="form-control" name="message" id="form-message" rows="5" required></textarea>
                        </div>
                        <div class="ctas mt-5 d-flex justify-content-center justify-content-lg-start">
                            <button type="submit" class="btn mr-3">Send Message</button>
                            <button type="submit" class="btn cta-2">Request Callback</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- contact end -->

    <!-- footer -->
    <section id="footer" class="p-3">
        <img src="{{ asset('assets/landing/img/footer-wave.png') }}" alt="" class="img-fluid wave">
        <div class="container">
            <div class="row justify-content-center mt-5 mt-md-3">
                <div class="col-12 col-md-6" data-aos="fade">
                    <img class="img-fluid logo" src="{{ asset('images/sage_cloud.png') }}" width="150" alt="logo">
                    <p class="text-justify p-3">SageCloud is the VAS company of CapitalSage Limited supporting African
                        businesses with the technological backbone to offer third party services that truly transform their
                        customer experiences and bottom line.</p>
                </div>
                <div class="col-12 col-md-6 d-flex justify-content-between" data-aos="fade">
                    <div class="col-6 services">
                        <div class="mx-auto">
                            <h3>Services</h3>
                            <div class="links">
                                <a href="#">Email Marketing</a>
                                <a href="#">Campaign</a>
                                <a href="#">Branding</a>
                                <a href="#">Offline</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 soc">
                        <div class="mx-auto">
                            <h3>Follow Us</h3>
                            <div class="links">
                                <div class="link">
                                    <img src="{{ asset('assets/landing/img/001-facebook.svg') }}"><a href="#" class="fb">Facebook</a>
                                </div>
                                <div class="link">
                                    <img src="{{ asset('assets/landing/img/003-twitter.svg') }}"><a href="#" class="tw">Twitter</a>
                                </div>
                                <div class="link">
                                    <img src="{{ asset('assets/landing/img/004-instagram.svg') }}"><a href="#" class="in">Instagram</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- footer end -->

</main>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
</script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="{{ asset('assets/landing/js/app.js') }}"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-CTKM2TCRZ4"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-CTKM2TCRZ4');
</script>

</body>

</html>
