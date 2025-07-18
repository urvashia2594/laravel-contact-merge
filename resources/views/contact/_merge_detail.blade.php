
<hr>
<h4>Secondary Details for: {{ $contact->mergedContact->child->name }} ({{ $contact->mergedContact->child->email }})</h4>

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
            <td>{{ $contact->mergedContact->child->name ?? '-' }}</td>
            <td>{{ $contact->mergedContact->child->email ?? '-' }}</td>
            <td>{{ $contact->mergedContact->child->Phone ?? '-' }}</td>
            <td>
                @php
                    $fields = ($contact->mergedContact->child->custom_field)?json_decode($contact->mergedContact->child->custom_field, true) : [];
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
                @if($contact->mergedContact->child->profile_image)
                    <img src="{{ asset('storage/' . $contact->mergedContact->child->profile_image) }}" 
                        alt="Profile Image" 
                        class="img-thumbnail" 
                        style="max-width: 200px;">
                @else
                    <p>No profile image uploaded.</p>
                @endif
            </div>
            <div class="col-md-6">
                <h5>Document</h5>
                @if($contact->mergedContact->child->doc)
                    <a href="{{ asset('storage/' . $contact->mergedContact->child->doc) }}" 
                    target="_blank" 
                    class="btn btn-outline-primary">
                        View Document
                    </a>
                @else
                    <p>No document uploaded.</p>
                @endif
            </div>
        </div>
<hr>
<h6>Merged Details</h6>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Email</th>
            <th>Phone</th>
            <th>Custom Fields</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $contact->mergedContact->email ?? '-' }}</td>
            <td>{{ $contact->mergedContact->Phone ?? '-' }}</td>
            <td>
                @php
                    $fields = ($contact->mergedContact->custom_field)?json_decode($contact->mergedContact->custom_field, true) : [];
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