<div class="p-1">
    <div class="row-mt-2">
        <div class="card-deck mb-3 text-center">
            <div class="card mb-4 shadow-sm">
              <div class="card-header">
                <h4 class="my-0 font-weight-normal">@if($profitloss) Profit @else loss @endif</h4>
              </div>
              <div class="card-body d-flex justify-content-center align-items-center">
                <h1 class="card-title pricing-card-title">${{$amount}} </h1>
              </div>
            </div>
            
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                  <h4 class="my-0 font-weight-normal">Total Customers</h4>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                  <h1 class="card-title pricing-card-title">{{$totalCustomer}}</h1>
                </div>
              </div>
              <div class="card mb-4 shadow-sm">
                <div class="card-header">
                  <h4 class="my-0 font-weight-normal">Total Supplier</h4>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <h1 class="card-title pricing-card-title">{{$totalSupplier}}</h1>
                </div>
              </div>
              <div class="card mb-4 shadow-sm">
                <div class="card-header">
                  <h4 class="my-0 font-weight-normal">Total User</h4>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <h1 class="card-title pricing-card-title">{{$totalUser}}</h1>
                </div>
              </div>
          </div>

          <div class="card-deck mb-3 text-center">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                  <h4 class="my-0 font-weight-normal">Total Sales Entry</h4>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                  <h1 class="card-title pricing-card-title">{{$totalSalesEntry}}</h1>
                </div>
              </div>

              <div class="card mb-4 shadow-sm">
                <div class="card-header">
                  <h4 class="my-0 font-weight-normal">Total Category</h4>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                  <h1 class="card-title pricing-card-title">{{$totalCategory}}</small></h1>
                </div>
              </div>
          </div>

          
    </div>
</div>
