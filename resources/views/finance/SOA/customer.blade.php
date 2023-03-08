@extends('layouts.app')


@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3> Customer SOA</h3>
@endsection

@section('content')

  <div class="row  mx-1">
    <form id="myform" class="row  mx-1">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

        <div class="col-2 col-sm-2 py-2 wow fadeInRight" data-wow-delay="300ms">
            <select class="form-select" aria-label="Default select example" name="year" id="year">
            <option value="">-Year-</option>
                @foreach ($searchyears as $searchyear)
                <option value="{{$searchyear->code}}">{{$searchyear->label}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-2 col-sm-2 py-2 wow fadeInRight" data-wow-delay="300ms" >
            <select class="form-select" aria-label="Default select example" name="month" id="month">
                <option value="" >-Month-</option>
                @foreach ($searchmonths as $searchmonth )
                    <option value="{{$searchmonth->code}}">{{$searchmonth->label}}</option>
                @endforeach
            </select>
      </div>

        <div class="col-3 col-sm-3 py-2 wow fadeInRight" data-wow-delay="300ms" >
            <select class="form-select" aria-label="Default select example" name="company_id" id="company_id" >
                <option value="" >-Select Company-</option>
                @foreach ($companies  as  $company)
                    <option value="{{$company->company_id}}">{{$company->name}}</option>
                @endforeach
            </select>
        </div>


        <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms" >
            <select class="form-select" aria-label="Default select example" name="customer_id" id="customer_id" multiple style="height: 150px" data-live-search="true">
                <option value="" >-Select Customer-</option>
                @foreach ($customers as $customer )
                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-1 col-sm-1 py-2 wow fadeInRight" data-wow-delay="300ms" >
            <button class="btn btn-warning mt-3  wow zoomIn">Reset</button>
        </div>


    </form>

        <div class="col-2 col-sm-2  mb-2 ml-0 wow fadeInRight  d-flex" data-wow-delay="300ms" style="margin-top: -6px;">
            <button type="submit" class="btn btn-primary mt-3 ml-1 wow zoomIn" id="soa" >View SOA</button>
            {{-- <button class="btn btn-warning mt-3 ml-1 wow zoomIn" id="reset">Reset</button> --}}
        </div>

  </div>

  <div id="table">

  </div>







<script>

@section('js')

//view soa tabel
$('#soa').on('click', function(){

    var searchyear = $('#year').val();
    var searchmonth = $('#month').val();
    var company_id = $('#company_id').val();
    var customer_id = $('#customer_id').val();

    $.ajax({
        url: '/get-customer-soa/',
        type: 'post',
        dataType: 'json',
        data: {
            "_token": $('#token').val(),
                searchyear:searchyear,
                searchmonth:searchmonth,
                company_id:company_id,
                customer_id:customer_id,
             },

        success: function (response) {
            console.log(response);
             $('#table').html(response[0]);

        },
    });

});




// //reset
// $('#reset').on('click', function(){
// $('#myform').trigger("reset");
// });


@endsection

</script>


@endsection
