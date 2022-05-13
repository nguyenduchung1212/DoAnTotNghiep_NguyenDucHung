<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailInvoiceImport extends Model
{
    use HasFactory;

    protected $table = 'details_invoice_import';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_import_id',
        'product_id',
        'quantity',
        'price',
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
     * Relation with invoice import
     *
     * @return BelongsTo
     */
    public function invoiceImport()
    {
        return $this->belongsTo(InvoiceImport::class, 'invoice_import_id');
    }
}
