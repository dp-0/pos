<?php

namespace App\Http\Livewire\Admin\Trialbalance;

use App\Models\Product;
use App\Models\Sales;
use Livewire\Component;

class TrialBalanceController extends Component
{
    public $startDate;
    public $endDate;
    public function mount()
    {
        $this->startDate = Date('Y-m-01 H:i:s');
        $this->endDate = Date('Y-m-d H:i:s');
    }

    public function render()
    {
        $product =  Product::with('supptrans')->where('products.created_at', '<=', $this->endDate)
            ->where('products.created_at', '>=', $this->startDate)
            ->get()->toArray();
            
        $sales = Sales::with('product','product.supptrans')->where('sales.created_at', '<=', $this->endDate)
            ->where('sales.created_at', '>=', $this->startDate)
            ->get()->toArray();
            $data = array_merge($product,$sales);
            array_multisort(array_column($data, 'created_at'), SORT_ASC, $data);
        
  


        return view('livewire.admin.trialbalance.trial-balance-controller',['data'=>$data]);
    }
}
