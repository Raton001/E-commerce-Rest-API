@extends('layouts.app')

@section('content')

<div class="row">
                                        <div class="col-12">
                                            <div class="collapsible email-detail-head">
                                               
                                      <?php
                                      foreach ($packages as $key => $value) {

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
                                                                <span class="text-primary"><?php echo $value->name;?></span>

                                                               
                                                            </div>
                                                        </div>
                                                        <div class="information d-sm-flex d-none align-items-center">
                                                           
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
                                                            <p class="text-bold-500">Users</p>
                                                          <?php
                                                            $i = 0;
                                                          foreach ($value->user as $k => $v) {
                                                            foreach ($v->name as $k2 => $v2) {
                                                                $i++;
                                                                ?>
                                                              <p><?php echo $i.' '.$v2;?> 
                                                              <?php
                                                              switch ($v->status[$k2]) {
                                                                  case 'suspended':
                                                                      $class="warning";
                                                                      break;
                                                                  
                                                                  default:
                                                                      $class="info";
                                                                     
                                                                      break;
                                                              }
                                                              ?>
                                                              <span class="badge badge-light-<?php echo $class;?>"><?php echo $v->status[$k2];?></span></p>
                                                              <?php
                                                            }
                                                              
                                                          }
                                                          ?>
                                                           
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
