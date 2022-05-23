@extends('user.layout')
@section('user_content')
    <!-- Start Content -->
    <div class="container py-5" id="shop">
        <div class="row">
            <div class="col-lg-3">
                <h1 class="h2 pb-4">Danh sách thương hiệu</h1>
            </div>

            <div class="col-lg-9">
                @if (session('message'))
                    <p class="noti">{{ session('message') }}</p>
                @endif
                <div class="row">
                    @foreach ($brands as $key => $brand)
                        <div class="col-md-4">
                            <div class="card mb-4 product-wap rounded-0">
                                <div class="card rounded-0">
                                    @if ($brand->image)
                                    <img class="card-img rounded-0 img-fluid" src="{{ asset('' . $brand->image) }}" />
                                     @else
                                    <img class="card-img rounded-0 img-fluid" src="{{ asset('' . Config::get('app.image.default')) }}" />
                                    @endif
                                    <div
                                        class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                        <ul class="list-unstyled">
                                            <li>
                                                <a class="btn btn-success text-white mt-2"
                                                    href="{{ URL::to(route('search_products')) }}?brand={{$brand->name}}"><i
                                                        class="far fa-eye"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="{{ URL::to(route('search_products')) }}?brand={{$brand->name}}"
                                        class="h3 text-decoration-none">{{ $brand->name }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection
