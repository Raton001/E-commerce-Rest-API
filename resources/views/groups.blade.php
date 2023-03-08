@extends('layouts.app')

@section('content')
  
<?php
if (isset($groups)) {
    echo $groups;
}
?>
@endsection


