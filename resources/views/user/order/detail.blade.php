@extends('user.layout')
@section('user_content')
    <!-- Open Content -->
    <section class="bg-light">
        <div class="container pb-5">
            <div class="row">
                <div class="mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="pb-2">
                                <h5 class="card-title">
                                    Nhập vào mã đơn hàng
                                </h5>
                                <form action="" method="post" class="form-inline">
                                    <div class="form-group mr-2">
                                        <input type="text" class="form-control" id="inputOrderCode"
                                            placeholder="Enter order code" />
                                    </div>
                                    <div class="form-group mr-2 pt-4" id="order_status">
                                        <p>orderstatus</p>
                                    </div>
                                </form>
                            </div>
                            <div class="row pb-3">
                                <div class="col d-grid">
                                    <button type="submit" class="btn btn-success btn-lg" name="submit" value="buy"
                                        id="find_status_order">
                                        search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Close Content --
@endsection
