<?php

namespace App\Models;

use App\Models\Category;
use App\Traits\ResponseTraits;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

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
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->url =  Config::get('app.image.url');
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
     * @return void
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
     * @return void
     */
    public function getProduct($id)
    {
        try {
            $status = false;
            $data = null;
            $message =  "Can't find product !!!";
            $products = Product::find($id);

            if ($products && !$products->is_deleted){
                $status = true;
                $message =  null;
                $data =  $products;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add product
     *
     * @param mixed $request
     * @return void
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
            if ($request->active){
                $product->active = true;
            }
            $product->save();
            $status = true;
            $message = 'Add product successful !';
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Update product
     *
     * @param mixed $request
     * @return void
     */
    public function updateProduct($request, $id)
    {
        try {
            $product = Product::find($id);
            if(!$product){
                $message = "Can't find product";
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
                    if(File::exists($product->image)){
                        File::delete($product->image);
                    }
                    $new_image = date('Ymdhis') . '.' . $request->image->getClientOriginalExtension();
                    $product->image = $this->url . $new_image;
                    $request->image->move($this->url, $new_image);

                } else {
                    throw new Exception($image['message']);
                }
            }

            if ($request->active){
                $product->active = true;
            }
            $product->save();
            $status = true;
            $message = 'Update product successful !';

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }


    /**
     * Delete product
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteProduct($id){
        try{
            $status = false;
            $message = "Can't delete product";
            $product = Product::find($id);
            if($product){
                $product->is_deleted = true;
                $product->save();
                $status = true;
                $message = "Delete product successful !";
                 if(File::exists($product->image)){
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
     * @return void
     */
    public function checkImage($image)
    {
        $status = true;
        $message = null;
        $allowed = array('gif', 'png', 'jpg', 'jpeg');
        if (!in_array($image->getClientOriginalExtension(), $allowed)) {
            $status = false;
            $message = "This isn't an image file";
        } else {
            $size = number_format($image->getSize() / Config::get('app.image.ratio'), 2);
            if ($size > Config::get('app.image.max')) {
                $status = false;
                $message = 'Files larger than ' . Config::get('app.image.max') . ' MB';
            }
        }
        return $this->responseData($status, $message);
    }
}
