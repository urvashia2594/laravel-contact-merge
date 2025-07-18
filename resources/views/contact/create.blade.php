@extends('layout.app')

@section('content')
    <h2 class="text-center mb-4">Add Contact</h2>
    @include('contact._form',[
        'contact' => $contact
    ])      
@endsection

@section('custom-js')
{!! JsValidator::formRequest('App\Http\Requests\contact\StoreRequest', '#contact-form'); !!}
<script>
$('#contact-form').on('submit', function (e) {
    e.preventDefault();

    // Only proceed if form is valid (client-side via JsValidator)
    if (!$(this).valid()) {
        return false;
    }

    let formData = new FormData(this);

    //disbale submit button
    let $btn = $(this).find('button[type=submit]');
    $btn.prop('disabled', true).text('Saving...');;

   

    $.ajax({
        url: "{{route('api.contact.store')}}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {

            toastr.success(response.message || 'Contact saved successfully.');

            // reset form
            $(this).trigger("reset");

            // redirect
            setTimeout(() => {
                window.location.href = "{{ route('contact.index') }}";
            }, 1000);
        },
        error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    // for (let field in errors) {
                    //     toastr.error(errors[field][0]);
                    // }
                    const firstError = Object.values(errors)[0][0]; 
                    toastr.error(firstError); 
                } else {
                    toastr.error('Something went wrong');
                }
        },
        complete: function () {
                $btn.prop('disabled', false).text('Submit');
        }
    });
});

//Add more functiolaity
<?php 
    $custom_field = json_decode($contact->custom_field, true);
    $count  = ($custom_field)?count($custom_field):0; 
?>
var count = {{ $count }}
$(document).on('click','.addfield', function(){
    
    count++;

    let str = `<div class="inline-fields" id="add_field_${count}">
        <div class="field-wrap">
            <input type="text" class="form-control" name="custom_field[${count}][name]" placeholder="Field name" required>
        </div>
        <div class="field-wrap">
            <input type="text" class="form-control" name="custom_field[${count}][value]" placeholder="Field value" required>
        </div>
        <button type="button" class="btn btn-danger removeField" data-id="${count}">Remove</button>
    </div>`;

    $('#add_field_div').append(str);

    $('input[name="custom_field['+count+'][name]"]').rules('add', {
        required: true,
        messages: {
            required: "Field name is required"
        }
    });

    $('input[name="custom_field['+count+'][value]"]').rules('add', {
        required: true,
        messages: {
            required: "Field value is required"
        }
    });

});

$(document).on('click','.removeField', function(){
    id = $(this).data('id');
    $('#add_field_' + id).remove()
});
</script>
@endsection