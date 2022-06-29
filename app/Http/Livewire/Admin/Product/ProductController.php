<?php

namespace App\Http\Livewire\Admin\Product;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Supplier_Transaction;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class ProductController extends Component
{
    protected $listeners = ['delete' => 'delete'];
    public $suppliers;
    public $categories;
    public $state = [];
    public $supp_trans_drcr = 1;
    public $edit = false;
    public $search;
    public $noOfRows = 5;
    public $sortBy = "prod_name";
    public $arrange = "asc";
    public $selectByCategory = "";
    public $selectBySupplier = "";
    public $supplierTransaction;


    public function generateBarCode()
    {
        $this->state['prod_barcode'] = rand(00000000, 9999999999);
    }
    public function render()
    {
        $this->suppliers = Supplier::select(['id', 'supp_name'])->get();
        $this->categories = Category::select(['id', 'cat_name'])->get();
        $product = Product::join('categories', 'products.category_id', 'categories.id')
            ->join('supplier__transactions', 'supplier__transactions.id', 'products.supp_trans_id')
            ->join('suppliers', 'supplier__transactions.supplier_id', 'suppliers.id')
            ->select('suppliers.id', 'products.id', 'cat_name', 'prod_name', 'supp_name', 'prod_stock', 'prod_selling_price', 'prod_purchase_stock', 'prod_barcode', 'supp_trans_amount', 'supp_trans_drcr')
            ->where(function ($s) {
                if ($this->search) {
                    $s->where('prod_name', 'like', '%' . $this->search . '%');
                }
            })
            ->where(function ($q) {
                if ($this->selectByCategory) {
                    $q->where('categories.id', '=', $this->selectByCategory);
                }
            })
            ->where(function ($query) {
                if ($this->selectBySupplier) {
                    $query->where('suppliers.id', '=', $this->selectBySupplier);
                }
            })
            ->orderBy($this->sortBy, $this->arrange)
            ->paginate($this->noOfRows);


        return view('livewire.admin.product.product-controller', ['products' => $product]);
    }

    public function store()
    {
        if ($this->edit) {
            $this->dispatchBrowserEvent('swal.toast', [
                'icon' => 'info',
                'title' => 'System is set to edit and your are creating user'
            ]);
            return;
        }
        $data = Validator::make($this->state, [
            'supplier_id' => 'required|exists:suppliers,id',
            'prod_name' => 'required|string',
            'supp_trans_amount' => 'required',
            'prod_selling_price' => 'required',
            'prod_purchase_stock' => 'required',
            'prod_barcode' => 'required',
            'category_id' => 'required|exists:categories,id',
        ], [
            'prod_name.required' => 'Name field is required',
            'prod_name.string' => 'Please enter valid Name',
            'prod_barcode.required' => 'Please insert barcode',
            'supp_trans_amount.required' => 'Please enter purchase price',
            'prod_selling_price.required' => 'Please enter selling price',
            'prod_purchase_stock.required' => 'Please enter quantity',
        ])->validate();
        $data['prod_stock'] = $data['prod_purchase_stock'];
        $val['supp_trans_drcr'] = $this->supp_trans_drcr;
        $data1 = Validator::make($val, [
            'supp_trans_drcr' => 'required|in:1,2'
        ], [
            'supp_trans_drcr.required' => 'Please select Debit/Credit option',
            'supp_trans_drcr.in' => 'Please Select Debit/Credit Option only'
        ])->validate();

        $data1['supp_trans_amount'] = $data['supp_trans_amount'];
        $data1['supplier_id'] = $data['supplier_id'];


        $transaction = Supplier_Transaction::create($data1);
        $data['supp_trans_id'] = $transaction->id;
        Product::create($data);


        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'success',
            'title' => 'Product Created Successfully'
        ]);
        unset($this->state);
        $this->supp_trans_drcr = 1;
        $this->hideModel();
    }

    //display model to update data
    function editModel($id)
    {
        $this->edit = true;
        $user['id'] = $id;
        $validator = Validator::make($user, [
            'id' => 'exists:products,id'
        ])->validate();
        $data = Product::with(['supptrans.supplier'])
        ->find($validator['id'])
        ->toArray();
        $data['supplier_id'] = $data['supptrans']['supplier']['id'];
        $data['supp_trans_amount'] =  $data['supptrans']['supp_trans_amount'];
        $this->supp_trans_drcr = $data['supptrans']['supp_trans_drcr'];
        session(['supptransid' => $data['supptrans']['id']]);
        unset($data['supptrans']);
        $this->state = $data;
    }
//update data in databse
function update()
{
    if (!$this->edit) {
        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'info',
            'title' => 'System is set to create user and your are editing user'
        ]);
        return;
    }
    $this->edit = false;
    $data = Validator::make($this->state, [
        'supplier_id' => 'required|exists:suppliers,id',
        'prod_name' => 'required|string',
        'supp_trans_amount' => 'required',
        'prod_selling_price' => 'required',
        'prod_purchase_stock' => 'required',
        'prod_barcode' => 'required',
        'category_id' => 'required|exists:categories,id',
    ], [
        'prod_name.required' => 'Name field is required',
        'prod_name.string' => 'Please enter valid Name',
        'prod_barcode.required' => 'Please insert barcode',
        'supp_trans_amount.required' => 'Please enter purchase price',
        'prod_selling_price.required' => 'Please enter selling price',
        'prod_purchase_stock.required' => 'Please enter quantity',
    ])->validate();
    
    $data['supp_trans_id'] = session('supptransid');
    $val['supp_trans_drcr'] = $this->supp_trans_drcr;
        
    $data1 = Validator::make($val, [
        'supp_trans_drcr' => 'required|in:1,2'
    ], [
        'supp_trans_drcr.required' => 'Please select Debit/Credit option',
        'supp_trans_drcr.in' => 'Please Select Debit/Credit Option only'
    ])->validate();
    
    $data1['supp_trans_amount'] = $data['supp_trans_amount'];
    $data1['supplier_id'] = $data['supplier_id'];
    
    $transaction = Supplier_Transaction::find($data['supp_trans_id'])->update($data1);
   
    Product::find($this->state['id'])->update($data);

   
    unset($this->state);
    $this->hideModel();
    $this->dispatchBrowserEvent('swal.toast', [
        'icon' => 'success',
        'title' => 'User Edited Successfully'
    ]);
}



    //confirm before delete
    public function deleteConfirm($id)
    {
        $this->dispatchBrowserEvent('swal.delete', [
            'id' => $id
        ]);
    }
    //delete user
    function delete($id)
    {
        $user['id'] = $id;

        $validator = Validator::make($user, [
            'id' => 'exists:products,id'
        ])->validate();

        if ($validator == null) {
            return  $this->dispatchBrowserEvent('swal.toast', [
                'icon' => 'error',
                'title' => 'Record Not Found',
            ]);
        }
        $user = Product::find($validator['id'])->delete();
        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'success',
            'title' => 'Record Deleted Successfully',
        ]);
    }
    public function hideModel()
    {
        $this->dispatchBrowserEvent('modalClose');
    }
}
