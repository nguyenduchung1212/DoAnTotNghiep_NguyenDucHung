<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ResponseTraits;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory, ResponseTraits;

    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'description'
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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get comments
     *
     * @return array
     */
    public function getComments($id){
        try {
            $comments = Comment::where('product_id', $id)->orderBy('id', 'DESC')->get();
            $status = true;
            $message = null;
            $data = $comments;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add comments
     *
     * @return array
     */
    public function addComments($request, $id){
        try {
            $status = false;
            $data = null;
            $comment = new Comment();
            $comment->user_id = Auth::id();
            $comment->product_id = $id;
            $comment->description = $request->description;
            $comment->save();
            $status = true;
            $message = null;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }
}
