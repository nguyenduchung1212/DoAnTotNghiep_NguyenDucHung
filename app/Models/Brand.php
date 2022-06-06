<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class Brand extends Model
{
    use HasFactory, ResponseTraits;

    protected $table = 'brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
    ];

    private $modelProduct;
    private $url;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->modelProduct = new Product();
        $this->url = Config::get('app.image.url');
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
     * Relation with brand
     *
     * @return HasMany
     */
    public function product()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    /**
     * Get brands
     *
     * @return array
     */
    public function getNameBrand($request)
    {
        try {
            $brands = Brand::where('name', 'like', '%'.$request->brand.'%')->get();
            $status = true;
            $message = null;
            $data = $brands;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get brands
     *
     * @return array
     */
    public function getBrands()
    {
        try {
            $brands = Brand::orderBy('id', 'DESC')->get();
            $status = true;
            $message = null;
            $data = $brands;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get brand
     *
     * @param $id
     * @return array
     */
    public function getBrand($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $brand = Brand::find($id);
            $data = null;
            if ($brand) {
                $status = true;
                $message = null;
                $data = $brand;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add brand
     *
     * @param $request
     * @return array
     */
    public function addBrand($request)
    {
        try {
            if (Brand::where('name', $request->name)->first()) {
                $message = Lang::get('message.exist');
                $status = false;
                return $this->responseData($status, $message);
            }

            $brand = new Brand();
            $brand->name = $request->name;
            $brand->user_id = Auth::id();
            if ($request->image_brand) {
                $image = $this->modelProduct->checkImage($request->image_brand);
                if ($image['status']) {
                    $newImage = date('Ymdhis') . '.' . $request->image_brand->getClientOriginalExtension();
                    $brand->image = $this->url . $newImage;
                    $request->image_brand->move($this->url, $newImage);
                    $brand->save();
                    $status = true;
                    $message = Lang::get('message.add_done');
                } else {
                    throw new Exception($image['message']);
                }
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Update brand with id
     *
     * @param $request
     * @param $id
     * @return array
     */
    public function updateBrand($request, $id)
    {
        try {
            $status = false;
            $message = Lang::get('message.exist');
            $data = null;
            $brand = Brand::find($id);
            if ($brand) {
                $brand_check = Brand::where('name', $request->name)->first();
                if (($brand_check) && $brand->id !== $brand_check->id) {
                    return $this->responseData($status, $message);
                }
                $brand->name = $request->name;
                $brand->user_id = Auth::id();

                if ($request->image_brand_edit) {
                    $image = $this->modelProduct->checkImage($request->image_brand_edit);
                    if ($image['status']) {
                        $newImage = date('Ymdhis') . '.' . $request->image_brand_edit->getClientOriginalExtension();
                        $brand->image = $this->url . $newImage;
                        $request->image_brand_edit->move($this->url, $newImage);
                    } else {
                        throw new Exception($image['message']);
                    }
                }

                $brand->save();
                $status = true;
                $message = Lang::get('message.update_done');
            } else {
                $message = Lang::get('message.can_not_find');
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }
}
