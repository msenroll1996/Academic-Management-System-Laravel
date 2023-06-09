<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\EnrollmentRequest;
use App\Models\Admission;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\BatchTransfer;
use App\Models\Country;
use App\Models\Course;
use App\Models\Student;
use App\Models\Student as Model;
use App\Services\AttendanceService;
use App\Services\Student\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AttendanceController extends Controller
{

    protected $view = 'admin.attendance.';

    private $attendanceService;

    public function __construct(AttendanceService $service){
        $this->attendanceService = $service;
    }

    public function index()
    {
        $batch = $this->attendanceService->getBatch();
        $courses = Course::all();
        if (Auth::user()->user_type == 4 && Auth::user()->userInfo->tutor_status == 1) {
            //for tutor
            $batches = Batch::whereHas('activeUserTeachersBatch', function ($q) {
                            $q->where('user_id', Auth::user()->id);
                         })->get();
            if(\request('batch_id')) {
                $my_batches = Batch::whereHas('activeUserTeachersBatch', function ($q) {
                                    $q->where('user_id', Auth::user()->id);
                                })->where('status', '1')->get();
                $batch = $my_batches->where('id', \request('batch_id'))->first();
            } else {
                $batch = $batches->first();
            }

        } else {
            $batches = Batch::all();
        }

        //start if for batch
        if($batch) {
            $attendance_date = date('Y-m-d');
//            dd($batch->end_date,$attendance_date);
            if($attendance_date >= $batch->end_date){
                //batch is completed
                $attendance_date = $batch->end_date;
                $minDate = $batch->start_date;
                $maxDate = $batch->end_date;
                $default_date = $batch->end_date;
            }else{
                $minDate = $batch->start_date;
                $maxDate = $attendance_date;
                $default_date = $attendance_date;
            }

            if(\request('batch_id') && \request('search_date')){
                $attendance_date = \request('search_date');
                $settings = $this->attendanceService->getBatchAttendances($batch->id, $attendance_date);
                $default_date = $attendance_date;
            }else{
                $settings = $this->attendanceService->getBatchAttendances($batch->id, $attendance_date);
            }

            //start if
            if ($settings->count() > 0) {
                $att = 1; //attendance data is available for given date
                return view($this->view.'index',compact('settings','batch','att','attendance_date','minDate','maxDate','default_date','courses','batches'));
            } else {
                //attendance data is not available for given date
                $settings = Student::whereHas('admission', function ($a) use ($batch) {
                                    //for admission which has not batch transfer
                                    $a->doesntHave('activeBatchTransfer')->where('batch_id', $batch->id)
                                        //for admission which has  batch transfer but less than batch transfer date
                                        ->orWhereHas('activeBatchTransfer', function ($pt) use ($batch) {
                                            $pt->where('date', '>', date('Y-m-d'))
                                                ->where('previous_batch_id', $batch->id);
                                        })
                                        //for admission which has  batch transfer from another branch and greater than equal to transfer date
                                        ->orWhereHas('activeBatchTransfer', function ($bt) use ($batch) {
                                            $bt->where('date', '<=', date('Y-m-d'))
                                                ->where('batch_id', $batch->id);
                                        });

                            })->orderBy('id', 'ASC')->get();
                return view($this->view.'index',compact('settings','batch','minDate','maxDate','default_date','courses','batches'));
            }
            //endif
        }else{
            return view($this->view.'index', compact('courses','batches'));
        }
        //end if for batch
    }

    public function indexOld()
    {
        $batch = $this->attendanceService->getBatch();
        $courses = Course::all();
        if (Auth::user()->user_type == 4 && Auth::user()->userInfo->tutor_status == 1) {
            //for tutor
            $batches = Batch::whereHas('activeUserTeachersBatch', function ($q) {
                            $q->where('user_id', Auth::user()->id);
                         })->get();
            if(\request('batch_id')) {
                $my_batches = Batch::whereHas('activeUserTeachersBatch', function ($q) {
                                    $q->where('user_id', Auth::user()->id);
                                })->where('status', '1')->get();
                $batch = $my_batches->where('id', \request('batch_id'))->first();
            } else {
                $batch = $batches->first();
            }

        } else {
            $batches = Batch::all();
        }

        //start if for batch
        if($batch) {
            $attendance_date = date('Y-m-d');
//            dd($batch->end_date,$attendance_date);
            if($attendance_date >= $batch->end_date){
                //batch is completed
                $attendance_date = $batch->end_date;
                $minDate = $batch->start_date;
                $maxDate = $batch->end_date;
                $default_date = $batch->end_date;
            }else{
                $minDate = $batch->start_date;
                $maxDate = $attendance_date;
                $default_date = $attendance_date;
            }

            if(\request('batch_id') && \request('search_date')){
                $attendance_date = \request('search_date');
                $settings = $this->attendanceService->getBatchAttendances($batch->id, $attendance_date);
                $default_date = $attendance_date;
            }else{
                $settings = $this->attendanceService->getBatchAttendances($batch->id, $attendance_date);
            }

            //start if
            if ($settings->count() > 0) {
                $att = 1; //attendance data is available for given date
                $batchTransfers = Student::whereHas('admission.activeBatchTransfer', function ($ab) use ($batch) {
                    $ab->where('date', '<=', date('Y-m-d'))->where('batch_id', $batch->id);
                })->get();
                return view($this->view.'index',compact('settings','batch','att','attendance_date','minDate','maxDate','default_date','courses','batches', 'batchTransfers'));
            } else {
                //attendance data is not available for given date
                $settings1 = Student::whereHas('admission', function ($q) use ($batch) {
                    $q->where('batch_id', $batch->id);
                })->get();

                $settings = $settings1->map(function ($item) use ($batch, $settings1) {
                    if ($item->admission->activeBatchTransferWithPreviousBatch($batch)->count() > 0) {
                        if (date('Y-m-d') < $item->admission->activeBatchTransferWithPreviousBatch($batch)->first()->date) {
                            return $item;
                        }
                    } else {
                        return $item;
                    }
                });
                $settings = $settings->filter();
                $batchTransfers = Student::whereHas('admission.activeBatchTransfer', function ($ab) use ($batch) {
                                            $ab->where('date', '<=', date('Y-m-d'))->where('batch_id', $batch->id);
                                        })->get();
                return view($this->view.'index',compact('settings','batch','minDate','maxDate','default_date','courses','batches', 'batchTransfers'));
            }
            //endif
        }else{
            return view($this->view.'index', compact('courses','batches'));
        }
        //end if for batch
    }

    public function store(Request $request)
    {
        $this->validate(\request(),[
            'attendance_date' => 'required|date',
            'real_batch_id' => 'required|numeric'
        ]);
        $attendance_data = json_decode(\request('attendance'));
        $attendance_date = \request('attendance_date');
        $settings = $this->attendanceService->storeData($attendance_data,$attendance_date);
        if(count($settings) > 0){
            $returnHTML = view($this->view.'result.attendance_table_dom',['settings'=> $settings,'attendance_date' => $attendance_date])->render();// or method that you prefere to return data + RENDER is the key here
            return response()->json( array('success' => true,'message' => 'Attendance has been done !', 'html'=>$returnHTML) );
        }else{
            return response()->json( array('success' => false,'message' => 'Please select date in between batch start and end date!') );
        }
    }


    public function update(Request $request,$id)
    {
        $this->validate(\request(),[
            'status' => 'required|numeric',
            'symbol' => 'required|string',
        ]);
        $status = $request->status;
        $symbol = $request->symbol;
        $setting = $this->attendanceService->updateData($id,$status,$symbol);
        $returnHTML = view($this->view.'result.status_button',['setting'=> $setting])->render();// or method that you prefere to return data + RENDER is the key here
        return response()->json( array('success' => true,'data' => $setting,'message' => 'Attendance has been updated ! !', 'html'=>$returnHTML) );
    }

    /**
     * @return  string html  Attendance by date and batch if found otherwise return list of student  to make attendance at that given date
     */
    public function attendanceByDate()
    {
        $this->validate(\request(),[
            'attendance_date' => 'required|date',
            'batch_id' => 'required|numeric',
        ]);
        $attendance_date = \request('attendance_date');
        $batch = Batch::findOrFail(\request('batch_id'));
        $settings = $this->attendanceService->getBatchAttendances($batch->id,$attendance_date);
        //start if
        if(count($settings) > 0){
            $returnHTML = view($this->view.'table.table_with_status',['settings'=> $settings,'attendance_date' => $attendance_date])->render();// or method that you prefere to return data + RENDER is the key here
            return response()->json( array('success' => true,'message' => 'Attendance has been done !', 'html'=>$returnHTML) );
        }else{
            $settings = Student::whereHas('admission', function ($a) use ($batch, $attendance_date) {
                //for admission which has not batch transfer
                $a->doesntHave('activeBatchTransfer')->where('batch_id', $batch->id)
                    //for admission which has  batch transfer but less than batch transfer date
                    ->orWhereHas('activeBatchTransfer', function ($pt) use ($batch, $attendance_date) {
                        $pt->where('date', '>', $attendance_date)
                            ->where('previous_batch_id', $batch->id);
                    })
                    //for admission which has  batch transfer from another branch and greater than equal to transfer date
                    ->orWhereHas('activeBatchTransfer', function ($bt) use ($batch, $attendance_date) {
                        $bt->where('date', '<=', $attendance_date)
                            ->where('batch_id', $batch->id);
                    });

            })->orderBy('id', 'ASC')->get();
            $returnHTML = view($this->view.'table.table_without_status',['settings'=> $settings,'attendance_date' => $attendance_date])->render();// or method that you prefere to return data + RENDER is the key here
            return response()->json( array('success' => true,'message' => 'Attendance has been done !', 'html'=>$returnHTML, 'date' => date("Y-m-d", strtotime($attendance_date))) );
        }
        //endif
    }

    public function attendanceByDateOld()
    {
        $this->validate(\request(),[
            'attendance_date' => 'required|date',
            'batch_id' => 'required|numeric',
        ]);
        $attendance_date = \request('attendance_date');
        $batch = Batch::findOrFail(\request('batch_id'));
        $settings = $this->attendanceService->getBatchAttendances($batch->id,$attendance_date);
        //start if
        if(count($settings) > 0){
            $returnHTML = view($this->view.'table.table_with_status',['settings'=> $settings,'attendance_date' => $attendance_date])->render();// or method that you prefere to return data + RENDER is the key here
            return response()->json( array('success' => true,'message' => 'Attendance has been done !', 'html'=>$returnHTML) );
        }else{
            //attendance data is not available for given date
//            $settings = Student::whereHas('admission', function ($q) use ($batch,$attendance_date) {
//                $q->where('batch_id', $batch->id)
//                    ->where('date','<=',$attendance_date);
//            })->get();
            //attendance data is not available for given date
            $settings1 = Student::whereHas('admission', function ($q) use ($batch) {
                $q->where('batch_id', $batch->id);
            })->get();
            $settings = $settings1->map(function ($item) use ($batch, $settings1, $attendance_date) {
                if ($item->admission->activeBatchTransferWithPreviousBatch($batch)->count() > 0) {
                    if ($attendance_date < $item->admission->activeBatchTransferWithPreviousBatch($batch)->first()->date) {
                        return $item;
                    }
                } else {
                    return $item;
                }
            });
            $settings = $settings->filter();
            $batchTransfers = Student::whereHas('admission.activeBatchTransfer', function ($ab) use ($batch, $attendance_date) {
                $ab->where('date', '<=', date("Y-m-d", strtotime($attendance_date)))->where('batch_id', $batch->id);
            })->get();
            $returnHTML = view($this->view.'table.table_without_status',['settings'=> $settings,'attendance_date' => $attendance_date, 'batchTransfers' => $batchTransfers])->render();// or method that you prefere to return data + RENDER is the key here
            return response()->json( array('success' => true,'message' => 'Attendance has been done !', 'html'=>$returnHTML, 'date' => date("Y-m-d", strtotime($attendance_date))) );
        }
        //endif
    }

    /**
     * @return  string html  Attendance by name and date and batch if found otherwise return list of student  to make attendance at that given date
     */
    public function getStudentSearch()
    {

    }
    public function studentIndex()
    {
        return view($this->view.'student');
    }
    public function financeIndex()
    {
        return view($this->view.'finance');
    }
    public function quizIndex()
    {
        return view($this->view.'quiz');
    }
    public function careerIndex()
    {
        return view($this->view.'career');
    }
    public function technicalIndex()
    {
        return view($this->view.'technical');
    }

}
