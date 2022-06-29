<div>
    <div class="pt-2">
        <div class="row shadow-lg p-3 rounded">
            <div class="card w-100">
                <div class="card-header">
                    <h2 class="card-title pb-2">User List</h2>
                    <div class="row w-100 justify-content-between">
                        <div>
                            <input type="text" class="form-control" name="search" id="search" wire:model="search"
                                placeholder="Search here..">
                        </div>
                        <div>
                            <select name="userType" id="userType" class="custom-select" wire:model="userType">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div>
                            <select name="userType" id="userType" class="custom-select" wire:model="sortBy">
                                <option value="">Sort By</option>
                                <option value="email">Email</option>
                                <option value="name">Name</option>
                                <option value="created_at">Created Date</option>
                                <option value="updated_at">Updated Date</option>
                            </select>
                        </div>
                        <div>
                            <select name="userType" id="userType" class="custom-select" wire:model="noOfRows">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div>
                            <select name="userType" id="userType" class="custom-select" wire:model="arrange">
                                <option value="asc">ASC</option>
                                <option value="desc">DESC</option>
                            </select>
                        </div>
                        <div>
                            <input type="button" value="Add User" data-toggle="modal" data-target="#showModal"
                                class="btn btn-primary">
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td><span class="tag tag-success">{{ $user->updated_at }}</span></td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm"
                                            wire:click="editModel({{ $user->id }})" data-toggle="modal" data-target="#showModal">
                                            <i class="far fa-edit"></i>
                                        </button>
                                        <button type="button" wire:click="deleteConfirm({{ $user->id }})"
                                            class="btn btn-danger btn-sm">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
        <div class="modal fade" id="showModal" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $edit == true ? 'Edit' : 'Add' }} User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" wire:model="state.email"
                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                    aria-describedby="emailHelp">
                                @error('email')
                                    <span id="emailHelp" class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" wire:model="state.name"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    aria-describedby="nameHelp">
                                @error('name')
                                    <span id="nameHelp" class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" wire:click="{{ $edit == true ? 'update' : 'store' }}"
                            class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('model', event => {
                $('#modalForm').show();
            });
            window.addEventListener('closeModal', event => {
                $('#modalForm').hide();
            });
    
            window.addEventListener('swal.delete', event => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('delete', event.detail.id);
                    }
                })
            });


            window.addEventListener('modalClose',event =>{
                $('#showModal').modal('hide');
            });
        </script>

        <script>
            
        </script>
    </div>
