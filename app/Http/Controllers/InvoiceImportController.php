<?php

namespace App\Http\Controllers;

use App\Exceptions\RoleAdminException;
use App\Models\InvoiceImport;
use App\Models\Product;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Routing\Redirector;

class InvoiceImportController extends Controller
{

    use ValidateTraits, ResponseTraits;

    private $model;
    private $modelProduct;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new InvoiceImport();
        $this->modelProduct = new Product();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|RedirectResponse
     * @throws RoleAdminException
     */
    public function index()
    {
        $this->checkRoleAdmin();
        $response = $this->model->getInvoicesImport();
        $invoicesImport = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.invoice_import.invoices_import', compact('invoicesImport'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     * @throws RoleAdminException
     */
    public function create()
    {
        $this->checkRoleAdmin();
        $response = $this->modelProduct->getProducts();
        $products = $response['data'];
        return view('admin.invoice_import.invoice_import_add', compact('products'));
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
            $this->checkRoleAdmin();
            $this->validateDetailInvoiceImport($request);
            $response = $this->model->addInvoiceImport($request);
            $message = $response['message'];
            $data = $response['data'];
        } catch (Exception $e) {
            $message = $e->getMessage();
            return back()->with('message', $message);
        }
        if (!$response['status']){
            return back()->with('message', $message);
        }else {
            return redirect(route('admin.invoice_import.edit', ['invoice_import' => $data]))->with('message', $message);
        }
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
        $this->checkRoleAdmin();
        $response = $this->model->getInvoiceImport($id);
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.invoice_import.index'), compact('message'));
        }

        $response = $this->model->getInvoiceImportPaid($id);
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.invoice_import.show', ['invoice_import' => $id]), compact('message'));
        }

        $invoiceImport = $response['data'];
        if (!$response['status']) {
            $message = $response['message'];
            return back()->with('message', $message);
        }
        return view('admin.invoice_import.invoice_import_show', compact('invoiceImport'));
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
        $this->checkRoleAdmin();
        $response = $this->model->getInvoiceImport($id);
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.invoice_import.index'), compact('message'));
        }

        $response = $this->model->getInvoiceImportNotPaid($id);
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.invoice_import.show', ['invoice_import' => $id]), compact('message'));
        }
        $invoiceImport = $response['data'];

        $response = $this->modelProduct->getProducts();
        $products = $response['data'];
        if (!$response['status']) {
            $message = $response['message'];
            return back()->with('message', $message);
        }
        return view('admin.invoice_import.invoice_import_edit', compact('invoiceImport', 'products'));
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
            $this->checkRoleAdmin();
            $this->validateDetailInvoiceImport($request);
            $response = $this->model->getInvoiceImport($id);
            $message = $response['message'];
            if (!$response['status']) {
                return redirect(route('admin.invoice_import.show', ['invoice_import' => $id]), compact('message'));
            }
            $response = $this->model->updateInvoiceImport($request, $id);
            $message = $response['message'];
        } catch (Exception $e) {
            $message = $e->getMessage();
            return back()->with('message', $message);
        }
        return back()->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Application|RedirectResponse|Redirector
     * @throws RoleAdminException
     */
    public function destroy($id)
    {
        $this->checkRoleAdmin();
        $response = $this->model->getInvoiceImportFromDetail($id);
        $invoice_import_id = $response['data'];
        $response = $this->model->getInvoiceImport($invoice_import_id);
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.invoice_import.index'), compact('message'));
        }

        $response = $this->model->getInvoiceImportNotPaid($invoice_import_id);
        if (!$response['status']) {
            $message = $response['message'];
            return redirect(route('admin.invoice_import.show', ['invoice_import' => $invoice_import_id]), compact('message'));
        }
        $response = $this->model->deleteDetailsInvoiceImport($id);
        $message = $response['message'];
        return redirect(route('admin.invoice_import.edit', ['invoice_import' => $invoice_import_id]))->with('message', $message);
    }

    /**
     * Pay invoice import
     *
     * @param $id
     * @return Application|RedirectResponse|Redirector
     * @throws RoleAdminException
     */
    public function pay($id)
    {
        $this->checkRoleAdmin();
        $response = $this->model->getInvoiceImportNotPaid($id);
        if (!$response['status']) {
            $message = $response['message'];
        } else {
            $response = $this->model->pay($id);
            $message = $response['message'];
        }
        return redirect(route('admin.invoice_import.show', ['invoice_import' => $id]))->with('message', $message);
    }
}
