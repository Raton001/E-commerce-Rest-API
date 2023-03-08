@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Customize Bulk Upload File</h4>
            </div>
            <div class="card-body">
                 <form action="{{ url('ebay/bulkupload') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-body">
                        <div class="row">


                             <div class="col-md-6 col-12">
                                <div class="form-label-group">
                                    <h6>SME</h6>

                                   <fieldset class="form-group">
                                        <select class="custom-select" id="customSelect" name="sme">
                                           
                                            <?php
                                            foreach ($sme as $key => $value) {
                                               ?>
                                               <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                               <?php
                                            }
                                            ?>
                                        </select>
                                    </fieldset>
                                    
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-label-group">
                                   <fieldset class="form-group">
                                    <h6>launchpackTitle</h6>
                                       
                                        <input type="text" name="launchpackTitle" class="form-control" placeholder="Launchpack Title">
                                    </fieldset>

                                 
                                    
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-label-group">
                                   <fieldset class="form-group">
                   

                                    <fieldset class="form-group">
                                        <label for="basicInputFile">Upload File</label>
                                        <div class="custom-file">
                                            <input type="file" name="spreadsheet" class="custom-file-input" id="inputGroupFile01">
                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                        </div>
                                    </fieldset>

                                 
                                    
                                </div>
                            </div>

                            

                           

                        </div>
                    </div> 

                    <input type="submit" value="Upload" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

