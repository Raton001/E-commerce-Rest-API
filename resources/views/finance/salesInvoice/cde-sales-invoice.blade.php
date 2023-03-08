<th>Invoice Date</th>
<th>Payment Due  </th>
<th>Invoice No</th>
<th>Customer Name</th>
<th>Payment Status</th>
<th>...Amount...</th>
<th>Sales Person</th>
<th>....Action....</th>



@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      var account = $('#account').val();
      //var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxSalesInvoice"
    });
  </script>

@endsection
