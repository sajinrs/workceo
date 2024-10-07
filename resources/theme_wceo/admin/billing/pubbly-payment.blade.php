@extends('layouts.popup')

@section('content')
    <div class="row">
        <!-- Zero Configuration  Starts-->
        <div class="col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($success))
                        <div class="alert alert-success">{{$success}}</div>
                    @elseif(isset($error))
                        <div class="alert alert-danger">{{$error}}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection


