<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Counselling\CounsellingAttendanceRequest;
use App\Http\Requests\Counselling\CounsellingRequest;
use App\Models\SCounselling;
use App\Services\Counselling\CounsellingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SCounsellingController extends Controller
{
    protected $view = 'admin.counselling.';
    protected $redirect = 'counselling';
    private $counsellingService;

    public function __construct(CounsellingService $service)
    {
        $this->counsellingService = $service;
    }

    public function index($admissionId)
    {
        $setting = SCounselling::findOrFail($admissionId);
        return view('admin.counselling.index', compact('setting'));
    }

    public function counselling_test()
    {
       dd(\request('comment')[1]);
    }

    public function store(CounsellingRequest $request)
    {
        $validatedData = $request->validated();
        $this->counsellingService->storeData($validatedData);
        Session::flash('success', 'Carrier Counselling is added!');
        return redirect($this->redirect);
    }

    public function attendance(CounsellingAttendanceRequest $request, $studentCounsellingId)
    {
        $studentCounselling = SCounselling::findOrFail($studentCounsellingId);
        $this->counsellingService->attendance($studentCounselling);
        Session::flash('success', 'Carrier Counselling Attendance is created!');
        return redirect($this->redirect);
    }

}
