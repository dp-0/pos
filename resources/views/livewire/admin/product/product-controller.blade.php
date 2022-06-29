<div>
    <div class="pt-2">
        <div class="row shadow-lg p-3 rounded">
            <div class="card w-100">
                <div class="card-header">
                    <h2 class="card-title pb-2">Product List</h2>
                    <div class="row w-100 justify-content-between">
                        <div>
                            <input type="text" class="form-control" name="search" id="search" wire:model="search"
                                placeholder="Search here..">
                        </div>
                        <div>
                            <select name="category"  class="custom-select" wire:model="selectByCategory">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" wire:key="category{{$category->id}}">{{ $category->cat_name }}</option>
                                @endforeach
                                
                            </select>
                        </div>
                        <div>
                            <select name="supplier"  class="custom-select" wire:model="selectBySupplier">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" wire:key="supplier{{$supplier->id}}">{{ $supplier->supp_name }}</option>
                            @endforeach
                                
                            </select>
                        </div>
                        <div>
                            <select name="userType" id="userType" class="custom-select" wire:model="sortBy">
                                <option value="">Sort By</option>
                                <option value="1">Debit</option>
                                <option value="2">Credit</option>
                                <option value="supp_trans_amount">Purchase Price</option>
                                <option value="prod_selling_price">Selling Price</option>
                                <option value="prod_stock">Stock</option>
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
                            <input type="button" value="Add Product" data-toggle="modal" data-target="#showModal"
                                class="btn btn-primary">
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Supplier</th>
                                <th>In Stock</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>BarCode</th>
                                <th>Total Quantity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration + $products->firstItem() - 1 }}</td>
                                    <td>{{ $product->prod_name }}</td>
                                    <td>{{ $product->cat_name }}</td>
                                    <td>{{ $product->supp_name }}</td>
                                    <td>{{ $product->prod_stock }}</td>
                                    <td>{{ $product->supp_trans_amount }}</td>
                                    <td>{{ $product->prod_selling_price }}</td>
                                    <td>{{ $product->prod_barcode }}</td>
                                    <td>{{ $product->prod_purchase_stock }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm"
                                        wire:click="editModel({{ $product->id }})" data-toggle="modal"
                                        data-target="#showModal">
                                        <i class="far fa-edit"></i>
                                    </button>
                                    <button type="button" wire:click="deleteConfirm({{ $product->id }})"
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

                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="showModal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $edit == true ? 'Edit' : 'Add' }} Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="email">Supplier</label>
                            <select class="form-control @error('supp_name') is-invalid @enderror"
                                wire:model="state.supplier_id">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" wire:key="supplier{{$supplier->id}}">{{ $supplier->supp_name }}</option>
                                @endforeach
                            </select>
                            @error('supp_name')
                                <span id="emailHelp" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="category">Product Category</label>
                            <select class="form-control @error('category_id') is-invalid @enderror"
                                wire:model="state.category_id">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" wire:key="category{{$category->id}}">{{ $category->cat_name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span id="emailHelp" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Product Name</label>
                            <input type="email" wire:model="state.prod_name"
                                class="form-control @error('prod_name') is-invalid @enderror" id="email"
                                aria-describedby="nameHelp">
                            @error('prod_name')
                                <span id="nameHelp" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">BarCode</label>
                            <div class="input-group">
                                
                                <input type="text" wire:model="state.prod_barcode"
                                    class="form-control @error('prod_barcode') is-invalid @enderror" id="name"
                                    aria-describedby="nameHelp">
                                    <div class="input-group-append">
                                        
                                        <button wire:click.prevent="generateBarCode" class="form-control btn btn-secondary">Auto Generate</button>
                                    </div>
                                @error('prod_barcode')
                                    <span id="nameHelp" class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Purchase Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select class="form-control @error('supp_trans_drcr') is-invalid @enderror"
                                        wire:model="supp_trans_drcr">
                                        <option value="1">Dr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                        <option value="2">Cr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    </select>
                                </div>
                                <input type="number" wire:model="state.supp_trans_amount"
                                    class="form-control @error('supp_trans_amount') is-invalid @enderror"
                                    id="email" aria-describedby="address">
                                @error('supp_trans_amount')
                                    <span id="address" class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Marked Price</label>
                            <input type="number" wire:model="state.prod_selling_price"
                                class="form-control @error('prod_selling_price') is-invalid @enderror" id="email"
                                aria-describedby="address">
                            @error('prod_selling_price')
                                <span id="address" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Quantity</label>
                            <input type="number" wire:model="state.prod_purchase_stock"
                                class="form-control @error('prod_purchase_stock') is-invalid @enderror" id="email"
                                aria-describedby="phone">
                            @error('prod_purchase_stock')
                                <span id="phone" class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" wire:click="{{ $edit == true ? 'update' : 'store' }}" class="btn btn-primary">Save</button>
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
            showToast('error', '{{ $error }}');
        </script>
    @endforeach
</div>
