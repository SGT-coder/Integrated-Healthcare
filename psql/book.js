$(document).ready(function() {
    // Function to map doctor names to IDs
    function getDoctorIdByName(name) {
        const doctorIds = {
            'Dr. Latifa Obaidi, DO': 1,
            'Dr. Mikias, DO': 2,
            'Dr. Samuel DO': 3
            // Add more mappings as needed
        };
        return doctorIds[name] || 0;
    }

    // Extract doctor's name from URL parameter
    var urlParams = new URLSearchParams(window.location.search);
    var doctorName = urlParams.get('doctor');

    // Pre-fill doctor's name in the booking form
    if (doctorName) {
        $('#doctorLabel').text(doctorName);
        $('#doctor').val(doctorName);
        $('#doctor_id').val(getDoctorIdByName(doctorName));
    }

    // AJAX form submission
    $('#appointmentForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Gather form data

        $.ajax({
            type: 'POST',
            url: 'book.php',
            data: formData,
            success: function(response) {
                var res = JSON.parse(response);
                $('#responseMessage').removeClass('success error').addClass(res.status);
                $('#responseMessage').text(res.message).show();
                if (res.status === 'success') {
                    $('#appointmentForm')[0].reset(); // Clear form fields
                    $('#doctorLabel').text(''); // Clear doctor label
                }
            },
            error: function(response) {
                $('#responseMessage').removeClass('success').addClass('error');
                $('#responseMessage').text('Error booking appointment.').show();
            }
        });
    });
});
