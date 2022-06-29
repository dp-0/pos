<?php

namespace App\Http\Livewire\Admin\Customer;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CustomerController extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $listeners = ['delete' => 'delete'];
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $noOfRows = 5;
    public $sortBy = "email";
    public $arrange = "asc";
    public $state = [];
    public $edit = false;
    public $photo;
    public function render()
    {
        $customer = Customer::Where(function ($query) {
            $query->orwhere('cust_name', 'like', '%' . $this->search . '%')
                ->orWhere('cust_email', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortBy, $this->arrange)
            ->paginate($this->noOfRows);

        if ($customer->count() == 0 && $this->search != null) {
            $this->dispatchBrowserEvent('swal.toast', [
                'icon' => 'error',
                'title' => 'No Record Found',
            ]);
        }
        return view('livewire.admin.customer.customer-controller',['customers'=>$customer]);
    }

    //add data to database
    function store()
    {
        if ($this->edit) {
            $this->dispatchBrowserEvent('swal.toast', [
                'icon' => 'info',
                'title' => 'System is set to edit and your are creating user'
            ]);
            return;
        }
        $data = Validator::make($this->state, [
            'cust_name' => 'required|string',
            'cust_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cust_address' => 'required|string',
            'cust_phone' => 'required|digits:10',
            'cust_email' => 'required|email|unique:customers',

        ], [
            'cust_name.required' => 'Name field is required',
            'cust_name.string' => 'Please enter valid Name',
            'cust_address.required' => 'Address field is required',
            'cust_address.string' => 'Please enter valid Address',
            'cust_phone.required' => 'Address field is required',
            'cust_phone.digits' => 'Please enter valid Phone number',
            'cust_email.required' => 'Email field is required',
            'cust_email.email' => 'Please Provide valid email address',
            'cust_email.unique' => 'Email already exists',
            'cust_photo.required' => 'Please Provide a Photo',
            'cust_photo.image' => 'Please provide a image file only',
            'cust_photo.mimes' => 'Pleae provide jpeg, png, jpg file only',
            'cust_photo.max' => 'photo size must be less then 2058 KB'
        ])->validate();
        $fileName = $this->state['cust_photo']->store('public/customers');
        $fileName = 'storage/' . str_replace("public/", "", $fileName);
        Customer::insert([
            'cust_name' => $data['cust_name'],
            'cust_photo' => $fileName,
            'cust_address' => $data['cust_address'],
            'cust_phone' => $data['cust_phone'],
            'cust_email' => $data['cust_email'],
            'created_at' => Carbon::now()
        ]);
        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'success',
            'title' => 'Customer Created Successfully'
        ]);
        unset($this->state);
        $this->hideModel();
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
            'id' => 'exists:customers,id'
        ])->validate();

        if ($validator == null) {
            return  $this->dispatchBrowserEvent('swal.toast', [
                'icon' => 'error',
                'title' => 'Record Not Found',
            ]);
        }
        $user = Customer::find($validator['id'])->delete();
        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'success',
            'title' => 'Record Deleted Successfully',
        ]);
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
            'cust_name' => 'required|string',
            'cust_address' => 'required|string',
            'cust_phone' => 'required|digits:10',
            'cust_email' => 'required|email|unique:customers,'.$this->state['id'],

        ], [
            'cust_name.required' => 'Name field is required',
            'cust_name.string' => 'Please enter valid Name',
            'cust_address.required' => 'Address field is required',
            'cust_address.string' => 'Please enter valid Address',
            'cust_phone.required' => 'Address field is required',
            'cust_phone.digits' => 'Please enter valid Phone number',
            'cust_email.required' => 'Email field is required',
            'cust_email.email' => 'Please Provide valid email address',
            'cust_email.unique' => 'Email already exists',
            
        ])->validate();
        if($this->state['cust_photo'] != null){
            Validator::make($this->state,[
                'cust_photo' => 'image|mimes:jpeg,png,jpg',
            ],[
                'cust_photo.required' => 'Please Provide a Photo',
                'cust_photo.image' => 'Please provide a image file only',
                'cust_photo.mimes' => 'Pleae provide jpeg, png, jpg file only',
            ])->validate();
            $fileName = $this->state['cust_photo']->store('public/customers');
            //unlink the photo
            $data['cust_photo'] = 'storage/' . str_replace("public/", "", $fileName);
        }
        else{
            
            $data['cust_photo'] = $this->photo;
        }
        Customer::find($this->state['id'])->update($data);
        unset($this->state);
        $this->hideModel();
        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'success',
            'title' => 'User Edited Successfully'
        ]);
    }
    //display model to update data
    function editModel($id)
    {
        $this->edit = true;
        $user['id'] = $id;
       
        $validator = Validator::make($user, [
            'id' => 'exists:customers,id'
        ])->validate();   
        $this->state = Customer::find($validator['id'])->toArray();
        $this->photo = $this->state['cust_photo'];
        $this->state['cust_photo']=null;
    }
    public function hideModel()
    {
        $this->dispatchBrowserEvent('modalClose');
    }
}
