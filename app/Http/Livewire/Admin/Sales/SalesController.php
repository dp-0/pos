<?php

namespace App\Http\Livewire\Admin\Sales;

use App\Models\Sales;

use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
class SalesController extends Component
{
   
    use WithPagination;
    protected $theme = ['bootstrap'];

    public $startDate;
    public $endDate;
    public $search;
    public $sortBy="";
    public $noOfRows = 5;
    public $arrange = 'desc';
    public function mount(){
        $this->startDate = Date('Y-m-01 H:i:s');
        $this->endDate = Date('Y-m-d H:i:s');
    }
    public function render()
    {
        
        $data = Sales::join('products','sales.product_id','products.id')
        ->join('supplier__transactions','products.supp_trans_id','supplier__transactions.id')
        ->where(function ($s) {
            if ($this->search) {
                $s->where('prod_name', 'like', '%' . $this->search . '%');
            }
        })
        ->where('sales.created_at','<=',$this->endDate)
        ->where('sales.created_at','>=',$this->startDate)
        ->where(function($q){
            if($this->sortBy == 'loss'){
                $q->where('supplier__transactions.supp_trans_amount','<','supplier__transactions.supp_trans_amount');
            }
        })
        ->orderBy($this->sortBy, $this->arrange)
        ->paginate($this->noOfRows);
        return view('livewire.admin.sales.sales-controller',['sales'=>$data]);
    }
   
    
}
