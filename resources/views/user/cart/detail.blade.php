@extends('user.layout')
@section('user_content')
    <section class="bg-light" id="card_container">
        <div class="container d-lg-flex">
            <div class="box-1 py-3">
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
                                            <h5>{{ $product->name }}</h5><a href="{{ URL::to(route('delete_cart', ['id'=>$productCart[0]['rowId']])) }}">Xóa</a>
                                            <p>Số lượng: {{ $productCart[0]['qty'] }}</p>
                                            <p>Đơn giá:
                                                {{ Lang::get('message.before_unit_money') . number_format($productCart[0]['price'], 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                            </p>
                                            <p>Thành tiền:
                                                {{ Lang::get('message.before_unit_money') . number_format($productCart[0]['qty'] * $productCart[0]['price'], 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                            </p>
                                            <?php $total = (int) $total + (int) $productCart[0]['qty'] * (int) $productCart[0]['price']; ?>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
            @if(isset($product))
            <div class="box-2">
                <div class="box-inner-2">
                    <div>
                        <h3 class="fw-bold">Thông tin thanh toán</h3>
                    </div>
                    <form action="{{ URL::to(route('create_order')) }}" id="create_order" method="POST">
                        @csrf
                        <div class="mb-3">
                            <p class="dis fw-bold mb-2">Email</p>
                            <input class="form-control" type="email" name="email_user" required @if ($user !== null) value="{{ $user->email }}" @endif/>
                        </div>
                        <div class="mb-3">
                            <p class="dis fw-bold mb-2">Tên người nhận</p>
                            <input class="form-control" type="text" name="name_user" required @if ($user !== null) value="{{ $user->name }}" @endif/>
                        </div>
                        <div class="mb-3">
                            <p class="dis fw-bold mb-2">Số điện thoại</p>
                            <input class="form-control" type="number" name="phone_user" required @if ($user !== null) value="{{ $user->phone }}" @endif/>
                        </div>
                        <div class="mb-3">
                            <p class="dis fw-bold mb-2">Địa chỉ</p>
                            <input class="form-control" type="text" name="address" required @if ($user !== null) value="{{ $user->address }}" @endif/>
                        </div>
                        <div class="mb-3">
                            <p class="dis fw-bold mb-2">Lời nhắn</p>
                            <textarea class="form-control" type="text" name="message"></textarea>
                        </div>
                        <input class="form-control" type="hidden" name="into_money"  value="{{$total}}"/>
                        <div>
                            <div class="address">
                                <div class="my-3">
                                    <p class="dis fw-bold mb-2">Phương thức thanh toán</p>
                                    <div class="inputWithcheck d-flex">
                                        <div>
                                            <input class="form-check-input" type="radio" value="0" name="is_pay_cod"
                                                id="flexRadioDefault2" checked />
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                Paypal
                                            </label>
                                        </div>
                                        <div>
                                            <input class="form-check-input" type="radio" value="1" name="is_pay_cod"
                                                id="flexRadioDefault2" checked />
                                            <label class="form-check-label" for="flexRadioDefault2">
                                                COD
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column dis">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <p class="fw-bold">Tổng cộng</p>
                                        <p class="fw-bold">
                                            {{ Lang::get('message.before_unit_money') . number_format($total, 0, ',', '.') . Lang::get('message.after_unit_money') }}
                                        </p>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2">
                                        Xác nhận
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
