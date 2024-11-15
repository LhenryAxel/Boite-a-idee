Idea Box Project
Description

This is a school project: a simple PHP and MySQL application for submitting and voting on ideas. Only authorized users can log in, submit ideas, and cast votes.
Features

    User Login: Only specific users can log in.
    Submit Ideas: Add ideas with a title and description.
    Voting: Users can cast positive or negative votes (one vote per user per idea).

Requirements

    PHP 7.4+
    MySQL
    Local server (XAMPP, WAMP, etc.)

Setup

    Database: Create a database boite_a_idee and run:

    CREATE TABLE idea (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(60) NOT NULL,
        description TEXT NOT NULL,
        author VARCHAR(15) NOT NULL,
        created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        vote_positive INT DEFAULT 0,
        vote_negative INT DEFAULT 0
    );

    CREATE TABLE vote (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_idea INT NOT NULL,
        voter_name VARCHAR(100) NOT NULL,
        vote_type ENUM('positive', 'negative') NOT NULL,
        UNIQUE (id_idea, voter_name),
        FOREIGN KEY (id_idea) REFERENCES idea(id) ON DELETE CASCADE
    );

    Configure: Update database.php with your MySQL credentials.

    Run: Place files in your server root, start the server, and access via http://localhost/<project_folder>/index.php.

Usage

    Log in with usernames like "Zasir" or "Axel".
    Submit ideas on submit.php.
    View and vote on ideas on ideas.php.
    Logout via logout.php.
