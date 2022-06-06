@extends('user.layout')
@section('user_content')
    <!-- Start Content -->
    <div class="container py-5" id="shop">
        <div class="row">
            <div class="col-lg-3">
                <h1 class="h2 pb-4">Danh sách danh mục</h1>
                <form method="GET" action="{{ URL::to(route('search_categories')) }}">
                    <select class="filter-select py-2 my-2" name="category">
                        <option selected value="">Chọn thương hiệu</option>
                        @foreach ($categories as $key => $catego)
                            <option value="{{ $catego->name }}">
                                {{ $catego->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </form>
            </div>

            <div class="col-lg-9">
                @if (session('message'))
                    <p class="noti">{{ session('message') }}</p>
                @endif
                <div class="row">
                    @foreach ($category as $key => $cate)
                        <div class="col-md-4">
                            <div class="card mb-4 product-wap rounded-0">
                                <div class="card rounded-0">
                                    @if ($cate->image)
                                    <img class="card-img rounded-0 img-fluid" src="{{ asset('' . $cate->image) }}" />
                                     @else
                                    <img class="card-img rounded-0 img-fluid" src="{{ asset('' . Config::get('app.image.default')) }}" />
                                    @endif
                                    <div
                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                        <ul class="list-unstyled">
                                            <li>
                                                <a class="btn btn-success text-white mt-2"
                                                    href="{{ URL::to(route('search_products')) }}?category={{$cate->name}}"><i
                                                        class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="{{ URL::to(route('search_products')) }}?category={{$cate->name}}"
                                        class="h3 text-decoration-none">{{ $cate->name }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->

    <section class="py-5" style="margin-top:24px">
        <div class="container my-4">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">

                </div>
                
                <div class="col-lg-9 m-auto tempaltemo-carousel">
                    <div class="row d-flex flex-row">
                        <!--Controls-->
                        <div class="col-1 align-self-center">

                        </div>
                        <!--End Controls-->

                        <!--Carousel Wrapper-->
                        <div class="col">

                        </div>
                        <!--End Carousel Wrapper-->

                        <!--Controls-->
                        <div class="col-1 align-self-center">

                        </div>
                        <!--End Controls-->
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-4">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">

                </div>
                
                <div class="col-lg-9 m-auto tempaltemo-carousel">
                    <div class="row d-flex flex-row">
                        <!--Controls-->
                        <div class="col-1 align-self-center">

                        </div>
                        <!--End Controls-->

                        <!--Carousel Wrapper-->
                        <div class="col">

                        </div>
                        <!--End Carousel Wrapper-->

                        <!--Controls-->
                        <div class="col-1 align-self-center">

                        </div>
                        <!--End Controls-->
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-4">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">

                </div>
                
                <div class="col-lg-9 m-auto tempaltemo-carousel">
                    <div class="row d-flex flex-row">
                        <!--Controls-->
                        <div class="col-1 align-self-center">

                        </div>
                        <!--End Controls-->

                        <!--Carousel Wrapper-->
                        <div class="col">

                        </div>
                        <!--End Carousel Wrapper-->

                        <!--Controls-->
                        <div class="col-1 align-self-center">

                        </div>
                        <!--End Controls-->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
