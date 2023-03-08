<th>Purchase Date</th>
<th>PO Number</th>
<th>Company</th>
<th>Vendor Name</th>
<th>Action</th>


@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      var account = $('#account').val();
      //var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxPurchaseOrder"
    });
  </script>

@endsection
