@extends('layouts.app')

@section('assets')

{{Html::style('assets/css/plugins/forms/wizard.css')}}
@endsection

@section('title')
    Shop
@endsection
<?php
  if (isset($shopname)) {

  
?>
@section('shopname')
    <?php echo $shopname;?>
@endsection
<?php
}
?>

@section('content')
<!-- page user profile start -->
                <section class="page-user-profile">
                    <div class="row">
                        <div class="col-12">
                            <!-- user profile heading section start -->
                            <div class="card">
                                <div class="user-profile-images">
                                    <!-- user timeline image -->
                                    <img src="/assets/images/profile/post-media/profile-banner.jpg" class="img-fluid rounded-top user-timeline-image" alt="user timeline image">
                                    <!-- user profile image -->
                                    <img src="/assets/images/portrait/small/avatar-s-16.jpg" class="user-profile-image rounded" alt="user profile image" height="140" width="140">
                                </div>
                                <div class="user-profile-text">
                                    <h4 class="mb-0 text-bold-500 profile-text-color"><?php echo $shopname;?></h4>
                                    <small>Shop</small>
                                </div>
                                <!-- user profile nav tabs start -->
                                <div class="card-body px-0">
                                    <ul class="nav user-profile-nav justify-content-center justify-content-md-start nav-pills border-bottom-0 mb-0" role="tablist">
                                        <li class="nav-item mb-0">
                                            <a class=" nav-link d-flex px-1 active" id="feed-tab" data-toggle="tab" href="#feed" aria-controls="feed" role="tab" aria-selected="true"><i class="bx bx-home"></i><span class="d-none d-md-block">Feed</span></a>
                                        </li>
                                        <li class="nav-item mb-0">
                                            <a class="nav-link d-flex px-1" id="activity-tab" data-toggle="tab" href="#activity" aria-controls="activity" role="tab" aria-selected="false"><i class="bx bx-user"></i><span class="d-none d-md-block">Activity</span></a>
                                        </li>
                                        <li class="nav-item mb-0">
                                            <a class="nav-link d-flex px-1" id="friends-tab" data-toggle="tab" href="#friends" aria-controls="friends" role="tab" aria-selected="false"><i class="bx bx-message-alt"></i><span class="d-none d-md-block">Managers</span></a>
                                        </li>
                                        <li class="nav-item mb-0 mr-0">
                                            <a class="nav-link d-flex px-1" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="false"><i class="bx bx-copy-alt"></i><span class="d-none d-md-block">Profile</span></a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- user profile nav tabs ends -->
                            </div>
                            <!-- user profile heading section ends -->

                            <!-- user profile content section start -->
                            <div class="row">
                                <!-- user profile nav tabs content start -->
                                <div class="col-lg-9">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="feed" aria-labelledby="feed-tab" role="tabpanel">
                                            <!-- user profile nav tabs feed start -->
                                            <div class="row">
                                                <!-- user profile nav tabs feed left section start -->
                                                <div class="col-lg-4">
                                                    <!-- user profile nav tabs feed left section info card start -->
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-1">Info
                                                                <i class="cursor-pointer bx bx-dots-vertical-rounded float-right"></i>
                                                            </h5>
                                                            <ul class="list-unstyled mb-0">
                                                                <li class="d-flex align-items-center mb-25">
                                                                    <i class="bx bx-briefcase mr-50 cursor-pointer"></i><span>UX
                                                                        Designer at<a href="JavaScript:void(0);">&nbsp;google</a></span>
                                                                </li>
                                                                <li class="d-flex align-items-center mb-25">
                                                                    <i class="bx bx-briefcase mr-50 cursor-pointer"></i> <span>Former
                                                                        UI
                                                                        Designer at<a href="JavaScript:void(0);">&nbsp;CBI</a></span>
                                                                </li>
                                                                <li class="d-flex align-items-center mb-25">
                                                                    <i class="bx bx-receipt mr-50 cursor-pointer"></i> <span>Studied
                                                                        <a href="JavaScript:void(0);">&nbsp;IT science</a> at<a href="JavaScript:void(0);">&nbsp;Torronto</a></span>
                                                                </li>
                                                                <li class="d-flex align-items-center mb-25">
                                                                    <i class="bx bx-receipt mr-50 cursor-pointer"></i><span>Studied at
                                                                        <a href="JavaScript:void(0);">&nbsp;College of new Jersey</a></span>
                                                                </li>
                                                                <li class="d-flex align-items-center">
                                                                    <i class="bx bx-rss mr-50 cursor-pointer"></i> <span>Followed by<a href="JavaScript:void(0);">&nbsp;338 people</a></span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <!-- user profile nav tabs feed left section info card ends -->
                                                    <!-- user profile nav tabs feed left section trending card start -->
                                                   
                                                   
                                                   
                                                </div>
                                                <!-- user profile nav tabs feed left section ends -->
                                                <!-- user profile nav tabs feed middle section start -->
                                                <div class="col-lg-8">
                                                    <!-- user profile nav tabs feed middle section post card start -->
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <!-- user profile middle section blogpost nav tabs card start -->
                                                            <ul class="nav nav-pills justify-content-center justify-content-sm-start border-bottom-0" role="tablist">
                                                                <li class="nav-item">
                                                                    <a class="nav-link active d-flex" id="user-status-tab" data-toggle="tab" href="#user-status" aria-controls="user-status" role="tab" aria-selected="true"><i class="bx bx-detail align-text-top"></i>
                                                                        <span class="d-none d-md-block">Status</span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link d-flex" id="multimedia-tab" data-toggle="tab" href="#user-multimedia" aria-controls="user-multimedia" role="tab" aria-selected="false"><i class="bx bx-movie align-text-top"></i>
                                                                        <span class="d-none d-md-block">Multimedia</span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item mr-0">
                                                                    <a class="nav-link d-flex" id="blog-tab" data-toggle="tab" href="#user-blog" aria-controls="user-blog" role="tab" aria-selected="false"><i class="bx bx-chat align-text-top"></i>
                                                                        <span class="d-none d-md-block">Blog Post</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content pl-0">
                                                                <div class="tab-pane active" id="user-status" aria-labelledby="user-status-tab" role="tabpanel">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-1 col-2">
                                                                                    <div class="avatar">
                                                                                        <img src="/assets/images/portrait/small/avatar-s-2.jpg" alt="user image" width="32" height="32">
                                                                                        <span class="avatar-status-online"></span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-11 col-10">
                                                                                    <textarea class="form-control border-0 shadow-none" id="user-post-textarea" rows="3" placeholder="Share what you are thinking here..."></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <hr>
                                                                            <div class="card-footer p-0">
                                                                                <i class="cursor-pointer bx bx-camera font-medium-5 text-muted mr-1 pt-50" data-toggle="tooltip" data-popup="tooltip-custom" data-placement="top" title="Upload a picture"></i>
                                                                                <i class="cursor-pointer bx bx-face font-medium-5 text-muted mr-1 pt-50" data-toggle="tooltip" data-popup="tooltip-custom" data-placement="top" title="Tag your friend"></i>
                                                                                <i class="cursor-pointer bx bx-map font-medium-5 text-muted pt-50" data-toggle="tooltip" data-popup="tooltip-custom" data-placement="top" title="Share your location"></i>
                                                                                <span class=" float-sm-right d-flex flex-sm-row flex-column justify-content-end">
                                                                                    <button class="btn btn-light-primary mr-0 my-1 my-sm-0 mr-sm-1">Preview</button>
                                                                                    <button class="btn btn-primary">Post Status</button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <!-- user profile middle section blogpost nav tabs card ends -->
                                                        </div>
                                                    </div>
                                                  
                                                  
                                                </div>
                                                <!-- user profile nav tabs feed middle section ends -->
                                            </div>
                                            <!-- user profile nav tabs feed ends -->
                                        </div>
                                        <div class="tab-pane " id="activity" aria-labelledby="activity-tab" role="tabpanel">
                                            <!-- user profile nav tabs activity start -->
                                            <div class="card">
                                                <div class="card-body">
                                                    <!-- timeline start -->
                                                    <ul class="timeline">
                                                        <li class="timeline-item timeline-icon-success active">
                                                            <div class="timeline-time">Tue 8:17pm</div>
                                                            <h6 class="timeline-title">Martina Ash</h6>
                                                            <p class="timeline-text">on <a href="JavaScript:void(0);">Received Gift</a></p>
                                                            <div class="timeline-content">
                                                                Welcome to video game and lame is very creative
                                                            </div>
                                                        </li>
                                                        <li class="timeline-item timeline-icon-primary active">
                                                            <div class="timeline-time">5 days ago</div>
                                                            <h6 class="timeline-title">Jonny Richie attached file</h6>
                                                            <p class="timeline-text">on <a href="JavaScript:void(0);">Project name</a></p>
                                                            <div class="timeline-content">
                                                                <img src="/assets/images/icon/sketch.png" alt="document" height="36" width="27" class="mr-50">Data Folder
                                                            </div>
                                                        </li>
                                                        <li class="timeline-item timeline-icon-danger active">
                                                            <div class="timeline-time">7 hours ago</div>
                                                            <h6 class="timeline-title">Mathew Slick docs</h6>
                                                            <p class="timeline-text">on <a href="JavaScript:void(0);">Project name</a></p>
                                                            <div class="timeline-content">
                                                                <img src="/assets/images/icon/pdf.png" alt="document" height="36" width="27" class="mr-50">Received Pdf
                                                            </div>
                                                        </li>
                                                        <li class="timeline-item timeline-icon-info active">
                                                            <div class="timeline-time">5 hour ago</div>
                                                            <h6 class="timeline-title">Petey Cruiser send you a message</h6>
                                                            <p class="timeline-text">on <a href="JavaScript:void(0);">Redited message</a></p>
                                                            <div class="timeline-content">
                                                                Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it
                                                                is
                                                                pain, but because occasionally circumstances
                                                            </div>
                                                        </li>
                                                        <li class="timeline-item timeline-icon-warning">
                                                            <div class="timeline-time">2 min ago</div>
                                                            <h6 class="timeline-title">Anna mull liked </h6>
                                                            <p class="timeline-text">on <a href="JavaScript:void(0);">Liked</a></p>
                                                            <div class="timeline-content">
                                                                The Amairates
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <!-- timeline ends -->
                                                    <div class="text-center">
                                                        <button class="btn btn-primary">View All Activity</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- user profile nav tabs activity start -->
                                        </div>
                                        @include('managers', ['data'=>$managers])
                                        @include('profile', ['profile'=>$profile])
                                       
                                        
                                    </div>
                                </div>
                                <!-- user profile nav tabs content ends -->
                                <!-- user profile right side content start -->
                                <div class="col-lg-3">
                                    <!-- user profile nav tabs feed left section today's events card start -->
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-1">Available Listings<i class="cursor-pointer bx bx-dots-vertical-rounded float-right"></i>
                                                            </h5>
                                                            <?php
                                                            if (isset($launchpacks)) {

                                                            foreach ($launchpacks as $key => $launchpack) {
                                                             ?>
                                                             <div class="user-profile-event">
                                                                <div class="pb-1 d-flex align-items-center">
                                                                    <i class="cursor-pointer bx bx-radio-circle-marked text-primary mr-25"></i>
                                                                    <small>10:00am</small>
                                                                </div>
                                                                <h6 class="text-bold-500 font-small-3"><?php echo $launchpack['name'];?></h6>
                                                                <p class="text-muted font-small-2">RM <?php echo $launchpack['price'];?></p>
                                                              
                                                            </div>
                                                            <hr>
                                                             <?php
                                                            }
                                                            }

                                                            ?>
                                                          
                                                           
                                                            <button class="btn btn-block btn-secondary">Check all your Available Listings</button>
                                                        </div>
                                                    </div>
                                                    <!-- user profile nav tabs feed left section today's events card ends -->
                                    <!-- user profile right side content related groups start -->
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-1">Pending Orders
                                                <i class="cursor-pointer bx bx-dots-vertical-rounded align-top float-right"></i>
                                            </h5>
                                            <?php
                                 
                                            foreach ($orders['READY_TO_SHIP'] as $key => $value) {
                                             ?>
                                              <div class="media d-flex align-items-center mb-1">
                                                <a href="JavaScript:void(0);">
                                                    <img src="/assets/images/banner/banner-30.jpg" class="rounded" alt="group image" height="64" width="64" />
                                                </a>
                                                <div class="media-body ml-1">
                                                    <h6 class="media-heading mb-0"><small><?php echo $value->ordersn;?></small></h6><small class="text-muted">(<?php echo $value->order_status;?>)</small>
                                                </div>
                                                <i class="cursor-pointer bx bx-plus-circle text-primary d-flex align-items-center "></i>
                                            </div>
                                             <?php
                                            }
                                            ?>
                                           
                                           
                                        </div>
                                    </div>
                                    <!-- user profile right side content related groups ends -->
                                    <!-- user profile right side content gallery start -->
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-1">Uploaded Listings
                                                <i class="cursor-pointer bx bx-dots-vertical-rounded align-top float-right"></i>
                                            </h5>
                                            <div class="row">
                                              <?php
                                              foreach ($listings as $key => $listing) {
                                               ?>
                                               <div class="col-md-4 col-6 pl-25 pr-0 pb-25">
                                                    <img src="<?php echo $listing;?>" class="img-fluid" alt="gallery avtar img">
                                                </div>
                                               <?php
                                              }
                                              ?>
                                                
                                               
                                            </div>
                                        </div>
                                    </div>
                                    <!-- user profile right side content gallery ends -->
                                </div>
                                <!-- user profile right side content ends -->
                            </div>
                            <!-- user profile content section start -->
                        </div>
                    </div>
                </section>
                <!-- page user profile ends -->

@endsection

@section('assets')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

{{Html::script('assets/js/scripts/navs/navs.js')}}
{{Html::script('assets/js/scripts/forms/wizard-steps.js')}}

@endsection
