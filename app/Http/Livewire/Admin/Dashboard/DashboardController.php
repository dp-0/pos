<?php

namespace App\Http\Livewire\Admin\Dashboard;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sales;
use App\Models\Supplier;
use App\Models\User;
use Livewire\Component;

class DashboardController extends Component
{
    public $totalCustomer;
    public $totalSupplier;
    public $totalUser;
    public $totalCategory;
    public $totalSalesEntry;
    protected $totalSupplierDebit;
    protected $totalSupplierCredit;
    protected $totalSalesDebit;
    protected $totalSalesCredit;
    protected $totalDiscount = 0;
    public $profitloss;
    public $amount;
    public function render()
    {
        $this->totalCustomer = Customer::count();
        $this->totalSupplier = Supplier::count();
        $this->totalUser = User::where('utype','user')->count();
        $this->totalSalesEntry = Sales::count();
        $this->totalCategory = Category::count();
       
        $data = Product::with(['supptrans'])->get();

       foreach($data as $item){

           if($item->supptrans->supp_trans_drcr == 1){
               $this->totalSupplierDebit += $item->supptrans->supp_trans_amount * $item->prod_purchase_stock;
           }
           else{
                $this->totalSupplierCredit = $item->supptrans->supp_trans_amount * $item->prod_purchase_stock;
           }
       }
       $data1 = Sales::select('seles_price','sales_stock','drcr','discount')->get();
       foreach($data1 as $item){
            if($item->drcr == 1){
                $this->totalSalesDebit += $item->seles_price * $item->sales_stock;
            }
            else{
                $this->totalSalesCredit = $item->seles_price * $item->sales_stock;
            }
            if($item->discount > 0){
                $this->totalDiscount = $this->totalDiscount + $item->discount;
            }
       }
       
       $total =  $this->totalSupplierDebit - ($this->totalSalesDebit + $this->totalDiscount);

       if($total < 0){
            $this->profitloss = true;
        }
        else{
            $this->profitloss =false;
        }
        $this->amount = abs($total);
        return view('livewire.admin.dashboard.dashboard-controller');
    }
}
