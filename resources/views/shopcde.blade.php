<th>Shop Name</th>
<th>Total User</th>
<th>User</th>

@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      var account = $('#account').val();
      var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxshopData"
    });
  </script>

@endsection

