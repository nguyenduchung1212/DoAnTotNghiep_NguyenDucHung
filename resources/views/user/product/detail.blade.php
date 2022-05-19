@extends('user.layout')
@section('user_content')
    <!-- Open Content -->
    <section class="bg-light">
        <div class="container pb-5">
            <div class="row">
                <div class="mt-5">
                    <div class="card">
                        <div class="card-body d-flex">
                            <div class="mx-2">
                                <div class="card mb-3">
                                    <img class="card-img img-fluid" src="{{ asset('' . $product->image) }}"
                                        alt="Card image cap" id="product-detail" />
                                </div>
                            </div>
                            <div>
                                <h1 class="h2">{{ $product->name }}</h1>
                                <p class="h3 py-2">
                                    {{ Lang::get('message.before_unit_money') . number_format($product->price, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                </p>

                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h6>Thương hiệu:</h6>
                                    </li>
                                    <li class="list-inline-item">
                                        <p><strong>{{ $product->brand->name }}</strong></p>
                                    </li>
                                </ul>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h6>Danh mục:</h6>
                                    </li>
                                    <li class="list-inline-item">
                                        <p><strong>{{ $product->category->name }}</strong></p>
                                    </li>
                                </ul>
                                <h6>Chi tiết:</h6>
                                <p>
                                    {{ $product->short_description }}
                                </p>
                                @if ($product->active == 0 || $product->is_deleted == 1)
                                    <p class="noti">{{ Lang::get('message.no_long_in_business') }}</p>
                                @elseif ($product->quantity <= 0)
                                    <p class="noti">{{ Lang::get('message.out_of_stock') }}</p>
                                @else
                                    <form action="{{ URL::to(route('add_cart', ['id' => $product->id])) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product-title" value="Activewear" />
                                        <div class="row">
                                            <div class="col-auto">
                                                <ul class="list-inline pb-3">
                                                    <li class="list-inline-item text-right">
                                                        Số lượng
                                                        <input type="hidden" name="quanity" id="product_quantity"
                                                            value="1" />
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <span class="btn btn-success" id="btn-minus">-</span>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <span class="badge bg-secondary" id="var-value">1</span>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <span class="btn btn-success" id="btn-plus">+</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row pb-3">
                                            <div class="col d-grid">
                                                <a class="btn btn-success btn-lg"
                                                    href="{{ URL::to(route('buy_product', ['id' => $product->id])) }}">
                                                    Mua ngay
                                                </a>
                                            </div>
                                            <div class="col d-grid">
                                                <button type="submit" class="btn btn-success btn-lg" name="submit"
                                                    value="addtocard">
                                                    Thêm vào giỏ hàng
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                                @if (session('message'))
                                    <p class="noti">{{ session('message') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="m-5">
            <div class="container">
                @if (Auth::check() && Auth::user()->role->name === Config::get('auth.roles.user'))
                <form action="{{ URL::to(route('comment', ['id' => $product->id])) }}" method="POST">
                    @csrf
                    <div class="text-area d-flex">
                        <textarea class="form-control" cols="1" placeholder="Nhận xét" name="description" required ></textarea>
                        <button type="submit" class="btn btn-primary">Gửi</button>
                    </div>
                </form>
                @endif
                <div class="py-2">
                    <div class="list-comment">
                        @if (isset($comments))
                        @foreach ($comments as $key => $comment)
                        <div class="d-flex comment-item m-3 align-items-center">
                            <i class="fas fa-user px-2 ico-user"></i>
                            <div>
                                @if (Auth::check() && $comment->user->id === Auth::user()->id && Auth::user()->role->name === Config::get('auth.roles.user'))
                                <p class="m-0 txt-name">Bạn</p>
                                @else
                                <p class="m-0 txt-name">{{$comment->user->name}}</p>
                                @endif
                                <p class="m-0">{{$comment->description}}</p>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Close Content -->
    
@endsection
