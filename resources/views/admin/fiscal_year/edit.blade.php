@extends('layouts.app')
@section('title')
    <title>Fiscal Year</title>
@endsection
@section('main-panel')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-md-12 stretch-card">
                    <div class="card-wrap form-block p-0">
                        <div class="block-header">
                            <h3>Add Fiscal Year</h3>
                            <p>Fill the following fields to create a Fiscal Year</p>
                            <div class="tbl-buttons">
                                <ul>
                                    <li class="m-0">
                                        <a href="{{url('fiscal-years')}}">
                                            <img src="{{url('images/cancel-icon.png')}}" alt="cancel-icon"/>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive grid-margin">
                                {!! Form::open(['url' => 'fiscal-years/'.$setting->id,'method'=>'Post']) !!}
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group batch-form">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Fiscal Year Name</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            <input name="name" value="{{$setting->name}}" type="text" class="form-control" placeholder="Username"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group batch-form">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Status</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            <select name="status" class="form-control" required>
                                                                <option value="" selected disabled>Please Select Status</option>
                                                                @foreach(config('custom.status') as $index => $value)
                                                                    <option value="{{$index}}" @if($index == $setting->status) ?? selected @endif>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group batch-form">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="exampleInputEmail1">Start Date</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            <input name="start_date" type="text" value="{{$setting->start_date}}" class="form-control" id="from_date"  placeholder="Please select course start date" required onchange="getMinDate()"/>
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <img src="{{url('images/calender-icon.png')}}" alt="calender-icon"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group batch-form">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="exampleInputEmail1">End Date</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="input-group">
                                                            <input  name="end_date" type="text" value="{{$setting->end_date}}" id="to_date" class="form-control" placeholder="Please select course end date"/>
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <img src="{{url('images/calender-icon.png')}}" alt="calender-icon"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="button-section d-flex justify-content-end">
                                        <a href="{{url('fiscal-years')}}">
                                            <button type="button">
                                                Skip
                                                <i class="fa-solid fa-angles-right"></i>
                                            </button>
                                        </a>

                                        <button>
                                            Save & Continue
                                            <i class="fas fa-angle-double-right"></i>
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
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
        $("#from_date").flatpickr({
            dateFormat: "Y-m-d"
        });
        function getMinDate(){
            var min_date = $('#from_date').val();
            if(min_date != ''){
                $('#to_date').flatpickr({
                    minDate: min_date,
                    dateFormat: 'Y-m-d',
                });
            }
        }

    </script>
@endsection
