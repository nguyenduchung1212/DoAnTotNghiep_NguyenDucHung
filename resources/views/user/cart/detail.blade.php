@extends('user.layout')
@section('user_content')
    <section class="bg-light" id="card_container">
        <div class="container d-lg-flex">
            <div class="box-1 py-3">
                <form action="{{ URL::to(route('update_cart')) }}" method="POST" >
                    @csrf
                <div class="dis list-product info d-flex">
                    <div class="products">
                        @if (session('message'))
                            <p class="noti">{{ session('message') }}</p>
                        @endif
                        <?php $total = 0; ?>
                        @foreach (Cart::content()->groupBy('id')->toArray() as $productCart)
                            @foreach ($products as $keyProduct => $product)
                                @if ($productCart[0]['id'] == $product->id)
                                    <div class="product-item d-flex">
                                        <div class="product-image">
                                            <img src="{{ asset('' . $product->image) }}" />
                                        </div>
                                        <div class="product-name d-flex">
                                            <h5>{{ $product->name }}</h5>
                                            <p>Số lượng: {{ $productCart[0]['qty'] }}</p>
                                            <p>Đơn giá:
                                                {{ Lang::get('message.before_unit_money') . number_format($productCart[0]['price'], 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                            </p>
                                            <p>Thành tiền:
                                                {{ Lang::get('message.before_unit_money') . number_format($productCart[0]['qty'] * $productCart[0]['price'], 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                            </p>
                                            <?php $total = (int) $total + (int) $productCart[0]['qty'] * (int) $productCart[0]['price']; ?>
                                        </div>
                                        <div class="product-event d-flex">
                                            <div class="d-flex action">
                                                <p>
                                                    Số lượng:
                                                </p>
                                                <input name= "{{$productCart[0]['rowId']}}" value="{{ $productCart[0]['qty'] }}" min="1" required type="number" id="quantity">
                                                <a type="button"
                                                    href="{{ URL::to(route('delete_cart', ['id' => $productCart[0]['rowId']])) }}">
                                                    <i id="btnMinus" class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2 d-flex justify-content-center">
                    Xác nhận
                </button>
            </form>
            </div>
            @if (isset($product))
                <div class="box-2">
                    <div class="box-inner-2">
                        <div>
                            <h3 class="fw-bold">Thông tin thanh toán</h3>
                        </div>
                        <form action="{{ URL::to(route('create_order')) }}" id="create_order" method="POST">
                            @csrf
                            <div class="mb-3">
                                <p class="dis fw-bold mb-2">Email</p>
                                <input class="form-control" type="email" id="email" name="email_user" required
                                    @if ($user !== null) value="{{ $user->email }}" @endif />
                            </div>
                            <div class="mb-3">
                                <p class="dis fw-bold mb-2">Tên người nhận</p>
                                <input class="form-control" type="text" id="name" name="name_user" required
                                    @if ($user !== null) value="{{ $user->name }}" @endif />
                            </div>
                            <div class="mb-3">
                                <p class="dis fw-bold mb-2">Số điện thoại</p>
                                <input class="form-control" type="number" id="phone" name="phone_user" required
                                    @if ($user !== null) value="{{ $user->phone }}" @endif />
                            </div>
                            <div class="mb-3">
                                <p class="dis fw-bold mb-2">Địa chỉ</p>
                                <input class="form-control" type="text" id="address" name="address" required
                                    @if ($user !== null) value="{{ $user->address }}" @endif />
                            </div>
                            <div class="mb-3">
                                <p class="dis fw-bold mb-2">Lời nhắn</p>
                                <textarea class="form-control" type="text" name="message"></textarea>
                            </div>
                            <input class="form-control" type="hidden" name="into_money" id="total"
                                value="{{ $total }}" />
                            <input class="form-control" type="hidden" name="is_pay_cod" id="is_pay_cod" value="1" />
                            <div>
                                <div class="address">
                                    <div class="d-flex flex-column dis">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <p class="fw-bold">Tổng cộng</p>
                                            <p class="fw-bold">
                                                {{ Lang::get('message.before_unit_money') . number_format($total, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                            </p>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-2">
                                            Thanh toán COD
                                        </button>
                                        <button class="btn ">
                                            <div id="paypal-button"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
