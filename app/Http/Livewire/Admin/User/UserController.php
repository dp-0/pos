<?php

namespace App\Http\Livewire\Admin\User;

use App\Jobs\SendEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Validator;


class UserController extends Component
{
    use WithPagination;

    protected $listeners = ['delete' => 'delete'];
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $noOfRows = 5;
    public $sortBy = "email";
    public $arrange = "asc";
    public $userType = "user";
    public $state = [];
    public $edit = false;
    protected $rules = [
        'search' => 'string',
        'noOfRows' => 'in:5,10,20,50,100',
        'sortBy' => 'in:email,name,created_at,updated_at|string',
        'arrange' => 'in:asc,desc|string',
        'userType' => 'in:admin,user|string',

    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    public function render()
    {
        $user = User::where('utype', '=', $this->userType)
            ->Where(function ($query) {
                $query->orwhere('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->select(['name', 'id', 'created_at', 'updated_at', 'email'])
            ->orderBy($this->sortBy, $this->arrange)
            ->paginate($this->noOfRows);

        if ($user->count() == 0 && $this->search!=null) {
            $this->dispatchBrowserEvent('swal.toast', [
                'icon' => 'error',
                'title' => 'No Record Found',
            ]);
        }
        return view('livewire.admin.user.user-controller', ['users' => $user]);
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
            'id' => 'exists:users,id'
        ])->validate();

        if ($validator == null) {
            return  $this->dispatchBrowserEvent('swal.toast', [
                'icon' => 'error',
                'title' => 'Record Not Found',
            ]);
        }
        $user = User::find($validator['id'])->delete();
        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'success',
            'title' => 'Record Deleted Successfully',
        ]);
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users'
        ], [
            'state.name.required' => 'Name field is required',
            'state.name.string' => '>Please enter valid Name',
            'state.email.required' => 'Email field is required',
            'state.email.email' => 'Please Provide valid email address',
            'state.email.unique' => 'Email already exists'
        ])->validate();

        $password = rand(00000000, 9999999999);
        $data['password'] = Hash::make($password);

        User::create($data);

        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'success',
            'title' => 'User Created Successfully'
        ]);
        unset($this->state);
        dispatch(new SendEmailJob($data['email'], $password, $data['name']));

        $this->hideModel();
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
        $data = Validator::make($this->state, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,'.$this->state['id'],
        ], [
            'state.name.required' => 'Name field is required',
            'state.name.string' => '>Please enter valid Name',
            'state.email.required' => 'Email field is required',
            'state.email.email' => 'Please Provide valid email address',
            'state.email.unique' => 'Email already exists'
        ])->validate();

        User::find($this->state['id'])->update($data);
        $this->edit = false;
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
            'id' => 'exists:users,id'
        ])->validate();
        $this->state = User::find($validator['id'])->toArray();
        
    }
   
    //hide model
    public function hideModel()
    {
        $this->dispatchBrowserEvent('modalClose');
    }
}