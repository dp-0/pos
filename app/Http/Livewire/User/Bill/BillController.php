<?php

namespace App\Http\Livewire\User\Bill;

use App\Models\Customer;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
class BillController extends Component
{
    public $startDate;
    public $endDate;
    public $search;
    public $data;
    public function mount()
    {
        $this->startDate = Date('Y-m-01 H:i:s');
        $this->endDate = Date('Y-m-d H:i:s');
    }
    public function render()
    {
        $this->data = Customer::join('sales', 'customers.id', 'sales.customer_id')
            ->join('products', 'products.id', 'sales.product_id')
            ->join('supplier__transactions', 'products.supp_trans_id', 'supplier__transactions.id')
            ->where('supplier_id', '!=', '0')
            ->where('sales.created_at', '<=', $this->endDate)
            ->where('sales.created_at', '>=', $this->startDate)
            ->get();
        return view('livewire.user.bill.bill-controller');
    }

    public function download()
    {
       
        $data = Customer::join('sales', 'customers.id', 'sales.customer_id')
        ->join('products', 'products.id', 'sales.product_id')
        ->join('supplier__transactions', 'products.supp_trans_id', 'supplier__transactions.id')
        ->where('supplier_id', '!=', '0')
        ->where('sales.created_at', '<=', $this->endDate)
        ->where('sales.created_at', '>=', $this->startDate)
        ->get();
        $pdf = PDF::loadView('pdf.salesproduct',compact('data'))->output();


        return response()->streamDownload(
            fn () =>print($pdf),
            'invoice.pdf'
        );
    }
}
