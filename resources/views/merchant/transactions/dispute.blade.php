@extends('merchant.layouts.app')

@section('page-title') Dispute Transaction @stop

@section('breadcrumb1')/&nbsp; Transactions @stop
@section('breadcrumb2')/&nbsp; Dispute @stop

<style>
    .important:after {
        content: ' *';
        color: red;
    }
</style>

@section('main_content')
    <div class="row">
        <div class="col-md-6 col-sm-6 grid-margin stretch-card" style="margin: auto">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" method="POST" action="{{route('merchant.dispute.log')}}">
                        @csrf
                        <div class="form-group">
                            <label for="subject" class="important">Subject</label>
                            <input type="text"
                                   class="form-control"
                                   id="subject" readonly
                                   value="Dispute Log: {{ $ref }}"
                                   name="subject"
                                   >
                        </div>
                        <div class="form-group">
                            <label for="text" class="important">Message</label>
                            <textarea type="text"
                                      class="form-control"
                                      id="text"
                                      name="text"
                                      placeholder="Enter message here..."
                                      required>
                            </textarea>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
