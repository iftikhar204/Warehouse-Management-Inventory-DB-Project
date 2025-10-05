$(document).ready(function() {
    let contactCount = 1;

    $('input[name="role"]').change(function() {
        const role = $(this).val();

        $('#supervisorFields, #orderProcessorFields, #inventoryClerkFields, #shippingClerkFields').hide();

        if (role === 'supervisor') {
            $('#supervisorFields').show();
        } else if (role === 'order_processor') {
            $('#orderProcessorFields').show();
        } else if (role === 'inventory_clerk') {
            $('#inventoryClerkFields').show();
        } else if (role === 'shipping_clerk') {
            $('#shippingClerkFields').show();
        }
    });

    $('#addContact').click(function() {
        const newRow = `
        <div class="row mb-3 contact-row">
            <div class="col-md-4">
                <select class="form-select" name="contacts[${contactCount}][contact_type]" required>
                    <option value="phone">Phone</option>
                    <option value="mobile">Mobile</option>
                    <option value="email">Email</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" class="form-control" name="contacts[${contactCount}][contact_value]" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-contact">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;

        $('#contactFields').append(newRow);
        contactCount++;
    });

    $(document).on('click', '.remove-contact', function() {
        $(this).closest('.contact-row').remove();
    });
});
