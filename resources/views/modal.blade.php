<div class="modal fade" role="dialog" id="transmittal_delete">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ asset('document/wholetrans/delete') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="trn_delete" class="trn_delete">
                    <fieldset>
                        <legend><i class="fa fa-trash"></i> Remove Transmittal</legend>
                    </fieldset>
                    <div class="alert alert-danger">
                        <label for="" class="text-danger">Are you sure you want to delete this document?</label>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-check"></i> Yes</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" role="dialog" id="duration_modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body duration_body">
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" role="dialog" id="ongoing_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body ongoing_body">
               
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" tabindex="-2" role="dialog" id="incident">
    <div class="modal-dialog modal-lg" role="document1">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class=""><i class="fa fa-line-chart"></i> Incident Log</h4>
            </div>
        <div class="modal-body">
            
            <table class="table table-hover table-form table-striped">
                <tr>
                    <td class="col-sm-3"><label>Route Number</label></td>
                    <td class="col-sm-1">:</td>
                    <td class="col-sm-8"><input type="text" readonly id="track_route_no1" value="" class="form-control"></td>
                </tr>
                <tr>
                <td class="col-sm-3"><label>Document Subject</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                <textarea name="subject" id="inci_subject" style="width: 100%; height: 100px" class="form-control" disabled></textarea>    
                </td>
             </tr>
            </table>        
            <form action="{{ asset('chd12report/addIncident') }}" method="POST" class="form-inline">
             {{ csrf_field() }}  
             <input type="hidden" id = "relnewid" name="relnewid">
         <div class="track_history1"></div>

         </form>
        </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-2" role="dialog" id="transincident">
    <div class="modal-dialog modal-lg" role="document1">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class=""><i class="fa fa-line-chart"></i> Transmittal Incident Log</h4>
            </div>
        <div class="modal-body">
        <form action="{{ asset('chd12report/transmittal/addIncident') }}" method="POST">
        {{ csrf_field() }} 
            <table class="table table-hover table-form table-striped">
                <tr>
                    <td class="col-sm-3"><label>TRN Number</label></td>
                    <td class="col-sm-1">:</td>
                    <td class="col-sm-8"><input type="text" id="trn" class="form-control" disabled> <input type="hidden" name="trn" id="trn2" class="form-control"></td>
                </tr>
                <tr>
                <td class="col-sm-3"><label>Transmittal Subject</label></td>
                <td class="col-sm-1">:</td>
                <td class="col-sm-8">
                <textarea name="subject" id="inci_subject" style="width: 100%; height: 100px" class="form-control" disabled></textarea>    
                </td>
             </tr>
            </table>        
           
              
         <div class="transinci"></div>

         </form>
        </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" role="dialog" id="all_form">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body allform">

            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" role="dialog" id="filter_delete">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ asset('document/filter/delete') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="doc_type" class="doc_type">
                    <fieldset>
                        <legend><i class="fa fa-trash"></i> Remove Document Type</legend>
                    </fieldset>
                    <div class="alert alert-danger">
                        <label for="" class="text-danger">Are you sure you want to delete this document?</label>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-check"></i> Yes</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" role="dialog" id="filter_modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body filter_body">
            
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" tabindex="-1" role="dialog" id="track">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class=""><i class="fa fa-line-chart"></i> Track Document</h4>
            </div>
        <div class="modal-body">             
            <table class="table table-hover table-form table-striped">
                <tr>
                    <td class="col-sm-3"><label>Route Number</label></td>
                    <td class="col-sm-1">:</td>
                    <td class="col-sm-8"><input type="text" readonly id="track_route_no" value="" class="form-control"></td>
                </tr>
            </table>
            <hr />                
            <div class="track_history"></div>
        </div>
     
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" tabindex="-1" role="dialog" id="trackDoc">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class=""><i class="fa fa-line-chart"></i> Track Document</h4>
            </div>
            <div class="modal-body">
                <table class="table table-form">
                    <tr>
                        <form action="{{ asset('document/track') }}" id="trackForm" onsubmit="return trackDocument(event);">
                            {{ csrf_field() }}
                            <td class="col-sm-2"><label>Route Number</label></td>
                            <td class="col-sm-9"><input type="text" placeholder="Enter route number..." id="track_route_no2" class="form-control"></td>
                            <td class="col-sm-1"><button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Track</button> </td>
                        </form>
                    </tr>
                </table>
                <hr />
                <div class="track_history"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                <button type="button" class="btn btn-success btn-print hide" onclick="window.open('{{ asset('pdf/track') }}')"><i class="fa fa-print"></i> Print</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade"  role="dialog" id="document_form">
    <div id="my_modal" class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Create Document</h4>
            </div>
        <div class="modal_content"><center><img src="{{ asset('resources/img/spin.gif') }}" width="150" style="padding:20px;"></center></div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="general_form">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <fieldset style="padding: 0px;margin:0px;">
                    <legend id="general_form_title">Create Document</legend>
                </fieldset>
                <div id="general_form_content">
                    <center><img src="{{ asset('resources/img/spin.gif') }}" width="150" style="padding:20px;"></center>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="document_info">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Document</h4>
            </div>
            <div class="modal-body">
                <div class="modal_content"><center><img src="{{ asset('resources/img/spin.gif') }}" width="150" style="padding:20px;"></center></div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="paperSize" style="z-index:999991;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-file-pdf-o"></i> Select Paper Size</h4>
            </div>
            <div class="modal-body text-center">
                <div class="row">
                    <div class="col-xs-12">
                        Portrait: <input type="radio" name="paperOrientation" value="portrait" id="paperOrientation" checked>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        Landscape: <input type="radio" name="paperOrientation" value="landscape" id="paperOrientation">
                        <br><br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <a href="#" class="text-success" onclick="paperSizeAndOrientation('<?php echo asset('pdf/v1/letter'); ?>')">
                            <i class="fa fa-file-pdf-o fa-5x"></i><br>
                            Letter
                        </a>
                    </div>
                    <div class="col-xs-4">
                        <a href="#" class="text-info" onclick="paperSizeAndOrientation('<?php echo asset('pdf/v1/a4'); ?>')">
                            <i class="fa fa-file-pdf-o fa-5x"></i><br>
                            A4
                        </a>
                    </div>
                    <div class="col-xs-4">
                        <a href="#" class="text-warning" onclick="paperSizeAndOrientation('<?php echo asset('pdf/v1/legal'); ?>')">
                            <i class="fa fa-file-pdf-o fa-5x"></i><br>
                            Legal
                        </a>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <br />
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="document_info_pending" style="z-index: 999999">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Document</h4>
            </div>
            <div class="modal_content"><center><img src="{{ asset('resources/img/spin.gif') }}" width="150" style="padding:20px;"></center></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="confirmation">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-question-circle"></i> DTS Says:</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Are you sure you want to end this cycle?</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                <button type="button" class="btn btn-success" id="confirm" data-dismiss="modal"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="calendar_modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Document</h4>
            </div>
            <div class="modal_content"><center><img src="{{ asset('resources/img/spin.gif') }}" width="150" style="padding:20px;"></center></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="online" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <table class="table table-hover">
                    <caption style="font-weight: bold" class="text-success">Who's Online</caption>
                    <tbody class="onlineContent">
                        <tr>
                            <td>
                                <center><img src="{{ asset('resources/img/spin.gif') }}" width="150" style="padding:20px;"></center>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="infoPending" style="margin-top: 30px;z-index: 99999;">
    <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-body">
            <table class="table table-hover">
                <caption style="font-weight: bold" class="text-success"><i class="fa fa-bookmark"></i> Details</caption>
            </table>
            <div class="pendingInfo">
                
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="deleteDocument">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-question-circle"></i> DTS Says:</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>Are you sure you want to delete this document?</strong>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ asset('document/update') }}" method="post">
                    {{ csrf_field() }}
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                    <button type="submit" name="delete" class="btn btn-danger" ><i class="fa fa-trash"></i> Yes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="deleteDocumentPRR">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-question-circle"></i> DTS Says:</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>Are you sure you want to delete this document?</strong>
                </div>
            </div>
            <div class="modal-footer">
                <form action="" method="post">
                    {{ csrf_field() }}
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                    <button type="submit" name="delete" class="btn btn-danger" ><i class="fa fa-trash"></i> Yes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
use App\Tracking_Details;
$incoming = Tracking_Details::select(
        'date_in',
        'id',
        'route_no',
        'received_by',
        'code',
        'delivered_by',
        'action',
        'alert'
        )
        ->where('code',$code)
        ->where('status',0)
        ->where('alert','>=',1)
        ->where('alert','<=',2)
        ->orderBy('tracking_details.date_in','desc')
        ->get();
?>

<div class="modal fade" tabindex="-1" role="dialog" id="pr_paperSize" style="z-index:99999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><i class="fa fa-file-pdf-o"></i> Select Paper Size</h4>
            </div>
            <div class="modal-body text-center">
                <div class="col-xs-4">
                    <a href="{{ asset('prr_supply_pdf/letter') }}" class="text-success" target="_blank">
                        <i class="fa fa-file-pdf-o fa-5x"></i><br>
                        Letter
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="{{ asset('prr_supply_pdf/a4') }}" class="text-info" target="_blank">
                        <i class="fa fa-file-pdf-o fa-5x"></i><br>
                        A4
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="{{ asset('prr_supply_pdf/legal') }}" class="text-warning" target="_blank">
                        <i class="fa fa-file-pdf-o fa-5x"></i><br>
                        Legal
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
            <br />

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" tabindex="-1" role="dialog" id="designation_newedit">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-question-circle"></i> DTS Says:</h4>
            </div>
            <div class="modal-body designation_body">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="deletePR">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-question-circle"></i> DTS Says:</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>Are you sure you want to delete this purchase request - supply?</strong>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ asset('prr_supply_remove') }}" method="post">
                    {{ csrf_field() }}
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                    <button type="submit" name="delete" class="btn btn-danger" ><i class="fa fa-trash"></i> Yes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" role="dialog" id="dur_delete">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ asset('admin/duration/delete') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="dur_id" class="dur_id">
                    <fieldset>
                        <legend><i class="fa fa-trash"></i> Remove Duration</legend>
                    </fieldset>
                    <div class="alert alert-danger">
                        <label for="" class="text-danger">Are you sure you want to delete this sub categorty?</label>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> No</button>
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-check"></i> Yes</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
