
function suggetion() {

    $('#sug_input').keyup(function (e) {

        var formData = {
            'product_name': $('input[name=title]').val()
        };

        if (formData['product_name'].length >= 1) {

            // process the form
            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                data: formData,
                dataType: 'json',
                encode: true
            })
                .done(function (data) {
                    //console.log(data);
                    $('#result').html(data).fadeIn();
                    $('#result li').click(function () {

                        $('#sug_input').val($(this).text());
                        $('#result').fadeOut(500);

                    });

                    $("#sug_input").blur(function () {
                        $("#result").fadeOut(500);
                    });

                });

        } else {

            $("#result").hide();

        };

        e.preventDefault();
    });

}
$('#sug-form').submit(function (e) {
    var formData = {
        'p_name': $('input[name=title]').val()
    };
    // process the form
    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: formData,
        dataType: 'json',
        encode: true
    })
        .done(function (data) {
            //console.log(data);
            $('#product_info').html(data).show();
            total();
            $('.datePicker').datepicker('update', new Date());

        }).fail(function () {
            $('#product_info').html(data).show();
        });
    e.preventDefault();
});
function total() {
    $('#product_info input').change(function (e) {
        var price = +$('input[name=price]').val() || 0;
        var qty = +$('input[name=quantity]').val() || 0;
        var total = qty * price;
        $('input[name=total]').val(total.toFixed(2));
    });
}

function imagePreview() {
    $('input[type="file"]').change(function (e) {
        var file = e.target.files[0];
        var $input = $(this);
        var $preview = $input.closest('form').find('.image-preview');

        if (file && file.type.match('image.*')) {
            var reader = new FileReader();
            reader.onload = function (e) {
                if ($preview.length) {
                    $preview.attr('src', e.target.result).show();
                } else {
                    // fallback to find conventional image locations
                    var $img = $input.closest('.row').find('img');
                    if ($img.length) {
                        $img.attr('src', e.target.result);
                    }
                }
            }
            reader.readAsDataURL(file);
        }
    });
}

$(document).ready(function () {

    //tooltip
    $('[data-toggle="tooltip"]').tooltip();

    $('.submenu-toggle').click(function () {
        $(this).parent().children('ul.submenu').toggle(200);
    });
    //suggetion for finding product names
    suggetion();
    // Callculate total ammont
    total();
    // Image Preview
    imagePreview();

    // List Filtering (Users/Products/Categories/Sales/Invoices/Media)
    $("#user-search, #product-search, #category-search, #sales-search, #invoices-search, #media-search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        var targetTable = "#" + $(this).attr('id').replace('-search', '-table');
        $(targetTable + " tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Active Sidebar Link Highlighting
    var currentPath = window.location.pathname.split("/").pop();
    $('.sidebar ul li a').each(function () {
        var linkPath = $(this).attr('href');
        if (linkPath === currentPath) {
            $(this).parent().addClass('active');
            $(this).closest('.submenu').show();
        }
    });

    // Friendly Delete Confirmation
    $('.btn-danger[title="Delete"], .btn-danger[title="Remove"]').on('click', function (e) {
        if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
            e.preventDefault();
        }
    });

    // Media Dropdown Preview
    $('#product-photo-select').on('change', function () {
        var filename = $(this).find(':selected').data('filename');
        if (filename) {
            $('#media-dropdown-preview').attr('src', 'uploads/products/' + filename);
        } else {
            $('#media-dropdown-preview').attr('src', 'uploads/products/no_image.png');
        }
    });

    $('.datepicker')
        .datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
});
