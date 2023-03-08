<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Coded Project') }}</title>
    <script type="text/javascript" src="//laz-g-cdn.alicdn.com/sj/securesdk/0.0.3/securesdk_lzd_v1.js" id="J_secure_sdk_v2" data-appkey="102326"></script>

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css"/>

<link rel="stylesheet" type="text/css" href=" https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css"/>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>

<link rel="stylesheet" type="text/css" href="https://rawgit.com/nobleclem/jQuery-MultiSelect/master/jquery.multiselect.css" />

<link rel="stylesheet" type="text/css" href="//cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css">
{{-- swal --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css"> --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css"> --}}




    {{Html::style('assets/css/bootstrap.css')}}
    {{Html::style('assets/css/bootstrap-extended.css')}}
    {{Html::style('assets/css/colors.css')}}
    {{Html::style('assets/css/components.css')}}

    {{Html::style('assets/theme/vendor/bootstrap/css/bootstrap.min.css')}}
    {{Html::style('assets/theme/vendor/bootstrap-icons/bootstrap-icons.css')}}
    {{HTML::style('assets/theme/vendor/boxicons/css/boxicons.min.css')}}


    {{Html::style('assets/css/coded.css')}}
    {{Html::style('assets/css/core/menu/menu-types/vertical-menu.css')}}

    {{Html::style('assets/theme/vendor/remixicon/remixicon.css')}}
    {{-- {{Html::style('assets/theme/vendor/swiper/swiper-bundle.min.css')}} --}}
    {{HTML::style('assets/css/plugins/forms/form-quill-editor.css')}}
    {{HTML::style('assets/js/vendors/css/editors/quill/quill.snow.css')}}
    {{HTML::style('assets/css/vendors/css/pickers/pickadate/pickadate.css')}}
    {{HTML::style('assets/css/vendors/css/pickers/daterange/daterangepicker.css')}} -->
    {{HTML::style('assets/js/vendors/css/extensions/swiper.min.css')}}
    {{HTML::style('assets/css/plugins/extensions/swiper.css')}}

    {{HTML::style('assets/css/pages/widgets.css')}}


    {{Html::style('assets/theme/css/style.css')}}
    {{Html::style('assets/css/custom.css')}}

    {{Html::style('assets/css/changelog.css')}}




    <script src="{{ asset('js/app.js') }}" ></script>
    <!-- Styles -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->

<style type="text/css">
    .row.portfolio-container.aos-init.aos-animate {
        /*height: 100vh !important;;*/
        /*height: 100% !important;*/
    }
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link
    {
        background-color: #475F7B;
    }
     #filled-pills .nav-pills .nav-link.active, .nav-pills .show>.nav-link
    {
        background-color:#879fba;
    }
    [data-item-col] input[type=text] {
        border: none;
        outline: none;
        background: none;
        width: 350px;
    }

</style>
    @yield('assets')

</head>

    @if(Request::segment(1) != 'login' && Request::segment(1) != 'register'&& Request::segment(1) != 'password')

        <body
        class="vertical-layout vertical-menu-modern semi-dark-layout 2-columns navbar-sticky footer-static"
        data-open="click"
        data-menu="vertical-menu-modern"
        data-col="2-columns">
    @else
        <body
        class="vertical-layout vertical-menu-modern boxicon-layout no-card-shadow content-left-sidebar todo-application navbar-sticky footer-static loginBg"
        data-open="click"
        data-menu="vertical-menu-modern"
        data-col="content-left-sidebar">


    @endif

<!--content starts-->
    <div id="app">
    @if(Request::segment(1) != 'login' && Request::segment(1) != 'register'&& Request::segment(1) != 'password')

         <header>
             @include('layouts.top-menu', ['stores'=>$stores])
         </header>

         <aside>
             @include('layouts.side-menu')
         </aside>
    @endif

      <div class="app-content content">
          <div class="content-overlay"></div>
          <div class="content-wrapper">
              <div class="content-header row"></div>
              <div class="content-body">

                <main>
                    <section id="portfolio" class="portfolio">
                      <div class="container" data-aos="fade-up">
                        @if(Request::segment(1) != 'login' && Request::segment(1) != 'register'&& Request::segment(1) != 'password')


                        @if (\Session::has('success'))
                          <div class="alert bg-rgba-success">
                            <i class="bx bx-like"></i>
                            {!! \Session::get('success') !!}

                          </div>
                      @endif


                      @if (\Session::has('error'))
                          <div class="alert bg-rgba-danger">
                            <i class="bx bx-like"></i>
                            {!! \Session::get('error') !!}

                          </div>
                      @endif

                      @if (\Session::has('warning'))
                          <div class="alert bg-rgba-danger">
                            <i class="bx bx-like"></i>
                            {!! \Session::get('warning') !!}

                          </div>
                      @endif

                      <!--header-->
                      <?php
                      if (!isset($skipHeader)) {


                      ?>
                      <div class="card">

                        <div class="section-title">
                          <div class="card-header">
                            <h2>@yield('title')</h2>
                            <h3><i class="bx bxs-store" style="font-size: 25px;"></i>@yield('shopname')</h3>

                          </div>
                          <div class="card-body">


                    <div class="row">
                        <div class="col-12 mt-1 mb-2">

                        </div>
                    </div>
                    <div class="row">
                                   <div class="col-xl-4 col-md-4 col-sm-4">
                            <?php
                            if (isset($role)) {


                            if ($role == 'admin') {
                            ?>
                            <div class="text-center">
                                <?php
                                if (isset($sme)) {
                                    ?>
                                    <!--sme-->
                                     <div class="form-group">
                                        <select class="form-control" id="sme">
                                             <?php
                                                foreach ($sme as $key => $value) {
                                                   ?>
                                                   <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                                   <?php
                                                }
                                                ?>
                                        </select>
                                     </div>
                                    <?php
                                }
                                  }
                                ?>
                            </div>

                         <?php } ?>
                        </div>

                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto my-1">
                                        <i class="bx bx-shopping-bag font-medium-5"></i>
                                    </div>
                                    <p class="text-muted mb-0 line-ellipsis">Cart</p>
                                    <h2 class="mb-0" data-qty-total>0</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto my-1">
                                        <i class="bx bx-money font-medium-5"></i>
                                    </div>
                                    <p class="text-muted mb-0 line-ellipsis">Total</p>
                                    <h2 class="mb-0" data-price-total>0</h2>
                                </div>
                            </div>
                        </div>


                    </div>

                          </div>


                        </div>
                      </div>

                      <?php
                        }
                        ?>
                      @endif
                      <!--body-->
                      <div class="card bottom-card">

                        @yield('content')


                      </div>

                    </div>
                  </section>
                </main>

              </div>
          </div>

      </div>
    </div>
<!--content ends-->

<script type="text/javascript">
  var csrfToken = "{{ csrf_token() }}";

</script>
{{Html::script('assets/js/vendors/js/vendors.min.js')}}

{{Html::script('assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js')}}
{{Html::script('assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js')}}
{{Html::script('assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js')}}




{{Html::script('assets/js/core/app-menu.js')}}
{{Html::script('assets/js/core/app.js')}}
{{HTML::script('assets/js/scripts/components.js')}}
{{HTML::script('assets/js/scripts/footer.js')}}
{{Html::script('assets/theme/vendor/aos/aos.js')}}

{{Html::script('assets/theme/vendor/glightbox/js/glightbox.min.js')}}
{{Html::script('assets/theme/vendor/isotope-layout/isotope.pkgd.min.js')}}
{{Html::script('assets/theme/vendor/swiper/swiper-bundle.min.js')}}
{{Html::script('assets/theme/vendor/waypoints/noframework.waypoints.js')}}
{{Html::script('assets/theme/js/main.js')}}


{{HTML::script('assets/js/vendors/js/pickers/pickadate/picker.js')}}
{{HTML::script('assets/js/vendors/js/pickers/pickadate/picker.date.js')}}
{{HTML::script('assets/js/vendors/js/pickers/pickadate/picker.time.js')}}
{{HTML::script('assets/js/vendors/js/pickers/pickadate/legacy.js')}}
{{HTML::script('assets/js/vendors/js/extensions/moment.min.js')}}

{{HTML::script('assets/js/vendors/js/pickers/daterange/daterangepicker.js')}}
{{HTML::script('assets/js/scripts/pickers/dateTime/pick-a-datetime.js')}}

{{HTML::script('assets/js/vendors/js/extensions/swiper.min.js')}}
{{HTML::script('assets/js/scripts/extensions/swiper.js')}}


{{Html::script('assets/js/vendors/js/extensions/jquery.steps.min.js')}}
{{Html::script('assets/js/vendors/js/forms/validation/jquery.validate.min.js')}}

{{Html::script('assets/js/scripts/navs/navs.js')}}
{{Html::script('assets/js/scripts/forms/wizard-steps.js')}}



{{Html::script('assets/js/scripts/extensions/sweet-alerts.js')}}
{{Html::script('assets/js/scripts/cards/widgets.js')}}

{{Html::script('assets/js/vendors/js/forms/repeater/jquery.repeater.min.js')}}
<!-- {{Html::script('assets/js/vendors/js/forms/repeater/repeater.js')}} -->

{{Html::script('assets/js/scripts/forms/form-repeater.js')}}
{{Html::script('assets/js/scripts/popover/popover.js')}}


{{Html::script('assets/js/app-assets/js/scripts/forms/select/form-select2.js')}}

{{Html::script('assets/js/coded.js')}}
{{Html::script('assets/js/coded2.js')}}



{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>
<!-- <script src="https://cdn.socket.io/4.1.3/socket.io.js"></script> -->
<script  type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.0.4/socket.io.js"> </script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>

  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>


<script type="text/javascript" src='https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js'></script>
<script type="text/javascript" src='https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js'></script>
<script type="text/javascript" src='//cdn.datatables.net/buttons/1.2.1/js/buttons.flash.min.js'></script>
<script type="text/javascript" src='//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'></script>
<script type="text/javascript" src='//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js'></script>
<script type="text/javascript" src='//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js'></script>
<script type="text/javascript" src='//cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js'></script>
<script type="text/javascript" src='//cdn.datatables.net/buttons/1.2.1/js/buttons.print.min.js'></script>
<script type="text/javascript" src='https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js'></script>

<script type="text/javascript" src= 'https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js'> </script>



{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> --}}
{{-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> --}}

{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script> --}}

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>



<script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.1/js/dataTables.scroller.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>


<script data-require="jqueryui@*" data-semver="1.10.0" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.0/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.9.4/jquery.dataTables.js" data-semver="1.9.4" data-require="datatables@*"></script>

<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.0/filtering/row-based/range_dates.js"> </script>


<script type="text/javascript" src="js/bootstrap-datepicker.min.js"></script>

{{-- swal --}}
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script> --}}



{{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script> --}}


{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
{{-- <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> --}}


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js"></script>


<script src="https://rawgit.com/nobleclem/jQuery-MultiSelect/master/jquery.multiselect.js"></script>






<script>

  // $(function() {

  // var sock = io('{{ env("PUBLISHER_URL") }}:{{ env("BROADCAST_PORT") }}', {
  //     transports: ['websocket'],
  //     enabledTransports: ["ws", "wss"]
  // });


  // sock.on('action-channel-one:App\\Events\\OrdersEvent', function (data){

  //     //data.actionId and data.actionData hold the data that was broadcast
  //     //process the data, add needed functionality here
  //     var action = data.actionId;
  //     var actionData = data.actionData;





  // });
  //   sock.on("connect_error", (err) => {
  //     console.log(`connect_error due to ${err.message}`);
  //     console.log(err);
  //   });
  // });


//submit form after preventing it
      $('body form').submit(function(e) {
        //category
        $('input[name=category_id]').val($('#category_id').val());
         var quill = new Quill ('.editor');
        var quillHtml = quill.root.innerHTML.trim();

        $('textarea[name=content]').text(quillHtml);


          return true;
        });



// });





@yield( 'js') //add delivery order form



</script>
@yield('footer-scripts')

</body>
</html>
