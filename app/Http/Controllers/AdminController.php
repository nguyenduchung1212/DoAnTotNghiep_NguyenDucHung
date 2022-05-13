<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleAdminException;
use App\Models\User;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Exception;
use Illuminate\Support\Facades\Lang;

class AdminController extends Controller
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
        $this->model = new User();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|RedirectResponse
     * @throws RoleAdminException
     */
    public function index()
    {
        $this->checkRoleManager();
        $response = $this->model->getAdmins();
        $admins = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.account.accounts', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     * @throws RoleAdminException
     */
    public function create()
    {
        $this->checkRoleManager();
        return view('admin.account.account_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $this->checkRoleManager();
            $this->validateAccount($request);
            $response = $this->model->addAccount($request);
            $message = $response['message'];
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function show($id)
    {
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function edit($id)
    {
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        return back();
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
            if ($this->checkRoleManager()) {
                $response = $this->model->deleteAdmin($id);
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
