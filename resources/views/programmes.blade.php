@extends('layouts.app')

@section('content')

<div class="row">
                                        <div class="col-12">
                                            <div class="collapsible email-detail-head">
                                               
                                      <?php
                                      foreach ($programmes as $key => $value) {

                                        ?>
                                         <div class="card collapse-header" role="tablist">
                                                    <div id="headingCollapse<?php echo $key;?>" class="card-header d-flex justify-content-between align-items-center" data-toggle="collapse" role="tab" data-target="#collapse<?php echo $key;?>" aria-expanded="false" aria-controls="collapse5">
                                                        <div class="collapse-title media">
                                                            <div class="pr-1">
                                                                <div class="avatar mr-75">
                                                                    <img src="/images/portrait/small/avatar-s-18.jpg" alt="avatar img holder" width="30" height="30">
                                                                </div>
                                                            </div>
                                                            <div class="media-body mt-25">
                                                                <span class="text-primary"><?php echo $value->title;?></span>

                                                                <small class="text-muted d-block">
                                                                   <?php
                                                                if (isset( $value->start_dt)) {
                                                                  ?>
                                                                 From <?php echo $value->start_dt;?> To <?php echo $value->end_dt;?>
                                                                  <?php
                                                                }
                                                                ?>
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="information d-sm-flex d-none align-items-center">
                                                            <small class="text-muted mr-50">15 Jul 2019, 10:30</small>
                                                            <span class="favorite">
                                                                <i class="bx bx-star mr-25"></i>
                                                            </span>
                                                            <div class="dropdown">
                                                                <a href="javascript:void(0);" class="dropdown-toggle" id="first-open-submenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class='bx bx-dots-vertical-rounded mr-0'></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="first-open-submenu">
                                                                    <a href="javascript:void(0);" class="dropdown-item mail-reply">
                                                                        <i class='bx bx-share'></i>
                                                                        Reply
                                                                    </a>
                                                                    <a href="javascript:void(0);" class="dropdown-item">
                                                                        <i class='bx bx-redo'></i>
                                                                        Forward
                                                                    </a>
                                                                    <a href="javascript:void(0);" class="dropdown-item">
                                                                        <i class='bx bx-info-circle'></i>
                                                                        Report Spam
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="collapse<?php echo $key;?>" role="tabpanel" aria-labelledby="headingCollapse<?php echo $key;?>" class="collapse">
                                                        <div class="card-body py-1">
                                                            <p class="text-bold-500">Description</p>
                                                           <p>
                                                             <?php echo $value->descr;?>
                                                           </p>
                                                           
                                                        </div>
                                                        <div class="card-footer pt-0 border-top">
                                                            <label class="sidebar-label">Details</label>
                                                            <ul class="list-unstyled mb-0">
                                                                <li class="cursor-pointer pb-25">
                                                                    <img src="/images/icon/psd.png" height="30" alt="psd.png">
                                                                    <small class="text-muted ml-1 attachment-text">Total Packages = <?php echo $value->totalPkg;?>
                                                                    <br/><a href="/ebay/programme/<?php echo $value->id;?>/packages">View all packages</a></small>
                                                                </li>
                                                               
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                      }
                                      ?>
                                            </div>
                                        </div>
                                    </div>

@endsection
