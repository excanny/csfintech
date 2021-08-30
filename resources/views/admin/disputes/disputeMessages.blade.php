@extends('admin.layouts.app')

@section('page-title') Disputes @stop

@section('breadcrumb1')/&nbsp; Disputes @stop

@section('page-styles')
    <style>
        .message {
            /*background: rgba(255, 231, 245, 0.13);*/
            border-radius: 5px;
            /*padding: 0.625rem;*/
            width: 100%;
            min-height: 60vh;
            max-height: 60vh;
            position: relative;
            overflow-y: auto;
            /*margin-bottom: 4rem;*/
        }

        input {
            width: 100%;
            height: 42px;
            padding-left: 0.625rem;
            /* border-radius: 25px; */
            border: 1px solid #d2d2d2;
            outline: none;
            background: #fff;
        }
        ::placeholder {
            text-align: center;
        }

        .chat_text {
            padding: 1.25rem;
            line-height: 1.2em;
            /*color: black;*/
        }

        .chat-bubble {
            position: relative;
            max-width: 50%;
            min-width: 30%;
            /*height: 30%;*/
            padding: 0px;
            background: #003399;
            color: #fff;
            border-radius: 0.6875rem;
        }

        .chat-bubble:after {
            content: "";
            position: absolute;
            border-style: solid;
            border-width: 41px 11px 0;
            border-color: #003399 transparent;
            display: block;
            width: 0;
            z-index: 1;
            bottom: -1.7625rem;
            left: 2%;
        }

        .chatuser {
            position: relative;
            max-width: 50%;
            min-width: 30%;
            /*height: 30%;*/
            padding: 0px;
            border-radius: 0.6875rem;
            background: #8e8e8e;
        }

        .chatuser:after {
            content: "";
            position: absolute;
            border-style: solid;
            border-width: 41px 10px 0;
            border-color: #8e8e8e transparent;
            display: block;
            width: 0;
            z-index: 1;
            bottom: -1.7625rem;
            left: 87%;
        }

        .btn {
            border: none;
            color: #ffffff;
            border-radius: 0;
            position: absolute;
            right: 0;
            /* top: 0.05rem; */
            height: 100%;
            /* width: 1.25rem; */
            outline: none;
            padding: 0.425rem 1rem;
        }

        .btn:hover {
            color: #ffffff;
        }

        .buttoninside {
            position: absolute;
            bottom: 0%;
            right: 0;
            left: 0;
            margin: auto;
            /* width: 92.5%; */
            z-index: 1;
        }

        .time {
            float:right;
        }

        @media only screen and (max-width:990px) and (min-width:601px) {
            .chatuser:after {
                left: 91%;
            }
            .chatuser {
                width:71%;
            }
            .chat-bubble {
                width:71%;
            }
        }

        @media only screen and (max-width:606px) and (min-width:451px) {
            .chatuser:after {
                left: 88%;
            }
            .chatuser {
                width:71%;
            }
            .chat-bubble {
                width:71%;
            }
        }

        @media only screen and (max-width:450px) {
            .chatuser:after {
                left: 86%;
            }
            .chatuser {
                width:71%;
            }
            .chat-bubble {
                width:71%;
            }
        }
        #disputeContent {
            /*max-height: 50vh;*/
        }

        .chat-image {
            max-width: 65%;
        }

        .upload-btn {
            position: absolute;
            overflow: hidden;
            top: 0.5rem;
            left: -1.8rem;
            font-size: 20px;
            display: inline-block;
            cursor: pointer;
            border-radius: 25px;
        }

        .upload-btn:hover {
            background: #fbdadf75;
        }

        .upload-btn input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }

        #cancel-upload-btn {
            position: absolute;
            right: 6rem;
            top: 0.9rem;
            font-weight: 800;
            color: black;
            display: none;
            cursor: pointer;
        }

        .img-wrapper {
            text-align: center;
            width: 16.5rem;
        }
    </style>
@stop

@section('main_content')
    <div class="row" id="disputeContent">
        <div class="col-md-12">
            <a class="btn btn-primary" href="{{ route('admin.disputes') }}"
               style="float:right;top:-3.9rem;height:fit-content">
                Back
            </a>
            <div class="card">
                <div class="card-body">
                    <div>
                        <p class="card-title text-center">{{$dispute->subject}}</p>
                    </div>

                    <div class="col pr-0 cha/partials/bottom-nav.blade.phpt-right-aside">
                        <!-- chat start-->
                        <div class="chat p-b-50">
                            <div class="message" id="messages">
                                @foreach($dispute->messages as $message)
                                    <div>
                                        @if(!is_null($message['admin_id']) && $message['admin_id'] === auth()->user()->id)
                                            <div class="row mt-3 mr-2 mb-5">
                                                <div class="chatuser ml-auto">
                                                    <div class="chat_text">
                                                        <p><b>{{ $message->admin->firstname }} {{ $message->admin->lastname }}</b>
                                                            <span class="time">{{\Carbon\Carbon::parse($message->created_at)->diffForHumans()}}</span>
                                                            <br>
                                                            @if($message->type == \App\Model\DisputeMessage::$text)
                                                            {{ $message->text }}
                                                            @elseif($message->type == \App\Model\DisputeMessage::$image)
                                                                <a title="Click to view" href="{{url($message->image_url)}}" target="_blank">
                                                                    <div class="img-wrapper">
                                                                        <img class="chat-image" src="{{ asset($message->image_url) }}" alt="">
                                                                    </div>
                                                                </a>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row mt-3 ml-2 mb-5">
                                                <div class="chat-bubble">
                                                    <div class="chat_text">
                                                        <p>
                                                           @if(is_null($message->admin_id))
                                                                <b>{{ $dispute->business->name }}</b>
                                                            @else
                                                                <b>{{ $message->admin->firstname }} {{ $message->admin->lastname }}</b>
                                                            @endif
                                                            <span class="time">{{\Carbon\Carbon::parse($message->created_at)->diffForHumans()}}</span>
                                                            <br>
                                                               @if($message->type == \App\Model\DisputeMessage::$text)
                                                                   {{ $message->text }}
                                                               @elseif($message->type == \App\Model\DisputeMessage::$image)
                                                                   <a title="Click to view" href="{{url($message->image_url)}}" target="_blank">
                                                                       <div class="img-wrapper">
                                                                           <img class="chat-image" src="{{ asset($message->image_url) }}" alt="">
                                                                       </div>
                                                                   </a>
                                                               @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($dispute->status === 0)
                            <form method="post"
                                  action="{{ route('dispute.reply', $dispute->id) }}"
                                  id="send-button"
                                  enctype="multipart/form-data"
                                  class="buttoninside">
                                @csrf
                                <div class="upload-btn">
                                    <input data-original-title=""
                                           title="Upload Image"
                                           type="file"
                                           id="image"
                                           name="image"
                                           onchange="showUploadFile()"
                                           accept="image/jpeg"><i class="icon-clip"></i>
                                </div>
                                <input type="text" id="message" placeholder="Type Message..." required name="message"/>
                                <i class="icon-close"
                                   title="Remove Image"
                                   onclick="removeImage()"
                                   id="cancel-upload-btn"></i>
                                <button class="btn btn-primary" type="submit" id="submit-button">Send</button>
                            </form>
                            @else
                            <p class="f-18 text-center">This dispute has been closed</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('page-scripts')
    <script>
        window.onload=function () {
            let objDiv = document.getElementById("messages");
            let disputeContent = document.getElementById("disputeContent");
            disputeContent.scrollTop = disputeContent.scrollHeight;
            objDiv.scrollTop = objDiv.scrollHeight;
            // $("form").submit(function(e){
            //     e.preventDefault();
            // });
        };
        const showUploadFile = () => {
            // Get html elements
            let new_image = document.getElementById("image").files[0].name;
            let message = document.getElementById("message");
            let submitButton = document.getElementById("submit-button");
            let cancelButton = document.getElementById("cancel-upload-btn");

            message.value = '';
            // Change placeholder text to file name
            message.placeholder = new_image;

            // Make to the input field read-only
            message.setAttribute('readonly', true);

            // Change input background
            message.style.background = '#d2d2d2';

            // Toggle submit button text to upload
            submitButton.innerHTML = 'Upload';

            // Show cancel button
            cancelButton.style.display = 'block';
        };

        const removeImage = () => {
            // Get html elements
            let input = $('#image');
            let message = document.getElementById("message");
            let cancelButton = document.getElementById("cancel-upload-btn");
            let submitButton = document.getElementById("submit-button");

            // Reset input
            input.replaceWith(input.val('').clone(true));

            // Make the input field editable
            message.removeAttribute('readonly');

            // Hide cancel button
            cancelButton.style.display = 'none';

            // Toggle submit button text to send
            submitButton.innerHTML = 'Send';

            // Toggle input background
            message.style.background = '#fff';

            // Toggle placeholder text
            message.placeholder = 'Type Message...';
        };

        // const validateForm = () => {
        //     let message = document.getElementById("message").value;
        //     let image = document.getElementById("image").files;
        //
        //     if(message === '' && image.length === 0) {
        //         return;
        //     }
        //
        //     $("#send-button").submit();
        // }
    </script>
@stop
