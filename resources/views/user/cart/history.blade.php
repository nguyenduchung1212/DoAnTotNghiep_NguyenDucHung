@extends('user.layout')
@section('user_content')
    <!-- Open Content -->
    <section class="bg-success py-5" id="user_detail">
        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <h4 class="text-center">Đơn hàng đã mua</h4>
            </div>
        </div>
        @if (isset($orders))
            @foreach ($orders as $key => $order)
                <div class="container rounded bg-white mt-5 mb-5">
                    <a href="{{ URL::to(route('detail_order', ['id' => $order->id])) }}" style="text-decoration: none; color: black">
                        <div class="row">
                            <div class="col-md-1 border-right"></div>
                            <div class="col-md-5 border-right">
                                <div class="p-3 py-5"
                                    style="padding-left: 0px!important; padding-bottom: 0px!important;">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="text-right">Mã đơn hàng</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 border-right">
                                <div class="p-3 py-5"
                                    style="padding-left: 0px!important; padding-bottom: 0px!important;">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="text-right">{{ $order->code_invoice }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 border-right"></div>
                            <div class="col-md-5 border-right">
                                <h5 class="">Trạng thái</h5>
                            </div>
                            <div class="col-md-5 border-right">
                                <h5 class="text-right">{{ $order->status_ship }}</h5>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-1 border-right"></div>
                            <div class="col-md-5 border-right">
                                <h5 class="text-right">Số tiền cần thanh toán</h5>
                            </div>
                            <div class="col-md-5 border-right">
                                <h5 class="text-right">
                                    {{ Lang::get('message.before_unit_money') . number_format($order->need_pay, 0, ',', '.') . Lang::get('message.after_unit_money') }}

                                </h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @endif
    </section>
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
    <!-- Close Banner -->
@endsection
