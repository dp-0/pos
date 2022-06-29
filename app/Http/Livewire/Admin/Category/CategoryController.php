<?php

namespace App\Http\Livewire\Admin\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class CategoryController extends Component
{
    protected $listeners = ['delete' => 'delete'];
    public $edit = false;
    public $state = [];
    public $arrange = 'asc';
    public $noOfRows;
    public $search;
    public function render()
    {
        $category = Category::where('cat_name','like','%'.$this->search.'%')
        ->orderBy('cat_name', $this->arrange)
        ->paginate($this->noOfRows);
        return view('livewire.admin.category.category-controller',['categories'=>$category]);
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
            'cat_name' => 'required|string|unique:categories,cat_name',
        ], [
            'cat_name.required' => 'Name field is required',
            'cat_name.string' => 'Please enter valid Name',
            'cat_name.unique' => 'Category Already Exist',
        ])->validate();

        
        Category::create($data);

        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'success',
            'title' => 'Category Created Successfully'
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
         'id' => 'exists:categories,id'
     ])->validate();

     if ($validator == null) {
         return  $this->dispatchBrowserEvent('swal.toast', [
             'icon' => 'error',
             'title' => 'Record Not Found',
         ]);
     }

     $user = Category::find($validator['id'])->delete();
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
        $data = Validator::make($this->state, [
            'cat_name' => 'required|string|unique:categories,cat_name',
        ], [
            'cat_name.required' => 'Name field is required',
            'cat_name.string' => 'Please enter valid Name',
            'cat_name.unique' => 'Category Already Exist',
        ])->validate();
           
        Category::find($this->state['id'])->update($data);
        $this->edit = false;
        unset($this->state);
        $this->hideModel();
        $this->dispatchBrowserEvent('swal.toast', [
            'icon' => 'success',
            'title' => 'Category Edited Successfully'
        ]);
    }



    //display model to update data
    function editModel($id)
    {
        $this->edit = true;
        $user['id'] = $id;
        $validator = Validator::make($user, [
            'id' => 'exists:categories,id'
        ])->validate();
        $this->state = Category::find($validator['id'])->toArray();
        
    }
    //hide the modal
    public function hideModel()
    {
        $this->dispatchBrowserEvent('modalClose');
    }
}
