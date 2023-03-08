@extends('layouts.app')

@section('content')

 <!-- users list start -->
<div class="media-body">

        <h6 class="media-heading"><span class="text-bold-500"><?php echo $group[0]['name'];?></span> <?php echo ($group[0]['parent']> 0? 'sub group':'');?></h6><small class="notification-text"><?php echo $group[0]['user_total'];?> users</small>
    </div>
                <section class="users-list-wrapper">
                    <div class="users-list-filter px-1">
                        <form>
                            <div class="row border rounded py-2 mb-2">
                                <!-- <div class="col-12 col-sm-6 col-lg-3">
                                    <label for="users-list-verified">Verified</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" id="users-list-verified">
                                            <option value="">Any</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </fieldset>
                                </div> -->
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label for="users-list-role">Role</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" id="users-list-role">
                                            <option value="">Any</option>
                                            <option value="Super">Super User</option>
                                            <option value="Staff">Staff</option>
                                            <option value="mgt">Management</option>
                                            <option value="Admin">Admin</option>
                                            <option value="apg">Apg</option>
                                            <option value="inventory">Inventory</option>
                                            <option value="fin">Fin</option>
                                            <option value="selluser">Sell User</option>


                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label for="users-list-status">Status</label>
                                    <fieldset class="form-group">
                                        <select class="form-control" id="users-list-status">
                                            <option value="">Any</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <!-- <option value="Banned">Banned</option> -->
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-center">
                                    <button type="reset" class="btn btn-primary btn-block glow users-list-clear mb-0">Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="users-list-table">
                        <div class="card">
                            <div class="card-body">
                                <!-- datatable start -->
                                <div class="table-responsive">
                                    <table id="users-list-datatable" class="table">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <!-- <th>username</th> -->
                                                <th>name</th>
                                                <!-- <th>last activity</th> -->
                                                <!-- <th>verified</th> -->
                                                <th>role</th>
                                                <th>status</th>
                                                <th>edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                            $count = 0;
                                            foreach ($users as $key => $value) {
                                                $count++;
                                               ?>
                                                <tr>
                                                <td><?php echo $count;?></td>
                                                <!-- <td></td> -->
                                                <td><?php echo $value->name;?></td>
                                                
                                               <!--  <td></td>
                                                <td></td> -->

                                                <td><?php echo $value->role;?> </td>
                                                <td>
                                                    <?php 
                                                    if ($value->status == 1) {
                                                        ?>
                                                        <span class="badge badge-light-success">Active</span>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <span class="badge badge-light-danger">Inactive</span>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                                <td><a href="../../../html/ltr/vertical-menu-template/app-users-edit.html"><i class="bx bx-edit-alt"></i></a></td>
                                            </tr>
                                               <?php
                                            }
                                            ?>
                              
                                        </tbody>
                                    </table>
                                </div>
                                <!-- datatable ends -->
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users list ends -->


@endsection
