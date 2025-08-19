# Saadan Film Voting System

## Overview
This project is a web application for voting on nominees for the Best Actor/Actress of the Year 2025. Users can view nominees, cast their votes, and share their participation on social media.

## Project Structure
```
saadanfilm-vote
├── src
│   ├── vote_for_award.php       # Handles the voting process and displays nominees
│   ├── header.php               # Contains the header section of the web application
│   ├── footer.php               # Contains the footer section of the web application
│   ├── config
│   │   └── database.php         # Establishes a connection to the database
│   ├── ajax
│   │   ├── subscribe.php        # Handles AJAX requests for user subscriptions
│   │   ├── vote.php             # Processes voting requests from users
│   │   ├── standings.php         # Retrieves current standings of nominees
│   │   └── share_callback.php    # Handles callbacks for social media shares
│   └── assets
│       ├── css
│       │   └── styles.css       # CSS styles for the web application
│       └── js
│           └── app.js           # JavaScript code for client-side interactions
├── sql
│   └── schema.sql               # SQL commands to create the necessary database schema
├── composer.json                 # Configuration file for Composer dependencies
├── .env                          # Environment variables for the project
└── README.md                     # Documentation for the project
```

## Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd saadanfilm-vote
   ```

2. **Install Dependencies**
   If you are using Composer, run:
   ```bash
   composer install
   ```

3. **Configure Database**
   - Create a MySQL database for the project.
   - Update the `.env` file with your database credentials.

4. **Import Database Schema**
   You can import the database schema using the provided SQL command:
   ```sql
   -- Assuming you are using MySQL
   CREATE TABLE nominees (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       bio TEXT,
       headshot VARCHAR(255),
       films JSON,
       votes_count INT DEFAULT 0,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```
   Alternatively, you can execute the `sql/schema.sql` file directly in your database management tool.

5. **Run the Application**
   - Start your local server (e.g., XAMPP, MAMP).
   - Access the application via your web browser at `http://localhost/saadanfilm-vote/src/vote_for_award.php`.

## Usage
- Users can view nominees and cast their votes.
- The application tracks votes and displays a live leaderboard.
- Users can subscribe with their email to vote and share their participation on social media.

## Contributing
Feel free to submit issues or pull requests for improvements or bug fixes.