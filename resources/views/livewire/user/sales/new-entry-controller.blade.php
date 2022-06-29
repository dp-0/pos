<div>
    <div class="pt-2">
        <div class="card w-100">
            <div class="card-header">
                <div class="card-title">Sale Product</div>
            </div>
            <div class="card-body w-100">
                <form>
                    <div class="form-group">
                        <label for="barcode">Enter Barcode</label>
                        <input type="text" class="form-control" wire:model="barcode" autofocus>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" wire:model="regcust" class="form-check-input" id="regcust"
                                aria-label="regcustomer">
                            <label class="form-check-label" for="regcust">Already Registered Customer</label>
                        </div>
                    </div>
                    <div class="form-group pb-0">
                        <label for="barcode">Select Product</label>
                        <input type="text" class="form-control @error('product_id') is-invalid @enderror" wire:model="prodQuery" data-toggle="dropdown"
                            area-expand="true">
                            @error('product_id')
                            <span id="nameHelp" class="error invalid-feedback">Please select valid product</span>
                        @enderror
                        @if (!empty($prodQuery) && empty($productData))
                            <div class="border border-1" aria-labelledby="searchProduct">
                                @if (!empty($products))
                                    @foreach ($products as $item)
                                        <li class="dropdown-item" wire:click="selectProduct({{ $item['id'] }})">
                                            {{ $item['prod_name'] }}</li>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-check-label" for="custName">Customer Name</label>
                        <input type="text" class="form-control @error('unrecust_name') is-invalid @enderror" wire:model="custName" data-toggle="dropdown"
                            area-expand="true">
                            @error('unrecust_name')
                            <span id="nameHelp" class="error invalid-feedback">Please Provide Customer Name</span>
                        @enderror
                        @if (!empty($custName) && empty($customerData))
                            <div class="border border-1" aria-labelledby="searchCustomer">
                                @if (!empty($customer))
                                    @foreach ($customer as $item)
                                        <li class="dropdown-item" wire:click="selectCustomer({{ $item['id'] }})">
                                            {{ $item['cust_name'] }}</li>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                    </div>
                    @if ($productData)
                        <div class="form-group">
                            <label class="form-check-label" for="custName">Quantity ({{ $productData['prod_stock'] }})
                                Available</label>
                            <input type="text" class="form-control" wire:model="quantity">
                        </div>

                        <div class="form-group">
                            <label class="form-check-label" for="custName">Price per Unit</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select class="form-control " wire:model="drcr">
                                        <option value="1">Dr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                        <option value="2">Cr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                    </select>
                                </div>
                                
                                <input type="text" class="form-control" wire:model="price" required>
                            </div>
                        </div>




                       
                        <div class="form-group">
                            <label class="form-check-label" for="custName">Discount </label>
                            <input type="text" class="form-control" wire:model="discount">
                        </div>
                        <div class="form-group">
                            <label class="form-check-label" for="remarks">Remarks </label>
                            <input type="text" class="form-control" wire:model="remarks" required>
                        </div>
                        <button type="submit" class="btn btn-primary" wire:click.prevent="saleProduct">Submit</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('swal.confirm', event => {
                Swal.fire({
                    title: 'You Are Selling Product less then Selling Price',
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('confirmSales');
                    }
                })
            });
    </script>
</div>
