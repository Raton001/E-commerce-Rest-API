<th>Received Date</th>
<th>Product Name  </th>
<th>Inspection Status</th>
<th>Return Qty</th>
<th>Resellable</th>
<th>Not Resellable</th>
<th>Not Resellable Action</th>
<th>Action</th>



@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      var account = $('#account').val();
      //var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxReturnStockHandling"
    });
  </script>

@endsection
