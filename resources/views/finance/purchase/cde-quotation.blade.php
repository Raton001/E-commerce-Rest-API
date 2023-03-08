<th>Quotation Date</th>
<th> Valid Until</th>
<th>Quotation No</th>
<th>Company </th>
<th>Customer Name</th>
<th>Action</th>


@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      var account = $('#account').val();
      //var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxQuotation"
    });
  </script>

@endsection
