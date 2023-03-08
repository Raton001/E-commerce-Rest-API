<th>Received Date</th>
<th>Do Number</th>
<th>Company Name</th>
<th>Customer</th>
<th>Credit Note</th>
<th>Action</th>


@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      var account = $('#account').val();
      //var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxSalesReturn"
    });
  </script>

@endsection
