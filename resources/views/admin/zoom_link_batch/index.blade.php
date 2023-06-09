@extends('layouts.app')
@section('title')
    <title>Zoom Link | Assign</title>
@endsection
@section('main-panel')
    <div class="main-panel">
        <div class="content-wrapper content-wrapper-bg">
            <div class="row">
                <div class="col-sm-12 col-md-12 stretch-card">
                    <div class="row">
                        <div class="card-heading d-flex justify-content-between">
                            <div>
                                <h4>Assigned Zoom Links</h4>
{{--                                <p>--}}
{{--                                    You can search the batch course by <a href="#" class="card-heading-link">name</a> and can view all available batch courses materials.--}}
{{--                                </p>--}}
                            </div>
                            <ul class="admin-breadcrumb">
                                <li><a href="{{url('')}}" class="card-heading-link">Home</a></li>
                                <li>Zoom Link Lists</li>
                            </ul>
                        </div>
{{--                        {!! Form::open(['url' => 'zoom-links-batch', 'method' => 'GET']) !!}--}}
{{--                            <div class="filter-btnwrap mt-4">--}}
{{--                                <div class="col-md-10">--}}
{{--                                    <div class="row align-items-center">--}}
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="input-group">--}}
{{--                                                <span>--}}
{{--                                                    <i class="fa-solid fa-magnifying-glass"></i>--}}
{{--                                                </span>--}}
{{--                                                <input type="text" class="form-control" id="inputText" placeholder="Search by Batch name or Course name" name="name"/>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-md-6 d-flex">--}}
{{--                                            <div class="filter-group mx-2">--}}
{{--                                                <span>--}}
{{--                                                    <img src="{{url('icons/filter-icon.svg')}}" alt="" class="img-flud">--}}
{{--                                                </span>--}}
{{--                                                <button class="fltr-btn" type="submit">Filter</button>--}}
{{--                                            </div>--}}
{{--                                            <div class="refresh-group mx-2">--}}
{{--                                                <a onclick="getReset('{{Request::segment(1)}}')">--}}
{{--                                                    <img src="{{url('icons/refresh-top-icon.svg')}}" alt="" class="img-flud">--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        {!! Form::close() !!}--}}
                        <div class="mt-1">
                            @include('success.success')
                            @include('errors.error')
                        </div>
                        <div class="col-sm-12 col-md-12 stretch-card mt-4">
                            <div class="card-wrap form-block p-0">
                                <div class="block-header bg-header d-flex justify-content-between p-4">
                                    <div class="d-flex flex-column">
                                        <h3>Assigned Zoom Links Table</h3>
                                    </div>
                                    @if(Auth::user()->customMenuPermission('create_zoom_link_batches'))
                                        <div class="add-button">
                                            <a class="nav-link" href="{{url('zoom-links-batch/create')}}"><i class="fa-solid fa-book-open"></i>&nbsp;Assign Zoom Link</a>
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
                                                            <th>Batch</th>
                                                            <th>Course</th>
                                                            <th>Link</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="student_list">
                                                        @foreach($settings as $setting)
                                                            <tr>
                                                                <td>{{$loop->iteration}}</td>
                                                                <td>{{$setting->batch->name}}</td>
                                                                <td>{{$setting->batch->time_slot->course->name}}</td>
                                                                <td><a href="{{url($setting->zoomLink->link)}}" target="_blank" role="button" data-bs-toggle="tooltip" data-bs-title="view zoom link">{{$setting->zoomLink->name}}</a></td>
                                                                <td class="action-icons">
                                                                    <ul class="icon-button d-flex">
                                                                        @if(Auth::user()->customMenuPermission('show_zoom_link_batches'))
                                                                            <li>
                                                                                <a class="dropdown-item"  href="{{url('zoom-links-batch/show/'.$setting->id)}}" role="button" data-bs-toggle="tooltip" data-bs-title="view"><i class="fa-solid fa-eye"></i></a>
                                                                            </li>
                                                                        @endif
                                                                        @if(Auth::user()->customMenuPermission('update_zoom_link_batches'))
                                                                            <li>
                                                                                <a class="dropdown-item"  href="{{url('zoom-links-batch/'.$setting->id.'/edit')}}" role="button" data-bs-toggle="tooltip" data-bs-title="edit"><i class="fa-solid fa-pen"></i></a>
                                                                            </li>
                                                                        @endif
                                                                        @if(Auth::user()->customMenuPermission('delete_zoom_link_batches'))
                                                                            <li>
                                                                                <a class="dropdown-item"   role="button" onclick="myConfirm({{$setting->id}})" data-bs-toggle="tooltip" data-bs-title="delete"><i class="fa-solid fa-trash"></i></a>
    {{--                                                                            <a class="dropdown-item"  href="{{url('zoom-links-batch/'.$setting->id.'/edit')}}" role="button" data-bs-toggle="tooltip" data-bs-title="edit"><i class="fa-solid fa-pen"></i></a>--}}
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
                            window.location = Laravel.url+'/zoom_links_batch/delete/'+id;
                        }
                    },
                    close: function () {
                    }
                }
            });
        }

    </script>
@endsection
