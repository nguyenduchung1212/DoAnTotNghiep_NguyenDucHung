<?php

namespace App\Http\Controllers;

use App\Models\InvoiceExport;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use App\Exceptions\RoleAdminException;

class InvoiceExportController extends Controller
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
        $this->model = new InvoiceExport();
    }

    /**
     * Orders list
     *
     * @return Application|Factory|View|RedirectResponse
     * @throws RoleAdminException
     */
    public function orders()
    {
        $this->checkRoleAdmin();
        $response = $this->model->getOrders();
        $orders = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.invoice_export.orders', compact('orders'));
    }

    /**
     * Order view
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     * @throws RoleAdminException
     */
    public function order($id)
    {
        $this->checkRoleAdmin();
        $response = $this->model->getOrder($id);
        $order = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.invoice_export.order_view', compact('order'));
    }

    /**
     * Accept order
     *
     * @param $id
     * @return Application|RedirectResponse|Redirector
     * @throws RoleAdminException
     */
    public function acceptOrder($id)
    {
        $this->checkRoleAdmin();
        $response = $this->model->acceptOrder($id);
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return redirect(route('admin.invoice_export.order'))->with('message', $message);
    }

    /**
     * Cancel order
     *
     * @param $id
     * @return RedirectResponse
     * @throws RoleAdminException
     */
    public function cancelOrder($id)
    {
        $this->checkRoleAdmin();
        $response = $this->model->cancelOrder($id);
        $message = $response['message'];
        return back()->with('message', $message);
    }

    /**
     * Invoice list
     *
     * @return Application|Factory|View|RedirectResponse
     * @throws RoleAdminException
     */
    public function invoices()
    {
        $this->checkRoleAdmin();
        $response = $this->model->getInvoices();
        $invoices = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.invoice_export.invoices', compact('invoices'));
    }

    /**
     * Up status ship
     *
     * @param $id
     * @return RedirectResponse
     * @throws RoleAdminException
     */
    public function upStatusShip($id)
    {
        $this->checkRoleAdmin();
        $response = $this->model->upStatusShip($id);
        $message = $response['message'];
        return back()->with('message', $message);
    }

    /**
     * Orders view
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     * @throws RoleAdminException
     */
    public function invoice($id)
    {
        $this->checkRoleAdmin();
        $response = $this->model->getInvoice($id);
        $invoice = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.invoice_export.invoice_view', compact('invoice'));
    }

    /**
     * Close orders list
     *
     * @return Application|Factory|View|RedirectResponse
     * @throws RoleAdminException
     */
    public function closeOrders()
    {
        $this->checkRoleAdmin();
        $response = $this->model->getCloseOrders();
        $closeOrders = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.invoice_export.close_orders', compact('closeOrders'));
    }

    /**
     * Close order view
     *
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     * @throws RoleAdminException
     */
    public function closeOrder($id)
    {
        $this->checkRoleAdmin();
        $response = $this->model->getCloseOrder($id);
        $closeOrder = $response['data'];
        $message = $response['message'];
        if (!$response['status']) {
            return back()->with('message', $message);
        }
        return view('admin.invoice_export.close_order_view', compact('closeOrder'));
    }
}
