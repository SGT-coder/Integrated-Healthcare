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
            fetch('search_doctors.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                let resultsHtml = '';
                if (data.length > 0) {
                    resultsHtml += '<div class="search-results-container">';
                    data.forEach((doctor, index) => {
                        if (index % 3 === 0 && index !== 0) {
                            resultsHtml += '</div><div class="search-results-container">';
                        }
                        resultsHtml += `
                            <div class="doctor-card">
                                <img src="${doctor.image || 'uploads/logoo.png'}" alt="Doctor Image" class="doctor-image">
                                <div class="doctor-info">
                                    <h3>${doctor.name}</h3>
                                    <p>Specialty: ${doctor.specialties || 'N/A'}</p>
                                    <p>Location: ${doctor.location || 'N/A'}</p>
                                    <p>Hospital: ${doctor.hospital || 'N/A'}</p>
                                    <p>Description: ${doctor.description || 'N/A'}</p>
                                </div>
                                <a href="book.html?doctor=${encodeURIComponent(doctor.name)}" class="book-btn">Book</a>
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
