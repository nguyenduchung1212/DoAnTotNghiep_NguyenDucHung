@extends('user.layout')
@section('user_content')
    <!-- Start Banner Hero -->
    <div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="1"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <?php $i = 1; ?>
            @foreach ($products as $key => $product)
                @if ($i <= 3)
                    @if ($i == 1)
                        <div class="carousel-item active">
                        @else
                            <div class="carousel-item">
                    @endif
                    <?php $i++; ?>
                    <div class="container">
                        <div class="row p-5">
                            <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                                <img class="img-fluid" src="{{ asset('' . $product->image) }}" alt="" />
                            </div>
                            <div class="col-lg-6 mb-0 d-flex align-items-center">
                                <div class="text-align-left align-self-center">
                                    <h1 class="h1 text-success"><b>Sản phẩm </b> {{ $product->name }}</h1>
                                    <h3 class="h2">
                                        {{ Lang::get('message.before_unit_money') . number_format($product->price, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                    </h3>
                                    <p> {{ $product->short_description }} </p>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
        @endif
        @endforeach
    </div>
    <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#template-mo-zay-hero-carousel" role="button"
        data-bs-slide="prev">
        <i class="fas fa-chevron-left"></i>
    </a>
    <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#template-mo-zay-hero-carousel" role="button"
        data-bs-slide="next">
        <i class="fas fa-chevron-right"></i>
    </a>
    </div>
    <!-- End Banner Hero -->

    <!-- Start Categories of The Month -->
    <section class="container py-5">
        <div class="row text-center pt-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Các sản phẩm mới nhất</h1>
                <p>
                    Các sản phẩm nổi bật và mới nhất
                </p>
            </div>
        </div>
        <div class="row">
            <?php $i = 1; ?>
            @foreach ($products as $key => $product)
                @if ($i <= 3)
                    <?php $i++; ?>
                    <div class="col-12 col-md-4 p-5 mt-3">
                        <a href="#"><img src="{{ asset('' . $product->image) }}"
                                class="rounded-circle img-fluid border" /></a>
                        <h5 class="text-center mt-3 mb-3">{{ $product->name }}</h5>
                        <p class="text-center"><a class="btn btn-success">Xem chi tiết</a></p>
                    </div>
                @endif
            @endforeach
        </div>
    </section>
    <!-- End Categories of The Month -->
@endsection
