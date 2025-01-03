<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
    <link rel="stylesheet" href="../assets/css/events.css">
    
</head>
<body>
    <?php include "../layout/nav.htm"; ?>
<main>
    <h2>Upcoming Events</h2>
    <div class="event-list" id="eventList"> 
    </div>
    </main>
    <script>
        const eventList = document.getElementById('eventList');

        // Fetch events from the server
        fetch('fetch_events.php')
            .then(response => response.json())
            .then(events => {
                events.forEach(event => {
                    const itemContainer = document.createElement('div');
                    itemContainer.className = 'event-item';

                    // Event details section
                    const item = document.createElement('div');
                    item.className = 'event-details';
                    item.innerHTML = `
                        <h3>${event.event_name}</h3>
                        <p><strong>Date:</strong> ${event.event_date}</p>
                        <a href="#">
                            <p class="event-description">${event.event_description}</p>
                        </a>
                        <p>Join us for the <strong>${event.event_name}</strong> event to engage, learn, and enjoy with fellow participants! Don’t miss out on the excitement and opportunities to connect and collaborate.</p>
                    `;

                    
                    const image = document.createElement('img');
                    image.src = "../assets/images/EVENT.jpeg" + event.image_name;  
                    image.alt = event.event_name;
                    image.className = 'event-image';

                    
                    itemContainer.appendChild(item);
                    itemContainer.appendChild(image); 

                  
                    eventList.appendChild(itemContainer);
                });
            })
            .catch(error => {
                console.error('Error fetching events:', error);
                const errorMessage = document.createElement('p');
                errorMessage.textContent = 'Failed to load events. Please try again later.';
                eventList.appendChild(errorMessage);
            });
    </script>

    <?php include "../layout/footer.htm"; ?>
</body>
</html>
