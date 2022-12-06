<?php
    if($size=="letter"){
        if($orientation=='portrait'){
            $top = '960px';
            $left = '50%';
        }
        else{
            $top = '720px';
            $left = '55%';
        }
    } elseif($size=="a4"){
        if($orientation=='portrait'){
            $top = '1030px';
            $left = '50%';
        }
        else{
            $top = '700px';
            $left = '55%';
        }
    } elseif($size=="legal"){
        if($orientation=='portrait'){
            $top ='1255px';
            $left = '50%';
        }
        else{
            $top = '717px';
            $left = '60%';
        }
    }
?>
<html>
<style type="text/css">
    .barcode {
        position: relative;
        left: -50%;
    }
    .route_no {
        font-size: 1em;
        text-align:center;
    }
</style>
<title>{{ Session::get('route_no') }}</title>
<body>
<div style="position: absolute; left:{{$left}};top:{{$top}}">
    <div class="barcode">
        <font class="route_no">{{ Session::get('route_no') }}</font>
        <?php echo DNS1D::getBarcodeHTML(Session::get('route_no'),"C39E",1,15) ?>
    </div>
</div>
</body>
</html>