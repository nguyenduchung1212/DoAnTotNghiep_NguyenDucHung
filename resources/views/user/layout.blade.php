<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mỹ phẩm chính hãng</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/templatemo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap" />
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}" />

    <!-- Slick -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/slick-theme.css') }}" />
</head>

<body>
    <div id="collapse_menu" class="collapse-menu">
        <ul class="collapse-menu-item">
            <li>
                <a id="close_collapse" href="#" class="dropdown-item" type="button">
                    <i class="fas fa-outdent"></i>
                </a>
            </li>
            <a href="{{ URL::to(route('search_brands')) }}" class="tit">Thương hiệu</a>
            @foreach ( $brands as $key => $brand)
            <li>
                <a href="{{ URL::to(route('search_products')) }}?brand={{$brand->name}}" class="dropdown-item" id="filter_menu" type="button">
                    {{$brand->name}}
                </a>
            </li>
            @endforeach
            <a href="{{ URL::to(route('search_categories')) }}" class="tit">Danh mục</a>
            @foreach ( $categories as $key => $category)
            <li>
                <a href="{{ URL::to(route('search_products')) }}?category={{$category->name}}" class="dropdown-item" id="filter_menu" type="button">
                    {{$category->name}}
                </a>
            </li>
            @endforeach
        </ul>
    </div>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">

        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand text-success logo h1 align-self-center" href="{{ URL::to(route('screen_home')) }}">
                Mỹ phẩm ABC
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between"
                id="templatemo_main_nav">
                <div class="flex-fill">
                </div>
                <div class="navbar align-self-center d-flex">
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ..." />
                            <div class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </div>
                        </div>
                    </div>
                    <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal"
                        data-bs-target="#templatemo_search">
                        <i class="fa fa-fw fa-search text-dark mr-2"></i>
                    </a>
                    <a class="nav-icon d-none d-lg-inline" href="{{ URL::to(route('search_order')) }}">
                        <i class="fa fa-fw fa-file text-dark mr-2"></i>
                    </a>
                    <a class="nav-icon position-relative text-decoration-none" href="{{ URL::to(route('cart')) }}">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        @if (Cart::total() > 0)
                            <span
                                class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">{{ Cart::content()->groupBy('id')->count() }}</span>
                        @endif
                    </a>
                    <div class="dropdown">
                        @if (Auth::check() && Auth::user()->role->name === Config::get('auth.roles.user'))
                            <a class="nav-icon position-relative text-decoration-none" type="button" id="dropdownMenu2"
                                data-bs-toggle="dropdown">
                                <i class="fa fa-fw fa-user text-dark mr-3"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <li>
                                    <a href="{{ URL::to(route('screen_info')) }}" class="dropdown-item"
                                        id="filter_menu" type="button">
                                        {{ auth()->user()->name }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ URL::to(route('logout')) }}" type="button">
                                        Đăng xuất </a>
                                </li>
                            </ul>
                        @else
                            <a class="nav-icon position-relative text-decoration-none"
                                href="{{ URL::to(route('screen_login')) }}">
                                <i class="fa fa-fw fa-sign-in-alt text-dark mr-3"></i>
                            </a>
                        @endif
                    </div>
                    <div>
                        <a class="nav-icon position-relative text-decoration-none" href="#" type="button"
                            id="menu_collapse_btn">
                            <i class="fas fa-bars text-dark mr-3"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Header -->

    <!-- Modal -->
    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="w-100 pt-1 mb-5 text-right">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ URL::to(route('search_products')) }}" method="get"
                class="modal-content modal-body border-0 p-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="inputModalSearch" name="product"
                        placeholder="Nhập vào tên sản phẩm ..." />
                    <button type="submit" class="input-group-text bg-success text-light">
                        <i class="fa fa-fw fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal -->

    @yield('user_content')

    <!-- Start Footer -->
    <footer class="bg-dark" id="tempaltemo_footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 pt-5">
                    <h2 class="h2 text-success border-bottom pb-3 border-light logo">
                        Mỹ phẩm ABC
                    </h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li>
                            <i class="fas fa-map-marker-alt fa-fw"></i>
                            450 Lê Văn Việt TP Thủ Đức
                        </li>
                        <li>
                            <i class="fa fa-phone fa-fw"></i>
                            <a class="text-decoration-none" href="tel:010-020-0340">0123456789</a>
                        </li>
                        <li>
                            <i class="fa fa-envelope fa-fw"></i>
                            <a class="text-decoration-none" href="mailto:info@company.com">info@utc2,edu,vn</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-4 pt-5 ">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">
                        Danh mục
                    </h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        @foreach ($categories as $key => $category)
                        @if($key <5)
                            <li><a class="text-decoration-none" href="{{ URL::to(route('search_products')) }}?category={{$category->name}}">{{ $category->name }}</a></li>
                        @endif
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-4 pt-5">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">
                        Thương hiệu
                    </h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        @foreach ($brands as $key => $brand)
                        @if($key <5)
                            <li><a class="text-decoration-none" href="{{ URL::to(route('search_products')) }}?brand={{$brand->name}}">{{ $brand->name }}</a></li>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
    <!-- Start Script -->
    <script src="{{ asset('assets/js/jquery-1.11.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-migrate-1.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/templatemo.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <!-- End Script -->

    <script src="{{ asset('assets/js/slick.min.js') }}"></script>
    <script>
        $('#carousel-related-product').slick({
            infinite: true,
            arrows: false,
            slidesToShow: 4,
            slidesToScroll: 3,
            dots: true,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                    },
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 3,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 3,
                    },
                },
            ],
        });
    </script>
    <script>
        var intoMoney = document.getElementById('total').value
        var total = Math.round((intoMoney / 23083) * 100) / 100
        total = parseFloat(total)

        paypal.Button.render({
            // Configure environment
            env: 'sandbox',
            client: {
                sandbox: 'AS_uK5RVtE8H5aiNaPx_HQD_FFax5tPA0_UnXnZddv7_xzq43lbjaRzzXY6xH2m1Ey8emi5mkowbvzxI',
                production: 'demo_production_client_id'
            },
            // Customize button (optional)
            locale: 'en_US',
            style: {
                size: 'large',
                color: 'gold',
                shape: 'pill',
            },

            // Enable Pay Now checkout flow (optional)
            commit: true,

            // Set up a payment
            payment: function(data, actions) {
                return actions.payment.create({
                    transactions: [{
                        amount: {
                            total: total,
                            currency: 'USD'
                        }
                    }]
                });
            },
            // Execute the payment
            onAuthorize: function(data, actions) {
                var email = document.getElementById("email").value
                var name = document.getElementById("name").value
                var phone = document.getElementById("phone").value
                var address = document.getElementById("address").value

                if (email && name && phone && address) {
                    return actions.payment.execute().then(function() {
                        // Show a confirmation message to the buyer
                        document.getElementById("is_pay_cod").value = 0;
                        document.getElementById("create_order").submit();
                        window.alert('Thank you for your purchase!');
                    });
                } else {
                    window.alert('Bạn chưa nhập đủ thông tin');
                }
            }
        }, '#paypal-button');
    </script>
</body>

</html>
