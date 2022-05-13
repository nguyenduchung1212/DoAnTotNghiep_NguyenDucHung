<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class InvoiceExport extends Model
{
    use HasFactory, ResponseTraits;

    protected $table = 'invoice_export';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code_invoice',
        'into_money',
        'user_id',
        'admin_id',
        'status_ship_id',
        'is_pay_cod',
        'is_payment',
        'is_done',
        'reason',
        'message',
        'email_user',
        'phone_user',
        'name_user'
    ];

    /**
     * Relation with user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation with admin
     *
     * @return BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relation with detail invoice export
     *
     * @return HasMany
     */
    public function detailInvoiceExport()
    {
        return $this->hasMany(DetailInvoiceExport::class, 'invoice_export_id');
    }

    /**
     * Get 0rders
     *
     * @return array
     */
    public function getOrders(){
        try {
            $status = true;
            $message = "";
            $orders = InvoiceExport::where([['status_ship', Lang::get('message.received')]])->orderBy('id', 'DESC')->get();
            if ($orders){
                $data = $orders;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get order
     *
     * @param $id
     * @return array
     */
    public function getOrder($id){
        try {
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            $order = InvoiceExport::where([['id', $id], ['status_ship', Lang::get('message.received')]])->first();

            if ($order){
                $data = $order;
                $status = true;
                $message = "";
            }

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Accept order
     *
     * @param $id
     * @return array
     */
    public function acceptOrder($id){
        try {
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            $order = InvoiceExport::where([['id', $id], ['status_ship', Lang::get('message.received')]])->first();

            if ($order){
                $order->status_ship = Lang::get('message.ready');
                $order->admin_id = Auth::id();
                $detailsInvoiceExport = DetailInvoiceExport::where('invoice_export_id', $order->id)->get();
                if ($detailsInvoiceExport){
                    foreach ($detailsInvoiceExport as $key => $detailInvoiceExport){
                        $product = Product::find($detailInvoiceExport->product_id);
                        $product->quantity = $product->quantity - $detailInvoiceExport->quantity;
                        $product->save();
                    }
                }
                $order->save();
                $status = true;
                $message = Lang::get('message.accept_done');
            }

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Cancel order
     *
     * @param $id
     * @return array
     */
    public function cancelOrder($id){
        try {
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            $order = InvoiceExport::where('id', $id)->first();

            if ($order){
                $order->status_ship = Lang::get('message.canceled');
                $order->is_payment = 0;
                $order->save();
                $detailsInvoiceExport = DetailInvoiceExport::where('invoice_export_id', $order->id)->get();
                if ($detailsInvoiceExport){
                    foreach ($detailsInvoiceExport as $key => $detailInvoiceExport){
                        $product = Product::find($detailInvoiceExport->product_id);
                        $product->quantity = $product->quantity + $detailInvoiceExport->quantity;
                        $product->save();
                    }
                }
                $status = true;
                $message = Lang::get('message.cancel_done');
            }

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

     /**
     * Get invoices
     *
     * @return array
     */
    public function getInvoices(){
        try {
            $status = true;
            $message = "";
            $invoices = InvoiceExport::whereNotIn('status_ship', [Lang::get('message.canceled'), Lang::get('message.received')])
                                    ->orderBy('id', 'DESC')
                                    ->get();
            if ($invoices){
                $data = $invoices;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Up status ship
     *
     * @param $id
     * @return array
     */
    public function upStatusShip($id){
        try {
            $status = true;
            $message = Lang::get('message.can_not_find');
            $order = InvoiceExport::where('id', $id)
                                    ->whereNotIn('status_ship', [Lang::get('message.canceled'), Lang::get('message.ship_done')])
                                    ->first();
            if ($order){
                $statusShip = $order->status_ship;
                if ($statusShip === Lang::get('message.ready')){
                    $order->status_ship = Lang::get('message.shipping');
                } else if ($statusShip === Lang::get('message.shipping')){
                    $order->status_ship = Lang::get('message.ship_done');
                    $order->is_payment = 1;
                } else {
                    $message = Lang::get('message.update_fail');
                }
                $order->save();
                $message = Lang::get('message.update_done');
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Get invoice
     *
     * @param $id
     * @return array
     */
    public function getInvoice($id){
        try {
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            $invoice = InvoiceExport::where('id', $id)
                                    ->whereNotIn('status_ship', [Lang::get('message.canceled'), Lang::get('message.received')])
                                    ->first();

            if ($invoice){
                $data = $invoice;
                $status = true;
                $message = "";
            }

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get close orders
     *
     * @return array
     */
    public function getCloseOrders(){

        try {
            $status = true;
            $message = "";
            $closeOrders = InvoiceExport::where([['status_ship', Lang::get('message.canceled')]])->orderBy('id', 'DESC')->get();
            if ($closeOrders){
                $data = $closeOrders;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get close order
     *
     * @param $id
     * @return array
     */
    public function getCloseOrder($id){

        try {
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            $closeOrder = InvoiceExport::where([['id', $id], ['status_ship', Lang::get('message.canceled')]])->first();

            if ($closeOrder){
                $data = $closeOrder;
                $status = true;
                $message = "";
            }

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    public function getProductPaidFromInvoiceExport($startDate, $endDate){
        try{
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            $invoices = InvoiceExport::where('status_ship', Lang::get('message.ship_done'))                                
                                        ->whereDate  ('created_at', '>=', $startDate)
                                        ->WhereDate  ('created_at', '<=', $endDate)
                                        ->orderBy('id', 'DESC')
                                        ->get();
            if (isset($invoices)){
                $products = [];
                foreach ($invoices as $keyInvoices => $invoice){
                    $detailsInvoice = DetailInvoiceExport::where('invoice_export_id', $invoice->id)->get();
                    if (isset($detailsInvoice)){
                        foreach ($detailsInvoice as $keyDetail => $detailInvoice){
                            if (!isset($products[$detailInvoice->product_id])){
                                $products[$detailInvoice->product_id] = 0;
                            }
                            $products[$detailInvoice->product_id] = $products[$detailInvoice->product_id] + $detailInvoice->quantity;
                        }
                    }
                }
            }
            if (isset($products)){
                $status = true;
                $data = $products;
                $message = '';
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    public function getInvoiceExportPaid($startDate, $endDate){
        try{
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            $invoices = InvoiceExport::where('status_ship', Lang::get('message.ship_done'))                                
                                        ->whereDate  ('created_at', '>=', $startDate)
                                        ->WhereDate  ('created_at', '<=', $endDate)
                                        ->orderBy('id', 'DESC')
                                        ->get();
            if (isset($invoices)){
                $status = true;
                $data = $invoices;
                $message = '';
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    public function getUsersPaid($startDate, $endDate){
        try{
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            if (isset($invoices)){
                $status = true;
                $data = $invoices;
                $message = '';
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }
}