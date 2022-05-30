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
            @foreach ($sidebars as $key => $sidebar)
                @if ($i <= 3)
                    @if ($i == 1)
                        <div class="carousel-item active">
                    @else
                        <div class="carousel-item">
                    @endif
                    <?php $i++; ?>
                    <div class="container">
                        <div class="row p-5">
                            <img class="img-fluid" src="{{ asset('' . $sidebar->image) }}" alt="" />
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
                <h1 class="h1">Các sản phẩm nổi bật</h1>
                <p>
                    Các sản phẩm bán chạy trong 3 tháng gần đây
                </p>
            </div>
        </div>
        <div class="row">
            <?php $i = 1; ?>
            @foreach ($productsMax as $keyMax => $productMax)
                @if ($i <= 3)
                    <?php $i++; ?>
                    @foreach ($products as $key => $product)
                        @if ($product->id == $keyMax)
                            <div class="col-12 col-md-4 p-5 mt-3">
                                <a href="{{ URL::to(route('detail_product', ['id' => $product->id])) }}"><img
                                        src="{{ asset('' . $product->image) }}"
                                        class="rounded-circle img-fluid border" /></a>
                                <h3 class="text-center mt-3 mb-3">{{ $product->name }}</h3>
                                <h5 class="text-center mt-3 mb-3">Số lượng bán: {{ $productMax }}</h5>
                                <p class="text-center"><a class="btn btn-success"
                                        href="{{ URL::to(route('detail_product', ['id' => $product->id])) }}">Xem chi
                                        tiết</a></p>
                            </div>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    </section>
    <!-- End Categories of The Month -->

    <section class="bg-light">
        <div class="container py-5">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">{{$brands->first()->name}}</h1>
                    <p>
                        Một số sản phẩm về thương hiệu {{$brands->first()->name}} mà bạn không thể bỏ qua
                    </p>
                </div>
            </div>
            <div class="row">
                @foreach ($brands->first()->product->take(3) as $key =>$pro) 
                <div class="col-12 col-md-4 mb-4">
                    <div class="card h-100">
                        <a href="{{ URL::to(route('detail_product', ['id' => $pro->id])) }}">
                            <img src="@if(isset ($pro->image)){{ asset('' . $pro->image) }} @else {{ asset('' . Config::get('app.image.default')) }} @endif" class="card-img-top" alt="..." />
                        </a>
                        <div class="card-body">
                            <ul class="list-unstyled d-flex justify-content-between">
                                <li class="text-muted text-right"> {{ Lang::get('message.before_unit_money') . number_format($pro->price, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                </li>
                            </ul>
                            <a href="{{ URL::to(route('detail_product', ['id' => $pro->id])) }}" class="h2 text-decoration-none text-dark">{{$pro->name}}</a>
                            <p class="card-text">{{$pro->short_description}}</p>
                            <p class="text-muted">{{$pro->comment->count()}} Review</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>            
        </div>
    </section>

    <section class="bg-light">
        <div class="container py-5">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">{{$categories->first()->name}}</h1>
                    <p>
                        Một số sản phẩm về danh mục {{$categories->first()->name}} mà bạn không thể bỏ qua
                    </p>
                </div>
            </div>
            <div class="row">
                @foreach ($categories->first()->product->take(3) as $key =>$pro) 
                <div class="col-12 col-md-4 mb-4">
                    <div class="card h-100">
                        <a href="{{ URL::to(route('detail_product', ['id' => $pro->id])) }}">
                            <img src="@if(isset ($pro->image)){{ asset('' . $pro->image) }} @else {{ asset('' . Config::get('app.image.default')) }} @endif" class="card-img-top" alt="..." />
                        </a>
                        <div class="card-body">
                            <ul class="list-unstyled d-flex justify-content-between">
                                <li class="text-muted text-right"> {{ Lang::get('message.before_unit_money') . number_format($pro->price, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                </li>
                            </ul>
                            <a href="{{ URL::to(route('detail_product', ['id' => $pro->id])) }}" class="h2 text-decoration-none text-dark">{{$pro->name}}</a>
                            <p class="card-text">{{$pro->short_description}}</p>
                            <p class="text-muted">{{$pro->comment->count()}} Review</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>            
        </div>
    </section>
@endsection
