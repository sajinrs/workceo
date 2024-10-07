<div class="modal-header">
   
    <h5 class="modal-title">@lang('modules.customFields.addField')</h5>
     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="portlet-body">
{!!  Form::open(['url' => '' ,'method' => 'post', 'id' => 'add-edit-form','class'=>'form-horizontal'])   !!}
<div class="form-body">
       <div class="form-group"> 
                       <label class="control-label" for="display_name">
                        @lang('modules.invoices.type')</label>
               {!! Form::select('module',
                    $customFieldGroups,
                    '',['class' => 'form-control  gender','id' => 'module'])
                 !!}
                    </div>

            <div class="form-group">
                       <label class="control-label" for="name">@lang('modules.customFields.label')</label>
                      <input type="text" name="label" id="label" class="form-control " onkeyup="slug(this.value)">
            
                   </div>


                     <div class="form-group">
                          <label class="control-label" for="name">@lang('app.name')</label>
                     <input type="text" name="name" id="name" class="form-control " >
            
                   </div>


                    <div class="form-group">
                           <label class="control-label" for="required">@lang('app.required')</label>
                    <input type="radio" name="required" id="optionsRadios1" value="yes" checked>
                    <label for="optionsRadios1"> @lang('app.yes') </label>


                    <input type="radio" name="required" id="optionsRadios2" value="no" >
                    <label for="optionsRadios2"> @lang('app.no') </label>
            
                   </div>


                    <div class="form-group">
                         <label class="control-label" for="display_name">@lang('modules.invoices.type')</label>
          
                {!! Form::select('type',
                    [
                        'text'      => 'text',
                        'number'    =>'number',
                        'password'  => 'password',
                        'textarea'  =>'textarea',
                        'select'    =>'select',
                        'radio'    =>'radio',
                        'date'      =>'date'

                    ],
                    '',['class' => 'form-control  gender','id' => 'type'])
                 !!}
                   </div>


            
        </div>
      




        <div class="form-group mt-repeater" style="display: none;">

                <div class="row">
                    <div class="col-md-9 pr-0"> 
                        <div class="form-group">
                            <input class="form-control " name="value[]" type="text" placeholder="@lang('app.value')"> 
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a id="plusButton" href="javascript:;" data-repeater-create class="btn btn-secondary mt-repeater-add"><i class="fa fa-plus"></i></a>
                    </div>
                </div>

                <div id="repeater"></div>


                          

    </div>
</div>

<div class="modal-footer">

  <div class="form-actions">
              <div class=" text-right">
            <button type="button" id="save" class="btn btn-primary"> @lang('app.save')</button>
                    </div>
        </div>
    </div>    
{{ Form::close() }}
<script>

var insertBefore = '#repeater';
$('#plusButton').click(function(){
var $i=1;
$i = $i+1;
var indexs = $i+1;
$(' <div id="addMoreBox'+indexs+'" class="clearfix"> ' +
    '<div class="row">'+
    '<div class="col-md-9 pr-0"> <div class="form-group">'+
        '<input class="form-control " name="value[]" type="text" placeholder="@lang('app.value')"/> </div>'+
    '</div>' +
    '<div class="col-md-3"><a href="javascript:;" onclick="removeBox('+indexs+')" class="btn btn-outline-danger btn-circle"><span class="icon-trash" aria-hidden="true"></span></a></div>' +
    '</div></div>').insertBefore(insertBefore);

});

function removeBox(index){
        $('#addMoreBox'+index).remove();
    }
    /* var FormRepeater = function () {

        return {
            //main function to initiate the module
            init: function () {
                $('.mt-repeater').each(function(){
                    $(this).repeater({
                        show: function () {
                            $(this).slideDown();
                        },

                        hide: function (deleteElement) {
                            $(this).slideUp(deleteElement);
                        },

                        ready: function (setIndexes) {

                        },
                        isFirstItemUndeletable: true,


                    });
                });
            }

        };

    }();

    jQuery(document).ready(function() {
        FormRepeater.init();
    }); */

    $('#type').on('change', function () {
        // if (this.value == '1'); { No semicolon and I used === instead of ==
        if (this.value === 'select' || this.value === 'radio' || this.value === 'checkbox'){
            $(".mt-repeater").show();
        } else {
            $(".mt-repeater").hide();
        }
    });

    function convertToSlug(Text)
    {
        return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-')
                ;
    }

    function slug(text){
        $('#name').val(convertToSlug(text));
    }

    $('#save').click(function () {
        $.easyAjax({
            url: '{{route('admin.custom-fields.store')}}',
            container: '#add-edit-form',
            type: "POST",
            data: $('#add-edit-form').serialize(),
            file:true,
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
        return false;
    })
</script>

