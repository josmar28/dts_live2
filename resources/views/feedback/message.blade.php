<div class="modal-body">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <table class="table table-hover table-striped">
                    <tr>
                        <td class="text-right col-lg-5">Subject :</td>
                        <td class="col-lg-7">{{ $feedback->subject }}</td>
                    </tr>
                    <tr>
                        <td class="text-right col-lg-5">Tel No. :</td>
                        <td class="col-lg-7">{{ $feedback->telno }}</td>
                    </tr>
                    <tr>
                        <td class="text-right col-lg-5">Message :</td>
                        <td class="col-lg-7">{!! nl2br($feedback->message) !!}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <form action="{{ asset('feedback/action') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $feedback->id }}" />
                    <table class="table table-hover table-form table-striped">
                        <tr>
                            <td class="col-sm-3"><label>Action</label></td>
                            <td class="col-sm-1">:</td>
                            <td class="col-sm-8">
                                <select name="actionid" class="form-control">
                                    <option value="" selected>Select action</option>
                                    @foreach(\App\FeedbackStatus::all() as $status)
                                        <option value="{{ $status->id }}">{{ $status->action }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="col-sm-8">
                                <button type="submit" class="btn btn-success" name="action" value="action">Ok</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>