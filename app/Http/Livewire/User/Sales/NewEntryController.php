<?php

namespace App\Http\Livewire\User\Sales;

use App\Jobs\SendStockLowEmailJob;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sales;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class NewEntryController extends Component
{
    public $regcust;
    public $barcode;
    public $prodQuery;
    public $productId;
    public $productData;
    public $products;
    public $custName;
    public $price;
    public $quantity;
    public $discount = 0;
    protected $newCustomer = true;
    public $customer;
    public $customerid;
    public $customerData;
    public $remarks;
    public $drcr = 1;
    protected $listeners = ['confirmSales' => 'confirmSales'];
    public function render()
    {
        if ($this->barcode) {
            $data = Product::where('prod_barcode', '=', $this->barcode)->get()->toArray();

            if ($data) {
                $this->productId = $data[0]['id'];
                $this->prodQuery = $data[0]['prod_name'];
            }
        } else {
            if ($this->prodQuery) {
                $this->products = Product::where('prod_name', 'like', '%' . $this->prodQuery . '%')
                    ->get()->toArray();
            }
        }
        if ($this->regcust && !empty($this->custName)) {;
            $this->customer = Customer::where('cust_name', 'like', '%' . $this->custName . '%')->get()->toArray();
            $this->newCustomer = false;
        }
        return view('livewire.user.sales.new-entry-controller');
    }
    public function selectProduct($id)
    {
        $this->productId = $id;
        $this->productData = Product::with('supptrans')->find($id)->toArray();
        $this->prodQuery = $this->productData['prod_name'];
        $this->barcode = $this->productData['prod_barcode'];
        $this->price =  $this->productData['prod_selling_price'];
    }
    public function selectCustomer($id)
    {
        $this->customerid = $id;
        $this->customerData = Customer::find($id)->toArray();
        $this->custName = $this->customerData['cust_name'];
    }
    public function saleProduct()
    {
        if ($this->drcr == 2 && $this->customerid == null) {
            $this->dispatchBrowserEvent(
                'swal.toast',
                [
                    'icon' => 'error',
                    'title' => 'Create or Select a Registered Customer'
                ]
            );
            return;
        }
        if ($this->quantity > $this->productData['prod_stock']) {
            $this->dispatchBrowserEvent(
                'swal.toast',
                [
                    'icon' => 'error',
                    'title' => 'Product is out of Stock'
                ]
            );
            return;
        }

        if ($this->productData['prod_selling_price'] > $this->price) {
            $this->dispatchBrowserEvent('swal.confirm');
            return;
        } else {
            $this->confirmSales();
        }

        // Sales::insert();
    }
    public function confirmSales()
    {
        if ($this->quantity < 1) {
            $this->dispatchBrowserEvent(
                'swal.toast',
                [
                    'icon' => 'error',
                    'title' => 'Please enter Quantity'
                ]
            );
            return;
        }
        $data = [
            'unrecust_name' => $this->custName,
            'seles_price' => $this->price * $this->quantity,
            'discount' => $this->discount,
            'sales_stock' => $this->quantity,
            'remarks' => $this->remarks,
            'product_id' => $this->productId,
            'drcr' => $this->drcr,
            'customer_id' => ($this->regcust) ? $this->customerid : '0',
            'created_at' => Carbon::now()
        ];
        $valid = Validator::make($data, [
            'unrecust_name' => 'required',
            'sales_stock' => 'required',
            'seles_price' => 'required',
            'discount' => 'nullable',
            'remarks' => 'required',
            'product_id' => 'required|exists:products,id',
            'drcr' => 'required|in:1,2',
            'customer_id' => 'required'
        ])->validate();
        $leftStock = $this->productData['prod_stock'] - $this->quantity;
        Sales::insert($data);
        Product::find($this->productId)->update([
            'prod_stock' => $leftStock,
        ]);
        if ($this->quantity < 10) {
            dispatch(new SendStockLowEmailJob($this->productData['prod_name']));
        }

        $this->regcust = '';
        $this->barcode = '';
        $this->prodQuery = '';
        $this->productId = '';
        $this->productData = '';
        $this->products = '';
        $this->custName = '';
        $this->price = '';
        $this->quantity = '';
        $this->discount = '';
        $this->newCustomer = true;
        $this->customer = '';
        $this->customerid = '';
        $this->customerData = '';
        $this->remarks = '';
        $this->drcr = 1;
    }
   
       
    
}
