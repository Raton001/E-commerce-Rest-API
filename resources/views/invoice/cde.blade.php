<th>Invoice ID</th>
<th>Order ID</th>
<th>Shipment Date</th>
<th>Shipment Mode</th>
<th>Shipment Status</th>
<th>Courier</th>
<th>Tracking Number</th>
<th>Shipping Fee</th>
<th>Price</th>
<th>Airway Bill</th>
<th>Order</th>
<th>Action</th>
@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      var account = $('#account').val();
      var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/"+marketplace+"/"+account+"/getAjaxInvoice"
    });
  </script>

@endsection