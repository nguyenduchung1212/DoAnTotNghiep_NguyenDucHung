@extends('user.layout')
@section('user_content')
    <!-- Start Content -->
    <div class="container py-5" id="shop">
        <div class="row">
            <div class="col-lg-3">
                <h1 class="h2 pb-4">Danh sách thương hiệu</h1>
                <form method="GET" action="{{ URL::to(route('search_brands')) }}">
                    <select class="filter-select py-2 my-2" name="brand">
                        <option selected value="">Chọn thương hiệu</option>
                        @foreach ($brands as $key => $bran)
                            <option value="{{ $bran->name }}">
                                {{ $bran->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </form>
            </div>
            
            <div class="col-lg-9">
                @if (session('message'))
                    <p class="noti">{{ session('message') }}</p>
                @endif
                @if (isset($brand))
                <div class="row">
                    @foreach ($brand as $key => $bra)
                        <div class="col-md-4">
                            <div class="card mb-4 product-wap rounded-0">
                                <div class="card rounded-0">
                                    @if ($bra->image)
                                    <img class="card-img rounded-0 img-fluid" src="{{ asset('' . $bra->image) }}" />
                                     @else
                                    <img class="card-img rounded-0 img-fluid" src="{{ asset('' . Config::get('app.image.default')) }}" />
                                    @endif
                                    <div
                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                        <ul class="list-unstyled">
                                            <li>
                                                <a class="btn btn-success text-white mt-2"
                                                    href="{{ URL::to(route('search_products')) }}?brand={{$bra->name}}"><i
                                                        class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="{{ URL::to(route('search_products')) }}?brand={{$bra->name}}"
                                        class="h3 text-decoration-none">{{ $bra->name }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
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
