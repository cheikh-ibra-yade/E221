(function($) {

    $('#reset').on('click', function() {
        $('#register-form').reset();
    });

})(jQuery);

function checkValueZone(val) {
    var element = document.getElementById('bien_zoneField');
    if (!val || val == 0) {

        element.style.display = 'block';
        element.setAttribute("required", "required");
    } else {

        element.style.display = 'none';
        element.removeAttribute("required")
    }
}

var avatarField = document.getElementById('bien_imageFile_file');
avatarField.removeAttribute("required");
(function($) {
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
})(jQuery);