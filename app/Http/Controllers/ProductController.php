<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Exception;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( $this->checkRoleAdmin()){
            $response = $this->model->getProducts();
            $products = $response['data'];
            $message = $response['message'];
            if (!$response['status']){
                return back()->with('message', $message);
            }
            return view('admin.product.products', compact('products'));
        } return redirect(route('screen_admin_login'))->with('message', 'Role must admin!');
    }

    /**
     * Show the form for creating a new resource.we
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->checkRoleAdmin()){
            $responseBramd = $this->modelBrand->getBrands();
            $brands = $responseBramd['data'];
            $responseCate = $this->modelCategory->getCategories();
            $categories = $responseCate['data'];
            return view('admin.product.product_add', compact('brands', 'categories'));
        } return redirect(route('screen_admin_login'))->with('message', 'Role must admin!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if ($this->checkRoleAdmin()){
                $this->validateProduct($request);
                $response = $this->model->addProduct($request);
                $message = $response['message'];
            } else {
                return redirect(route('screen_admin_login'))->with('message', 'Role must admin!');
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($this->checkRoleAdmin()){
            $response = $this->model->getProduct($id);
            if (!$response['status']){
                $message = $response['message'];
                return redirect(route('admin.product.index'))->with('message', $message);
            }
            $product = $response['data'];
            return view('admin.product.product_view', compact('product'));
        } return redirect(route('screen_admin_login'))->with('message', 'Role must admin!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($this->checkRoleAdmin()){
            $response = $this->model->getProduct($id);
            if (!$response['status']){
                $message = $response['message'];
                return redirect(route('admin.product.index'))->with('message', $message);
            }
            $product = $response['data'];

            $responseBramd = $this->modelBrand->getBrands();
            $brands = $responseBramd['data'];
            $responseCate = $this->modelCategory->getCategories();
            $categories = $responseCate['data'];
            return view('admin.product.product_edit', compact('product', 'brands', 'categories'));
        } return redirect(route('screen_admin_login'))->with('message', 'Role must admin!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            if($this->checkRoleAdmin()){
                $this->validateProduct($request);
                $response = $this->model->updateProduct($request, $id);
                $message = $response['message'];
            } else {
                return redirect(route('screen_admin_login'))->with('message', 'Role must admin!');
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            if($this->checkRoleAdmin()){
                $response = $this->model->deleteProduct($id);
                $message = $response['message'];
            } else {
                return redirect(route('screen_admin_login'))->with('message', 'Role must admin!');
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }
}
