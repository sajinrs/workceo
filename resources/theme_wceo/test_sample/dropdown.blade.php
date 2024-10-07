@extends('layouts.test')
@push('head-script')


@endpush

@section('content')
<!-- Container-fluid starts-->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Basic Color Dropdown</h5>
                </div>
                <div class="card-body dropdown-basic">

                    <div class="dropdown">
                        <div class="btn-group mb-0">
                            <button class="dropbtn btn-primary" type="button">Action <span><i class="icofont icofont-arrow-down"></i></span></button>
                            <div class="dropdown-content"><a href="#">Action</a><a href="#">Another Action</a><a href="#">Something Else Here</a>
                                <div class="dropdown-divider"></div><a href="#">Separated Link </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Container-fluid Ends-->
@endsection

@push('footer-script')
@endpush