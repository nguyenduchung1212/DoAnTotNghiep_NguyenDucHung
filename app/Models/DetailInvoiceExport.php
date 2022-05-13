<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailInvoiceExport extends Model
{
    use HasFactory, ResponseTraits;

    protected $table = 'details_invoice_export';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_export_id',
        'product_id',
        'quantity',
        'into_money',
    ];

    /**
     * Relation with product
     *
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relation with invoice export
     *
     * @return BelongsTo
     */
    public function invoiceExport()
    {
        return $this->belongsTo(InvoiceExport::class, 'invoice_export_id');
    }
}
