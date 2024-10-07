@extends('layouts.app')

@section('page-title')
  <div class="col-md-12">
        <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3><i class="{{ $pageIcon }}"></i> {{ __($pageTitle) }}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a  href="{{ route('admin.dashboard') }}">@lang('app.menu.home')</a></li>
                             @if(!$roles->isEmpty())
                              <li class="breadcrumb-item">
                <a href="javascript:;" id="addRole">@lang("modules.roles.addRole")</a>
                         </li>
            @endif

                            <li class="breadcrumb-item active">{{ __($pageTitle) }}</li>
                        </ol>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
@endsection

@section('content')
 <div class="container-fluid">
   <div class="row">
        <div class="col-md-3">
        @include('sections.admin_setting_menu')
       </div>
        <div class="col-md-9">
                <div class="card">
                    <div class="panel panel-inverse">
                        <div class="card-header">
                            <h5>{{ __($pageTitle) }} </h5>
                        </div>
                     <div  class="card-body">
             
                 <div class="form-body" >
                        <div class="vtabs customvtab m-t-10">


                    <div class="tab-content">
                        <div id="vhome3" class="tab-pane active">  

   <div class="row">
                                <div class="col-sm-12 col-xs-12">


                    @forelse($roles as $role)
                        <div class="col-md-12 b-all m-t-10">
                            <div class="row">
                                <div class="col-md-2 text-center p-10 bg-primary ">
                                    <h5 class="text-white"><span id="role_display_name">{{ ucwords($role->display_name) }}</span></h5>
                                </div>
                                <div class="col-md-6 text-center bg-primary role-members">
                                    <button class="btn btn-xs btn-danger btn-rounded show-members m-t-15" data-role-id="{{ $role->id }}"><i class="fa fa-users"></i> {{ count($role->roleuser)  }} Member(s)</button>
                                </div>
                                <div class="col-md-4 p-10 bg-primary" style="padding-bottom: 11px !important;">
                                    <button class="btn btn-pill btn-light pull-right toggle-permission" data-role-id="{{ $role->id }}"><i class="fa fa-key"></i> Permissions</button>
                                </div>


                                <div class="col-md-12 b-t permission-section" style="display: none;" id="role-permission-{{ $role->id }}" >
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr class="bg-white">
                                            <th>
                                                <div class="form-group">
                                                    <div class="checkbox checkbox-info pl-0 col-md-10">
                                                        <input id="select_all_permission_{{ $role->id }}"
                                                               @if(count($role->permissions) == $totalPermissions) checked @endif
                                                               class="select_all_permission" value="{{ $role->id }}" type="checkbox">
                                                        <label for="select_all_permission_{{ $role->id }}">@lang('modules.permission.selectAll')</label>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>@lang('app.add')</th>
                                            <th>@lang('app.view')</th>
                                            <th>@lang('app.update')</th>
                                            <th>@lang('app.delete')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($modulesData as $moduleData)
                                                @if($moduleData->module_name != 'messages')
                                                    <tr>
                                                        <td>@lang('modules.module.'.$moduleData->module_name)

                                                        @if($moduleData->description != '')
                                                            <a class="mytooltip" href="javascript:void(0)" data-trigger="hover" data-placement="top" data-content="{{ $moduleData->description  }}"> <i class="fa fa-info-circle"></i></a>
                                                        @endif
                                                        </td>

                                                        @foreach($moduleData->permissions as $permission)
                                                            <td>
                                                            <div class="switch-showcase icon-state">
                                                                    <label class="switch">
                                                                        <input type="checkbox" class="assign-role-permission permission_{{ $role->id }}"
                                                                                @if($role->hasPermission([$permission->name]))
                                                                                        checked
                                                                                @endif
                                                                                data-permission-id="{{ $permission->id }}" data-role-id="{{ $role->id }}" />
                                                                        <span class="switch-state"></span>
                                                                    </label>
                                                                </div>    

                                                                
                                                            </td>
                                                        @endforeach

                                                        @if(count($moduleData->permissions) < 4)
                                                            @for($i=1; $i<=(4-count($moduleData->permissions)); $i++)
                                                                <td>&nbsp;</td>
                                                            @endfor
                                                        @endif

                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        @empty

                            <div class="text-center">
                                <div class="empty-space" style="height: 200px;">
                                    <div class="empty-space-inner">
                                        <div class="icon" style="font-size:30px"><i
                                                    class="ti-lock"></i>
                                        </div>
                                        <div class="title m-b-15">@lang('messages.defaultRolesCantDelete')
                                        </div>
                                        <div class="subtitle">
                                            <a href="javascript:;" id="addRole"
                                               class="btn btn-success btn-sm btn-outline  waves-effect waves-light"><i
                                                        class="fa fa-gear"></i> @lang("modules.roles.addRole")</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforelse

                </div>
            </div>
        </div>
    </div>
     </div>
    </div> </div>
    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-md in" id="projectCategoryModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->.
    </div>
    {{--Ajax Modal Ends--}}

@endsection

@push('footer-script')
<script>
    $(function () {
        $('.assign-role-permission').on('change', assignRollPermission);
    });

    $('.toggle-permission').click(function () {
        var roleId = $(this).data('role-id');
        $('#role-permission-'+roleId).toggle();
    })


    // Switchery
    /* var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    $('.js-switch').each(function() {
        new Switchery($(this)[0], $(this).data());

    }); */

    // Initialize multiple switches
    var animating = false;
    var masteranimate = false;

//    if (Array.prototype.forEach) {
//        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
//        elems.forEach(function() {
//            var switcherys = new Switchery($(this)[0], $(this).data());
//        });
//    }
//    else {
//        var elems = document.querySelectorAll('.js-switch');
//        for (var i = 0; i < elems.length; i++) {
//            var switcherys = new Switchery(elems[i]);
//        }
//    }

    var assignRollPermission = function () {

        var roleId = $(this).data('role-id');
        var permissionId = $(this).data('permission-id');

        if($(this).is(':checked'))
            var assignPermission = 'yes';
        else
            var assignPermission = 'no';

        var url = '{{route('admin.role-permission.store')}}';

        $.easyAjax({
            url: url,
            type: "POST",
            data: { 'roleId': roleId, 'permissionId': permissionId, 'assignPermission': assignPermission, '_token': '{{ csrf_token() }}' }
        })
    };

    $('.assign-role-permission').change(assignRollPermission());

    $('.select_all_permission').change(function () {
        if($(this).is(':checked')){
            var roleId = $(this).val();
            var url = '{{ route('admin.role-permission.assignAllPermission') }}';

            $.easyAjax({
                url: url,
                type: "POST",
                data: { 'roleId': roleId, '_token': '{{ csrf_token() }}' },
                success: function () {
                    masteranimate = true;
                    if (!animating){
                        var masterStatus = true;
                        $('.assign-role-permission').off('change');
                        $('input.permission_'+roleId).each(function(index){
                            var switchStatus = $('input.permission_'+roleId)[index].checked;
                            if(switchStatus != masterStatus){

                                $(this).trigger('click');


                            }
                            // $('.assign-role-permission').on('change');
                        });
                        $('.assign-role-permission').on('change', assignRollPermission);
                    }
                    masteranimate = false;
                }
            })
        }
        else{
            var roleId = $(this).val();
            var url = '{{ route('admin.role-permission.removeAllPermission') }}';

            $.easyAjax({
                url: url,
                type: "POST",
                data: { 'roleId': roleId, '_token': '{{ csrf_token() }}' },
                success: function () {
                    masteranimate = true;
                    if (!animating){
                        var masterStatus = false;
                        $('.assign-role-permission').off('change');
                        $('input.permission_'+roleId).each(function(index){
                            var switchStatus = $('input.permission_'+roleId)[index].checked;
                            if(switchStatus != masterStatus){
                                $(this).trigger('click');
                            }
                        });
                        $('.assign-role-permission').on('change', assignRollPermission);
                    }
                    masteranimate = false;
                }
            })
        }
    })

    $('.show-members').click(function () {
        var id = $(this).data('role-id');
        var url = '{{ route('admin.role-permission.showMembers', ':id')}}';
        url = url.replace(':id', id);

        $('#modelHeading').html('Role Members');
        $.ajaxModal('#projectCategoryModal', url);
    })

    $('#addRole').click(function () {
        var url = '{{ route('admin.role-permission.create')}}';

        $('#modelHeading').html('Role Members');
        $.ajaxModal('#projectCategoryModal', url);
    });

    $(function () {
    $('.mytooltip').popover({
        container: 'body'
    });
    var dcolor = $(".mytooltip").attr("data-theme");
    if(dcolor == "dark") {
        $(".mytooltip").addClass("bg-dark");
    }
})

</script>
@endpush

