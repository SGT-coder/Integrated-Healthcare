document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-query');
    const searchResults = document.getElementById('search-results');

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const searchTerm = searchInput.value.trim();

        const formData = new FormData();
        formData.append('search_term', searchTerm);

        if (searchTerm) {
            fetch('search_hospitals.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                let resultsHtml = '';
                if (data.length > 0) {
                    resultsHtml += '<div class="search-results-container">';
                    data.forEach(hospital => {
                        resultsHtml += `
                            <div class="hospital-card">
                                <img src="${hospital.image || 'uploads/default_hospital.webp'}" alt="Hospital Image" class="hospital-image">
                                <div class="hospital-info">
                                    <h3 class="hospital-name">${hospital.name}</h3>
                                    <p>Location: ${hospital.location || 'N/A'}</p>
                                    <p>Specialties: ${hospital.specialties || 'N/A'}</p>
                                    <p>Description: ${hospital.description || 'N/A'}</p>
                                    <p>Emergency Services: ${hospital.emergency_services || 'N/A'}</p>
                                </div>
                                <a href="hospital_book.html?hospital=${encodeURIComponent(hospital.name)}" class="book-btn">Book Appointment</a>
                            </div>
                        `;
                    });
                    resultsHtml += '</div>';
                } else {
                    resultsHtml = '<p>No results found</p>';
                }
                searchResults.innerHTML = resultsHtml;
                searchResults.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching search results.');
            });
        } else {
            alert('Please enter a search term.');
        }
    });
});
