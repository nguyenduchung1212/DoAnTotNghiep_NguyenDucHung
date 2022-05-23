<?php

namespace App\Models;

use App\Models\DetailProduct;
use App\Traits\ResponseTraits;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Lang;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Str;

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
     * Relation with detail product
     *
     * @return HasMany
     */
    public function detailProduct()
    {
        return $this->hasMany(DetailProduct::class, 'product_id');
    }

    /**
     * Filter with category
     *
     * @param $query
     * @param $request
     * @return mixed
     */
    public function scopeCategory($query, $request)
    {
        if ($request->has('category') && !is_null($request->category)) {
            $query->where('category_id', $request->category);
        }
        return $query;
    }

    /**
     * Filter with brand
     *
     * @param $query
     * @param $request
     * @return mixed
     */
    public function scopeBrand($query, $request)
    {
        if ($request->has('brand') && !is_null($request->brand)) {
            $query->where('brand_id', $request->brand);
        }
        return $query;
    }

    /**
     * Filter with name
     *
     * @param $query
     * @param $request
     * @return mixed
     */
    public function scopeTitle($query, $request)
    {
        if ($request->has('product') && !is_null($request->product)) {
            $query->where('name', 'LIKE', '%' . $request->product . '%');
        }
        return $query;
    }

    /**
     * Get products
     *
     * @return array
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
     * @return array
     */
    public function getProduct($id)
    {
        try {
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            $product = Product::find($id);

            if ($product && !$product->is_deleted) {
                $status = true;
                $message = null;
                $data = $product;
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get detail product
     *
     * @param $id
     * @return array
     */
    public function getDetailProduct($id)
    {
        try {
            $status = false;
            $data = null;
            $message = Lang::get('message.can_not_find');
            $detailsProduct = DetailProduct::where('product_id',$id)->get();

            if ($detailsProduct ) {
                $status = true;
                $message = null;
                $data = $detailsProduct;
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
     * @return array
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
                    $newImage = date('Ymdhis') . '.' . $request->image->getClientOriginalExtension();
                    $product->image = $this->url . $newImage;
                    $request->image->move($this->url, $newImage);

                } else {
                    throw new Exception($image['message']);
                }
            }
            if ($request->active) {
                $product->active = true;
            }
            $product->save();
            if (isset($request->image_detail)){
                foreach ($request->image_detail as $image_detail){
                    $detailProduct = new DetailProduct();
                    $detailProduct->product_id = $product->id;
                    $detailImage = $this->checkImage($image_detail);
                    if($detailImage['status']) {
                        $newDeatilImage = date('Ymdhis'). Str::random(10) . '.' . $image_detail->getClientOriginalExtension();
                        $detailProduct->image = $this->url . $newDeatilImage;
                        $image_detail->move($this->url, $newDeatilImage);
                        $detailProduct->save();
                    } else {
                        throw new Exception($detailImage['message']);
                    }
                }
            }

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
     * @return array
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

            if (isset($request->image_detail)){
                foreach ($request->image_detail as $key => $image_detail){
                    $detailProduct = DetailProduct::find($key);
                    $detailImage = $this->checkImage($image_detail);
                    if($detailImage['status']) {
                        $newDeatilImage = date('Ymdhis'). Str::random(10) . '.' . $image_detail->getClientOriginalExtension();
                        $detailProduct->image = $this->url . $newDeatilImage;
                        $image_detail->move($this->url, $newDeatilImage);
                        $detailProduct->save();
                    } else {
                        throw new Exception($detailImage['message']);
                    }
                }
            }
            if (isset($request->image_detail_new)) {
                 foreach ($request->image_detail_new as $key => $image_detail_new){
                    $detailProduct = new DetailProduct();
                    $detailProduct->product_id = $product->id;
                    $detailImage = $this->checkImage($image_detail_new);
                    if($detailImage['status']) {
                        $newDeatilImage = date('Ymdhis'). Str::random(10) . '.' . $image_detail_new->getClientOriginalExtension();
                        $detailProduct->image = $this->url . $newDeatilImage;
                        $image_detail_new->move($this->url, $newDeatilImage);
                        $detailProduct->save();
                    } else {
                        throw new Exception($detailImage['message']);
                    }
                }
            }

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
     * @return array
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
     * Search product with scope
     *
     * @param $request
     * @return array
     */
    public function searchProducts($request)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $data = '';
            $products = Product::query()->category($request)->brand($request)->title($request)->where([['active', 1], ['is_deleted', 0], ['quantity', '>', 0]])->orderBy('id', 'DESC')->get();
            $status = true;
            $message = '';
            $data = $products;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add product to cart
     *
     * @param $request
     * @param $id
     * @return array
     */
    public function addProductToCart($request, $id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $data = '';
            $product = Product::find($id);
            if ($product && !$product->is_deleted && $product->active) {
                if ($product->quantity < $request->quantity) {
                    $message = Lang::get('message.quantity_not_enough');
                } else {
                    $status = true;
                    $message = Lang::get('message.add_done');
                    Cart::add(['id' => $product->id, 'name' => $product->name, 'price' => $product->price, 'weight' => 0, 'qty' => $request->quanity]);
                }
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Buy product
     *
     * @param $id
     * @return array
     */
    public function buyProduct($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $data = '';
            $product = Product::find($id);
            if ($product && !$product->is_deleted) {
                $status = true;
                $message = '';
                Cart::add(['id' => $product->id, 'name' => $product->name, 'price' => $product->price, 'weight' => 0, 'qty' => 1]);
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get data cart
     *
     * @return array
     */
    public function getCart()
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $data = Cart::content()->groupBy('id');
            $status = true;
            $message = '';

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Delete product in cart
     *
     * @param $id
     * @return array
     */
    public function deleteProductInCart($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            Cart::remove($id);
            $status = true;
            $message = Lang::get('message.delete_done');

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
     * @return array
     */
    public function checkImage($image)
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
