@extends('layout.app')

@section('title', 'Contact List')

@section('content')
    <div class="row align-items-center mb-3">
        <div class="col-md-6 mb-2 mb-md-0">
            <h1 class="mb-0">Contact</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a class="btn btn-success me-2" href="{{ route('contact.create') }}">Create New Contact</a>
            <button class="btn btn-warning" id="mergeContactsBtn">Merge Contacts</button>
        </div>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Image</th>
                    <th>Document</th>
                    <!-- <th>Custom Field</th> -->
                    <th></th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="modal fade" id="mergeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Merge Contacts</h5></div>
            <div class="modal-body">
                <p>Select the master contact:</p>
                <select id="masterContactSelect" class="form-control"></select>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmMerge" class="btn btn-primary">Confirm Merge</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </div>
        </div>
        <input type="hidden" name="secondory_contact_id" id="secondory_contact_id" value=""/>
    </div>
@endsection

@section('custom-js')
<script>
    $(function () {
        $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('contact.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'Phone', name: 'Phone' },
                { data: 'gender', name: 'gender' },
                { data: 'image', name: 'image' },
                { data: 'document', name: 'document' },
                // { data: 'custom', name: 'custom' },
                { data: 'merge', name: 'merge', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });

  
    $(document).on('click', '.delete-contact-btn', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "This will delete the contact.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('api/contact') }}/" + id,
                    type: 'DELETE',
                    success: function (res) {
                        toastr.success(res.message || 'Contact deleted successfully.');
                        $('.data-table').DataTable().ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Delete failed.');
                    }
                });
            }
        });
    });

    // get master record
    function show_master(secondary_contact)
    {
        $('#secondory_contact_id').val("");
        // Send as an array (jQuery will encode as ?ids[]=1&ids[]=2)
        $.get('/api/contact/get-master-contacts', { id: secondary_contact }, function(data) {

            if (data.length === 0) {
                toastr.error('No master contacts found for merging.');
                return;
            }

            let options = data.map(c => `<option value="${c.id}">${c.name} - (${c.email})</option>`).join('');
            $('#masterContactSelect').html(options);
            $('#mergeModal').modal('show');
            $('#secondory_contact_id').val(secondary_contact);
        });
    }

    //confirm before merge 
    $('#confirmMerge').on('click', function () {
        let master_id = $('#masterContactSelect').val();
        let secondory_contact_id = $('#secondory_contact_id').val();
        Swal.fire({
            title: "Are you sure you want to merge contacts?",
            text: "This will merge the selected contacts with the chosen master contact.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, merge!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('api.contact.merge_contact') }}", {
                    master_id,
                    secondory_contact_id
                })
                .done(function(res) {
                    toastr.success(res.message || 'Contact merged successfully.');
                    $('.data-table').DataTable().ajax.reload(null, false);
                })
                .fail(function(xhr, status, error) {

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                       
                        const firstError = Object.values(errors)[0][0]; 
                        toastr.error(firstError); 
                    } else {
                        toastr.error('Something went wrong, Merge failed.');
                    }
                    
                    $('.data-table').DataTable().ajax.reload(null, false);
                })
                .always(function() {
                    console.log("Merge request finished.");
                    $('#mergeModal').modal('hide');
                    $('#secondory_contact_id').val("");
                });
            }
            else if (result.dismiss === Swal.DismissReason.cancel) {
                $('#mergeModal').modal('hide');
                $('#secondory_contact_id').val("");
            }
        });
      

    });
</script>
@endsection
