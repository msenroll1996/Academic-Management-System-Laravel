<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\QuizBatchRequest;
use App\Models\Batch;
use App\Models\Course;
use App\Models\QuizBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class QuizBatchController extends Controller
{

    protected $view = 'admin.quiz_batch.';
    protected $redirect = 'quiz_batch';

    public function index()
    {
        if (Auth::user()->user_type == 4 && Auth::user()->userInfo->tutor_status == 1) {
            $settings = QuizBatch::whereHas('batch.time_slot.course.activeUserTeachers', function ($t) {
                $t->where('user_id', Auth::user()->id);
            });
            $batches = Batch::whereHas('activeUserTeachersBatch', function ($q) {
                $q->where('user_id', Auth::user()->id);
            })->where('status', '1')->get();
        } else {
            $settings = QuizBatch::orderBy('id', 'desc');
            $batches = Batch::whereHas('quiz_batches')->get();
        }

        if(\request('name')){
            $key = \request('name');
            $settings = $settings->whereHas('quiz',function ($q) use($key){
                                $q->where('name','like','%'.$key.'%');
                         });
        }
        if(\request('batch_id')){
            $key = \request('batch_id');
            $settings = $settings->where('batch_id',$key);
        }
        $settings = $settings->paginate(config('custom.per_page'));
       return view($this->view.'index',compact('settings','batches'));
    }

    public function create()
    {
        if (Auth::user()->user_type == 4 && Auth::user()->userInfo->tutor_status == 1) {
            //for tutor
            $courses = Course::whereHas('activeUserTeachers', function ($q) {
                $q->where('user_id', Auth::user()->id);
            })->where('status', 1)->get();
        } else {
            $courses = Course::where('status', 1)->get();
        }
        return view($this->view.'create',compact('courses'));
    }

    public function getBatch($course_id)
    {
        $course = Course::findOrFail($course_id);
        $settings = $course->batches->where('end_date','>=',date('Y-m-d'))->where('status',array_search('Active',config('custom.status')));
        $quizzes = $course->quizzes->where('status',1);
        $returnHtml = view($this->view.'batch_dom',['settings' => $settings])->render();
        $quizzesHtml = view($this->view.'quiz_dom',['quizzes' => $quizzes])->render();
        return response()->json(array('success' =>true, 'html' => $returnHtml,'quiz_html' => $quizzesHtml));
    }

    public function store(QuizBatchRequest $request)
    {
        $validatedData = $request->validated();
        $setting = QuizBatch::firstOrNew(['quiz_id' => \request('quiz_id'),'batch_id' => \request('batch_id')]);
        $setting->user_id = Auth::user()->id;
        $setting->status = \request('status');
        $setting->save();
        Session::flash('success','Quiz has been assigned to batch!');
        return redirect($this->redirect);
    }

    public function edit($id)
    {
        $setting = QuizBatch::findOrFail($id);
        $courses = Course::where('status',1)->get();
        $course = Course::findOrFail($setting->batch->time_slot->course_id);
        $batches = $course->batches;
        return view($this->view.'edit',compact('setting','batches','courses'));
    }

    public function update(QuizBatchRequest $request,$id)
    {
        $setting = QuizBatch::findOrFail($id);
        $setting->quiz_id = \request('quiz_id');
        $setting->batch_id = \request('batch_id');
        $setting->status = \request('status');
        $setting->user_id = Auth::user()->id;
        $setting->save();
        Session::flash('success','Quiz has been assigned to batch has updated!');
        return redirect($this->redirect);
    }
}
