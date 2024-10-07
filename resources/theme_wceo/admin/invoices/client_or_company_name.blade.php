@if($projectID == '')
    <select placeholder="-" class="form-control form-control-lg select2" name="client_id" id="client_company_id" data-style="form-control">
        @foreach($clients as $client)
            <option value="{{ $client->id }}">{{ ucwords($client->name) }}
                @if($client->company_name != '') {{ '('.$client->company_name.')' }} @endif</option>
        @endforeach
    </select>
    <label for="client_company_id" class="control-label" id="companyClientName">@lang('app.client_name')</label>

    
    <script>
        $("#client_company_id").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });

        $('#client_company_id').change(function() {
            checkShippingAddress();
        });
    </script>
@else

        <input placeholder="-" type="text" readonly class="form-control form-control-lg" name="company_name" id="company_name" value="{{ $companyName }}">
        <label for="company_name" class="control-label" id="companyClientName">@lang('app.client_name')</label>

        <input type="hidden" class="form-control" name="" id="client_id" value="{{ $clientId }}">
@endif
