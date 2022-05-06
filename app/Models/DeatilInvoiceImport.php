<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeatilInvoiceImport extends Model
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
}
