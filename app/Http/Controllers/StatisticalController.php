<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use App\Models\InvoiceExport;
use App\Models\Product;
use App\Models\User;

class StatisticalController extends Controller
{
    use ValidateTraits, ResponseTraits;

    private $modelInvoiceExport;
    private $modelProduct;
    private $modelUser;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->modelInvoiceExport = new InvoiceExport();
        $this->modelProduct = new Product();
        $this->modelUser = new User();
    }

    public function statisticalProduct(Request $request){
        $this->checkRoleAdmin();
        $products = null;
        $date = null;
        if (isset($request->date)){
            $startDate = date('Y-m-d',strtotime(substr($request->date,0,10)));
            $endDate = date('Y-m-d',strtotime(substr($request->date,13,23)));
            $date = [
                'start' => $startDate,
                'end' =>$endDate
            ];
            $response = $this->modelInvoiceExport->getProductPaidFromInvoiceExport($startDate, $endDate);
            if(isset($response['data'])){
                $products = $response['data'];
            }
        }
        $response = $this->modelProduct->getProducts();
        $infoProducts = $response['data'];
        return view('admin.statistical.products')->with('products',$products)->with('infoProducts', $infoProducts)->with('date', $date);
    }

    public function statisticalInvoice(Request $request){
        $this->checkRoleAdmin();
        $invoices = null;
        $date = null;
        if (isset($request->date)){
            $startDate = date('Y-m-d',strtotime(substr($request->date,0,10)));
            $endDate = date('Y-m-d',strtotime(substr($request->date,13,23)));
            $date = [
                'start' => $startDate,
                'end' => $endDate
            ];
            $response = $this->modelInvoiceExport->getInvoiceExportPaid($startDate, $endDate);
            if(isset($response['data'])){
                $invoices = $response['data'];
            }
        }
        return view('admin.statistical.invoices')->with('invoices',$invoices)->with('date', $date);
    }

    public function statisticalUser(Request $request){
        $this->checkRoleAdmin();
        $invoices = null;
        $date = null;
        if (isset($request->date)){
            $startDate = date('Y-m-d',strtotime(substr($request->date,0,10)));
            $endDate = date('Y-m-d',strtotime(substr($request->date,13,23)));
            $date = [
                'start' => $startDate,
                'end' => $endDate
            ];
            $response = $this->modelInvoiceExport->getUsersPaid($startDate, $endDate);
            if(isset($response['data'])){
                $invoices = $response['data'];
            }
        }
        return view('admin.statistical.users')->with('invoices',$invoices)->with('date', $date);
    }
}
