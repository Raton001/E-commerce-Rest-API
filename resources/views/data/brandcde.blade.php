<th>Brand ID</th>
<th>Brand Name</th>
<th>Status</th>


@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      // var account = $('#account').val();
      var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxBrandAnalysis"
    });
  </script>

@endsection
