
    <!--custom columns-->
    <th>SKU</th>
    <th>name</th>
    <th>selling price</th>
    <th>brand</th>

    @section('footer-scripts')

{{Html::script('assets/js/plugin-coded.js')}}


  <script type="text/javascript">

     var account = $('#account').val();
     var marketplace = $('#marketplace').val();
     var brand_id = $('#brand_id').val();


    //initialize the datalist plugin
    $('body').datalist({
       url: "/"+marketplace+"/"+account+"/getProducts",
       'brand_id': brand_id
    });

    $('#sme').on('change', function() {

      var sme_id = $(this).val();
      $('body').datalist({
       url: "/"+marketplace+"/"+account+"/getProducts",
       'sme_id': sme_id,

    });
    });
  </script>

@endsection

