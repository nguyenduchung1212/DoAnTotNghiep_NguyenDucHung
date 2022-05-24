<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ResponseTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class SideBar extends Model
{

    use HasFactory, ResponseTraits;
    protected $table = 'sidebar';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
     * Get side bars
     *
     * @return array
     */
    public function getSidebar(){
        try{
            $sidebars = SideBar::orderBy('id', 'DESC')->get();
            $status = true;
            $message = null;
            $data = $sidebars;
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $data = null;
        }
        return $this->responseData($status, $message, $data);
    }

    /**
     * Add sidebar
     *
     * @param $request
     * @return array
     */
    public function addSidebar($request)
    {
        try {
            $sidebar = new Sidebar();
            if ($request->image_sidebar) {
                $image = $this->modelProduct->checkImage($request->image_sidebar);
                if ($image['status']) {
                    $newImage = date('Ymdhis') . '.' . $request->image_sidebar->getClientOriginalExtension();
                    $sidebar->image = $this->url . $newImage;
                    $request->image_sidebar->move($this->url, $newImage);
                    $sidebar->save();
                    
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
}
