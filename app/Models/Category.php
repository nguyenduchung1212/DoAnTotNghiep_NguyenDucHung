<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class Category extends Model
{
    use HasFactory, ResponseTraits;

    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
    ];

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
     * Relation with category
     *
     * @return HasMany
     */
    public function product()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

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
     * Get categories
     *
     * @return array
     */
    public function getNameCategory($request)
    {
        try {
            $categories = Category::where('name', 'like', '%'.$request->category.'%')->get();
            $status = true;
            $message = null;
            $data = $categories;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get categories
     *
     * @return array
     */
    public function getCategories()
    {
        try {
            $categories = Category::orderBy('id', 'DESC')->get();
            $status = true;
            $message = null;
            $data = $categories;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Get category
     *
     * @param $id
     * @return array
     */
    public function getCategory($id)
    {
        try {
            $status = false;
            $message = Lang::get('message.can_not_find');
            $category = Category::find($id);
            $data = null;
            if ($category) {
                $status = true;
                $message = null;
                $data = $category;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add category
     *
     * @param $request
     * @return array
     */
    public function addCategory($request)
    {
        try {
            if (Category::where('name', $request->name)->first()) {
                $message = 'Exist category !!!';
                $status = false;
                return $this->responseData($status, $message);
            }

            $category = new Category();
            $category->name = $request->name;
            $category->user_id = Auth::id();
            if ($request->image_category) {
                $image = $this->modelProduct->checkImage($request->image_category);
                if ($image['status']) {
                    $newImage = date('Ymdhis') . '.' . $request->image_category->getClientOriginalExtension();
                    $category->image = $this->url . $newImage;
                    $request->image_category->move($this->url, $newImage);
                    $category->save();
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
     * Update category with id
     *
     * @param $request
     * @param $id
     * @return array
     */
    public function updateCategory($request, $id)
    {
        try {
            $status = false;
            $message = Lang::get('message.exist');
            $data = null;
            $category = Category::find($id);
            if ($category) {
                $category_check = Category::where('name', $request->name)->first();
                if ($category_check && $category->id !== $category_check->id) {
                    return $this->responseData($status, $message);
                }
                $category->name = $request->name;
                $category->user_id = Auth::id();
                if ($request->image_category_edit) {
                    $image = $this->modelProduct->checkImage($request->image_category_edit);
                    if ($image['status']) {
                        $newImage = date('Ymdhis') . '.' . $request->image_category_edit->getClientOriginalExtension();
                        $category->image = $this->url . $newImage;
                        $request->image_category_edit->move($this->url, $newImage);
                    } else {
                        throw new Exception($image['message']);
                    }
                }
                $category->save();
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
