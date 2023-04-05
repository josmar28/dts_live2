<form method="POST" action="{{ asset('document/transmittal/add') }}" class="form-submit">
{{ csrf_field() }}
<div class="modal-body">             
            <table class="table table-hover table-form table-striped">
                <tr>
                    <td class="col-sm-3"><label>Transmittal Number</label></td>
                    <td class="col-sm-1">:</td>
                    <td class="col-sm-8"><input name="act_trn" type="text" readonly  value="@if(isset($trn)){{$trn}}@endif" class="form-control"></td>
                    <input type="hidden" name="trn_code" value="{{$trn_code}}">
                </tr>
                <tr>
                    <td class="col-sm-3"><label>Description</label></td>
                    <td class="col-sm-1">:</td>
                    <td class="col-sm-8"><input type="text" name="desc" value="@if(isset($desc)) {{$desc}} @endif"  class="form-control" required></td>
                  
                </tr>
            </table>
            <hr />      
         
    <div class="table-responsive">
    <div id="scrollid" style="height: 450px;overflow: scroll;">
        <table class="table table-list table-hover table-striped">
            <thead>
                <tr>
                    <th width="8%"></th>
                    <th width="20%">Route #</th>
                    <th width="15%">Delivered By</th>
                    <th width="15%">Prepared Date</th>
                    <th width="20%">Document Type</th>
                    <th>Deliverd To</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach($documents as $key => $doc)
                <tr>
                    <td class="action">
                    <input type="checkbox" id="<?php echo "checked".$key;?>" name="route_no[]" value="{{ $doc->route_no }}">
                        <br />
                      
                    </td>
                    <td>
                    {{ $doc->route_no }}
                    </td>        
                    <td>
                    {{ $doc->fname }} {{ $doc->lname }}
                    </td>          
                    <td>{{ date('M d, Y',strtotime($doc->prepared_date)) }}<br>{{ date('h:i:s A',strtotime($doc->prepared_date)) }}</td>
                    <td>
                    {{ $doc->doc_type }}
                    </td>
                    <td>
                    <?php
                                $temp = explode(';',$doc->code);
                                
                                if($section = \App\Section::find($temp[1])){
                                    $section = $section->description;
                                } else {
                                    $section = "NO SECTION";
                                }
                            ?>
                    {{ $section }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <div id="myDIV" style="display:none">  
            <a href="#transmittal_modal" data-toggle="modal" class="btn btn-success dropdown-toggle" >Add transmittal</a>
           </div>
        </table>
        </div>
     </div>
</div>
    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
    <button type="submit" class="btn btn-success btn-submit"><i class="fa fa-send"></i> Submit</button>
</div>
</form>
<script>
    $('.form-submit').on('submit',function(){
        $('.btn-submit').attr("disabled", true);
    });
</script>