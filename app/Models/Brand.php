<?php

namespace App\Models;

use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Exception;
use Illuminate\Support\Facades\Auth;

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
     * @return void
     */
    public function getBrands(){
        try{
            $categories = Brand::orderBy('id', 'DESC')->get();
            $status = true;
            $message = null;
            $data = $categories;
        } catch(Exception $e){
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

     /**
     * Get brand
     *
     * @return void
     */
    public function getBrand($id){
        try{
            $status = false;
            $message =  "Can't find brand !!!";
            $brand = Brand::find($id);
            $data = null;
            if ($brand){
                $status = true;
                $message =  null;
                $data =  $brand;
            }
        }catch(Exception $e){
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add brand
     *
     * @param  mixed $request
     * @return void
     */
    public function addBrand($request){
        try{
            if (Brand::where('name', $request->name)->first()){
                $message = 'Exist brand !!!';
                $status = false;
                return $this->responseData($status, $message);
            }

            $category = new Brand();
            $category->name = $request->name;
            $category->user_id = Auth::id();
            $category->save();
            $status = true;
            $message = 'Add brand successful !';
        }catch(Exception $e){
            $status = false;
            $message = $e->getMessage();
        }
        return $this->responseData($status, $message);
    }

    /**
     * Update brand with id
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function updateBrand($request, $id){
        try{
            $status =  false;
            $message =  "Exist brand !!!";
            $data = null;
            $brand = Brand::find($id);
            if ($brand){
                $brand_check = Brand::where('name', $request->name)->first();
                if (($brand_check) && $brand->id !== $brand_check->id){
                    return $this->responseData($status, $message);
                }
                $brand->name = $request->name;
                $brand->user_id = Auth::id();
                $brand->save();
                $status =  true;
                $message =  "Update successful";
            } else{
                $message =  "Can't find brand !!!";
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }
}
