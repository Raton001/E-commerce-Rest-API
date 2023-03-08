<th>Order ID</th>
<th>Status</th>
<th>Carrier</th>
<th>State</th>
<th>item</th>

<th>Airway Bill</th>
<th>Action</th>

@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      var account = $('#account').val();
      var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/"+marketplace+"/"+account+"/getAjaxOrders"
    });
  </script>

@endsection