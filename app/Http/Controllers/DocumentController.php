<?php

namespace App\Http\Controllers;

use App\Section;
use App\Tracking_Releasev2;
use Illuminate\Http\Request;
use App\Tracking;
use App\User;
use App\Users;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Tracking_Filter;
use App\Tracking_Details;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SystemController as System;
use App\Http\Controllers\ReleaseController as Rel;
use App\Release;
use PDO;
use DateTime;
use App\SoLogs;

class DocumentController extends Controller
{
    public function __construct()
    {
        if(!$login = Session::get('auth')){
            $this->middleware('auth');
        }
    }
    public function index()
    {
        $user = Session::get('auth');
        $id = $user->id;
        $keyword = Session::get('keyword');

        $data['documents'] = Tracking::where('prepared_by',$id)
            ->where(function($q) use ($keyword){
                $q->where('route_no','like',"%$keyword%")
                    ->orwhere('description','like',"%$keyword%")
                    ->orWhere('purpose','like',"%$keyword%");
            })
            ->orderBy('id','desc')
            ->paginate(15);
        $data['access'] = $this->middleware('access');
        return view('document.list',$data);

    }

    public function search(Request $request){
        Session::put('keyword',$request->keyword);   
        return self::index();
    }

    public function accept(Request $request){
        $user = Session::get('auth');
        $code = 'temp;'.$user->section;
        $data = Tracking_Details::select(
            'date_in',
            'id',
            'route_no',
            'received_by',
            'code',
            'delivered_by',
            'action'
        )
            ->where('code',$code)
            ->where('status',0)
            ->orderBy('tracking_details.date_in','desc')
            ->paginate(200);

        return view('document.accept',[
            'data' => $data
        ]);
    }

    public function update(Request $req)
    {
        $id = $req->currentID;
        $user_id = Session::get('auth')->id;
        if($req->submit=='update'){
            $update = array();
            foreach($_POST as $key => $value):
                if($key=='currentID' || $key=='_token' || $key=='submit'){
                    continue;
                }else{
                    $update[$key] = $value;
                }
            endforeach;
            Tracking::where('id',$id)
                ->update($update);
            $route_no = Session::get('route_no');
           $desc_up = Tracking_Details::where('route_no',$route_no)
            ->orderby('id','desc');

            if($desc_up->first())
            {
                $desc_up->update([
                    'action' => $req->description
                ]);
            }

          
            System::logDocument($user_id,$id);
            Session::put('updated',true);
        }else{
            $route_no = Session::get('route_no');
            System::logDefault('Deleted',$route_no);
            Tracking::where('route_no',$route_no)->delete();
            Tracking_Details::where('route_no',$route_no)->delete();
            Release::where('route_no',$route_no)->delete();
            Session::put('deleted',true);
        }
        return redirect()->back();
    }


    //RUSEL
    public function connect()
    {
        return new PDO("mysql:host=192.168.1.51;dbname=dohdtr",'root','r00t');
    }
    public function getSO($route_no)
    {
        $db = $this->connect();
        $sql = "select * from office_order where route_no = ?";
        $pdo = $db->prepare($sql);
        $pdo->execute(array($route_no));
        $row = $pdo->fetch();
        $db = null;

        return $row;
    }
    public function updateSO($approved_status,$route_no)
    {
        $db = $this->connect();
        $sql = "update office_order set approved_status = ? where route_no = ?";
        $pdo = $db->prepare($sql);
        $pdo->execute(array($approved_status,$route_no));
        $db = null;
    }
    public function inclusive_name($route_no)
    {
        $db = $this->connect();
        $sql = "select * from inclusive_name where route_no = ?";
        $pdo = $db->prepare($sql);
        $pdo->execute(array($route_no));
        $row = $pdo->fetchAll();
        $db = null;

        return $row;
    }
    public function calendar($route_no)
    {
        $db = $this->connect();
        $sql = "select * from calendar where route_no = ?";
        $pdo = $db->prepare($sql);
        $pdo->execute(array($route_no));
        $row = $pdo->fetchAll();
        $db = null;

        return $row;
    }
    //END RUSEL
    public function saveDocument(Request $request){
        $user = Session::get('auth');
        $id = $user->id;
        $status = array();
        echo '<pre>';
        for($i=0;$i<10;$i++):
            if(!$request->route_no[$i])
            {
                continue;
            }
            $route_no = $request->route_no[$i];
            $doc = Tracking::where('route_no',$route_no)
                ->orderBy('id','desc')
                ->first();
            if($doc){
                $document = Tracking_Details::where('route_no',$route_no)
                    ->orderBy('id','desc')
                    ->first();
                if($document):
                    Tracking_Details::where('route_no',$route_no)
                        ->where('received_by',$document->received_by)
                        ->update(['status'=> 1]);
                    $received_by = $document->received_by;
                else:
                    $received_by = $doc->prepared_by;
                endif;

                $section = 'temp;'.$user->section;
                if($document->code === $section)
                {
                    Tracking_Details::where('id',$document->id)
                        ->update([
                            'code' => 'accept;'.$user->section,
                            'date_in' => date('Y-m-d H:i:s'),
                            'received_by' => $id,
                            'status' => 0,
                            'action' => $request->remarks[$i]
                        ]);
                }else{
                    $q = new Tracking_Details();
                    $q->route_no = $route_no;
                    $q->code = 'accept;'.$user->section;
                    $q->date_in = date('Y-m-d H:i:s');
                    $q->received_by = $id;
                    $q->delivered_by = $received_by;
                    $q->action = $request->remarks[$i];
                    $q->save();
                }

                $time = 0;
                $rel = Release::where('route_no', $route_no)->orderBy('id','desc')->first();
                if($rel){
                    $time = Rel::hourDiff($rel->date_reported);
                }
                if($time < 4){
                    $sec = $user->section;
                    Release::where('route_no',$route_no)
                        ->where('section_id',$sec)
                        ->delete();

                    Release::where('route_no',$route_no)->update(['status'=>2]);
                }else{
                    Release::where('route_no',$route_no)->update(['status'=>2]);
                }
                $status['success'][] = 'Route No. "'. $route_no . '" <strong>ACCEPTED!</strong> ';
                //RUSEL
                //RELEASED TO
                $this->releasedStatusChecker($route_no,Session::get('auth')->section);

                $getSO = $this->getSO($route_no);
                if(isset($request->so_no[$i]))
                {
                    $so_no = $request->so_no[$i];
                }else{
                    $so_no = '';
                }
               
                if(Session::get('auth')->section == 36 and $doc->doc_type == 'OFFICE_ORDER' and $getSO and $so_no)
                {
                    $this->updateSO(1,$route_no);
                    $remarks = $request->remarks[$i];
                    foreach($this->calendar($route_no) as $calendar):
                        $dtr_enddate  = date('Y-m-d',(strtotime ($calendar['end'])));
                        $f = new DateTime($calendar['start'].' '. '00:00:00');
                        $t = new DateTime($dtr_enddate.' '. '00:00:00');

                        $interval = $f->diff($t);

                        $datein = '';
                        $f_from = explode('-',$calendar['start']);

                        foreach($this->inclusive_name($route_no) as $inclusive_name):
                            $j = 0;
                            $startday = $f_from[2];
                            $type = null;
                            while($j <= $interval->days) {

                                if($calendar['so_time']){
                                    if($calendar['so_time'] == 'am'){
                                        $time = array('08:00:00','12:00:00');
                                        $type = 'AM';
                                    }
                                    elseif($calendar['so_time'] == 'pm'){
                                        $time = array('13:00:00','17:00:00');
                                        $type = 'PM';
                                    }
                                    else{
                                        $time = array('08:00:00','12:00:00','13:00:00','17:00:00');
                                        $type = 'WH';
                                    }
                                }
                                $event = null;
                                $datein = $f_from[0].'-'.$f_from[1] .'-'. $startday;

                                for($i = 0; $i < count($time); $i++):
                                    if($i % 2 === 0)
                                        $event = 'IN';
                                    else
                                        $event = 'OUT';
                                    $name = $inclusive_name['userid'];
                                    $soLogs = new SoLogs();
                                    $soLogs->userid = $name;
                                    $soLogs->datein = $datein;
                                    $soLogs->time = $time[$i];
                                    $soLogs->event = $event;
                                    $soLogs->remark = $so_no;
                                    $soLogs->edited = 1;
                                    $soLogs->holiday = '003';
                                    $soLogs->time_type = $type;
                                    $soLogs->save();
                                endfor;

                                $startday = $startday + 1;
                                $j++;
                            }
                        endforeach;
                    endforeach;
                }
                //END RUSEL
                System::logDefault('Accepted',$route_no);
            }else{
                $status['errors'][] = 'Route No. "'. $route_no . '" not found in the database. ';
            }
        endfor;
        return redirect('document/accept')->with('status',$status);
    }

    public function updateDocument($id)
    {
        $user = Session::get('auth');
        $section_id = Section::find($user->section)->id;
        $update = array(
            'code' => 'accept;'.$section_id,
            'received_by' => $user->id,
            'date_in' => date('Y-m-d H:i:s')
        );
        Tracking_Details::where('id',$id)->update($update);
        $status = 'documentAccepted';
        return redirect()->back()->with('status',$status);
    }

    public function createDocument(Request $req)
    {
        $data = $_POST;
        $route_no = date('Y-').Session::get('auth')->id.date('mdHis');
        $q = new Tracking();
        $q->route_no = $route_no;
        $q->prepared_date = date('Y-m-d H:i:s');
        $q->prepared_by = Session::get('auth')->id;


        foreach($data as $key => $value)
        {
            if($key=='_token'){
                continue;
            }
            $q->$key = $value;
        }

        if($req->doc_type == 'PO')
        {
            $tmp = $req->description;
            $tmp .= '<br><br>';
            $tmp .= 'PR # : '.$req->pr_no.' dtd '. date('M d, Y',strtotime($req->pr_date));
            $tmp .= '<br><br>';
            $tmp .= 'PO # : '.$req->po_no.' dtd '. date('M d, Y',strtotime($req->po_date));
            $q->description = $tmp;
        }

        $q->save();
        Session::put('added',true);

        $r = new Tracking_Details();
        $r->route_no = $route_no;
        $r->code = 'accept;'.Session::get('auth')->section;
        $r->date_in = $q->prepared_date;
        $r->received_by = Session::get('auth')->id;
        $r->delivered_by = Session::get('auth')->id;
        $r->action = $q->description;
        $r->save();
        System::logDefault('Created',$route_no);
        return redirect()->back();
    }

    public function formDocument($type)
    {
        return view('document.allform',['doc_type'=> $type]);
    }

    public function cancelRequest($route_no){
        $user = Session::get('auth');
        $id = $user->id;

        Tracking_Details::where('route_no',$route_no)
            ->where('received_by',$id)
            ->orderBy('id','desc')
            ->first()
            ->delete();
    }
    public function session(Request $request){
        Session::put('name','Lourence Rex');
        return Session::get('name');
    }

    public static function getDocType($route_no)
    {
        $doc = Tracking::where('route_no',$route_no)->first();
        return self::docTypeName($doc->doc_type);
    }
    public static function docTypeName($type)
    {
        switch($type){
            case "SAL":
                return "Salary, Honoraria, Stipend, Remittances, CHT Mobilization";
            case "TEV":
                return "Travel Expenses Voucher";
            case "BILLS":
                return "Bills, Cash Advance Replenishment, Grants/Fund Transfer";
            case "SUPPLIER":
                return "Supplier (Payment of Transactions with PO)";
            case "INFRA":
                return "Infra - Contractor";
            case "INCOMING":
                return "Incoming Letter";
            case "OUTGOING":
                return "Outgoing Letter";
            case "SERVICE":
                return "Service Record";
            case "SALN":
                return "SALN";
            case "PLANS":
                return "Plans (includes Allocation List)";
            case "ROUTE":
                return "Routing Slip";
            case "MEMO":
                return "Memorandum";
            case "ISO":
                return "ISO Documents";
            case "APPOINTMENT":
                return "Appointment";
            case "RESOLUTION":
                return "Resolutions";
            case "WORKSHEET":
                return "Activity Worksheet";
            case "JUST_LETTER":
                return "Justification";
            case "CERT":
                return "Certifications";
            case "CERT_APPEARANCE":
                return "Certificate of Appearance";
            case "CERT_EMPLOYMENT":
                return "Certificate of Employment";
            case "CERT_CLEARANCE":
                return "Certificate of Clearance";
            case "OFFICE_ORDER":
                return "Office Order";
            case "DTR":
                return "DTR";
            case "APP_LEAVE":
                return "Application for Leave";
            case "OT":
                return "Certificate of Overtime Credit";
            case "TIME_OFF":
                return "Compensatory Time Off";
            case "PO":
                return "Purchase Order";
            case "PRC":
                return "Purchase Request - Cash Advance Purchase";
            case "PRR_S":
                return "Purchase Request - Regular Purchase - Supply";
            case "PRR_M":
                return "Purchase Request - Regular Purchase - Meal";
            case "REPORT":
                return "Reports";
            case "GENERAL" :
                return "General Documents";
            case "TRF" :
                return "Travel Request -TRF";
            case "VEHICLE" :
                return "Vehicle Request";
        case "INSP_RTP" :
            return "Inspection & Acceptance Report";
        case "RFQ" :
             return "Request of Quotation - RFQ/Canvas";
        case "NOA" :
             return "Notice of Award";
         case "DISV" :
            return "Disbursement Voucher";
        case "RPO" :
             return "Regional Personnel Order";
        case "SALN" :
            return "Statement of Assests, Liabilities, and Net Worth - SALN";
         case "TRAVEL_REP" :
            return "Travel Report";
        case "PLANE" :
            return "Request for Plane Ticket";
         case "OBR" :
            return "Obligation Request";
        case "PPMP" :
            return "PPMP";
         case "WFP" :
             return "Work and Financial Plan - WFP";
        case "INV_RECEIPT" :
          return "Invoice Receipt";
        case "APP" :
            return "Annual Procurement Plan";
        case "REQ_CANCEL" :
             return "Request for Cancellation/Postponement/Change of Schedule";
        case "OPT_PERMIT" :
            return "Operational Permit";
        case "PERMIT" :
            return "Permit";
        case "CERT_ENDORSE" :
            return "Certificate of Endorsement";
        case "CLEARANCE" :
            return "Clearance";
        case "COM_LETTER" :
            return "Communication Letter";
        case "PAYMENT" :
            return "Payment";
        case "ACT_TRAIN" :
             return "Activity / Training Design";
        case "REQ_VAC" :
            return "Request for Vacant Position";
        case "AUTH_TRAV" :
            return "Authority to Travel";
         case "RFA" :
             return "Request for Action";
         case "advisory" :
              return "Advisory";
        case "LIQ" :
            return "Liquidation Report";
        case "VAC_POS" :
            return "Vacant Positions";
        case "ACC_RES" :
             return "Acceptance of Resignation";
         case "NOT_OFSAL" :
            return "Notice of Salary Adjustment";
        case "APP_FOR_RSLIB" :
            return "Application for Retirement / Separation/ Life Insurance Benefits";
        case "PR_DRUG" :
             return "Purchase Request - Regular Purchase - Drugs & Meds";
        case "PR_CATERING" :
            return "Purchase Request - Regular Purchase - Catering";
        case "PR_VAN" :
            return "Purchase Request - Regular Purchase - Van Rental";
        case "PR_MEDSUP" :
            return "Purchase Request - Regular Purchase - Medical Supplies";
        case "PR_MEDEQ" :
            return "Purchase Request - Regular Purchase - Medical Equipment";
        case "PR_ITSUP" :
            return "Purchase Request - Regular Purchase - IT Supplies";
        case "PR_OFFSUP" :
            return "Purchase Request - Regular Purchase - Office Supplies";
        case "PR_VEHREQM" :
            return "Purchase Request - Regular Purchase -  Vehicle Repair & Maintenance";
        case "PR_SECURITY" :
            return "Purchase Request - Regular Purchase - Security";
        case "PR_SOFTWARE" :
            return "Purchase Request - Regular Purchase - Software";
        case "PR_COLAT" :
            return "Purchase Request - Regular Purchase - Collateral";
        case "REQ_ISSUE" :
             return "Request and Issue Slip";
        case "sched_inter" :
          return "Schedule of Interview";
        case "IPCR" :
            return "IPCR";
        case "PROP_SLIP" :
            return "Property Return Slip";
        case "MULTI_LOAN" :
            return "Multipurpose Loan";
        case "CONTRACT" :
            return "Contract";
        case "SRF_ICT" :
            return "Service Request Form - ICT";
        case "RO" :
            return "Regional Order";
        case "ACCOM_REP" :
            return "Accomplishment Report";
        case "CONFIRM_FORM" :
            return "Confirmation Form";
        case "PER_EVA_FORM" :
            return "Performance Evaluation Form";
        case "SPA_FS" :
            return "Statement of Premium Account - Formal Sector";
        case "ADMIN_INFO_FORM" :
            return "Administrator Information Form";
        case "SYS_ACCEPT_FORM" :
            return "System Acceptance Form";
        case "BENEFITS_REMIT" :
            return "Health Benefits Remittances";
        case "SLA" :
            return "Service Level Agreement";
        case "HOUSE_MAT" :
            return "Housekeeping Materials";
        case "HARDWARE_SUP" :
            return "Hardware Supplies";
        case "INFRA_PROJ" :
            return "Infrastructure Project";
        case "RATA" :
            return "Representation and Transportation Allowance";
        case "OT" :
            return "Overtime";
        case "REIMBURSE" :
            return "Re-imbursement";
        case "RETAIN_NOT" :
            return "Retainers Notarized";
        case "REF_SLIP_ANN_MED_DEN_EXAM" :
            return "Referral Slip - Annual Medical and Dental Examination";
        case "1ST_INDORSEMENT" :
            return "First Indorsement";
        case "WEB_ACC_REQ_FORM" :
            return "Request Form - Website Access";
         case "ALL" :
            return "All Documents";
         default:
            return "N/A";
        }
    }
    public static function isIncluded($doc_type)
    {
        $filter = array(
            'description',
            'amount',
            'pr_no',
            'po_no',
            'purpose',
            'source_fund',
            'requested_by',
            'route_to',
            'route_from',
            'supplier',
            'event_date',
            'event_location',
            'event_participant',
            'cdo_applicant',
            'cdo_day',
            'event_daterange',
            'payee',
            'item',
            'dv_no',
            'ors_no',
            'fund_source_budget'
        );
        for($i=0;$i<count($filter);$i++){
            if(!Tracking_Filter::where($filter[$i],1)
                ->where('doc_type',$doc_type)
                ->first()){
                $filter[$i] = 'hide';
            }
        }
        return $filter;
    }

    public function show($route_no,$doc_type=null,$prr_type=null){
        $document = Tracking::where('route_no',$route_no)
            ->first();
        Session::put('route_no', $route_no);
        Session::put('doc_type', $doc_type);
        Session::put('prr_type', $prr_type);
        return view('document.info',['document' => $document]);
    }

    public function track($route_no)
    {
            $document = Tracking_Details::where('route_no',$route_no)
            ->orderBy('date_in','asc')
            ->get();

            $prepared_by = Tracking::where('route_no',$route_no)->pluck('prepared_by')->first();

            $doc_type = Tracking::where('route_no',$route_no)
            ->pluck('doc_type')
            ->first();

            $barcode = Tracking::where('route_no',$route_no)
            ->pluck('pr_no')
            ->first();
            
            if($barcode)
            {
                $pr_no = DB::connection('prdb')->table('procure_main')->where('id',$barcode)
                ->whereNotNull('L1-trackno')
                ->pluck('L1-trackno');
            }
            else{
                $pr_no = array();
            }
            $pr_no_count = count($pr_no);
            if($pr_no_count != 0)
            {
                 $po_no = DB::connection('podb')->table('tbl_poentry')->where('PR_NO',$pr_no)
                ->whereNotNull('PO_NO')
                ->pluck('PO_NO');
            }
            else
             {
                $po_no = array();
            }
            
        Session::put('route_no', $route_no);
         return view('document.track',[
                'document' => $document,
                'prepared_by' => $prepared_by,
                'doc_type' => $doc_type,
                'barcode' => $barcode,
                'pr_no' => $pr_no,
                'po_no' => $po_no
                ]);
    }

    public function allPendingDocuments(Request $request)
    {
        $incomingPage = 1;
        $outgoingPage = 1;
        $unconfirmPage = 1;

        // if($request->page){
        //     switch (explode('type=',$request->page)[1]){
        //         case 'incoming':
        //             $incomingPage =  explode('?',$request->page)[0];
        //             break;
        //         case 'outgoing':
        //             $outgoingPage = explode('?',$request->page)[0];
        //             break;
        //         case 'unconfirm':
        //             $unconfirmPage = explode('?',$request->page)[0];
        //             break;
        //     }
        // }

        $user = Session::get('auth');
        $code = 'temp;'.$user->section;
        $code2 = 'accept;'.$user->section;
        $code3 = 'return;'.$user->section;

        $keywordIncoming = $request->incomingInput;
        $keywordOutgoing = $request->outgoingInput;
        $keywordUnconfirmed = $request->unconfirmedInput;

        $data['incoming'] = Tracking_Details::select(
            'date_in',
            'id',
            'route_no',
            'received_by',
            'code',
            'delivered_by',
            'action'
        )
            ->where('code',$code)
            ->where('status',0)
            ->where(function($q) use ($keywordIncoming){
                $q->where('route_no','like',"%$keywordIncoming%");
            })
            ->orderBy('tracking_details.date_in','desc')
            ->paginate(10, ['*'], 'incoming');

        $data['outgoing'] = Tracking_Details::select(
            'date_in',
            'id',
            'route_no',
            'received_by',
            'code',
            'delivered_by',
            'action'
        )
            ->where(function($q) use($code,$code2,$code3) {
                $q->where('code', $code2)
                    ->orwhere('code', $code3);
            })
            ->where(function($q) use ($keywordOutgoing){
                $q->where('route_no','like',"%$keywordOutgoing%");
            })
            ->where('status',0)
            ->orderBy('tracking_details.date_in','desc')
            ->paginate(10, ['*'], 'outgoing');

        $data['unconfirm'] = Tracking_Details::select(
            'tracking_details.date_in',
            'tracking_details.id',
            'tracking_details.route_no',
            'tracking_details.received_by',
            'tracking_details.code',
            'tracking_details.delivered_by',
            'tracking_details.alert'
        )
            ->leftJoin('users','tracking_details.delivered_by','=','users.id')
                ->where('tracking_details.code','like',"%temp%")
                ->where('users.section',$user->section)
                ->where('tracking_details.status',0)
                ->where(function($q) use ($keywordUnconfirmed){
                    $q->where('route_no','like',"%$keywordUnconfirmed%");
            })
            ->orderBy('tracking_details.date_in','desc')
            ->paginate(10, ['*'], 'unconfirm');

        return view('document.pending',[
            'data'=> $data,
            'incomingInput' => $keywordIncoming,
            'outgoingInput' => $keywordOutgoing,
            'unconfirmedInput' => $keywordUnconfirmed
        ]);
    }

    public static function pendingDocuments()
    {
        $user = Session::get('auth');
        $code = 'temp;'.$user->section;
        $code2 = 'accept;'.$user->section;
        $documents = Tracking_Details::select(
            'tracking_details.date_in',
            'tracking_details.id',
            'tracking_details.status',
            'tracking_details.route_no',
            'tracking_master.doc_type',
            'tracking_master.description',
            'tracking_details.received_by',
            'tracking_details.code',
            'tracking_details.delivered_by'

        )
            ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
            ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')

            ->where(function($q) use($code,$code2) {
                $q->where('code',$code2)
                    ->orwhere('code',$code);
            })
            ->where('tracking_details.status',0)
            ->orderBy('tracking_details.id','desc')
            ->limit(3)
            ->get();
        return $documents;
    }

    public function returnDocument(Request $req)
    {
        $release_to_datein = date('Y-m-d H:i:s');
        $id = $req->id;
        $remarks = $req->remarks;
        $info = Tracking_Details::where('id','=',$id)->orderBy('id','desc');

        $from = Section::find(Session::get('auth')->section)->description;
        $remarks = 'From: '.$from. '<br><br>Message: <strong style="display: inline-block;color: #a6201d">' .$remarks.'</strong>';

        //checker
        // $this->releasedStatusChecker($info->first()->route_no,Session::get('auth')->section);
        $released_section_to = Users::select('users.section')->leftJoin('section','section.id','=','users.section')->where('users.id','=',$info->first()->delivered_by)->first()->section;

        //accepted document
        $info->update(array(
            'code' => '',
            'date_in' => $release_to_datein,
            'received_by' => Session::get('auth')->id,
            'delivered_by' => $released_section_to,
            'action' => $req->remarks,
            'alert' => 0
        ));


        //RELEASED/RETURNED TO
        $q = new Tracking_Details();
        $q->route_no = $info->first()->route_no;
        $q->date_in = $release_to_datein;
        $q->action = 'Returned';
        $q->delivered_by = Session::get('auth')->id;
        $q->code= 'temp;' . $released_section_to;
        $q->save();


        $tracking_release = new Tracking_Releasev2();
        $tracking_release->released_by = Session::get('auth')->id;
        $tracking_release->released_section_to = $released_section_to;
        $tracking_release->released_date = $release_to_datein;
        $tracking_release->remarks = $remarks;
        $tracking_release->document_id = $info->first()->id;
        $tracking_release->route_no = $info->first()->route_no;
        $tracking_release->status = "return";
        $tracking_release->save();

    }

    static function checkMinutes($start_date)
    {
       /* $start_date = "2018-11-16 11:24:33";
        $end_date = "2018-11-16 14:43:00";*/
       $global_end_date = date("Y-m-d H:i:s");
        $end_date = $global_end_date;

        $start_checker = date("Y-m-d",strtotime($start_date));
        $end_checker = date("Y-m-d",strtotime($end_date));
        $fhour_checker = date("H",strtotime($start_date));
        $lhour_checker = date("H",strtotime($end_date));
        $minutesTemp = 0;


        if($start_checker != $end_checker) return 1440;

        if($fhour_checker <= 7 && $lhour_checker >= 8){
            $fhour_checker = 8;
            $start_date = $start_checker.' '.'08:00:00';
        }
        elseif($fhour_checker == 11 && $lhour_checker >= 12){
            $start_date = new DateTime($start_date);
            $end_date = $start_date->diff(new DateTime($start_checker." 12:00:00"));

            $minutes = $end_date->days * 24 * 60;
            $minutes += $end_date->h * 60;
            $minutes += $end_date->i;

            $start_date = $start_checker.' '.'13:00:00';
            $minutesTemp = $minutes;
            $end_date = $global_end_date;
        }
        elseif($fhour_checker == 12 && $lhour_checker >= 13){
            $fhour_checker = 13;
            $start_date = $start_checker.' '.'13:00:00';
        }
        elseif($fhour_checker >= 17 && $lhour_checker >= 17){
            $start_date = $start_checker.' '.'17:00:00';
            $end_date = $end_checker.' '.'17:00:00';
        }
        elseif($lhour_checker >= 17){
            $end_date = $end_checker.' '.'17:00:00';
        }

        if(
            ($fhour_checker >= 8 && $fhour_checker < 12)
            || ($fhour_checker >= 13)
 
            && ($lhour_checker >= 8 && $lhour_checker < 12)
            || ($lhour_checker >= 13)
        )
        {
            $start_date = new DateTime($start_date);
            $end_date = $start_date->diff(new DateTime($end_date));

            $minutes = $end_date->days * 24 * 60;
            $minutes += $end_date->h * 60;
            $minutes += $end_date->i;

            if($minutesTemp){
                $minutes += $minutesTemp;
            }
            return $minutes;
        }
        return 1440;

    }

    public function releasedStatusChecker($route_no,$section){

        $rled = "9";
        $pdoho = "10";
        $datrc = "12";

        // $data1 = DB::table('tracking_releasev2')
        // ->select('tracking_releasev2.*','section.id')
        // ->leftJoin('section', 'section.id', '=', 'tracking_releasev2.released_section_to')
        //      ->where ("route_no","=",$route_no)
        //     ->where("released_section_to","=",$section)

        $validation = DB::table('tracking_releasev2')
        ->select('tracking_releasev2.*','section.division as sec_div')
        ->leftJoin('section', 'section.id', '=', 'tracking_releasev2.released_section_to')
        ->where("tracking_releasev2.route_no","=",$route_no)
        ->where("tracking_releasev2.released_section_to","=",$section)
        ->where(function ($query) {
                $query->where('status','=','waiting')
                    ->orWhere('status','=','return');
            })
            ->orderBy('id', 'DESC')
            ->first();

        $release = Tracking_Releasev2::where("route_no","=",$route_no)
            ->where("released_section_to","=",$section)
            ->where(function ($query) {
                $query->where('status','=','waiting')
                    ->orWhere('status','=','return');
            })
            ->orderBy('id', 'DESC');

            if($release->first()){
                $user = User::find($release->first()->released_by);
               $released_by = (Section::find($user->section)) ? Section::find($user->section)->division:'';
               $minute = DocumentController::checkMinutes($validation->released_date); 
  
                if($minute <= 45 && ($validation->status == "waiting" || $validation->status == "return" )
                && $validation->sec_div != $pdoho && $validation->sec_div != $rled && $validation->sec_div != $datrc
                && $released_by != $pdoho && $released_by != $rled && $released_by != $datrc){
                    $release->update([
                        "status" => "accept"
                    ]);
                }
                elseif($minute > 45 && ($validation->status == "waiting"  || $validation->status == "return") 
                && $validation->sec_div != $pdoho && $validation->sec_div != $rled && $validation->sec_div != $datrc
                && $released_by != $pdoho && $released_by != $rled && $released_by != $datrc){
                    $release->update([
                        "status" => "report"
                    ]);
                } elseif($minute <= 960 && ($validation->status == "waiting"  || $validation->status == "return") 
                && ($validation->sec_div == $pdoho || $validation->sec_div == $rled || $validation->sec_div == $datrc)) {
                    $release->update([
                        "status" => "accept"
                    ]);
                } elseif($minute > 960 && ($validation->status == "waiting"  || $validation->status == "return") 
                && ($validation->sec_div == $pdoho || $validation->sec_div == $rled || $validation->sec_div == $datrc)) {
                    $release->update([
                        "status" => "report"
                    ]);
                }
                elseif($minute <= 960 && ($validation->status == "waiting"  || $validation->status == "return") 
                && ($released_by == $pdoho || $released_by == $rled || $released_by == $datrc) ) {
                    $release->update([
                        "status" => "accept"
                    ]);
            
                } 
                elseif($minute > 960 && ($validation->status == "waiting"  || $validation->status == "return") 
                && ($released_by == $pdoho || $released_by == $rled || $released_by == $datrc) ) {
                    $release->update([
                        "status" => "report"
                    ]);
                }
        }
    }

    public function acceptDocument(Request $req)
    {
        $id = $req->id;
        $remarks = $req->remarks;
        $date_in = date('Y-m-d H:i:s');

        $tracking_details = Tracking_Details::where('id',$id)->orderBy('id', 'DESC');

        //RELEASED TO
        $this->releasedStatusChecker($tracking_details->first()->route_no,Session::get('auth')->section);

        $tracking_details->update(array(
                'code' => 'accept;' . Session::get('auth')->section,
                'date_in' => $date_in,
                'action' => $remarks,
                'received_by' => Session::get('auth')->id,
                'alert' => 0
            ));
        $data = array(
            'code' => 'accept;' . Session::get('auth')->section,
            'date_in' => $date_in,
            'action' => $remarks,
            'received_by' => Session::get('auth')->id
        );
        echo json_encode($data);
    }

    public static function countPendingDocuments()
    {
        $user = Session::get('auth');
        $id = $user->id;
        $code = 'temp;'.$user->section;
        $code2 = 'accept;'.$user->section;
        $documents = Tracking_Details::select('tracking_details.date_in', 'tracking_details.id','tracking_details.status','tracking_details.route_no','tracking_master.doc_type','tracking_details.received_by')
            ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
            ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
            ->where(function($q) use($code,$code2) {
                $q->where('code',$code2)
                    ->orwhere('code',$code);
            })
            ->where('tracking_details.status',0)
            ->orderBy('tracking_details.id','desc')
            ->get();

        $data = array();
        foreach($documents as $doc){
            $user = Session::get('auth');
            $data[] = array(
                'id' => $doc->id,
                'status' => $doc->status,
                'route_no' => $doc->route_no,
                'doc_type' => self::docTypeName($doc->doc_type),
                'from' => $user->fname.' '.$user->lname,
                'duration' => self::timeDiff($doc->date_in)
            );
        }
        return $data;
    }

    public function get_date_in($count){
        return $this->timeDiff($_SESSION['count'][$count]);
    }

    public static function timeDiff($date_in,$date_out=null)
    {
        $date_now = isset($date_out) ? $date_out : date('Y-m-d H:i:s');

        $start_time = strtotime($date_in);
        $end_time = strtotime($date_now);
        $difference = $end_time - $start_time;

        $seconds = $difference % 60;            //seconds
        $difference = floor($difference / 60);

        $min = $difference % 60;              // min
        $difference = floor($difference / 60);

        $hours = $difference % 24;  //hours
        $difference = floor($difference / 24);

        $days = $difference % 30;  //days
        $difference = floor($difference / 30);

        $month = $difference % 12;  //month
        $difference = floor($difference / 12);

        $year = $difference % 1;  //month
        $difference = floor($difference / 1);

        $result = null;
        if($year!=0) {
            if($year == 1){
                $result.=$year.' Year ';
            }else{
                $result.=$year.' Years ';
            }
        }
        if($month!=0) {
            if($month == 1){
                $result.=$month.' Month ';
            }else{
                $result.=$month.' Months ';
            }
        }
        if($days!=0) {
            if($days == 1){
                $result.=$days.' Day ';
            }else{
                $result.=$days.' Days ';
            }
        }
        if($hours!=0) {
            if($hours == 1){
                $result.=$hours.' Hour ';
            }else{
                $result.=$hours.' Hours ';
            }
        }
        if($min!=0) {
            if($min == 1){
                $result.=$min.' Minute ';
            }else{
                $result.=$min.' Minutes ';
            }
        }
        if($seconds!=0) {
            if($seconds == 1){
                $result.=$seconds.' Second ';
            }else{
                $result.=$seconds.' Seconds ';
            }
        }

        return $result;

    }
    public static function duration($start_date)
    {
        $end_date=date('Y-m-d H:i:s');

        $start_time = strtotime($start_date);
        $end_time = strtotime($end_date);
        $difference = $end_time - $start_time;

        $seconds = $difference % 60;            //seconds
        $difference = floor($difference / 60);

        $min = $difference % 60;              // min
        $difference = floor($difference / 60);

        $hours = $difference % 24;  //hours
        $difference = floor($difference / 24);

        $days = $difference % 30;  //days
        $difference = floor($difference / 30);

        $month = $difference % 12;  //month
        $difference = floor($difference / 12);

        $tmp = ($days * 24) + ($month * 24 * 30);
        $hours+=$tmp;
        return $hours;
    }

    public function removePending($id)
    {
        Tracking_Details::where('id',$id)
            ->update(['status'=> 1]);
    }

    public function removeOutgoing($id)
    {
        $details = Tracking_Details::where('id',$id);
        System::logDefault('Remove Outgoing',$details->first()->route_no);

        $details->update(['code'=> '']);
    }

    public function removeIncoming($id)
    {
        $details = Tracking_Details::where('id',$id);
        System::logDefault('Remove Incoming',$details->first()->route_no);

        $details->update(['code'=> '']);
    }

    public static function checkLastRecord($route_no)
    {
        $document = Tracking_Details::where('route_no',$route_no)
            ->orderBy('id','desc')
            ->first();
        return $document->id;
    }

    public static function getNextRecord($route_no,$id)
    {
        $document = DB::table('tracking_details')
            ->where('id', ( DB::raw("(SELECT min(id) FROM tracking_details WHERE id > $id)")) )
            ->get();
        $new_array[] = json_decode(json_encode($document), true);
        return $new_array[0];
    }

    static function deliveredDocument($route_no,$id,$doc_type='ALL'){
        $last = Tracking_Details::where('route_no',$route_no)->orderBy('id','desc')->first();

        if($last->received_by == $id){
            return false;
        }
        $documents = Tracking_Details::select(
            'tracking_details.id',
            'tracking_details.received_by',
            'tracking_details.date_in',
            'tracking_details.code',
            'tracking_details.route_no'
        )
            ->where('delivered_by',$id)
            ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
            ->where('tracking_details.route_no',$route_no)
            ->orderBy('tracking_details.id','desc')
            ->first();
//        $documents = DB::table('tracking_details')
//            ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
//            ->where('tracking_details.route_no',$route_no)
//            ->where('doc_type',$doc_type)
//            ->where('delivered_by',$id)
//            ->where('received_by','!=',$id)
//            ->first();
        Session::put('deliveredDocuments',$documents);
        return $documents;
    }
    static function secDocument($route_no,$id,$doc_type='ALL'){
        $last = Tracking_Details::where('route_no',$route_no)->orderBy('id','desc')->first();

        if($last->received_by == $id){
            return false;
        }
        $documents = Tracking_Details::select(
            'tracking_details.id',
            'tracking_details.received_by',
            'tracking_details.date_in',
            'tracking_details.code',
            'tracking_details.route_no'
        )
        
            ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
            ->where('tracking_details.route_no',$route_no)
            ->orderBy('tracking_details.id','desc')
            ->first();
//        $documents = DB::table('tracking_details')
//            ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
//            ->where('tracking_details.route_no',$route_no)
//            ->where('doc_type',$doc_type)
//            ->where('delivered_by',$id)
//            ->where('received_by','!=',$id)
//            ->first();
        Session::put('deliveredDocuments',$documents);
        return $documents;
    }

    static function printLogsDocument()
    {
        $keyword = Session::get('searchLogs');
        $doc_type = $keyword['doc_type'];
        $keywordLogs = $keyword['keywordLogs'];
        $id = Session::get('auth')->id;

        $str = $keyword['str'];
        $temp1 = explode('-',$str);
        $temp2 = array_slice($temp1, 0, 1);
        $tmp = implode(',', $temp2);
        $startdate = date('Y-m-d H:i:s',strtotime($tmp));

        $temp3 = array_slice($temp1, 1, 1);
        $tmp = implode(',', $temp3);
        $enddate = date('Y-m-d H:i:s',strtotime($tmp));

        Session::put('startdate',$startdate);
        Session::put('enddate',$enddate);
        Session::put('doc_type',self::docTypeName($doc_type));
        Session::put('doc_type_code',$doc_type);
        Session::put('keywordLogs',$keywordLogs);
        if($doc_type!='ALL'){
            $data = DB::table('tracking_details')
                ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                ->where(function($q) use ($keywordLogs){
                    $q->where('tracking_details.route_no','like',"%$keywordLogs%")
                        ->orwhere('description','like',"%$keywordLogs%")
                        ->orWhere('purpose','like',"%$keywordLogs%");
                })
                ->where('doc_type',$doc_type)
                ->where('received_by',$id)
                ->where('date_in','>=',$startdate)
                ->where('date_in','<=',$enddate)
                ->orderBy('date_in','desc');
            $documents = $data->paginate(15);
            $logs = $data->get();
        }else{
            $data = DB::table('tracking_details')
                ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                ->where(function($q) use ($keywordLogs){
                    $q->where('tracking_details.route_no','like',"%$keywordLogs%")
                        ->orwhere('description','like',"%$keywordLogs%")
                        ->orWhere('purpose','like',"%$keywordLogs%");
                })
                ->where('received_by',$id)
                ->where('date_in','>=',$startdate)
                ->where('date_in','<=',$enddate)
                ->orderBy('date_in','desc');
            $documents = $data->paginate(15);
            $logs = $data->get();
        }
        return $logs;
    }
    function logsDocument(){
        $keyword = Session::get('searchLogs');

        if(isset($keyword))
        {
            $doc_type = $keyword['doc_type'];
            $keywordLogs = $keyword['keywordLogs'];
        }
        else{
            $doc_type = '';
            $keywordLogs = '';
        }
        
        $user = Session::get('auth');
        $id = Session::get('auth')->id;
        $str = Session::get('range_logsdoc');
        $temp1 = explode('-',$str);
        $temp2 = array_slice($temp1, 0, 1);
        $tmp = implode(',', $temp2);
        $startdate = date('Y-m-d 00:00:00',strtotime($tmp));

        $temp3 = array_slice($temp1, 1, 1);
        $tmp = implode(',', $temp3);
        $enddate = date('Y-m-d 23:59:59',strtotime($tmp));

        Session::put('startdate',$startdate);
        Session::put('enddate',$enddate);
        Session::put('doc_type',self::docTypeName($doc_type));
        Session::put('doc_type_code',$doc_type);
        Session::put('keywordLogs',$keywordLogs);
        if($user->user_priv == 1)
        {
            if($doc_type!='ALL'){
                $data = DB::table('tracking_details')
                    ->select('tracking_master.id as master_id',
                    'tracking_details.id as tracking_id',
                    'tracking_master.route_no',
                    'tracking_details.status',
                    'tracking_master.description',
                    'tracking_details.date_in',
                    'tracking_details.received_by',
                    'tracking_master.doc_type',
                    'tracking_details.delivered_by')
                    ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                    ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
                    ->leftJoin('section', 'users.section', '=', 'section.id')
                    ->where(function($q) use ($keywordLogs){
                        $q->where('tracking_details.route_no','like',"%$keywordLogs%")
                            ->orwhere('tracking_master.description','like',"%$keywordLogs%");
                    })
                    ->where('doc_type',$doc_type)
                    ->where('date_in','>=',$startdate)
                    ->where('date_in','<=',$enddate)
                    ->orderBy('date_in','desc');
                $documents = $data->paginate(15);
                $logs = $data->get();
                
            }else{
                $data = DB::table('tracking_details')
                    ->select(
                        'tracking_master.id as master_id',
                        'tracking_master.description',
                        'tracking_master.doc_type',
                        'tracking_details.id as tracking_id',
                        'tracking_details.route_no',
                        'tracking_details.date_in',
                        'tracking_details.received_by',
                        'tracking_details.delivered_by',
                        'tracking_details.status',
                        'tracking_details.code'
                    )
                    ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                    ->where(function($q) use ($keywordLogs){
                        $q->where('tracking_details.route_no','like',"%$keywordLogs%")
                            ->orwhere('description','like',"%$keywordLogs%")
                            ->orWhere('purpose','like',"%$keywordLogs%");
                    })      
                    ->where('date_in','>=',$startdate)
                    ->where('date_in','<=',$enddate)
                    ->orderBy('date_in','desc');

                $documents = $data->paginate(15);
                $logs['data'][] = $data->get(); 
                 }
        }
        else
        {
            if($doc_type!='ALL'){
                $data = DB::table('tracking_details')
                    ->select('tracking_master.id as master_id',
                    'tracking_details.id as tracking_id',
                    'tracking_master.route_no',
                    'tracking_details.status',
                    'tracking_master.description',
                    'tracking_details.date_in',
                    'tracking_details.received_by',
                    'tracking_master.doc_type',
                    'tracking_details.delivered_by')
                    ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                    ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
                    ->leftJoin('section', 'users.section', '=', 'section.id')
                    ->where(function($q) use ($keywordLogs){
                        $q->where('tracking_details.route_no','like',"%$keywordLogs%")
                            ->orwhere('tracking_master.description','like',"%$keywordLogs%");
                    })
                    ->where('doc_type',$doc_type)
                    ->where('received_by',$id)
                    ->where('date_in','>=',$startdate)
                    ->where('date_in','<=',$enddate)
                    ->orderBy('date_in','desc');
                $documents = $data->paginate(15);
                $logs = $data->get();
                
            }else{
                $data = DB::table('tracking_details')
                    ->select(
                        'tracking_master.id as master_id',
                        'tracking_master.description',
                        'tracking_master.doc_type',
                        'tracking_details.id as tracking_id',
                        'tracking_details.route_no',
                        'tracking_details.date_in',
                        'tracking_details.received_by',
                        'tracking_details.delivered_by',
                        'tracking_details.status',
                        'tracking_details.code'
                    )
                    ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                    ->where(function($q) use ($keywordLogs){
                        $q->where('tracking_details.route_no','like',"%$keywordLogs%")
                            ->orwhere('description','like',"%$keywordLogs%")
                            ->orWhere('purpose','like',"%$keywordLogs%");
                    })      
                    ->where('received_by',$id)
                    ->where('date_in','>=',$startdate)
                    ->where('date_in','<=',$enddate)
                    ->orderBy('date_in','desc');

                $documents = $data->paginate(15);
                $logs['data'][] = $data->get(); 
                 }
               
                }
        return view('document.logs',['documents' => $documents, 'doc_type' => $doc_type, 'daterange' => $str,'keywordLogs' => $keywordLogs]);
    }

    function searchLogs(Request $req)
    {
        $keyword = array(
            'doc_type' => $req->doc_type,
            'str' => $req->daterange,
            'keywordLogs' => $req->keywordLogs
        );
        Session::put('range_logsdoc',$req->daterange);
        Session::put('searchLogs',$keyword);
        return self::logsDocument();
    }

    function sectionLogs(){
        $user = Session::get('auth');
        $keyword = Session::get('sectionLogs');
        if(isset($keyword))
        {
            $doc_type = $keyword['doc_type'];
            $keywordSectionLogs = $keyword['keywordSectionLogs'];
        }
        else{
            $doc_type = '';
            $keywordSectionLogs = '';
        }
        
        $section = Session::get('auth')->section;
        $str = Session::get('range_seclogs');
        $temp1 = explode('-',$str);
        $temp2 = array_slice($temp1, 0, 1);
        $tmp = implode(',', $temp2);
        $startdate = date('Y-m-d H:i:s',strtotime($tmp));

        $temp3 = array_slice($temp1, 1, 1);
        $tmp = implode(',', $temp3);
        $enddate = date('Y-m-d H:i:s',strtotime($tmp));

        Session::put('startdate',$startdate);
        Session::put('enddate',$enddate);
        Session::put('doc_type',self::docTypeName($doc_type));
        Session::put('doc_type_code',$doc_type);
        Session::put('keywordSectionLogs',$keywordSectionLogs);
        if($user->user_priv == 1)
        {
            if($doc_type!='ALL'){
                $data = DB::table('tracking_details')
                    ->select('tracking_details.id as tracking_id','tracking_master.route_no','tracking_master.description','tracking_details.date_in','tracking_details.received_by','tracking_master.doc_type','tracking_details.delivered_by')
                    ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                    ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
                    ->leftJoin('section', 'users.section', '=', 'section.id')
                    ->where(function($q) use ($keywordSectionLogs){
                        $q->where('tracking_details.route_no','like',"%$keywordSectionLogs%")
                            ->orwhere('tracking_master.description','like',"%$keywordSectionLogs%");
                    })
                    ->where('doc_type',$doc_type)
                    ->where('date_in','>=',$startdate)
                    ->where('date_in','<=',$enddate)
                    ->orderBy('date_in','desc');
                $documents = $data->paginate(15);
                $logs = $data->get();

            }else{
                $data = DB::table('tracking_details')
                    ->select('tracking_details.id as tracking_id','tracking_master.route_no','tracking_master.description','tracking_details.date_in','tracking_details.received_by','tracking_master.doc_type','tracking_details.delivered_by')
                    ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                    ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
                    ->leftJoin('section', 'users.section', '=', 'section.id')
                    ->where(function($q) use ($keywordSectionLogs){
                        $q->where('tracking_details.route_no','like',"%$keywordSectionLogs%")
                            ->orwhere('tracking_master.description','like',"%$keywordSectionLogs%");
                    })
                    ->where('date_in','>=',$startdate)
                    ->where('date_in','<=',$enddate)
                    ->orderBy('date_in','desc');
                $documents = $data->paginate(15);
                $logs = $data->get();
            }
        }
        else{
            if($doc_type!='ALL'){
                $data = DB::table('tracking_details')
                    ->select('tracking_details.id as tracking_id','tracking_master.route_no','tracking_master.description','tracking_details.date_in','tracking_details.received_by','tracking_master.doc_type','tracking_details.delivered_by')
                    ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                    ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
                    ->leftJoin('section', 'users.section', '=', 'section.id')
                    ->where(function($q) use ($keywordSectionLogs){
                        $q->where('tracking_details.route_no','like',"%$keywordSectionLogs%")
                            ->orwhere('tracking_master.description','like',"%$keywordSectionLogs%");
                    })
                    ->where('doc_type',$doc_type)
                    ->where('section.id',$section)
                    ->where('date_in','>=',$startdate)
                    ->where('date_in','<=',$enddate)
                    ->orderBy('date_in','desc');
                $documents = $data->paginate(15);
                $logs = $data->get();

            }else{
                $data = DB::table('tracking_details')
                    ->select('tracking_details.id as tracking_id','tracking_master.route_no','tracking_master.description','tracking_details.date_in','tracking_details.received_by','tracking_master.doc_type','tracking_details.delivered_by')
                    ->leftJoin('tracking_master', 'tracking_details.route_no', '=', 'tracking_master.route_no')
                    ->leftJoin('users', 'tracking_details.received_by', '=', 'users.id')
                    ->leftJoin('section', 'users.section', '=', 'section.id')
                    ->where(function($q) use ($keywordSectionLogs){
                        $q->where('tracking_details.route_no','like',"%$keywordSectionLogs%")
                            ->orwhere('tracking_master.description','like',"%$keywordSectionLogs%");
                    })
                    ->where('section.id',$section)
                    ->where('date_in','>=',$startdate)
                    ->where('date_in','<=',$enddate)
                    ->orderBy('date_in','desc');
                $documents = $data->paginate(15);
                $logs = $data->get();
            }
        }
           
        Session::put('logsDocument',$logs);

        return view('document.sectionLogs',['documents' => $documents, 'doc_type' => $doc_type, 'daterange' => $str,'keywordSectionLogs' => $keywordSectionLogs]);
    }

    function searchSectionLogs(Request $req)
    {
        $keyword = array(
            'doc_type' => $req->doc_type,
           
            'keywordSectionLogs' => $req->keywordSectionLogs
        );
        Session::put('range_seclogs',$req->daterange);
        Session::put('sectionLogs',$keyword);
        return self::sectionLogs();
    }

    static function countOnlineUsers()
    {
        $startTime = date('Y-m-d ').'00:00:00';
        $endTime = date('Y-m-d ').'23:59:59';
        $count = User::where('updated_at','>=',$startTime)
            ->where('updated_at','<=',$endTime)
            ->where('status',1)
            ->count();

        return $count;
    }

    public function autocomplete(Request $request)
    {   
        $user = Session::get('auth')->id;

        if($request->get('query'))
            {
            $query = $request->get('query');
            $data =  \App\Tracking::where('prepared_by', $user)  
                                ->where("desription","LIKE","%$query%")
                                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($data as $row)
            {
            $output .= '
            <li><a href="#">'.$row->desription.'</a></li>
            ';
            }
            $output .= '</ul>';
            echo $output;
            }
    }

    public function ongoingHome($id)
    {
        $report_year = Session::get('report_year');
        $report_month = Session::get('report_month');

        if($report_year != 0 && $report_month != 0)
        {
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $ongoing = DB::connection('mysql')->select("CALL on_going_report('$start', '$end','$id')")
           
            ->get();
            
        // $ongoing = DB::table('tracking_master')
        // ->leftJoin('users', 'tracking_master.prepared_by', '=', 'users.id')
        // ->leftJoin('section', 'users.section', '=', 'section.id')
        // ->leftJoin('tracking_details', 'tracking_master.route_no', '=', 'tracking_details.route_no')
        // ->where('tracking_details.status',0)
        // ->where('tracking_master.prepared_date','>=',$start)
        // ->where('tracking_master.prepared_date','<=',$end)
        // ->where('section.id',$section)
        // ->orderby('tracking_details.id','desc')
        // ->count();
        }
        elseif($report_month != 0 )
        {
            $report_year = date("Y"); 
            $start = $report_year.'-'.$report_month.'-01 00:00:00';
            $end = $report_year.'-'.$report_month.'-31 23:59:59';

            $ongoing = DB::connection('mysql')->select("CALL on_going_report('$start', '$end','$id')")  
            ->get();
            
        }
        else{
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
           
                        
            $ongoing = DB::connection('mysql')->select("CALL on_going_report('$start', '$end','$id')");

        }

        return view('report.ongoinghome',[
            'ongoing' => $ongoing,
            'id' => $id
        ]);
    }

    public function allongoingHome($quarter)
    {
 
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
                        
            $ongoing = DB::connection('mysql')->select("CALL all_ongoing('$start', '$end','0','$year','$quarter')");

        return view('report.allongoing',[
            'ongoing' => $ongoing
        ]);
    }

    
    public function allongoingSearch(Request $req)
    {
        $keyword = $req->keyword;
        Session::put('on_keyword',$keyword);
      
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
            $ongoing = DB::connection('mysql')->select("CALL all_going_search('$start','$end','0','$keyword')");
        

        return view('report.allongoing',[
            'ongoing' => $ongoing
        ]);

    }

    public function ongoingSearch(Request $req)
    {
        $keyword = $req->keyword;
        Session::put('on_keyword',$keyword);
        $id = Session::get('auth')->section;
      
            $year = date("Y");
            $start = $year.'-01-01 00:00:00';
            $end = $year.'-12-31 23:59:59';
            $ongoing = DB::connection('mysql')->select("CALL chd12_search('$start', '$end','$id','0','$keyword')");
        

        return view('report.ongoinghome',[
            'ongoing' => $ongoing,
            'id' => $id
        ]);

    }


}
