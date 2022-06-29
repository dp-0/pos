<?php

namespace App\Http\Livewire\Admin\Supplier;

use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SupplierController extends Component
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
        $supplier = Supplier::Where(function ($query) {
            $query->orwhere('supp_name', 'like', '%' . $this->search . '%')
                ->orWhere('supp_email', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortBy, $this->arrange)
            ->paginate($this->noOfRows);

        if ($supplier->count() == 0 && $this->search != null) {
            $this->dispatchBrowserEvent('swal.toast', [
                'icon' => 'error',
                'title' => 'No Record Found',
            ]);
        }
        return view('livewire.admin.supplier.supplier-controller', ['suppliers' => $supplier]);
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
            'supp_name' => 'required|string',
            'supp_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'supp_address' => 'required|string',
            'supp_phone' => 'required|digits:10',
            'supp_email' => 'required|email|unique:suppliers',

        ], [
            'supp_name.required' => 'Name field is required',
            'supp_name.string' => 'Please enter valid Name',
            'supp_address.required' => 'Address field is required',
            'supp_address.string' => 'Please enter valid Address',
            'supp_phone.required' => 'Address field is required',
            'supp_phone.digits' => 'Please enter valid Phone number',
            'supp_email.required' => 'Email field is required',
            'supp_email.email' => 'Please Provide valid email address',
            'supp_email.unique' => 'Email already exists',
            'supp_photo.required' => 'Please Provide a Photo',
            'supp_photo.image' => 'Please provide a image file only',
            'supp_photo.mimes' => 'Pleae provide jpeg, png, jpg file only',
            'supp_photo.max' => 'photo size must be less then 2058 KB'
        ])->validate();
        

        $fileName = $this->state['supp_photo']->store('public/suppliers');
        $fileName = 'storage/' . str_replace("public/", "", $fileName);


        
        Supplier::insert([
            'supp_name' => $data['supp_name'],
            'supp_photo' => $fileName,
            'supp_address' => $data['supp_address'],
            'supp_phone' => $data['supp_phone'],
            'supp_email' => $data['supp_email'],
            'created_at' => Carbon::now()
        ]);



        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'success',
            'title' => 'Supplier Created Successfully'
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
            'id' => 'exists:suppliers,id'
        ])->validate();

        if ($validator == null) {
            return  $this->dispatchBrowserEvent('swal.toast', [
                'icon' => 'error',
                'title' => 'Record Not Found',
            ]);
        }
        $user = Supplier::find($validator['id'])->delete();
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
            'supp_name' => 'required|string',
            'supp_address' => 'required|string',
            'supp_phone' => 'required|digits:10',
            'supp_email' => 'required|email|unique:suppliers,'.$this->state['id'],

        ], [
            'supp_name.required' => 'Name field is required',
            'supp_name.string' => 'Please enter valid Name',
            'supp_address.required' => 'Address field is required',
            'supp_address.string' => 'Please enter valid Address',
            'supp_phone.required' => 'Address field is required',
            'supp_phone.digits' => 'Please enter valid Phone number',
            'supp_email.required' => 'Email field is required',
            'supp_email.email' => 'Please Provide valid email address',
            'supp_email.unique' => 'Email already exists',
            
        ])->validate();
            
        if($this->state['supp_photo'] != null){
            
            Validator::make($this->state,[
                'supp_photo' => 'image|mimes:jpeg,png,jpg',
            ],[
                'supp_photo.required' => 'Please Provide a Photo',
                'supp_photo.image' => 'Please provide a image file only',
                'supp_photo.mimes' => 'Pleae provide jpeg, png, jpg file only',
            ])->validate();
            
            $fileName = $this->state['supp_photo']->store('public/suppliers');
            //unlink the photo
          
            $data['supp_photo'] = 'storage/' . str_replace("public/", "", $fileName);
            
        }
        else{
            
            $data['supp_photo'] = $this->photo;
        }
        Supplier::find($this->state['id'])->update($data);
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
            'id' => 'exists:suppliers,id'
        ])->validate();

        

        $this->state = Supplier::find($validator['id'])->toArray();
        $this->photo = $this->state['supp_photo'];
        $this->state['supp_photo']=null;
    }
    public function hideModel()
    {
        $this->dispatchBrowserEvent('modalClose');
    }
}
