<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleAdminException;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Lang;

class ProductController extends Controller
{
    use ValidateTraits, ResponseTraits;

    private $model;
    private $modelBrand;
    private $modelCategory;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Product();
        $this->modelBrand = new Brand();
        $this->modelCategory = new Category();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|RedirectResponse|Redirector
     * @throws RoleAdminException
     */
    public function index()
    {
        if ($this->checkRoleAdmin()) {
            $response = $this->model->getProducts();
            $products = $response['data'];
            $message = $response['message'];
            if (!$response['status']) {
                return back()->with('message', $message);
            }
            return view('admin.product.products', compact('products'));
        }
        $message = Lang::get('message.not_have_role');
        return redirect(route('screen_admin_login'))->with('message', $message);
    }

    /**
     * Show the form for creating a new resource.we
     *
     * @return Application|Factory|View|RedirectResponse|Redirector
     * @throws RoleAdminException
     */
    public function create()
    {
        if ($this->checkRoleAdmin()) {
            $responseBrand = $this->modelBrand->getBrands();
            $brands = $responseBrand['data'];
            $responseCate = $this->modelCategory->getCategories();
            $categories = $responseCate['data'];
            return view('admin.product.product_add', compact('brands', 'categories'));
        }
        $message = Lang::get('message.not_have_role');
        return redirect(route('screen_admin_login'))->with('message', $message);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        try {
            if ($this->checkRoleAdmin()) {
                $this->validateProduct($request);
                $response = $this->model->addProduct($request);
                $message = $response['message'];
            } else {
                $message = Lang::get('message.not_have_role');
                return redirect(route('screen_admin_login'))->with('message', $message);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse|Redirector
     * @throws RoleAdminException
     */
    public function show($id)
    {
        if ($this->checkRoleAdmin()) {
            $response = $this->model->getProduct($id);
            if (!$response['status']) {
                $message = $response['message'];
                return redirect(route('admin.product.index'))->with('message', $message);
            }
            $product = $response['data'];
            return view('admin.product.product_view', compact('product'));
        }
        $message = Lang::get('message.not_have_role');
        return redirect(route('screen_admin_login'))->with('message', $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse|Redirector
     * @throws RoleAdminException
     */
    public function edit($id)
    {
        if ($this->checkRoleAdmin()) {
            $response = $this->model->getProduct($id);
            if (!$response['status']) {
                $message = $response['message'];
                return redirect(route('admin.product.index'))->with('message', $message);
            }
            $product = $response['data'];

            $responseBramd = $this->modelBrand->getBrands();
            $brands = $responseBramd['data'];
            $responseCate = $this->modelCategory->getCategories();
            $categories = $responseCate['data'];
            $responseCate = $this->model->getDetailProduct($id);
            $detailsProduct = $responseCate['data'];
            return view('admin.product.product_edit', compact('product', 'brands', 'categories', 'detailsProduct'));
        }
        $message = Lang::get('message.not_have_role');
        return redirect(route('screen_admin_login'))->with('message', $message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        try {
            if ($this->checkRoleAdmin()) {
                $this->validateProduct($request);
                $response = $this->model->updateProduct($request, $id);
                $message = $response['message'];
            } else {
                $message = Lang::get('message.not_have_role');
                return redirect(route('screen_admin_login'))->with('message', $message);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy($id)
    {
        try {
            if ($this->checkRoleAdmin()) {
                $response = $this->model->deleteProduct($id);
                $message = $response['message'];
            } else {
                $message = Lang::get('message.not_have_role');
                return redirect(route('screen_admin_login'))->with('message', $message);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }
}
