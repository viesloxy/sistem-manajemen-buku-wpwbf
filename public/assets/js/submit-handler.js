$(document).ready(function() {
    $('.btn-submit').on('click', function(e) {
        e.preventDefault();

        let btn = $(this);
        let form = btn.closest('form')[0];
        let btnText = btn.find('.btn-text');

        if (form.checkValidity()) {

            btn.prop('disabled', true);

            let originalContent = btn.html();

            btn.html('<i class="mdi mdi-loading mdi-spin me-1"></i> Memproses...');

            form.submit();
            
        } else {
            form.reportValidity();
        }
    });
});