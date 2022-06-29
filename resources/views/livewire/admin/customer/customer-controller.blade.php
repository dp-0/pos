<div>
    <div class="pt-2">
        <div class="row shadow-lg p-3 rounded">
            <div class="card w-100">
                <div class="card-header">
                    <h2 class="card-title pb-2">Customer List</h2>
                    <div class="row w-100 justify-content-between">
                        <div>
                            <input type="text" class="form-control" name="search" id="search" wire:model="search"
                                placeholder="Search here..">
                        </div>

                        <div>
                            <select name="sortby" id="sortby" class="custom-select" wire:model="sortBy">
                                <option value="">Sort By</option>
                                <option value="cust_email">Email</option>
                                <option value="cust_name">Name</option>
                                <option value="cust_address">Address</option>
                                <option value="cust_phone">Phone</option>
                            </select>
                        </div>
                        <div>
                            <select name="noOfRows" id="noOfRows" class="custom-select" wire:model="noOfRows">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div>
                            <select name="arrange" id="arrange" class="custom-select" wire:model="arrange">
                                <option value="asc">ASC</option>
                                <option value="desc">DESC</option>
                            </select>
                        </div>
                        <div>
                            <input type="button" value="Add Customer" data-toggle="modal" data-target="#showModal"
                                class="btn btn-primary">
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration + $customers->firstItem() - 1 }}</td>
                                    <td>
                                        <img src="{{ asset($customer->cust_photo) }}"
                                            alt="{{ $customer->cust_name }} logo" class="img img-fluid"
                                            style="width: 30px">
                                    </td>
                                    <td>{{ $customer->cust_name }}</td>
                                    <td>{{ $customer->cust_email }}</td>
                                    <td>{{ $customer->cust_address }}</td>
                                    <td>{{ $customer->cust_phone }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm"
                                            wire:click="editModel({{ $customer->id }})" data-toggle="modal"
                                            data-target="#showModal">
                                            <i class="far fa-edit"></i>
                                        </button>
                                        <button type="button" wire:click="deleteConfirm({{ $customer->id }})"
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
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="showModal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $edit == true ? 'Edit' : 'Add' }} custlier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" wire:model="state.cust_email"
                                class="form-control @error('cust_email') is-invalid @enderror" id="email"
                                aria-describedby="emailHelp">
                            @error('cust_email')
                                <span id="emailHelp" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" wire:model="state.cust_name"
                                class="form-control @error('cust_name') is-invalid @enderror" id="name"
                                aria-describedby="nameHelp">
                            @error('cust_name')
                                <span id="nameHelp" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" wire:model="state.cust_address"
                                class="form-control @error('cust_address') is-invalid @enderror" id="email"
                                aria-describedby="address">
                            @error('cust_address')
                                <span id="address" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="number" wire:model="state.cust_phone"
                                class="form-control @error('phone') is-invalid @enderror" id="email"
                                aria-describedby="phone">
                            @error('cust_phone')
                                <span id="phone" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cust_photo">Customer Photo</label>
                            <input type="file" class="form-control @error('cust_photo') is-invalid @enderror"
                                id="cust_photo" wire:model="state.cust_photo">
                            @error('cust_photo')
                                <span id="phone" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div wire:loading wire:target="state.cust_photo">Uploading...</div>
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


        window.addEventListener('modalClose', event => {
            $('#showModal').modal('hide');
        });
    </script>

    @foreach ($errors->all() as $error)
        <script>
            showToast('error','{{ $error }}');
        </script>
    @endforeach
</div>
