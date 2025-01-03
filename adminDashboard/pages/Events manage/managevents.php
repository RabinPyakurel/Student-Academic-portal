

    <div class="admin-container">
        <h2>Add New Event</h2>

        <form action="add_event.php" method="POST" enctype="multipart/form-data">
            <label for="event_name">Event Name</label>
            <input type="text" id="event_name" name="event_name" required>

            <label for="event_date">Event Date</label>
            <input type="date" id="event_date" name="event_date" required>

            <label for="event_description">Event Description</label>
            <textarea id="event_description" name="event_description" rows="4" required></textarea>

            <label for="event_image">Event Image</label>
            <input type="file" id="event_image" name="event_image" accept="image/*" required>

            <button type="submit" name="submit">Add Event</button>
        </form>
    </div>

