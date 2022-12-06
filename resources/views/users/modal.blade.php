

@extends('layouts.app')
@section('content')

    <a href="#" class="paulund_modal">Click Here</a>


@endsection

@section('js')

   @@parent
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
   <script src="jquery.paulund_modal_box.js"></script>
   <script>
   </script>
@endsection