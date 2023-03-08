 <!--content starts-->

                   <div class="row" data-store-id="<?php echo $value;?>">
                        <div class="col-xl-4 col-md-4 col-sm-6 " data-dash-stats>
                            <div class="card text-center">
                                <div class="card-body text-center">
                                        
                                    

                                      <div class="d-flex mb-75">
                                        <div class="avatar bg-rgba-primary m-0 mr-75">
                                            <div class="avatar-content">
                                                <i class="bx bx-dollar text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="activity-progress flex-grow-1">
                                            <small class="text-muted d-inline-block mb-50">Available Selling Limit</small>
                                            <small class="float-right"><?php echo $limit[$value];?></small>
                                            <div class="progress progress-bar-primary progress-sm">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="50" style="width:50%"></div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-xl-2 col-md-4 col-sm-6 " data-dash-stats>
                            <div class="card text-center">
                                <div class="card-body">
                          

                                       <div class="d-flex mb-75">
                                        <div class="avatar bg-rgba-success m-0 mr-75">
                                            <div class="avatar-content">
                                                <i class="bx bx-dollar text-success"></i>
                                            </div>
                                        </div>
                                        <div class="activity-progress flex-grow-1">
                                            <small class="text-muted d-inline-block mb-50">Order Total</small>
                                            <small class="float-right" data-order-total></small>
                                            <div class="progress progress-bar-success progress-sm">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="80" style="width:80%"></div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6 " data-dash-stats>
                            <div class="card text-center">
                                <div class="card-body">
                          


                                     <div class="d-flex mb-75">
                                        <div class="avatar bg-rgba-warning m-0 mr-75">
                                            <div class="avatar-content">
                                                <i class="bx bx-stats text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="activity-progress flex-grow-1">
                                            <small class="text-muted d-inline-block mb-50">Order Volume</small>
                                            <small class="float-right" data-order-volume></small>
                                            <div class="progress progress-bar-warning progress-sm">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="60" style="width:60%"></div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div> -->
                        <div class="col-xl-4 col-md-4 col-sm-6 " data-dash-stats>
                            <div class="card text-center">
                                <div class="card-body">
                     

                                   <div class="d-flex mb-75">
                                        <div class="avatar bg-rgba-info m-0 mr-75">
                                            <div class="avatar-content">
                                                <i class="bx bx-check text-info"></i>
                                            </div>
                                        </div>
                                        <div class="activity-progress flex-grow-1">
                                            <small class="text-muted d-inline-block mb-50">Active Listing</small>
                                            <small class="float-right"><?php echo $active[$value];?></small>
                                            <div class="progress progress-bar-info progress-sm">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="30" style="width:30%"></div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-4 col-sm-6 " data-dash-stats>
                            <div class="card text-center">
                                <div class="card-body">
                         


                                    <div class="d-flex mb-75">
                                        <div class="avatar bg-rgba-danger m-0 mr-75">
                                            <div class="avatar-content">
                                                <i class="bx bx-check text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="activity-progress flex-grow-1">
                                            <small class="text-muted d-inline-block mb-50">Awaiting Shipment</small>
                                            <small class="float-right"><?php echo $awaiting[$value];?></small>
                                            <div class="progress progress-bar-danger progress-sm">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="30" style="width:30%"></div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-xl-2 col-md-4 col-sm-6 ">
                            <div class="card text-center">
                                <div class="card-body">
                                    <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto my-1">
                                        <i class="bx bx-shopping-bag font-medium-5"></i>
                                    </div>
                                    <p class="text-muted mb-0 line-ellipsis">Order</p>
                                    <h2 class="mb-0">40</h2>
                                </div>
                            </div>
                        </div> -->
                    </div>
               <!--content ends-->