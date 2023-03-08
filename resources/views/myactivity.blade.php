@extends('layouts.app')

@section('content')

<!-- DISPLAY ACTIVE UNSOLD LISTING -->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <section id="active-unsold-table">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h4>MY ACTIVITY </h4>
                        </div>
                        <div class="float-right">
                            <a href="/ebay/<?php echo $account;?>/listings" type="button" class="btn btn-primary glow invoice-create mr-2 mt-2">LISTINGS</a>
                        </div>
                    </div>
                                 
                    <div class="card-body">
                        <div class="users-list-filter px-1 border rounded mb-1">
                            <form>
                                <div class="row p-1">
                                    <div class="col-12 col-sm-6 col-lg-3 mt-1">
                                        <label for="users-list-verified">Item ID / Package ID</label>
                                        <input type="number" class="form-control" id="item-package-id" name="item-package-id" placeholder="Package ID or Item ID" />
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-3 mt-1">
                                        <label for="users-list-role">Date From</label>
                                        <input type="date" class="form-control" id="date-from" name="date-from" placeholder="Date From" />
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-3 mt-1">
                                        <label for="users-list-status">Date End</label>
                                        <input type="date" class="form-control" id="date-end" name="date-end" placeholder="Date End" />
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg-3 mt-1 float-right d-flex flex-column">
                                        <button type="reset" class="btn btn-success btn-block mb-0">Search</button>
                                        <button type="reset" class="btn btn-danger btn-block mb-0">Clear</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-4 table-hover">
                                <?php $i=1; ?>
                                <thead class="thead-dark text-center">
                                    <tr>
                                        <td width="3%">#</td>
                                        <td width="13%">Item ID / Package ID</td>
                                        <td>Title</td>
                                        <td>Action</td>
                                        <td>Origin</td>
                                        <td width="12%">Created At</td>
                                        <td width="12%">Updated At</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mylaunchpacks as $mlp)
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td class="text-center">{{ $mlp->package_id }}</td>
                                        <td class="text-center"> - </td>
                                        <td class="text-center">{{ $mlp->action }}</td>
                                        <td class="text-center"><div class="badge badge-light-success mb-1 p-1"><span style="color: black">CODED</span></div></td>
                                        <td>{{ date('Y-m-d h:i A', strtotime($mlp->created_at)) }}</td>
                                        <td>{{ date('Y-m-d h:i A', strtotime($mlp->updated_at)) }}</td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                    @endforeach
                                    @foreach ($oldlistings as $ol)
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td class="text-center">{{ $ol->item_id }}</td>
                                        <td>{{ $ol->title }}</td>
                                        <td class="text-center">{{ $ol->action }}</td>
                                        <td class="text-center"><div class="badge badge-light-secondary mr-1 mb-1"><span style="color: black">NON-CODED</span></div></td>
                                        <td class="text-center">{{ date('Y-m-d h:i A', strtotime($ol->created_at)) }}</td>
                                        <td class="text-center">{{ date('Y-m-d h:i A', strtotime($ol->updated_at)) }}</td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- END DISPLAY ACTIVE UNSOLD LISTING -->
