@extends('layouts.app')
@section('title')
    <title>Admission</title>
@endsection
@section('main-panel')
    <div class="main-panel">
        <div class="content-wrapper content-wrapper-bg">
            <div class="row">
                <div class="col-sm-12 col-md-12 stretch-card">
                    <div class="row">
                        <div class="card-heading">
                            <div>
                                <h4>Student Lists</h4>
                                <p>
                                    You can search the student by <a href="#" class="card-heading-link">name, group, date,</a> and can view all available courses.
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11">
                                <div class="row">
                                    <div class="filter-btnwrap justify-content-between">
                                        <div class="d-flex">
                                            <div class="input-group">
                                                <span>
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </span>
                                                <input type="text" class="form-control" id="inputText" placeholder="Search by Name" name="fullname" value=""/>
                                            </div>
                                            <div class="input-group mx-4">
                                                <span>
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </span>
                                                <input type="text" class="form-control" id="inputText" placeholder="Search by Code" name="fullname" value=""/>
                                            </div>
                                            <div class="refresh-btn mx-4">
                                                <a href="">
                                                    <img src="{{url('images/refresh-icon.png')}}" alt=""/>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex justify-content-end">
                                            <div class="d-flex align-items-center">
                                                <p class="m-0">
                                                    Show
                                                </p>
                                                <select class="form-select mx-2 show-select" aria-label="Default select example">
                                                    <option selected>10</option>
                                                    <option value="1">10</option>
                                                    <option value="2">20</option>
                                                    <option value="3">30</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1 d-flex align-items-center p-0">
                                <div class="tbl-buttons">
                                    <div class="export-button">
                                        <div class="dropdown-export">
                                            <button type="submit" name="submit" onclick="submit;" class="student-btn d-flex">
                                                <img src="http://localhost/ams/public/images/export-icon.png" alt="">Export
                                            </button>
                                            <div class="dropdown-content-export">
                                                <ul>
                                                    <li>
                                                        <a href="#">
                                                            Export.csv
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#">
                                                            Export.pdf
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            @include('success.success')
                            @include('errors.error')
                        </div>
                        <div class="col-sm-12 col-md-12 stretch-card mt-4">
                            <div class="card-wrap form-block p-0">
                                <div class="block-header bg-header d-flex justify-content-between p-4">
                                    <div class="d-flex flex-column">
                                        <h3>New Student Table</h3>
                                    </div>
                                    <div class="add-button">
                                        <a class="nav-link" href="{{url('admissions/create')}}"><i class="fa-solid fa-book-open"></i>&nbsp;&nbsp;Add Admission</a>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-12 stretch-card sl-stretch-card">
                                        <div class="card-wrap form-block p-4 card-wrap-bs-none pt-0">
                                            <div class="row">
                                                <div class="col-12 table-responsive table-details">
                                                    <table class="table" id="">
                                                        <thead>
                                                            <tr>
                                                                <th>S.N.</th>
                                                                <th>Name</th>
                                                                <th>Course</th>
                                                                <th>Batch</th>
                                                                <th>Time Slot</th>
                                                                <th>date</th>
                                                                <th>Amount To Pay</th>
                                                                <th>Discount</th>
                                                                <th>First Installment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="student_list">
                                                            @foreach($settings as $setting)
                                                                <tr>
                                                                    <td>{{$settings->firstItem() + $loop->index}}</td>
                                                                    <td class="d-flex">
                                                                        <div class="d-flex flex-column name-table">
                                                                            <p>{{$setting->user->name}}</p>
                                                                            <p>{{$setting->student_id}}</p>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{$setting->batch->time_slot->course->name}}</td>
                                                                    <td>{{$setting->batch->name}}</td>
                                                                    <td>{{$setting->batch->time_slot->time_table->day}} [{{$setting->batch->time_slot->time_table->start_time}}-{{$setting->batch->time_slot->time_table->end_time}}]</td>
                                                                    <td>{{$setting->date}}</td>
                                                                    <td>{{$setting->payable_amount}}</td>
                                                                    <td>{{$setting->discount->amount}}</td>
                                                                    <td>{{$setting->finances->first()->amount}}</td>
                                                                    <td class="action-icons">
                                                                        <ul class="icon-button d-flex">
                                                                            <li>
                                                                                <a class="dropdown-item"  href="{{url('admissions/show/'.$setting->id)}}" role="button"><i class="fa-solid fa-eye"></i></a>
                                                                            </li>
                                                                            <li>
                                                                                <a class="dropdown-item"  href="{{url('admissions/'.$setting->id.'/edit')}}" role="button"><i class="fa-solid fa-pen"></i></a>
                                                                            </li>
                                                                        </ul>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
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
    </div>

    @include('admin.admission.admission_modal');
@endsection
