<th>Invoice ID</th>
<th>User ID</th>
<th>Shipment Date</th>
<th>Courier</th>
<th>Shipment Status</th>
<th>Shipment Mode</th>
<th>Account Number</th>
<th>Weight</th>
<th>Tracking Number</th>
<th>Shipping Fee</th>
<th>Shipping Cost</th>

@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      // var account = $('#account').val();
      var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxUserInvoice"
    });
  </script>

@endsection