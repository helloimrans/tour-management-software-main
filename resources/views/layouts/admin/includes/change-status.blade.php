<script>
    $(document).ready(function() {
        $(document).on('change', '.change-status-checkbox', function() {
            var id = $(this).data('id');
            var status = $(this).prop('checked') ? 1 : 0;
            let table = '{{@$table}}';
            let column = '{{@$column}}' != '' ? '{{@$column}}' : 'is_active';

            $.ajax({
                url: "{{ route('change.status') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    table: table,
                    status: status,
                    column: column,
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    }else{
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    toastr.error('Something went wrong!');
                }
            });
        });
    });
</script>
