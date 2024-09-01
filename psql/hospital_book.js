$(document).ready(function() {
    // Function to map hospital names to IDs
    function getHospitalIdByName(name) {
        const hospitalIds = {
            'Yekatit 12 Hospital': 1,
            'Black Lion Hospital': 2,
            'St. Paul’s Hospital': 3,
            'Beijing Hospital':4
            // Add more mappings as needed
        };
        return hospitalIds[name] || 0;
    }

    // Extract hospital's name from URL parameter
    var urlParams = new URLSearchParams(window.location.search);
    var hospitalName = urlParams.get('hospital');

    // Pre-fill hospital's name in the booking form
    if (hospitalName) {
        $('#hospitalLabel').text(hospitalName);
        $('#hospital').val(hospitalName);
        $('#hospital_id').val(getHospitalIdByName(hospitalName));
    }

    // AJAX form submission
    $('#appointmentForm').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = $(this).serialize(); // Gather form data

        $.ajax({
            type: 'POST',
            url: 'hospital_book.php',
            data: formData,
            dataType: 'json', // Expect JSON response
            success: function(response) {
                var res = JSON.parse(response);
                $('#responseMessage').removeClass('success error').addClass(res.status);
                $('#responseMessage').text(res.message).show();
                if (res.status === 'success') {
                    $('#appointmentForm')[0].reset(); // Clear form fields
                    $('#hospitalLabel').text(''); // Clear hospital label
                }
            },
            error: function(response) {
                $('#responseMessage').removeClass('success').addClass('error');
                $('#responseMessage').text('Error booking appointment.').show();
            }
        });
    });
});
