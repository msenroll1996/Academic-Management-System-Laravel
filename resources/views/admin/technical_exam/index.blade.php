@extends('layouts.app')
@section('title')
    <title>Technical Exam Lists</title>
@endsection
@section('main-panel')
    <div class="main-panel">
        <div class="content-wrapper content-wrapper-bg">
            <div class="row">
                <div class="col-sm-12 col-md-12 stretch-card">
                    <div class="row">
                        <div class="card-heading d-flex justify-content-between">
                            <div>
                                <h4>Technical Exam Lists</h4>
                                {{-- <p>
                                    You can search the materials by <a href="#" class="card-heading-link">name</a> and can view all available courses materials.
                                </p> --}}
                            </div>
                            <ul class="admin-breadcrumb">
                                <li><a href="{{url('')}}" class="card-heading-link">Home</a></li>
                                <li>Technical Exam Lists</li>
                            </ul>
                        </div>
                        {{-- {!! Form::open(['url' => 'technical_exam_location', 'method' => 'GET']) !!}
                            <div class="filter-btnwrap mt-4">
                                <div class="col-md-12">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span>
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </span>
                                                <input type="text" class="form-control" id="inputText" placeholder="Search by material name or course name" name="name"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 d-flex">
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
                        {!! Form::close() !!} --}}

                        <div class="mt-1">
                            @include('success.success')
                            @include('errors.error')
                        </div>
                        <div class="col-sm-12 col-md-12 stretch-card mt-4">
                            <div class="card-wrap form-block p-0">
                                <div class="block-header bg-header d-flex justify-content-between p-4">
                                    <div class="d-flex flex-column">
                                        <h3>Technical Exams Table</h3>
                                    </div>
                                    {{-- @if(Auth::user()->customMenuPermission('create_technical_exam_location')) --}}
                                        <div class="add-button">
                                            <a class="nav-link" href="{{url('technical_exam/create')}}"><i class="fa-solid fa-book-open"></i>&nbsp;&nbsp;Add Exam</a>
                                        </div>
                                    {{-- @endif --}}
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
                                                            <th>Exam Type</th>
                                                            <th>Date</th>
                                                            {{-- <th>Course</th>
                                                            <th>Timeslot</th>
                                                            <th>Branch</th> --}}
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="student_list">
                                                        @foreach($settings as $setting)
                                                            <tr>
                                                                <td>{{$loop->iteration}}</td>
                                                                <td>{{config('custom.exam_types')[$setting->exam_type]}}</td>
                                                                <td>{{$setting->date}}</td>
                                                                {{-- <td>
                                                                    @foreach ($setting->courses as $course)
                                                                         {{ $course->name }}<br>
                                                                    @endforeach
                                                                </td>
                                                                <td>
                                                                    @foreach ($setting->technical_exam_timeslots as $timeslot)
                                                                         {{ $timeslot->start_time . ' - '  . $timeslot->end_time }}<br>
                                                                    @endforeach
                                                                </td>
                                                                <td>
                                                                    @foreach ($setting->branches as $branch)
                                                                         {{ $branch->name}}<br>
                                                                    @endforeach
                                                                </td> --}}
                                                                <td>{{config('custom.status')[$setting->status]}}</td>
                                                                <td class="action-icons">
                                                                    <ul class="icon-button d-flex">
                                                                        @if(Auth::user()->customMenuPermission('show_course_materials'))
                                                                            <li>
                                                                                <a class="dropdown-item" href="{{url('technical_exam/'.$setting->id.'/view')}}" data-bs-toggle="tooltip"  role="button" data-bs-title="details"><i class="fa-solid fa-eye"></i></a>
                                                                            </li>
                                                                        @endif
                                                                        @if(Auth::user()->customMenuPermission('update_course_materials'))
                                                                            <li>
                                                                                <a class="dropdown-item"  href="{{url('technical_exam/'.$setting->id.'/edit')}}" role="button" data-bs-toggle="tooltip" data-bs-title="edit"><i class="fa-solid fa-pen"></i></a>
                                                                            </li>
                                                                        @endif
                                                                        @if(Auth::user()->customMenuPermission('delete_course_materials'))
                                                                            <li>
                                                                                <a class="dropdown-item"  onclick="myConfirm({{$setting->id}})" role="button" data-bs-toggle="tooltip" data-bs-title="Delete"><i class="fa-solid fa-trash"></i></a>
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
        function myConfirm(id){
            debugger;
            $.confirm({
                title: 'Do you sure want to delete?',
                content: false,
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'Delete',
                        btnClass: 'btn-red',
                        action: function(){
                            window.location = Laravel.url+'/technical_exam/delete/'+id;
                        }
                    },
                    close: function () {
                    }
                }
            });
        }
    </script>
@endsection
