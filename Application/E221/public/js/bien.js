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