<div class="p-1">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title pb-2">User List</h2>
            <div class="row w-100 justify-content-between">
                <div>
                    <input type="text" class="form-control" name="search" id="search" wire:model="search"
                        placeholder="Search here..">
                </div>
                <div>
                    <input type="text" name="daterange" class="form-control" />
                </div>
                <div>
                    <input type="button" value="Download" class="btn btn-primary" wire:click="download">
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
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->prod_name }}</td>
                            <td>{{ $item->unrecust_name }}</td>
                            <td>{{ $item->seles_price }}</td>
                            <td>{{ $item->discount }}</td>
                            <td>{{ $item->sales_stock }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
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
