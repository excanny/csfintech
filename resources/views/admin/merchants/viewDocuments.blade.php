@extends('admin.layouts.app')

@section('page-title') Documents @stop

@section('breadcrumb1')<li class="breadcrumb-item"><a href="{{route('business.view', $business->id)}}">View Business</a></li> @stop
@section('breadcrumb2')/&nbsp; View Compliance Documents @stop

@section('page-styles')
    <style>
        .card label {
            min-height: 6rem;
            padding: 0;
        }

        .card .card-body{
            padding: 40px 20px;
        }
        .card-body p {
            margin-bottom: 0;
        }
    </style>
@stop

@section('main_content')
    <div class="row">
        <div class="col-md-6 col-sm-6 grid-margin stretch-card">
            <div class="card" id="directors">
                <div class="card-body">
                    <h5 class="text-center">Directors</h5>
                    @if(!is_null($business->directors) && count($business->directors) > 0)
                        @foreach($business->directors as $director)
                            <div class="mb-3" style="border-bottom: 1px solid #f1f1f1">
                                <p><b>Name:</b> {{$director->name}}</p>
                                <p><b>Email:</b> {{$director->email}}</p>
                                <p><b>Phone Number:</b> {{$director->phone}}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-danger">No directors added yet</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 grid-margin stretch-card">
            <div class="card" id="beneficial_owners">
                <div class="card-body">
                    <h5 class="text-center">Beneficial Owners</h5>
                    @if(!is_null($business->beneficial_owners) && count($business->beneficial_owners) > 0)
                        @foreach($business->beneficial_owners as $beneficial_owner)
                            <div class="mb-3" style="border-bottom: 1px solid #f1f1f1">
                                <p><b>Name:</b> {{$beneficial_owner->name}}</p>
                                <p><b>Email:</b> {{$beneficial_owner->email}}</p>
                                <p><b>Phone Number:</b> {{$beneficial_owner->phone}}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-danger">No Beneficial Owners added yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div style="text-align: center;">
                        <label for="business_incorporation_cert">Certificate of Incorporation</label>
                        <div>
                            <img width="50" src="{{ asset('assets/dashboard/images/pdf.png') }}" alt="">
                            @if(is_null($business->certificate_of_incorporation))
                                <p class="mt-5 text-danger">Not uploaded</p>
                                @else
                                <div style="margin-top: 2.2rem">
                                    <a target="_blank" href="{{ url('storage/'.$business->certificate_of_incorporation) }}" class="btn btn-primary">View</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div style="text-align: center;">
                        <label for="business_incorporation_cert">CAC Form</label>
                        <div>
                            <img width="50" src="{{ asset('assets/dashboard/images/pdf.png') }}" alt="">
                            @if(is_null($business->cac_form))
                                <p class="mt-5 text-danger">Not uploaded</p>
                                @else
                                <div style="margin-top: 2.2rem">
                                    <a target="_blank" href="{{ url('storage/'.$business->cac_form) }}" class="btn btn-primary">View</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div style="text-align: center;">
                        <label for="business_incorporation_cert">Memorandum / Articles of Association</label>
                        <div>
                            <img width="50" src="{{ asset('assets/dashboard/images/pdf.png') }}" alt="">
                            @if(is_null($business->articles_of_association))
                                <p class="mt-5 text-danger">Not uploaded</p>
                                @else
                                <div style="margin-top: 2.2rem">
                                    <a target="_blank" href="{{ url('storage/'.$business->articles_of_association) }}" class="btn btn-primary">View</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div style="text-align: center;">
                        <label for="business_incorporation_cert">Other</label>
                        <div>
                            <img width="50" src="{{ asset('assets/dashboard/images/pdf.png') }}" alt="">
                            @if(is_null($business->other_document))
                                <p class="mt-5 text-danger">Not uploaded</p>
                                @else
                                <div style="margin-top: 2.2rem">
                                    <a target="_blank" href="{{ url('storage/'.$business->other_document) }}" class="btn btn-primary">View</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
