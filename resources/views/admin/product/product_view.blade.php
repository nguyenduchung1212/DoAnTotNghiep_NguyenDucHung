@extends('admin.layout')
@section('admin_content')
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a class="d-block">{{ auth()->user()->name }}</a>
                </div>
            </div>
            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Tìm kiếm"
                           aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ URL::to(route('screen_admin_home')) }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Trang chủ</p>
                        </a>
                    </li>
                    <li class="nav-header">Sản phẩm</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-bookmark"></i>
                            <p>
                                Thương hiệu
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ URL::to(route('admin.brand.index')) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Danh sách thương hiệu</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ URL::to(route('admin.brand.create')) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Thêm thương hiệu</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Danh mục
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ URL::to(route('admin.category.index')) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Danh sách danh mục</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ URL::to(route('admin.category.create')) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Thêm danh mục</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p>
                                Sản phẩm
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ URL::to(route('admin.product.index')) }}" class="nav-link active">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Danh sách sản phẩm</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ URL::to(route('admin.product.create')) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Thêm sản phẩm</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-header">Hóa đơn</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-download"></i>
                            <p>
                                Hóa đơn nhập
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ URL::to(route('admin.invoice_import.index')) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Danh sách hóa đơn</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ URL::to(route('admin.invoice_import.create')) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Nhập hàng</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::to(route('admin.invoice_export.order')) }}" class="nav-link">
                            <i class="nav-icon fas fa-paste"></i>
                            <p>Đơn đặt hàng</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::to(route('admin.invoice_export.invoice')) }}" class="nav-link">
                            <i class="nav-icon fas fa-file-export"></i>
                            <p>Hóa đơn bán</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::to(route('admin.invoice_export.close_orders')) }}" class="nav-link">
                            <i class="nav-icon fas fa-times-circle"></i>
                            <p>Đơn đã hủy</p>
                        </a>
                    </li>
                    <li class="nav-header">Thống kê</li>
                    <li class="nav-item">
                        <a href="{{ URL::to(route('admin.statistical.products')) }}" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Thống kê sản phẩm</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::to(route('admin.statistical.invoices')) }}" class="nav-link">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>Thống kê hóa đơn</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ URL::to(route('admin.statistical.users')) }}" class="nav-link">
                            <i class="nav-icon fas fa-id-card-alt"></i>
                            <p>Thống kê khách hàng</p>
                        </a>
                    </li>
                    @if(auth()->user()->role->name === Config::get('auth.roles.manager'))
                        <li class="nav-header">Tài khoản</li>
                        <li class="nav-item">
                            <a href="{{ URL::to(route('admin.account.index')) }}" class="nav-link">
                                <i class="nav-icon fas fa-address-book"></i>
                                <p>Danh sách tài khoản</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ URL::to(route('admin.account.create')) }}" class="nav-link">
                                <i class="nav-icon fas fa-user-plus"></i>
                                <p>Cấp tài khoản mới</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Chi tiết sản phẩm</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ URL::to(route('screen_admin_home')) }}">Trang
                                    chủ</a></li>
                            <li class="breadcrumb-item active">Sản phẩm</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card">
                            @if (session('message'))
                                <div class="card-header">
                                    <p class="noti">{{ session('message') }}</p>
                                </div>
                        @endif
                        <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="required">Tên sản phẩm</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-address-card"></i>
                                                </span>
                                        </div>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Nhập vào tên sản phẩm" value="{{ $product->name }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="required">Thương hiệu</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-th"></i></span>
                                        </div>
                                        <select class="form-control select2bs4" name="brand" disabled>
                                            <option>
                                                {{ $product->brand->name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="required">Danh mục</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-th"></i></span>
                                        </div>
                                        <select class="form-control select2bs4" name="category" disabled>
                                            <option>
                                                {{ $product->category->name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="required">Giá</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="text" name="price" class="form-control" placeholder="Nhập vào giá"
                                            value="{{Lang::get('message.before_unit_money'). number_format($product->price, 0, ",", "."). Lang::get('message.after_unit_money')}}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                        </div>
                                        <textarea class="form-control" name="short_description" rows="2"
                                                  placeholder="Nhập vào mô tả"
                                                  disabled>{{$product->short_description}}</textarea>
                                    </div>
                                </div>
                                @if($product->image)
                                    <div class="form-group">
                                        <label>Hình ảnh</label>
                                        <div class="input-group">

                                        </div>
                                        <img class="img-vie" src="{{asset (''.$product->image) }}"/>
                                    </div>
                                @endif
                                <div class="form-group row pt-3">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="active" class="custom-control-input"
                                                   id="customSwitch1" disabled @if ($product->active) checked @endif>
                                            <label class="custom-control-label" for="customSwitch1">Hoạt dộng</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
