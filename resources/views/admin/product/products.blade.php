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
                        <h1 class="m-0">Danh sách sản phẩm</h1>
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
                    <div class="col-12">
                        <div class="card">
                            @if (session('message'))
                                <div class="card-header">
                                    <p class="noti">{{ session('message') }}</p>
                                </div>
                        @endif
                        <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Tên</th>
                                        <th>Số lượng tồn</th>
                                        <th>Hình ảnh</th>
                                        <th>Hoạt động</th>
                                        @if(auth()->user()->role->name === Config::get('auth.roles.manager'))
                                            <th>Người tạo</th>
                                        @endif
                                        <th>Thời gian tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{$product->name}}</td>
                                            <td>{{number_format($product->quantity, 0, ",", ".")}}</td>
                                            <td>
                                                @if($product->image)
                                                    <img class="img-ctr" src="{{asset (''.$product->image) }}"/>
                                                @else
                                                    <img class="img-ctr"
                                                        src="{{asset (''.Config::get('app.image.default')) }}"/>
                                                    <img>
                                                @endif
                                            </td>
                                            <td>
                                                @if($product->active)
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @else
                                                    <span class="badge bg-danger">Ngừng hoạt động</span>
                                                @endif
                                            </td>
                                            @if(auth()->user()->role->name === Config::get('auth.roles.manager'))
                                                <td>{{ $product->user->name }}</td>
                                            @endif
                                            <td>{{ $product->created_at }}</td>
                                            <td>
                                                <div class="row pd-12">
                                                    <a href="{{ URL::to(route('admin.product.show', ['product' => $product->id])) }}">
                                                        <i class="text-success fas fa-eye ico"></i>
                                                    </a>
                                                    <a href="{{ URL::to(route('admin.product.edit', ['product' => $product->id])) }}">
                                                        <i class="fas fa-edit ico"></i>
                                                    </a>
                                                    <form
                                                        action="{{ URL::to(route('admin.product.destroy', ['product'=>$product->id])) }}"
                                                        method="POST">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <button class="btn-ico" type="submit"><i
                                                                class="text-danger fas fa-trash-alt ico"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
