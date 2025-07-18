@extends('layout.app')

@section('content')
    <div class="d-flex justify-content-end">
        <a href="{{ route('contact.index') }}" class="btn btn-secondary ms-2">Back</a>
    </div>

    <h3>Master Details Of: {{ $contact->name }} ({{ $contact->email }})</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Contact Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Custom Fields</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $contact->name ?? '-' }}</td>
                    <td>{{ $contact->email ?? '-' }}</td>
                    <td>{{ $contact->Phone ?? '-' }}</td>
                    <td>
                        @php
                            $fields = ($contact->custom_field)?json_decode($contact->custom_field, true) : [];
                        @endphp
                        <ul>
                            @if(!empty($fields))
                                @foreach($fields as $field)
                                    <li>{{ $field['name'] }}: {{ $field['value'] }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Profile Image</h5>
                @if($contact->profile_image)
                    <img src="{{ asset('storage/' . $contact->profile_image) }}" 
                        alt="Profile Image" 
                        class="img-thumbnail" 
                        style="max-width: 200px;">
                @else
                    <p>No profile image uploaded.</p>
                @endif
            </div>
            <div class="col-md-6">
                <h5>Document</h5>
                @if($contact->doc)
                    <a href="{{ asset('storage/' . $contact->doc) }}" 
                    target="_blank" 
                    class="btn btn-outline-primary">
                        View Document
                    </a>
                @else
                    <p>No document uploaded.</p>
                @endif
            </div>
        </div>

        
        @if($contact->mergedContact()->exists())
            @include('contact._merge_detail',$contact)
        @endif
@endsection
