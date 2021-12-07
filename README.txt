This module allows user to upload CSV with Movie names, and create nodes for each row.  

HOW TO USE?
--------------------------------

1. Place this file in the custom modules directory.
2. Enable the module.
3. Go to path "/movie/upload"
4. Fill the form and upload CSV
5. Click on Submit

HOW IT WORKS?
---------------------------

1. Upload the CSV.
2. The form will validate the if the CSV has the correct headers . 
3. The form will also validate if CSV is empty.
4. On submit, Movie service is called, and saveMovies handler is executed using dependency injection.