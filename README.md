# Unofficial Infinite Craft Backend

This is an unofficial backend server for Infinite Craft by Neal from neal.fun: https://neal.fun/infinite-craft/
I was a bit too stupid to realize that Neal wrote it in js, so all of this is in php. Feel free to translate it to js though.

## Setup Instructions

1. **Download and Extract Files**
   - Place the entire contents of this repository into your server's main directory.

2. **Configure API Key and Database Credentials**
   - Navigate to the `private` folder.
   - In `apikey.php`, replace the placeholder with your actual API key.
   - In `databasecreds.php`, replace the placeholder values with your actual database credentials.

3. **Optional Configuration**
   - If needed, update the allowed hosts in `pair.php`.

4. **Database Setup**
   - Before running the backend, execute the following SQL commands to set up the necessary tables in your database:

   ```sql
   CREATE TABLE recipes (
     id INT AUTO_INCREMENT PRIMARY KEY,
     first VARCHAR(255) NOT NULL,
     second VARCHAR(255) NOT NULL,
     result VARCHAR(255) NOT NULL
   );

   CREATE TABLE emojis (
     id INT AUTO_INCREMENT PRIMARY KEY,
     item VARCHAR(255) NOT NULL,
     emoji VARCHAR(255) NOT NULL
   );
   ```

   - Then insert the following data into the `recipes` and `emojis` tables:

   ```sql
   INSERT INTO recipes (first, second, result) VALUES
   ('Water', 'Water', 'Lake'),
   ('Earth', 'Earth', 'Mountain'),
   ('Water', 'Fire', 'Steam'),
   ('Wind', 'Wind', 'Tornado'),
   ('Earth', 'Water', 'Plant'),
   ('Cow', 'Fire', 'Steak');

   INSERT INTO emojis (item, emoji) VALUES
   ('Earth', '🌍'),
   ('Fire', '🔥'),
   ('Water', '💧'),
   ('Wind', '🌬️');
   ```

5. **Run the Backend**
   - After completing the setup, the backend should be ready to run!

## Troubleshooting

@activetutorial on Discord dm me and if you get an equal sign thats because I forgot to implement all after ``=``

## License

Read LICENSE
