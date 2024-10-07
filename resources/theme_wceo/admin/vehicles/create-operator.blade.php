
<div class="modal-header">
    <h5 class="modal-title">Add Operator</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

</div>
{!! Form::open(['id'=>'createOperator','class'=>'ajax-form','method'=>'POST']) !!}

<div class="modal-body">
    <div class="portlet-body">
         
        <div class="form-body">
            <div class="row">
                <div class="col-md-6">
                    <input type="hidden" name="id" id="operatorID" />
                    <div class="form-label-group form-group">      
                        <input type="text" name="first_name" id="first_name" class="form-control form-control-lg" placeholder="-" autocomplete="nope">  
                        <label for="first_name" class="required">First Name</label>  
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-label-group form-group">      
                        <input type="text" name="last_name" id="lname" class="form-control form-control-lg" placeholder="-" autocomplete="nope">  
                        <label for="lname" class="required">Last Name</label>  
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-label-group form-group">                                         
                        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="-" autocomplete="nope">
                        <label for="email" class="required">Email</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-label-group form-group">                                     
                        <input type="tel" name="mobile" id="mobile"  class="form-control form-control-lg" placeholder="-" autocomplete="nope">  
                        <label for="mobile" class="required">Mobile</label> 
                    </div>                         
                </div> 

                <div class="col-md-6"> 
                    <div class="form-label-group form-group">                                   
                        <select name="gender" id="gender" class="hide-search form-control form-control-lg" placeholder="-">
                            <option value="male">@lang('app.male')</option>
                            <option value="female">@lang('app.female')</option>
                            <option value="others">@lang('app.others')</option>
                        </select>  
                        <label for="gender" class="control-label">@lang('modules.employees.gender')</label>
                    </div>                                  
                </div>

                <div class="col-md-6">
                    <div class="form-label-group form-group">                                        
                        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="-" autocomplete="nope">
                        <label for="password" class="required">@lang('modules.employees.employeePassword')</label>
                        <span class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    </div>
                </div> 

                <div class="col-md-12">
                    <div class="form-actions" align="right">
                        <button type="button" id="save-operator" class="btn btn-primary"> @lang('app.save')</button>
                    </div>
                </div>

            </div>
        </div>

        <hr />
        {!! $dataTable->table(['class' => 'display']) !!}

    </div>
</div>

{!! Form::close() !!}

<script src="{{ asset('themes/wceo/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('themes/wceo/assets/js/datatable/datatable-extension/custom.js')}}"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
{!! $dataTable->scripts() !!}

<script>

$('body').on('click', '.sa-params', function () {
    var id = $(this).data('user-id');
    swal({

        title: "Are you sure?",
        text: "You will not be able to recover the deleted operator!",
        icon: "warning",
        buttons: ["No, cancel please!", "Yes, delete it!"],
        dangerMode: true
    })
    .then((willDelete) => {
        if (willDelete) {

            var url = "{{ route('admin.vehicles.destroy-operator',':id') }}";
            url = url.replace(':id', id);

            var token = "{{ csrf_token() }}";

            $.easyAjax({
                type: 'POST',
                url: url,
                data: {'_token': token, '_method': 'DELETE'},
                success: function (response) {
                    if (response.status == "success") {
                        $('#vehicles-table').DataTable().ajax.reload();
                        $.unblockUI();
                        var options = [];
                        var rData = [];
                        rData = response.data;
                        $.each(rData, function( index, value ) {
                            var selectData = '';
                            if(value.id != id){
                                selectData = '<option value="'+value.id+'">'+value.name+'('+value.email+')</option>';
                                options.push(selectData);
                            }
                            
                        });

                            $('#operator').html(options);
                            $('#operator').select2();
                        }
                }
            });
        }
    });
});
    

$('#save-operator').click(function () {
    var operatorID = $('#operatorID').val();
    if(operatorID)
    {
        updateOperator(operatorID);
        return false;
    } 
    
    $.easyAjax({
        url: '{{route('admin.vehicles.store-operator')}}',
        container: '#createOperator',
        type: "POST",
        data: $('#createOperator').serialize(),
        success: function (response) {
            if(response.status == 'success'){
                if(response.status == 'success'){
                    // console.log(response.data);
                    var options = [];
                    var rData = [];
                    rData = response.data;
                    $.each(rData, function( index, value ) {
                        var selectData = '';
                        if(rData.length === index+1)
                            selectData = '<option value="'+value.id+'">'+value.name+'('+value.email+')</option>';
                            options.push(selectData);
                    });

                    $('#operator').append(options);
                    $('#operator').select2();
                    $('#operatorModel').modal('hide');
                }
            }
        }
    })
});

//Edit
$('body').on('click', '.operator-edit', function () {
    var id = $(this).data('user-id');
    var url = "{{ route('admin.vehicles.edit-operator',':id') }}";
        url = url.replace(':id', id);

    $.easyAjax({
        type: 'GET',
        url: url,
        data:  {},
        container: "#createOperator",
        success: function (response) {
            //console.log(response);
            $('#operatorID').val(response.id);
            $('#first_name').val(response.first_name);
            $('#lname').val(response.last_name);
            $('#email').val(response.email);
            $('#mobile').val(response.mobile);
            $("#gender option[value="+response.gender+"]").prop("selected", "selected");
            $('#save-operator').html('Update');
            $('#operatorModel').animate({ scrollTop: 0 }, 'slow');
        }
    });
});

function updateOperator(id)
{
    var url = "{{ route('admin.vehicles.update-operator',':id') }}";
        url = url.replace(':id', id);
    $.easyAjax({
        url: url,        
        container: '#createOperator',
        type: "POST",
        redirect: true,
        data: $('#createOperator').serialize(),
        success: function (response) {
            $('#vehicles-table').DataTable().ajax.reload();
            var options = [];
            var rData = [];
                rData = response.data;
                $.each(rData, function( index, value ) {
                    var selectData = '';
                        selectData = '<option value="'+value.id+'">'+value.name+'('+value.email+')</option>';
                        options.push(selectData);
                    
                });

                $('#operator').html(options);
                $('#operator').select2();
        }
    })
}
</script>