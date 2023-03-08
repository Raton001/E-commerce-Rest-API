
<th>Product Name</th>
<th>Brand Name</th>
<th>Category Name</th>
<th>Product Status</th>
<th>SKU</th>
<th>Date</th>


@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      // var account = $('#account').val();
      var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxDataAnalysis"
    });
  </script>

@endsection
