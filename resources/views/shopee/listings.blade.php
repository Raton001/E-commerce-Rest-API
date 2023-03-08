<th>SKU</th>
<th>Name</th>
<th>Price</th>
<th>Variation</th>
<th>View</th>


@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      var account = $('#account').val();
      var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/"+marketplace+"/"+account+"/getAjaxListings"
    });
  </script>

@endsection