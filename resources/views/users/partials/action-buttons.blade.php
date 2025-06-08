<button class="btn btn-sm btn-warning edit-user" data-id="{{ $data->id }}" title="Edit">
  <i class="ti ti-pencil"></i> Edit
</button>

<button class="btn btn-sm btn-danger btn-delete" 
        data-id="{{ $data->id }}" 
        data-url="{{ route('users.destroy', $data->id) }}" 
        title="Delete">
  <i class="ti ti-trash"></i> Delete
</button>
