<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Lang;

class Product extends Model
{
    use HasFactory, ResponseTraits;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'quantity',
        'image',
        'short_description',
        'active',
        'is_deleted'
    ];

    private $url;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->url = Config::get('app.image.url');
    }

    /**
     * Relation with brand
     *
     * @return BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Relation with category
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
     * Get products
     *
     * @return array|void
     */
    public function getProducts()
    {
        try {
            $products = Product::where('is_deleted', false)
                ->orderBy('id', 'DESC')
                ->get();
            $status = true;
            $message = null;
            $data = $products;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get product
     *
     * @param $id
     * @return array|void
     */
    public function getProduct($id)
    {
        try {
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            $products = Product::find($id);

            if ($products && !$products->is_deleted) {
                $status = true;
                $message = null;
                $data = $products;
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add product
     *
     * @param $request
     * @return array|void
     */
    public function addProduct($request)
    {
        try {
            $product = new Product();
            $product->name = $request->name;
            $product->brand_id = $request->brand;
            $product->category_id = $request->category;
            $product->price = $request->price;
            $product->short_description = $request->short_description;
            $product->user_id = Auth::id();
            if ($request->image) {
                $image = $this->checkImage($request->image);
                if ($image['status']) {
                    $new_image = date('Ymdhis') . '.' . $request->image->getClientOriginalExtension();
                    $product->image = $this->url . $new_image;
                    $request->image->move($this->url, $new_image);

                } else {
                    throw new Exception($image['message']);
                }
            }
            if ($request->active) {
                $product->active = true;
            }
            $product->save();
            $status = true;
            $message = Lang::get('message.add_done');
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Update product
     *
     * @param $request
     * @param $id
     * @return array|void
     */
    public function updateProduct($request, $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                $message = Lang::get('message.can_not_find');
                throw new Exception($message);
            }
            $product->name = $request->name;
            $product->brand_id = $request->brand;
            $product->category_id = $request->category;
            $product->price = $request->price;
            $product->short_description = $request->short_description;
            $product->user_id = Auth::id();

            if ($request->image) {
                $image = $this->checkImage($request->image);
                if ($image['status']) {
                    if (File::exists($product->image)) {
                        File::delete($product->image);
                    }
                    $new_image = date('Ymdhis') . '.' . $request->image->getClientOriginalExtension();
                    $product->image = $this->url . $new_image;
                    $request->image->move($this->url, $new_image);

                } else {
                    throw new Exception($image['message']);
                }
            }

            if ($request->active) {
                $product->active = true;
            }
            $product->save();
            $status = true;
            $message = Lang::get('message.update_done');

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Delete product
     *
     * @param $id
     * @return array|void
     */
    public function deleteProduct($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.delete_fail');

            $product = Product::find($id);
            if ($product) {
                $product->is_deleted = true;
                $product->save();
                $status = true;
                $message = Lang::get('message.delete_done');
                if (File::exists($product->image)) {
                    File::delete($product->image);
                }
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Check image
     *
     * @param $image
     * @return array|void
     */
    private function checkImage($image)
    {
        $status = true;
        $message = null;
        $allowed = array('gif', 'png', 'jpg', 'jpeg');
        if (!in_array($image->getClientOriginalExtension(), $allowed)) {
            $status = false;
            $message = Lang::get('message.not_image');
        } else {
            $size = number_format($image->getSize() / Config::get('app.image.ratio'), 2);
            if ($size > Config::get('app.image.max')) {
                $status = false;
                $message = Lang::get('message.file_langer') . Config::get('app.image.max') . Config::get('app.image.unit_file');
            }
        }
        return $this->responseData($status, $message);
    }
}
