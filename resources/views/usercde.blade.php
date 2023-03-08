<th>User ID</th>
<th>Name</th>
<th>Username</th>
<th>Ic Number</th>
<th>Address</th>
<th>Phone Number</th>
<th>Email</th>
<th>User Status</th>
<th>Role</th>
<th>Date Join</th>


@section('footer-scripts')

  {{Html::script('assets/js/plugin-coded.js')}}
  <script type="text/javascript">

      // var account = $('#account').val();
      var marketplace = $('#marketplace').val();

    //initialize the datalist plugin

    $('body').datalist({
        url: "/getAjaxUserList"
    });
  </script>

@endsection
