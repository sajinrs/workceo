
<div class="row m-t-30">
    @foreach($project->files as $file)
        <div class="col-md-2 m-b-10">
            <div class="card">
                    <div class="file-bg">
                        <div class="overlay-file-box">
                            <div class="user-content">
                                @if($file->icon == 'images')
                                <img class="card-img-top img-responsive" src="{{ $file->file_url }}" alt="Card image cap">
                                @else
                                    <i class="f-60 m-t-50 fa {{$file->icon}}"></i>
                                @endif
                            </div>
                        </div>
                    </div>

                <div class="card-block p-l-5 p-r-5 p-t-10 p-b-10">
                    <div class="f-12 p-b-10">{{ $file->filename }}</div>
                    <div class="text-center clearfix">
                    <a target="_blank" href="{{ $file->file_url }}" data-toggle="tooltip" data-original-title="View" class="btn btn-small badge-info"><i class="fa fa-search m-r-0"></i></a>
                   
                    <a href="{{ route('admin.files.download', $file->id) }}" data-toggle="tooltip" data-original-title="Download" class="btn btn-small badge-dark"><i class="fa fa-download"></i></a>
                   
                    <a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-file-id="{{ $file->id }}" class="btn btn-small badge-danger sa-params" data-pk="thumbnail"><i class="fa fa-times"></i></a>
</div>

                </div>
            </div>
        </div>
    @endforeach
</div>


