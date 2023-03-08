 <div class="tab-pane" id="friends" aria-labelledby="friends-tab" role="tabpanel">
                                            <!-- user profile nav tabs friends start -->
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5>This shop is currently managed by:</h5>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-12">

                                                            <ul class="list-unstyled mb-0">

                                                                <?php
                                                        
                                                            foreach ($managers as $key => $manager) {
                                                               ?>
                                                               <li class="media my-50">
                                                                    <a href="JavaScript:void(0);">
                                                                        <div class="avatar mr-1">
                                                                            <img src="/assets/images/portrait/small/avatar-s-2.jpg" alt="avtar images" width="32" height="32">
                                                                            <span class="avatar-status-online"></span>
                                                                        </div>
                                                                    </a>
                                                                    <div class="media-body">
                                                                        <h6 class="media-heading mb-0"><a href="javaScript:void(0);"><?php echo $manager->username;?></a></h6>
                                                                        <small class="text-muted"><?php echo $manager->rolename;?></small>
                                                                    </div>
                                                                </li>
                                                               <?php
                                                            }
                                                            ?>
                                                                
                                                                
                                                            </ul>
                                                        </div>
                                                        
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                            <!-- user profile nav tabs friends ends -->
                                        </div>