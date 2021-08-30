@extends('merchant.layouts.app')

@section('page-title') Compliance @stop

@section('breadcrumb1')/&nbsp; Settings @stop
@section('breadcrumb2')/&nbsp; Compliance @stop

@section('page-styles')
    <style>
        .upload-btn {
            /*position: absolute;*/
            overflow: hidden;
            cursor: pointer;
        }

        .upload-btn input:hover {
            cursor: pointer;
        }
        input#file-upload-button {
            cursor: pointer;
        }

        .upload-btn input[type=file] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            width: 3.3rem;
            height: 3.3rem;
        }

        .documents label {
            min-height: 6rem;
            padding: 0;
        }

        .card .card-body{
            padding: 40px 20px;
        }
        .card-body p {
            margin-bottom: 0;
        }
        #add_director, #add_beneficial_owner {
            display: none;
        }
        .remove-icon {
            position: absolute;
            right: 23px;
            margin-top: -47px;
            z-index: 5;
            cursor: pointer;
        }
    </style>
@stop

@section('main_content')
    <ul class="nav nav-tabs px-4" role="tablist" style="margin-bottom: 2rem;">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('settings.profile') }}" role="tab">Profile</a>
        </li>
        @role('MERCHANT|MERCHANT_ADMIN')
            <li class="nav-item">
                <a class="nav-link" href="{{route('settings.business')}}" role="tab">Business</a>
            </li>
        @endrole
        <li class="nav-item">
            <a class="nav-link active" href="#" role="tab">Compliance</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('settings.password')}}" role="tab">Reset Password</a>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-6 col-sm-6 grid-margin stretch-card">
            <div class="card" id="add_director">
                <div class="card-body">
                    <h5 class="text-center">Add Director</h5>
                    <form class="forms-sample" method="POST" action="{{route('settings.add.director')}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="number"
                                   class="form-control"
                                   id="phone"
                                   name="phone">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                        <a href="javascript:void(0)" onclick="hideAddForm(this, 'director')" class="btn btn-light mr-2">Cancel</a>
                        {{--                    <button class="btn btn-light">Cancel</button>--}}
                    </form>
                </div>
            </div>

            <div class="card" id="directors">
                <div class="card-body">
                    <h5 class="text-center">Directors</h5>
                    @if(!is_null($business->directors) && count($business->directors) > 0)
                        @foreach($business->directors as $director)
                            <div class="mb-3" style="border-bottom: 1px solid #f1f1f1">
                                <p><b>Name:</b> {{$director->name}}</p>
                                <p><b>Email:</b> {{$director->email}}</p>
                                <p><b>Phone Number:</b> {{$director->phone}}</p>
                                <a href="{{ route('settings.delete.compliance', ['email' => $director->email, 'compliance' => 'director']) }}"
                                   title="Delete" class="text-danger remove-icon"><i data-feather="slash"></i></a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-danger">No directors added yet</p>
                    @endif
                    <div class="text-center mt-3">
                        <button class="btn btn-primary" onclick="showAddForm(this, 'director')">Add Director</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 grid-margin stretch-card">
            <div class="card" id="add_beneficial_owner">
                <div class="card-body">
                    <h5 class="text-center">Add Beneficial Owner</h5>
                    <form class="forms-sample" method="POST" action="{{route('settings.add.beneficial')}}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label for="firstname">Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="number"
                                   class="form-control"
                                   id="phone"
                                   name="phone">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                        <a href="javascript:void(0)" onclick="hideAddForm(this, 'beneficial')" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
            <div class="card" id="beneficial_owners">
                <div class="card-body">
                    <h5 class="text-center">Beneficial Owners</h5>
                    @if(!is_null($business->beneficial_owners) && count($business->beneficial_owners) > 0)
                        @foreach($business->beneficial_owners as $beneficial_owner)
                            <div class="mb-3" style="border-bottom: 1px solid #f1f1f1">
                                <p><b>Name:</b> {{$beneficial_owner->name}}</p>
                                <p><b>Email:</b> {{$beneficial_owner->email}}</p>
                                <p><b>Phone Number:</b> {{$beneficial_owner->phone}}</p>
                                <a href="{{ route('settings.delete.compliance', ['email' => $beneficial_owner->email, 'compliance' => 'beneficial_owner']) }}"
                                    title="Delete" class="text-danger remove-icon"><i data-feather="slash"></i></a>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-danger">No Beneficial Owners added yet</p>
                    @endif
                    <div class="text-center mt-3">
                        <button class="btn btn-primary" onclick="showAddForm(this, 'beneficial')">Add Beneficial Owner</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5 documents">
        <div class="col-md-12 mb-3">
            <h4 class="text-center"> My Documents </h4>
            <p class="text-center text-danger">*File size should not exceed 2MB</p>
        </div>
        <div class="col-md-4 col-sm-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('settings.documents.add') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group" style="text-align: center;">
                            <label for="certificate_of_incorporation">Certificate of Incorporation</label>
                            <div class="upload-btn">
                                <input data-original-title="Upload"
                                       title="Upload"
                                       type="file"
                                       id="certificate_of_incorporation"
                                       name="certificate_of_incorporation"
                                       onchange="showUploadFile(this)"
                                       accept="application/pdf">
                                <img width="50" src="{{ asset('assets/dashboard/images/pdf.png') }}" alt="">
                                @if(is_null($business->certificate_of_incorporation))
                                    <p class="mt-5 text-danger">Not uploaded</p>
                                @else
                                    <div style="margin-top: 2.2rem">
                                        <a target="_blank" href="{{ url('storage/'.$business->certificate_of_incorporation) }}" class="btn btn-primary">View</a>
                                        <a onclick="showLoader(this)" href="{{ route('settings.documents.delete', ['file' => 'certificate_of_incorporation'])}}" class="btn btn-danger">Delete</a>
                                    </div>
                                @endif
                                <div class="mt-4" style="display: none">
                                    <button type="submit" class="btn btn-primary" onclick="showLoader(this)">Save</button>
                                    <button class="btn btn-warning" onclick="removeImage(this)" type="button">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('settings.documents.add') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group" style="text-align: center;">
                            <label for="cac_form"> CAC Form</label>
                            <div class="upload-btn">
                                <input data-original-title="Upload"
                                       title="Upload"
                                       type="file"
                                       id="cac_form"
                                       name="cac_form"
                                       onchange="showUploadFile(this)"
                                       accept="application/pdf">
                                <img width="50" src="{{ asset('assets/dashboard/images/pdf.png') }}" alt="">
                                @if(is_null($business->cac_form))
                                    <p class="mt-5 text-danger">Not uploaded</p>
                                @else
                                    <div style="margin-top: 2.2rem">
                                        <a target="_blank" href="{{ url('storage/'.$business->cac_form) }}" class="btn btn-primary">View</a>
                                        <a onclick="showLoader(this)" href="{{ route('settings.documents.delete', ['file' => 'cac_form'])}}" class="btn btn-danger">Delete</a>
                                    </div>
                                @endif
                                <div class="mt-4" style="display: none">
                                    <button type="submit" class="btn btn-primary" onclick="showLoader(this)">Save</button>
                                    <button class="btn btn-warning" onclick="removeImage(this)" type="button">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('settings.documents.add') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group" style="text-align: center;">
                            <label for="articles_of_association">Memorandum / Articles of Association</label>
                            <div class="upload-btn">
                                <input data-original-title="Upload"
                                       title="Upload"
                                       type="file"
                                       id="articles_of_association"
                                       name="articles_of_association"
                                       onchange="showUploadFile(this)"
                                       accept="application/pdf">
                                <img width="50" src="{{ asset('assets/dashboard/images/pdf.png') }}" alt="">
                                @if(is_null($business->articles_of_association))
                                    <p class="mt-5 text-danger">Not uploaded</p>
                                @else
                                    <div style="margin-top: 2.2rem">
                                        <a target="_blank" href="{{ url('storage/'.$business->articles_of_association) }}" class="btn btn-primary">View</a>
                                        <a onclick="showLoader(this)" href="{{ route('settings.documents.delete', ['file' => 'articles_of_association'])}}" class="btn btn-danger">Delete</a>
                                    </div>
                                @endif
                                <div class="mt-4" style="display: none">
                                    <button onclick="showLoader(this)" type="submit" class="btn btn-primary">Save</button>
                                    <button class="btn btn-warning" onclick="removeImage(this)" type="button">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('settings.documents.add') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group" style="text-align: center;">
                            <label for="other_document">Other</label>
                            <div class="upload-btn">
                                <input data-original-title="Upload"
                                       title="Upload"
                                       type="file"
                                       id="other_document"
                                       name="other_document"
                                       onchange="showUploadFile(this)"
                                       accept="application/pdf">
                                <img width="50" src="{{ asset('assets/dashboard/images/pdf.png') }}" alt="">
                                @if(is_null($business->other_document))
                                    <p class="mt-5 text-danger">Not uploaded</p>
                                @else
                                    <div style="margin-top: 2.2rem">
                                        <a target="_blank" href="{{ url('storage/'.$business->other_document) }}" class="btn btn-primary">View</a>
                                        <a onclick="showLoader(this)" href="{{ route('settings.documents.delete', ['file' => 'other_document'])}}" class="btn btn-danger">Delete</a>
                                    </div>
                                @endif
                                <div class="mt-4" style="display: none">
                                    <button onclick="showLoader(this)" type="submit" class="btn btn-primary">Save</button>
                                    <button class="btn btn-warning" onclick="removeImage(this)" type="button">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-scripts')
    <script>
        const showUploadFile = (element) => {
            // Get html elements
            let fileName = element.files[0].name;
            let textElement = element.parentNode.childNodes[5];
            let buttons = element.parentNode.childNodes[7];

            // Change text to file name
            textElement.innerHTML = fileName;

            // Remove classes
            textElement.classList.remove('text-danger', 'mt-5');

            // Display save and cancel buttons
            buttons.style.display = 'block';
        };

        const removeImage = (element) => {
            // Get html elements
            let parentElement = element.parentNode.parentNode;
            let textElement = parentElement.childNodes[5];
            let buttons = parentElement.childNodes[7];
            let input = parentElement.childNodes[1];

            // Reset input
            $(input).replaceWith($(input).val('').clone(true));

            // Change text
            textElement.innerHTML += ' <b>(Not Saved)</b>';

            // Add classes
            textElement.classList.add('text-danger', 'mt-5');

            // Hide save and cancel buttons
            buttons.style.display = 'none';
        };

        const showLoader = (element) => {
            if(element.innerHTML === 'Delete')
                element.innerHTML = 'Deleting...';
            else
                element.innerHTML = 'Uploading...';
            element.insertAdjacentHTML( 'beforeend',' <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        };

        const showAddForm = (element, mode) => {
            let form;
            if (mode === 'director') {
                form = $('#add_director');
                $('#directors').hide();
                form.show();
            }
            if (mode === 'beneficial') {
                form = $('#add_beneficial_owner');
                $('#beneficial_owners').hide();
                form.show();
            }
        };

        const hideAddForm = (element, mode) => {
            let form;
            if (mode === 'director') {
                form = $('#add_director');
                $('#directors').show();
                form.hide();
            }
            if (mode === 'beneficial') {
                form = $('#add_beneficial_owner');
                $('#beneficial_owners').show();
                form.hide();
            }
        };
    </script>
@stop
