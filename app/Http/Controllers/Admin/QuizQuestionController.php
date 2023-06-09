<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\QuizQuestionRequest;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Services\QuizQuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class QuizQuestionController extends Controller
{

    protected $view = 'admin.quiz_question.';
    protected $redirect = 'quiz';

    private $quizQuestionService;

    public function __construct(QuizQuestionService $service)
    {
        $this->quizQuestionService = $service;
    }

    public function create($quiz_id)
    {
        $setting = Quiz::findOrFail($quiz_id);
       return view($this->view.'create',compact('setting'));
    }

    public function quizOptionDom($dom_id,$no_of_option)
    {
        if($no_of_option == 4){
            return response()->json(['no_of_option' => 4],200);
        }
        if($no_of_option == 5){
            $returnHtml = view($this->view.'design.option',['id' => $dom_id])->render();
            return response()->json(array('success' =>true, 'no_of_option' => 5,'html' => $returnHtml));
        }

    }

    public function quizQuestionDom($dom_id,$question_type)
    {
        //for Text type
        if($question_type == 1){
            $returnHtml = view($this->view.'design.question_text_dom',['id' => $dom_id])->render();
            return response()->json(array('success' =>true, 'question_type' => 2,'html' => $returnHtml));
        }
        //for Text Image
        if($question_type == 2){
            $returnHtml = view($this->view.'design.question_image_dom',['id' => $dom_id])->render();
            return response()->json(array('success' =>true, 'question_type' => 2,'html' => $returnHtml));
        }
    }

    public function quizQuestion($dom_id)
    {
        $returnHtml = view($this->view.'design.question',['id' => $dom_id])->render();
        return response()->json(array('success' =>true,'append_after_id' =>$dom_id-1,'html' => $returnHtml));
    }

    public function store($quiz_id)
    {
        $this->validate(\request(),[
            'question_type' => 'required|array',
            'count' => 'required|array',
            'no_of_option' => 'required|array',
            'status' => 'required|array',
        ]);
        $quiz = Quiz::findOrFail($quiz_id);
        $aaa = $this->quizQuestionService->storeData($quiz);
        Session::flash('success','Quiz question has been created successfully!');
        return redirect($this->redirect);
    }

    public function show($quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $key = \request('name');
        $settings = QuizQuestion::where('quiz_id',$quiz->id);
        if(\request('name')){
            $settings = $settings->where('question','like','%'.$key.'%');
        }
        $settings = $settings->paginate(config('custom.per_page'));
        return view($this->view.'index',compact('settings','quiz'));
    }

    public function getShow($id)
    {
        $setting = QuizQuestion::findOrFail($id);
//        dd($setting->);
//        dd($setting->quiz_options);
//        dd($setting->quiz_options[0]->quiz_question_answer);
        return view($this->view.'show',compact('setting'));
    }

    public function edit($id)
    {
        $setting = QuizQuestion::findOrFail($id);
//        dd($setting->);
//        dd($setting->quiz_options);
//        dd($setting->quiz_options[0]->quiz_question_answer);
        return view($this->view.'edit',compact('setting'));
    }

    public function update($id)
    {
        $this->validate(\request(),[
            'question_type' => 'required',
            'no_of_option' => 'required',
            'status' => 'required',
        ]);
//        dd(\request()->all());

        $setting = QuizQuestion::findOrFail($id);
        $this->quizQuestionService->updateData($setting);
        return redirect('quiz/question_show/'.$setting->quiz->id);
    }

    public function quizQuestionDomUpdate($dom_id,$question_type)
    {
        //for Text type
        if($question_type == 1){
            $returnHtml = view($this->view.'design.question_text_dom_update',['id' => $dom_id])->render();
            return response()->json(array('success' =>true, 'question_type' => 2,'html' => $returnHtml));
        }
        //for Text Image
        if($question_type == 2){
            $returnHtml = view($this->view.'design.question_image_dom_update',['id' => $dom_id])->render();
            return response()->json(array('success' =>true, 'question_type' => 2,'html' => $returnHtml));
        }
    }

    public function delete($id)
    {
        $setting = QuizQuestion::findOrFail($id);
        if ($setting->studentQuizQuestionBatches->count() > 0 || $setting->studentQuizQuestionIndividuals->count() > 0 ) {
            $message = 'You can not delete quiz questions, Student has already given exam to this question!';
            Session::flash('custom_error', $message);
        } else {
            try {
                DB::beginTransaction();
                    $setting->quiz_question_answers()->delete();
                    $setting->quiz_options()->delete();
                    if ($setting->question_type == 2) {
                        $path = public_path().'/'.$setting->quiz_question_image->image;
                        if (is_file($path) && file_exists($path)) {
                            unlink($path);
                        }
                        $setting->quiz_question_image->delete();
                    }
                    $setting->delete();
                DB::commit();
                $message = 'Quiz Question has been deleted!';
                Session::flash('success', $message);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
        return redirect()->back();
    }
}
