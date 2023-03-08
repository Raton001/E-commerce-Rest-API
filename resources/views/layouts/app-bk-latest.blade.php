<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Coded Project') }}</title>
    <script type="text/javascript" src="//laz-g-cdn.alicdn.com/sj/securesdk/0.0.3/securesdk_lzd_v1.js" id="J_secure_sdk_v2" data-appkey="102326"></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.css"/>
 
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
<!--     {{HTML::style('assets/css/vendors/css/pickers/pickadate/pickadate.css')}}
    {{HTML::style('assets/css/vendors/css/pickers/daterange/daterangepicker.css')}} -->
    {{HTML::style('assets/js/vendors/css/extensions/swiper.min.css')}}
    {{HTML::style('assets/css/plugins/extensions/swiper.css')}}

    {{HTML::style('assets/css/pages/widgets.css')}}


    {{Html::style('assets/theme/css/style.css')}}
    {{Html::style('assets/css/custom.css')}}



    
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
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
                       
                        <div class="col-xl-8 col-md-4 col-sm-6">
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


<!-- {{HTML::script('assets/js/vendors/js/pickers/pickadate/picker.js')}}
{{HTML::script('assets/js/vendors/js/pickers/pickadate/picker.date.js')}}
{{HTML::script('assets/js/vendors/js/pickers/pickadate/picker.time.js')}} -->
{{HTML::script('assets/js/vendors/js/pickers/pickadate/legacy.js')}}
{{HTML::script('assets/js/vendors/js/extensions/moment.min.js')}}

<!-- {{HTML::script('assets/js/vendors/js/pickers/daterange/daterangepicker.js')}} -->
<!-- {{HTML::script('assets/js/scripts/pickers/dateTime/pick-a-datetime.js')}} -->

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


<!-- {{Html::script('assets/js/app-assets/js/scripts/forms/select/form-select2.js')}} -->

{{Html::script('assets/js/coded.js')}}
{{Html::script('assets/js/coded2.js')}}




<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- <script src="https://cdn.socket.io/4.1.3/socket.io.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.0.4/socket.io.js"> </script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.3/datatables.min.js"></script>

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

</script>
@yield('footer-scripts')

</body>
</html>
