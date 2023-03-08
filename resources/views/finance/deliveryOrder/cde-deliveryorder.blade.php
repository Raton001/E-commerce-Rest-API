<th>...Date...</th>
<th>Do Number</th>
<th>Status</th>
<th>Company Name</th>
<th>Customer Name</th>
<th>Sales Person</th>
<th style="text-align: center">Invoice</th>
<th>Action</th>


@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      var account = $('#account').val();
      //var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxDeliveryOrder"
    });
  </script>

@endsection
