@extends('layouts.app')
@section('title')
    <title>Quiz</title>
@endsection
@section('main-panel')
    <div class="main-panel">
        <div class="content-wrapper content-wrapper-bg">
            <div class="row">
                <div class="col-sm-12 col-md-12 stretch-card">
                    <div class="row">
                        <div class="card-heading d-flex justify-content-between">
                            <div>
                                <h4>Quiz Lists</h4>
                                <p>
                                    You can search the quiz by <a href="#" class="card-heading-link">name and course</a> and can view all available quiz.
                                </p>
                            </div>
                            <ul class="admin-breadcrumb">
                                <li><a href="/" class="card-heading-link">Home</a></li>
                                <li>Quiz Lists</li>
                            </ul>
                        </div>
                        {!! Form::open(['url' => 'quiz', 'method' => 'GET']) !!}
                            <div class="filter-btnwrap mt-4">
                                <div class="col-md-12">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span>
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </span>
                                                <input type="text" class="form-control" id="inputText" placeholder="Search by quiz name" name="name"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span>
                                                    <i class="fa-solid fa-book-open"></i>
                                                </span>
                                                <select name="course_id" class="form-control">
                                                    <option value="" selected disabled>Search by Course</option>
                                                    @foreach($courses as $course)
                                                        <option value="{{$course->id}}">{{$course->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex">
                                            <div class="filter-group mx-2">
                                                <span>
                                                    <img src="{{url('icons/filter-icon.svg')}}" alt="" class="img-flud">
                                                </span>
                                                <button class="fltr-btn" type="submit">Filter</button>
                                            </div>
                                            <div class="refresh-group mx-2">
                                                <a onclick="getReset('{{Request::segment(1)}}')">
                                                    <img src="{{url('icons/refresh-top-icon.svg')}}" alt="" class="img-flud">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        {!! Form::close() !!}

                        <div>
                            @include('success.success')
                            @include('errors.error')
                        </div>
                        <div class="col-sm-12 col-md-12 stretch-card mt-4">
                            <div class="card-wrap form-block p-0">
                                <div class="block-header bg-header d-flex justify-content-between p-4">
                                    <div class="d-flex flex-column">
                                        <h3>Quiz Table</h3>
                                    </div>
                                    @if(Auth::user()->customMenuPermission('create_quizzes'))
                                        <div class="add-button">
                                            <a class="nav-link" href="{{url('quiz/create')}}"><i class="fa-solid fa-book-open"></i>&nbsp;&nbsp;Add quiz</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 stretch-card sl-stretch-card">
                                        <div class="card-wrap card-wrap-bs-none form-block p-4 pt-0">
                                            <div class="row">
                                                <div class="col-12 table-responsive table-details">
                                                    <table class="table" id="">
                                                        <thead>
                                                        <tr>
                                                            <th>S.N.</th>
                                                            <th>Quiz</th>
                                                            <th>Course</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="student_list">
                                                            @foreach($settings as $setting)
                                                                <tr>
                                                                    <td>{{$loop->iteration}}</td>
                                                                    <td>{{$setting->name}}</td>
                                                                    <td>{{$setting->course->name}}</td>
                                                                    <td>{{config('custom.status')[$setting->status]}}</td>
                                                                    <td class="action-icons">
                                                                        <ul class="icon-button d-flex">
                                                                            @if(Auth::user()->customMenuPermission('create_quizzes'))
                                                                                <li>
                                                                                    <a class="dropdown-item"   href="{{url('quiz/question_create/'.$setting->id)}}" data-bs-toggle="tooltip" data-bs-title="add"><i class="fa-solid fa-plus"></i></a>
                                                                                </li>
                                                                            @endif
                                                                            @if(Auth::user()->customMenuPermission('show_quizzes'))
                                                                                <li>
                                                                                    <a class="dropdown-item"   href="{{url('quiz/question_show/'.$setting->id)}}" data-bs-toggle="tooltip" data-bs-title="view"><i class="fa-solid fa-eye"></i></a>
                                                                                </li>
                                                                            @endif
                                                                            @if(Auth::user()->customMenuPermission('show_quizzes'))
                                                                                <li>
                                                                                    <a class="dropdown-item"   href="{{url('quiz/show_all_questions/'.$setting->id)}}" data-bs-toggle="tooltip" data-bs-title="lists"><i class="fa-solid fa-list-ul"></i></a>
                                                                                </li>
                                                                            @endif
                                                                            @if(Auth::user()->customMenuPermission('update_quizzes'))
                                                                                <li>
                                                                                    <a class="dropdown-item"  href="{{url('quiz/'.$setting->id.'/edit')}}" role="button" data-bs-toggle="tooltip" data-bs-title="edit"><i class="fa-solid fa-pen"></i></a>
                                                                                </li>
                                                                            @endif
                                                                            @if(Auth::user()->customMenuPermission('delete_quizzes'))
                                                                                <li>
                                                                                    <a class="dropdown-item"  href="{{url('quiz/delete/'.$setting->id)}}" role="button" data-bs-toggle="tooltip" data-bs-title="delete" onclick="getConfirm()"><i class="fa-solid fa-trash"></i></a>
                                                                                </li>
                                                                            @endif
                                                                        </ul>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <div class="pagination-section">
                                                        {{$settings->links()}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function getConfirm() {
            if (confirm('Do you sure want to delete quiz?')) {

            } else {
                event.preventDefault();
                location.reload();
            }
        }
    </script>
@endsection
