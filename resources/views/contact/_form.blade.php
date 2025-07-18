<form id="contact-form" enctype="multipart/form-data">
    @csrf
    
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{old('name',$contact->name)}}">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{old('email',$contact->email)}}">
    </div>

    <div class="mb-3">
        <label for="Phone" class="form-label">Phone No</label>
        <input type="text" class="form-control" id="Phone" name="Phone" placeholder="Enter Phone No" value="{{old('Phone',$contact->Phone)}}">
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Gender</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="gender1" value="1" {{$contact->gender == '1' ? 'checked':''}}>
            <label class="form-check-label" for="gender1">Male</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="gender2" value="2" {{$contact->gender == '2' ? 'checked':''}}>
            <label class="form-check-label" for="gender2">Female</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="gender3" value="3" {{$contact->gender == '3' ? 'checked':''}}>
            <label class="form-check-label" for="gender3">Other</label>
        </div>
    </div>

    <div class="mb-3">
        <label for="profile_image" class="form-label">Profile Image</label>
        <input type="file" class="form-control" id="profile_image" name="profile_image">
    </div>

    <div class="mb-3">
        <label for="doc" class="form-label">Upload Document</label>
        <input type="file" class="form-control" id="doc" name="doc">
    </div>

    <!-- custom filed start -->
    <button type="button" class="btn btn-primary mb-3 addfield">Add Custom Field</button>
    <div id="add_field_div">
        @if($contact->exists && $contact->custom_field != '')
            @php
                $count = 1;
                $fields = json_decode($contact->custom_field, true);
            @endphp
            
            @foreach ($fields as $key => $value)
                <div class="inline-fields" id="add_field_{{$count}}">
                    <input type="text" class="form-control" name="custom_field[{{$count}}][name]" placeholder="Field name" value="{{$value['name']}}" required>
                    <input type="text" class="form-control" name="custom_field[{{$count}}][value]" placeholder="Field value" value="{{$value['value']}}" required >
                    <button type="button" class="btn btn-danger removeField" data-id={{$count}}>Remove</button>
                </div>

                @php  $count++ @endphp
            @endforeach
            
        @endif
    </div>
    <!-- custom filed end -->


    <div class="d-flex justify-content-center mt-4">
        @if($contact->exists)
            <button type="submit" class="btn btn-primary btn-sm px-4 updatedata">Edit</button>
        @else
            <button type="submit" class="btn btn-primary btn-sm px-4 submitdata">Submit</button>
        @endif
        <a href="{{ route('contact.index') }}" class="btn btn-secondary ms-2">Back</a>
    </div>

</form>
