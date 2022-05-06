<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleAdminException;
use App\Models\Category;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ValidateTraits, ResponseTraits;

    private $model;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Category();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkRoleAdmin();
        $response = $this->model->getCategories();
        $categories = $response['data'];
        $message = $response['message'];
        if (!$response['status']){
            return back()->with('message', $message);
        }
        return view('admin.category.categories', compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkRoleAdmin();
        return view('admin.category.category_add');
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
            $this->checkRoleAdmin();
            $this->validateCategory($request);
            $response = $this->model->addCategory($request);
            $message = $response['message'];
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->checkRoleAdmin();
        $response = $this->model->getCategory($id);
        if (!$response['status']){
            $message = $response['message'];
            return redirect(route('admin.category.index'))->with('message', $message);
        }
        $category = $response['data'];
        return view('admin.category.category_edit', compact('category'));
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
                $this->validateCategory($request);
                $response = $this->model->updateCategory($request, $id);
                $message = $response['message'];
            } else {
                Throw new RoleAdminException();
            }
        } catch(Exception $e){
            $message = $e->getMessage();
        }
        return redirect(route('admin.category.edit', ['category'=>$id]))->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return back();
    }
}
