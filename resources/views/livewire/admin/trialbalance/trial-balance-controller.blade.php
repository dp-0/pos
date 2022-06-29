<div>
    <div class="row mt-2">
        <div class="card w-100">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <h2 class="card-title pb-2">Transaction History</h2>
                </div>
                
                <div class="form-group col-3 ml-auto">
                    <input type="text" name="daterange" class="form-control" />
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Description</th>
                            <th>Debit Amount</th>
                            <th>Credit Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @if (empty($item['prod_name']))
                                    
                                    <td>Sales: <br>
                                        Sales of {{ $item['product']['prod_name'] }} on
                                        {{ $item['drcr'] == 1 ? 'Cash' : 'Credit' }}
                                    </td>
                                    @if ($item['drcr'] == 1)
                                        
                                        <td>
                                            {{ $item['seles_price'] - $item['discount'] }}
                                        </td>
                                        <td></td>
                                    @else
                                        
                                        <td></td>
                                        <td>{{ $item['seles_price'] - $item['discount'] }}</td>
                                    @endif

                                    
                                @else
                                    <td>Product: <br>
                                        Purchase of {{ $item['prod_name'] }} on
                                        {{ $item['supptrans']['supp_trans_drcr'] == 1 ? 'Cash' : 'Credit' }}
                                    </td>

                                    @if ($item['supptrans']['supp_trans_drcr'] == 1)
                                        
                                        <td>
                                            {{ $item['supptrans']['supp_trans_amount'] }}
                                        </td>
                                        <td></td>
                                    @else
                                        
                                        <td></td>
                                        {{ $item['supptrans']['supp_trans_amount'] }}
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                   
                </table>
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
