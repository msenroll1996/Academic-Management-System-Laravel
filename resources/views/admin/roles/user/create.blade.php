@extends('layouts.app')
@section('title')
    <title>Users</title>
@endsection
@section('main-panel')
    <div class="main-panel">
        <div class="content-wrapper">
            {{--start loader--}}
            <div class="loader loader-default" id="loader"></div>
            {{--end loader--}}
            <div class="row">
                <div class="col-sm-12 col-md-12 stretch-card">
                    <div class="card-wrap form-block p-0">
                        <div class="block-header p-4">
                            <div class="d-flex flex-column">
                                <h3>Add User</h3>
                                <p class="mt-2 sub-header">Fill the following fields to add a new user.</p>
                            </div>
                            <div class="tbl-buttons">
                                <ul>
                                    <li>
                                        <a href="{{url('users')}}"><img src="{{url('images/cancel-icon.png')}}" alt="cancel-icon"/></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @include('success.success')
                        @include('errors.error')
                        <div class="row p-4">
                            <div class="col-sm-12 col-md-12 stretch-card sl-stretch-card">
                                {!! Form::open(['url' => 'users','method' => 'POST','onsubmit' => 'return validateForm()', 'files' => true]) !!}
                                    <div class="row">
                                        <div class="col-12 table-responsive">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 mt-2">
                                                    <div class="form-group batch-form">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>User's Name</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="Name" autocomplete="off" required/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 mt-2">
                                                    <div class="form-group batch-form">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>User's Email</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <input type="email" name="email" class="form-control" value="{{old('email')}}" placeholder="Email" autocomplete="off" required/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 mt-2">
                                                    <div class="form-group batch-form">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>Mobile No.</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <input type="number" name="mobile_no" class="form-control" value="{{old('mobile_no')}}" autocomplete="off" required/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 mt-2">
                                                    <div class="form-group batch-form">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>Address</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <input type="text" name="address" class="form-control" value="{{old('address')}}" autocomplete="off" required/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 mt-2">
                                                    <div class="form-group batch-form">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>EmployeeId</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <input type="text" name="emp_id" class="form-control" value="{{old('emp_id')}}" autocomplete="off"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 mt-4">
                                                    <div class="form-group batch-form">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>Image</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <input type="file" name="image"  value="{{old('image')}}" class="form-control" required/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-6 mt-4">
                                                    <div class="form-group batch-form">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>Status</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <select name="status" id="status" class="form-control" required>
                                                                            <option value="" selected disabled class="option" >Please Select the Status</option>
                                                                            @foreach(config('custom.status') as $in => $val)
                                                                                <option value="{{$in}}" @if(old('status') == $in) selected @endif>{{$val}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 mt-4">
                                                    <div class="form-group batch-form">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>Remark</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <input type="text" name="remark"  value="{{old('remark')}}" class="form-control"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 mt-4">
                                                    <div class="form-group batch-form">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>Branch</label>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="input-group">
                                                                        <select multiple  name="branch_id[]" id="branch_id" class="form-control" required>
                                                                            <option value="" selected disabled >Please Select the Branch</option>
                                                                            @foreach($branches as $branch)
                                                                                <option value="{{$branch->id}}" @if(old('branch_id') == $branch->id) selected @endif>{{$branch->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12 mt-4">
                                                    <div class="form-group batch-form">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="role-radios">
                                                                        <label for="" class="form-label">Is  Tutor?</label>
                                                                        <div class="d-flex gap-2">
                                                                            <div class="form-check role-user-istutor">
                                                                                <input class="form-check-input"  type="radio" name="tutor" id="yes" value="1" onclick="getCourses()">
                                                                                <label class="form-check-label" for="flexRadioDefault1">
                                                                                    Yes
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check role-user-istutor">
                                                                                <input class="form-check-input" type="radio" name="tutor" id="no" value="2" onclick="getCourses()">
                                                                                <label class="form-check-label" for="flexRadioDefault2">
                                                                                    No
                                                                                </label>
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
                                        @include('admin.roles.user.table.course')
                                        <div class="row mt-4">
                                            <div class="button-section d-flex justify-content-end mt-2 mb-4">
                                                <div class="row">
                                                    <div class="button-section d-flex justify-content-end mt-2 mb-4">
                                                        <a href="{{url('users')}}">
                                                            <button type="button">
                                                                Skip
                                                                <i class="fa-solid fa-angles-right"></i>
                                                            </button>
                                                        </a>
                                                        <button type="submit">
                                                            Save & Continue
                                                            <i class="fas fa-angle-double-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection
        @section('script')
            <script>
                var select_all = document.getElementById("select_all"); //select all checkbox
                var checkboxes = document.getElementsByClassName("checkbox"); //checkbox items
                //select all checkboxes
                select_all.addEventListener("change", function(e){
                    for (i = 0; i < checkboxes.length; i++) {
                        checkboxes[i].checked = select_all.checked;
                    }
                });


                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].addEventListener('change', function(e){ //".checkbox" change
                        //uncheck "select all", if one of the listed checkbox item is unchecked
                        if(this.checked == false){
                            select_all.checked = false;
                        }
                        //check "select all" if all checkbox items are checked
                        if(document.querySelectorAll('.checkbox:checked').length == checkboxes.length){
                            select_all.checked = true;
                        }
                    });
                }

                function getCourses() {
                    if($('#yes').is(':checked')){
                        $("#tutor-course").show();
                    }
                    if($('#no').is(':checked')){
                        $("#tutor-course").hide();
                    }
                }
                function validateForm() {
                    if($('#yes').is(':checked')){
                        if(document.querySelectorAll('.checkbox:checked').length > 0){
                            return true;
                        } else {
                            errorDisplay('Please select at least one course!');
                            return false;
                        }
                    } else {
                        return true;
                    }
                }
            </script>
@endsection
