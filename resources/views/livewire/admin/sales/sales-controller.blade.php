<div>
    <div class="pt-2">
        <div class="row shadow-lg p-3 rounded">
            <div class="card w-100">
                <div class="card-header">
                    <h2 class="card-title pb-2">Sales Product</h2>
                    <div class="row w-100 justify-content-between">
                        <div>
                            <input type="text" class="form-control" name="search" id="search" wire:model="search"
                                placeholder="Search here..">
                        </div>
                        <div>
                            <input type="text" name="daterange" class="form-control"/>
                        </div>
                        <div>
                            <select name="sortby" id="sortby" class="custom-select" wire:model="sortBy">
                                <option value="">Sort By</option>
                                <option value="seles_price">Selling Price</option>
                                <option value="discount">Discount</option>
                                <option value="profit">Profit</option>
                                <option value="supp_trans_amount">Loss</option>
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
                            <a class="btn btn-primary" href="{{ route('sales.entry') }}"type="button">New Entry</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Customer Name</th>
                                <th>Selling Price</th>
                                <th>Discount</th>
                                <th>Quantity</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $item)
                                <tr class="{{($item->drcr == '1')?'':'text-danger'}}">
                                    <td>{{ $loop->iteration + $sales->firstItem() - 1 }}</td>
                                    <td>{{ $item->product->prod_name }}</td>
                                    <td>{{ $item->unrecust_name }}</td>
                                    <td>{{ $item->seles_price }}</td>
                                    <td>{{ $item->discount }}</td>
                                    <td>{{ $item->sales_stock }}</td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                timePicker: true,
                opens: 'left',
                maxDate: '{{ Date('m/d/Y') }}'
            }, function(start, end, label) {
                @this.set('startDate', start.format('YYYY-MM-DD hh:mm:ss'));
                @this.set('endDate', end.format('YYYY-MM-DD hh:mm:ss'));
            });
        });
    </script>
</div>
