$(document).ready(function() {
    function preFillDoctorNameAndRedirect() {
        var doctorName = $(this).closest('.doctor-card').find('.doctor-info h3').text().trim();
        var url = 'book.html?doctor=' + encodeURIComponent(doctorName);
        window.location.href = url;
    }

    $(document).on('click', '.online-booking', preFillDoctorNameAndRedirect);
});