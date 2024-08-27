<div id="add-service-modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Service</h4>
                &nbsp;
                {{csrf_field()}}
                <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="add-service-form">
                    {{csrf_field()}}
                    <input type="hidden" name="id" class="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Service type   </label> 
                                    <br />  
                                       <input type="text" class="form-control description" placeholder="Description" name="description">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <center><label> Timeframe   </label></center> 
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control days" placeholder="Day/s" name="days">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control hours" placeholder="Hour/s" name="hours">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control minutes" placeholder="Minute/s" name="minutes">
                                            </div>
                                        </div>  
                                        
                                </div>
                            </div>
                        </div>
                        <center><button type="submit" class="btn btn-primary td_section">Save</button></center>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="delete-service-type-modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content modal-filled bg-danger">
            <div class="modal-header">
                <h4 class="modal-title">DELETE TYPE OF SERVICE</h4>
            </div>
            <div class="modal-body">
                <form id="service-delete-form" method="POST">
                    {{csrf_field()}}
                    <p class="mt-3">Are you sure you want to delete type of service?</p>
                    <input type="hidden" name="id" id="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
                <button type="submit" class="btn waves-effect waves-light btn-danger">DELETE</button>
                </form>
            </div>
        </div>
    </div>
</div>