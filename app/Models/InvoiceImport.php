<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Lang;

class InvoiceImport extends Model
{
    use HasFactory, ResponseTraits;

    protected $table = 'invoice_import';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'into_money',
        'status',
    ];

    /**
     * Relation with detail invoice import
     *
     * @return HasMany
     */
    public function detailInvoiceImport()
    {
        return $this->hasMany(DetailInvoiceImport::class, 'invoice_import_id');
    }

    /**
     * Relation with user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get invoices import
     *
     * @return array|void
     */
    public function getInvoicesImport()
    {
        try {
            $status = true;
            $message = "";
            $invoicesImport = InvoiceImport::orderBy('id', 'DESC')->get();
            $data = $invoicesImport;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get invoice import
     *
     * @param $id
     * @return array|void
     */
    public function getInvoiceImport($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $invoiceImport = InvoiceImport::where('id', $id)->first();
            $data = null;
            if ($invoiceImport) {
                $status = true;
                $message = null;
                $data = $invoiceImport;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get invoice import paid
     *
     * @param $id
     * @return array|void
     */
    public function getInvoiceImportPaid($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $invoiceImport = InvoiceImport::where([['id', $id], ['status', '1']])->first();
            $data = null;
            if ($invoiceImport) {
                $status = true;
                $message = null;
                $data = $invoiceImport;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get invoice import not paid
     *
     * @param $id
     * @return array|void
     */
    public function getInvoiceImportNotPaid($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $invoiceImport = InvoiceImport::where([['id', $id], ['status', '0']])->first();
            $data = null;
            if ($invoiceImport) {
                $status = true;
                $message = null;
                $data = $invoiceImport;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get invoice import from detail
     *
     * @param $id
     * @return array|void
     */
    public function getInvoiceImportFromDetail($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $detailInvoiceImport = DetailInvoiceImport::find($id);
            $data = null;
            if ($detailInvoiceImport) {
                $status = true;
                $message = null;
                $data = $detailInvoiceImport->invoiceImport->id;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add invoice import
     *
     * @param $request
     * @return array|void
     */
    public function addInvoiceImport($request)
    {
        try {
            $message = '';
            $invoiceImport = new InvoiceImport();
            $invoiceImport->user_id = Auth::id();
            $invoiceImport->save();

            $detailInvoiceImport = new DetailInvoiceImport();
            $detailInvoiceImport->invoice_import_id = $invoiceImport->id;
            $detailInvoiceImport->product_id = $request->product;
            $detailInvoiceImport->quantity = $request->quantity;
            $detailInvoiceImport->price = $request->price;
            $detailInvoiceImport->into_money = $request->quantity * $request->price;
            $detailInvoiceImport->save();

            $invoiceImport->into_money = $detailInvoiceImport->into_money;
            $invoiceImport->save();

            $data = $invoiceImport->id;
            $status = true;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = '';
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Update invoice import
     *
     * @param $request
     * @param $id
     * @return array|void
     */
    public function updateInvoiceImport($request, $id)
    {
        try {
            $status = false;
            $message = '';
            $invoiceImport = InvoiceImport::where([['id', $id], ['status', '0']])->first();
            if (!$invoiceImport) {
                $message = "Can't find";
            } else {
                $detailInvoiceImport = DetailInvoiceImport::where([['invoice_import_id', $id], ['product_id', $request->product]])->first();
                if (!$detailInvoiceImport) {
                    $detailInvoiceImport = new DetailInvoiceImport();
                }

                $detailInvoiceImport->invoice_import_id = $invoiceImport->id;
                $detailInvoiceImport->product_id = $request->product;
                $detailInvoiceImport->quantity = $request->quantity;
                $detailInvoiceImport->price = $request->price;
                $detailInvoiceImport->into_money = $request->quantity * $request->price;
                $detailInvoiceImport->save();

                $listDetailInvoiceImport = DetailInvoiceImport::where('invoice_import_id', $id)->get();
                $invoiceImport->into_money = $listDetailInvoiceImport->sum('into_money');
                $invoiceImport->save();

                $status = true;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }


    /**
     * Delete details invoice import
     *
     * @param $id
     * @return array|void
     */
    public function deleteDetailsInvoiceImport($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.delete_fail');
            $detailInvoiceImport = DetailInvoiceImport::find($id);
            if ($detailInvoiceImport) {
                $invoiceImportId = $detailInvoiceImport->invoice_import_id;
                $detailInvoiceImport->delete();
                $invoiceImport = InvoiceImport::find($invoiceImportId);
                $listDetailInvoiceImport = DetailInvoiceImport::where('invoice_import_id', $invoiceImportId)->get();
                $invoiceImport->into_money = $listDetailInvoiceImport->sum('into_money');
                $invoiceImport->save();

                $data = $invoiceImportId;
                $status = true;
                $message = Lang::get('message.delete_done');
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Pay invoice import
     *
     * @param $id
     * @return array|void
     */
    public function pay($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.pay_fail');
            $invoiceImport = InvoiceImport::find($id);
            if ($invoiceImport) {
                $invoiceImport->status = true;
                $invoiceImport->save();
                $status = true;
                $message = Lang::get('message.pay_done');
                
                $detailInvoiceImport = DetailInvoiceImport::where('invoice_import_id', $id)->get();
                foreach ($detailInvoiceImport as $key => $item){
                    $product = Product::find($item->product_id);
                    $product->quantity = $product->quantity + $item->quantity;
                    $product->save();
                }
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }
}
