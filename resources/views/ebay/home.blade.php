@extends('layouts.app')

@section('assets')
    {{Html::style('theme/vendor/glightbox/css/glightbox.min.css')}}

@endsection


@section('title')
    Dashboard
@endsection

@section('title-content')

    <!--tabbed content-->
    <ul id="portfolio-flters" class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
        <li data-filter="*" class="filter-active">All</li>
        @foreach ($store as $value)
         <li data-filter=".filter-<?php echo $value;?>"><?php echo $value;?></li>
        @endforeach
    </ul>

    <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

        @foreach ($store as $value)
          <div class="col-lg-12 col-md-6 portfolio-item filter-<?php echo $value;?>">
          @include('ebay.dashboard.stats');
          </div>
        @endforeach

    </div>

@endsection


@section('content')
    @include('ebay.dashboard.launchpacks');
@endsection