<div class="modal-header">
    <h5 class="modal-title">Card Details</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="card-details">
            

           <div class="row">
               <div class="col-md-4"><b>Full Name <span class="pull-right">:</span></b></div>
               <div class="col-md-6">{{ ucfirst($card_details['first_name']) }} {{ ucfirst($card_details['last_name']) }}</div>
            </div>

            <div class="row">
               <div class="col-md-4"><b>Status <span class="pull-right">:</span></b></div>
               <div class="col-md-6">{{ ucfirst($card_details['status']) }}</div>
            </div>

            <div class="row">
               <div class="col-md-4"><b>Card <span class="pull-right">:</span></b></div>
               <div class="col-md-6">XXXX-XXXX-XXXX-{{$card_details['last_four_digits']}}</div>
            </div>

            <div class="row">
               <div class="col-md-4"><b>Exp Date <span class="pull-right">:</span></b></div>
               <div class="col-md-6">{{$card_details['expiry_month']}}/{{$card_details['expiry_year']}}</div>
            </div>

            <div class="row">
               <div class="col-md-4"><b>Address <span class="pull-right">:</span></b></div>
               <div class="col-md-6">
                   {{$card_details['street']}}, {{$card_details['city']}} <br />
                   {{$card_details['state']}}, {{$card_details['country']}} <br />
                   {{$card_details['zip']}}
                </div>
            </div>
            <!-- <table class="table table-hover">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col">Status</th>
                          <th scope="col">Card</th>
                          <th scope="col">Exp Date</th>
                          <th scope="col">Address</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">1</th>
                          <td>Alexander</td>
                          <td>Orton</td>
                          <td>@mdorton</td>
                          <td>Admin</td>
                          <td>USA</td>
                        </tr>
                        
                      </tbody>
                    </table> -->
        </div>

    </div>
</div>
