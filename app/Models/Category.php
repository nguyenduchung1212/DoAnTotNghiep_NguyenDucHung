<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
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
     * Get categories
     *
     * @return array|void
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
     * @return array|void
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
     * @return array|void
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
            $category->save();
            $status = true;
            $message = Lang::get('message.add_done');
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
     * @return array|void
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
