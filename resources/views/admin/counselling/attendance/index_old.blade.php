@extends('layouts.app')
@section('title')
    <title>Attendance</title>
@endsection
@section('main-panel')
    <div class="main-panel">
        <div class="content-wrapper content-wrapper-bg">
            <div class="row">
                {{--start loader--}}
                <div class="loader loader-default" id="loader"></div>
                {{--end loader--}}
                <div class="col-sm-12 col-md-12 stretch-card">
                    <div class="row">
                        {!! Form::open(['url' => 'attendance','method' => 'GET']) !!}
                        <div class="filter-btnwrap my-2">
                            <div class="col-md-12">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                                <span>
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </span>
                                            <input type="text" class="form-control" id="inputText" placeholder="Search" name="name"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                                <span>
                                                    <i class="fa-solid fa-book-open"></i>
                                                </span>
                                            <select class="form-select" aria-label="Default select example" name="course_id">
                                                <option selected disabled >Search by courses</option>
                                                @foreach($courses as $course)
                                                    <option value="{{$course->id}}">{{$course->name}}</option>
                                                @endforeach
                                            </select>
                                            <span>
                                                    <i class="fa-solid fa-caret-down"></i>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                                <span>
                                                    <i class="fa-solid fa-book-open"></i>
                                                </span>
                                            <select class="form-select" aria-label="Default select example" name="batch_id">
                                                <option selected>Search by Batch</option>
                                                @foreach($batches as $bat)
                                                    <option value="{{$bat->id}}">{{$bat->name}}</option>
                                                @endforeach
                                            </select>
                                            <span>
                                                    <i class="fa-solid fa-caret-down"></i>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text igt-calender">
                                                    <img src="{{url('images/calender-icon.png')}}" alt="calender-icon"/>
                                                </div>
                                            </div>
                                            <input name="search_date" type="text" class="form-control currentDate" />
                                            <span>
                                                    <i class="fa-solid fa-caret-down"></i>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="row">
                                            <div class="refresh-btn filter-btn">
                                                <a href="" class="d-flex text-decoration-none" role="button" type="submit">
                                                    <i class="fa-solid fa-filter"></i>
                                                    <button type="submit" class="btn">Filter</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="row">
                                            <div class="refresh-btn">
                                                <a href="">
                                                    <img src="{{url('images/refresh-icon.png')}}" alt=""/>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        @if(isset($batch))
                            <div class="col-sm-12 col-md-12 stretch-card mt-4">
                                <div class="card-wrap form-block p-0 pt-4">
                                    <div class="block-header bg-header d-flex justify-content-between p-4 py-0">
                                        <div class="col-md-11 d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <h3>{{$batch->time_slot->course->name}} [{{$batch->name}}]</h3>
                                                <p class="mt-2 sub-header">{{$batch->time_slot->time_table->day}} [{{$batch->time_slot->time_table->start_time}} - {{$batch->time_slot->time_table->end_time}}]</p>
                                            </div>
                                            <div class="d-flex attendance-toggle">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="1" onclick="getAttendance(1)">
                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                        Present
                                                    </label>
                                                </div>
                                                <div class="form-check mx-4">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="2" onclick="getAttendance(2)">
                                                    <label class="form-check-label" for="flexRadioDefault2">
                                                        Absent
                                                    </label>
                                                </div>
                                            </div>
                                            <form id="search">
                                                <div class="attendance-calender mx-4">
                                                    <div class="input-group">
                                                    <span>
                                                        <i class="fa-solid fa-magnifying-glass"></i>
                                                    </span>
                                                        <input name="student_name" type="text" class="form-control" id="student_name_id"  placeholder="Search by name" required onkeyup="getStudentSearch()"/>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="attendance-calender mx-4">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text igt-calender">
                                                            <img src="{{url('images/calender-icon.png')}}" alt="calender-icon"/>
                                                        </div>
                                                    </div>
                                                    <input name="date" type="text" class="form-control" id="fromDateToDate"  placeholder="" required onchange="getDatewiseBatchAttendance()"/>
                                                    <span>
                                                        <i class="fa-solid fa-caret-down"></i>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-1 d-flex align-items-center p-0">
                                            <div class="tbl-buttons">
                                                <div class="export-button">
                                                    <div class="dropdown-export">
                                                        <button type="submit" name="submit" onclick="submit;" class="student-btn d-flex">
                                                            <img src="{{url('images/export-icon.png')}}" alt=""/>Export
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
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 stretch-card sl-stretch-card">
                                            <div class="card-wrap card-wrap-bs-none form-block p-4">
                                                <div class="row">
                                                    <div class="col-12 table-responsive table-details" id="mytable">
                                                        @if(isset($att))
                                                            @include('admin.attendance.table.table_with_status')
                                                        @else
                                                            @include('admin.attendance.table.table_without_status')
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        @if(isset($batch))
        $('#fromDateToDate').flatpickr({
            minDate: "<?php echo $minDate;?>)",
            maxDate: "<?php echo $maxDate;?>)",
            {{--maxDate: "<?php echo $batch->end_date;?>)",--}}
                {{--defaultDate: "<?php echo date('Y-m-d');?>)",--}}
            defaultDate: "<?php echo $default_date;?>)",
            dateFormat: 'Y-m-d',
        });
        @endif

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

        // function getAllSelect() {
        //     if($('#form-check-input-master').is(':checked')){
        //         $(".form-check-input").prop("checked",true);
        //     }else {
        //         $(".form-check-input").prop("checked",false);
        //     }
        // }
        // function getSelect(id) {
        //     var aa = $('.form-check-input').length;
        //     debugger;
        //     if($('#form-check-input'+id).is(':checked')){
        //        debugger;
        //     }else {
        //        debugger
        //     }
        //     debugger;
        //
        // }

        function getAttendance(att_status) {
            var arr = [];
            if(att_status === 1){
                var checked_status = 1;
                var unchecked_status = 2;
                var checked_symbol = 'P';
                var unchecked_symbol = 'A';
            }
            if(att_status === 2){
                var checked_status = 2;
                var unchecked_status = 1;
                var checked_symbol = 'A';
                var unchecked_symbol = 'P';
            }

            for (var i = 0; i < checkboxes.length; i++) {
                var id = checkboxes[i].id;
                if($('#'+id).is(':checked')){
                    var student_id = checkboxes[i].value;
                    var symbol = checked_symbol;
                    var status = checked_status;
                    arr.push({student_id,status,symbol});
                }else {
                    var student_id = checkboxes[i].value;
                    var symbol = unchecked_symbol;
                    var status = unchecked_status;
                    arr.push({student_id,status,symbol});
                }
            }
            if(arr.length > 0){
                //start  confirmation for attendance
                $.confirm({
                    title: 'Do you sure want to make attendance?',
                    content: false,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Yes',
                            btnClass: 'btn-red',
                            action: function(){
                                start_loader();
                                var formData = new FormData();
                                var myJson = JSON.stringify(arr);
                                var attendance_date  = $('#fromDateToDate').val();
                                formData.append('attendance', myJson);
                                formData.append('attendance_date', attendance_date);
                                //start ajax call
                                $.ajax({
                                    /* the route pointing to the post function */
                                    type: 'POST',
                                    url: Laravel.url +"/attendance",
                                    dataType: 'json',
                                    data: formData,
                                    processData: false,  // tell jQuery not to process the data
                                    contentType: false,
                                    /* remind that 'data' is the response of the AjaxController */
                                    success: function (data) {
                                        end_loader();
                                        if(data['success']){
                                            $('#attendance_table').remove();
                                            $('#mytable').append(data['html']);
                                        }else {
                                            errorDisplay(data['message']);
                                        }
                                    },
                                    error: function(error) {
                                        end_loader();
                                        errorDisplay('Something went wrong !');

                                    }
                                });
                                //end ajax call
                            }
                        },
                        close: function () {
                        }
                    }
                });
                //end confirmation for attendance
            }else {
                errorDisplay('Please select student for attendance!');
            }
        }

        //update single student attendance
        function singleAttendance(attendance_id,status) {
            //start  confirmation for single attendance
            $.confirm({
                title: 'Do you sure want to make attendance?',
                content: false,
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'Yes',
                        btnClass: 'btn-red',
                        action: function(){
                            if(status == 1){
                                //if present(1) then make absent(2)
                                var new_status = 2;
                                var symbol = 'A';
                            }
                            if(status == 2){
                                //if absent(2) then make present(1)
                                var new_status = 1;
                                var symbol = 'P';
                            }
                            start_loader();
                            var formData = new FormData();
                            formData.append('status', new_status);
                            formData.append('symbol', symbol);
                            //start ajax call
                            $.ajax({
                                /* the route pointing to the post function */
                                type: 'POST',
                                url: Laravel.url +"/attendance/"+attendance_id,
                                dataType: 'json',
                                data: formData,
                                processData: false,  // tell jQuery not to process the data
                                contentType: false,
                                /* remind that 'data' is the response of the AjaxController */
                                success: function (data) {
                                    end_loader();
                                    $('#att_btn'+data['data']['id']).remove();
                                    $('#td_status'+data['data']['id']).append(data['html'])
                                    successDisplay(data['message']);
                                },
                                error: function(error) {
                                    end_loader();
                                    errorDisplay('Something went wrong !');
                                }
                            });
                            //end ajax call
                        }
                    },
                    close: function () {
                    }
                }
            });
            //end confirmation for  single attendance
        }

        @if(isset($batch))
        //get batch attendances using date
        function getDatewiseBatchAttendance() {
            var attendance_date = $('#fromDateToDate').val();
            var batch_id = '<?php echo $batch->id; ?>';
            start_loader();
            var formData = new FormData();
            formData.append('attendance_date', attendance_date);
            formData.append('batch_id', batch_id);
            //start ajax call
            $.ajax({
                /* the route pointing to the post function */
                type: 'POST',
                url: Laravel.url +"/attendance_by_date",
                dataType: 'json',
                data: formData,
                processData: false,  // tell jQuery not to process the data
                contentType: false,
                /* remind that 'data' is the response of the AjaxController */
                success: function (data) {
                    end_loader();
                    $('#attendance_table').remove();
                    $('#mytable').append(data['html']);
                },
                error: function(error) {
                    end_loader();
                    errorDisplay('Something went wrong !');
                }
            });
        }

        function getStudentSearch() {
            debugger;
            var student_name = $('#student_name_id').val();
            var attendance_date = $('#fromDateToDate').val();
            var batch_id = '<?php echo $batch->id; ?>';
            if(student_name.trim().length > 0){
                start_loader();
                debugger;
            }
            debugger;
        }
        @endif

        {{--        for searching student --}}


    </script>
@endsection
