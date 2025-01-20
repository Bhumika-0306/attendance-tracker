Attendance Tracker System
This project is a web-based Attendance Tracker System designed for professors to
manage student attendance, view detailed reports, and perform administrative tasks like
adding/removing students. The system ensures a streamlined user experience with easy
navigation and dynamic interaction.
Technologies Used
● HTML: For structuring the web pages.
● CSS: For styling the web pages and making them responsive.
● Bootstrap: For designing a clean and modern layout with mobile responsiveness.
● PHP: For server-side scripting, including user authentication, database interaction,
and form submissions.
● SQLite3: For storing and managing data related to students and attendance records.
● XAMPP: For running the local development environment.
Key Features
1. User Authentication
● Login Page: Professors can enter their credentials (username and password) to
access the system.
● Forgot Password: Professors can reset their password via email if they forget it.
2. Navigation Bar
● Personalized Welcome: Shows the professor's profile picture and name after login.
● Navigation Links:
○ Manage Students
○ Mark Attendance
○ View Reports
○ Raise Queries
○ Logout
3. Dashboard Cards
● Manage Students: Allows professors to manage student details.
● Mark Attendance: Lets professors mark attendance for each student.
● View Reports: Displays detailed attendance reports.
● Raise Query: Professors can submit queries or concerns related to students or
classes.
4. Student Management
● Search and Filter Students: Professors can search for students by name or ID and
filter by division (e.g., Div A, Div B).
● Add/Edit Students: Professors can add new students or edit existing student
information.
● Remove Students: Professors can remove students from the system when needed.
● Pagination: Ensures smooth navigation for large databases.
5. Attendance Management
● Mark Attendance: Professors can mark students as "Present" or "Absent."
● Dynamic Student List: Displays students' details in a table for easy attendance
marking.
● Attendance Summary: Shows a summary of present, absent, and total students.
● Success Message: After submitting attendance, a confirmation message is shown.
6. Attendance Reports
● Overall Attendance: Displays each student's total attendance, including present and
absent days.
● Monthly Attendance Data: Tracks attendance on a monthly basis.
● Defaulter Detection: Marks students with more than 2 absences as defaulters.
● Raise Red Flag: Professors can flag students for attendance issues.
● Dynamic Table: Attendance data is dynamically populated in the table from the
database.
● Chart Visualization: A bar chart visualizes attendance trends over multiple months.
7. Responsive Design
● The layout adapts to different screen sizes using Bootstrap, ensuring a good user
experience on both desktop and mobile devices.
8. Interactive Modal
● Raise Red Flag Modal: Professors can raise a red flag for students via a modal
popup by entering their name and ID.
9. Footer
● The footer contains copyright information and contact details for support.
Installation Instructions
1. Clone the Repository
bash
CopyEdit
git clone <repository_url>
2. Set Up XAMPP (or Local Server)
● Download and install XAMPP (or your preferred server solution).
● Start Apache and MySQL from the XAMPP control panel.
3. Create Database
● Open phpMyAdmin (usually accessible at http://localhost/phpmyadmin/).
● Create a new database named attendance_tracker.
● Use the following SQL queries to create tables:
sql
CopyEdit
CREATE TABLE students (
id INTEGER PRIMARY KEY AUTOINCREMENT,
name TEXT NOT NULL,
division TEXT NOT NULL
);
CREATE TABLE attendance (
id INTEGER PRIMARY KEY AUTOINCREMENT,
student_id INTEGER NOT NULL,
date TEXT NOT NULL,
status TEXT NOT NULL,
FOREIGN KEY (student_id) REFERENCES students(id)
);
4. Update Database Configuration
● Open the config.php file and configure the database connection:
php
CopyEdit
define('DB_PATH', 'sqlite:attendance_tracker.db'); // Path to your
SQLite database
5. Run the Project
● Place the project files in the htdocs folder (for XAMPP) or the equivalent directory
for your local server.
● Access the project through your browser by visiting
http://localhost/your_project_folder.
Usage
1. Login
● Professors should enter their credentials (username and password) to access the
system.
2. Dashboard
● Once logged in, professors can use the dashboard to manage students, mark
attendance, view reports, and raise queries.
3. Attendance Marking
● Navigate to the "Mark Attendance" section, select the students, and mark them as
"Present" or "Absent."
4. Reports
● Professors can view attendance reports, track monthly attendance, and check for
defaulters.
5. Raise Query
● Use the "Raise a Query" button to submit any concerns or questions regarding
students or attendance.
Project Structure
arduino
CopyEdit
/Attendance-Tracker
/css
styles.css // Custom styles
/js
scripts.js // Custom JavaScript functions
/uploads
(student profile images if applicable)
config.php // Database connection configuration
dashboard.php // Dashboard page
index.php // Login page
attendance.php // Attendance marking page
report.php // Attendance report page
students.php // Manage students page
raise_query.php // Query submission page
logout.php // Log out functionality
verify_student.php // Backend for red flag authentication
Contact
For any inquiries or issues, please contact:
Email: bhumikamittall0306@gmail.com
Screenshot
Login page:
Key Features:
● Username & Password Authentication: Professors can enter their credentials to
access the system.
● Forgot Password Option: If a professor forgets their password, they can request a
reset link via email.
● User-Friendly Design: The interface features a clean, professional, and modern
aesthetic with a soft gradient background and an easy-to-use login card.
Dashboard:
Key Features:
1. Navigation Bar:
○ The top navigation bar features the professor’s profile picture and name,
offering a personalized welcome.
○ It includes links to manage students, mark attendance, view reports, and raise
queries.
○ The professor can log out at any time using the logout button.
2. Dashboard Cards:
○ Manage Students: This card leads to the section where professors can view
and manage student information.
○ Mark Attendance: Professors can easily mark attendance for their classes
through this card.
○ View Reports: This card grants access to detailed reports on student
attendance for performance tracking.
○ Each card is equipped with a prominent button that directs professors to the
respective section for seamless navigation.
3. Raise Query Modal:
○ The "Raise a Query" feature allows professors to submit questions or
concerns, with a simple form to input the query.
4. Footer:
○ At the bottom, a footer with copyright details and contact information
enhances professionalism and offers a way for users to reach out for support.
Students:
Key features:
Search and Filter Students: Professors can search for students by name or ID and filter
them by division (Div A, Div B) to quickly locate specific students.
Add New Students: Professors can add new students by entering their name and division
through a simple form.
View and Edit Student Information: Each student's information, including their name and
division, is displayed in a clean and modern card layout. Professors can view the details and
make edits, such as updating the student's name or division, directly within a modal.
Remove Students: If necessary, professors can remove students from the system through a
simple "Remove" button.
Pagination: The page supports pagination to manage large student databases efficiently,
allowing professors to navigate through the records in manageable chunks.
Modern, Responsive Design: The page is built with Bootstrap, ensuring it is fully
responsive and looks good on both desktop and mobile devices. Cards are used for each
student, creating a visually appealing and organized layout.
Interactive Elements: Hover effects on cards and modals provide a modern and interactive
user experience.
Consistent Branding: The design incorporates modern colors and styles for buttons,
headers, and cards, ensuring consistency across the application and maintaining a
professional and clean look.
Mark attendance:
Key features:
Session Management: Ensures only logged-in professors can access the page.
Database Integration: Fetches student data and stores attendance records in the database.
Attendance Marking: Allows professors to mark each student's attendance as "Present" or
"Absent."
Dynamic Student List: Displays students' details (ID, Name, Division) in a table for easy
attendance marking.
Success Message: Shows a confirmation message after submitting attendance.
Attendance Summary: Displays a summary of the day's attendance (Present, Absent, Total
students).
Responsive Design: UI adapts to different screen sizes, using Bootstrap for a modern look.
Navbar Navigation: Provides links to Dashboard, Reports, and Logout, with a profile
picture.
Form Submission: Attendance data is submitted via POST, updating the database
accordingly.
Footer: Contains copyright information and contact details.
Attendance report:
Key features:
User Authentication: Professors are required to log in to access attendance reports.
Attendance Reports: Displays overall attendance for each student, showing present and
absent days.
Monthly Attendance Data: Tracks student attendance month-by-month.
Defaulter Detection: Identifies students with more than 2 absences and marks them as
defaulters.
Raise Red Flag: Professors can flag students for attendance issues by entering their name
and ID.
Dynamic Table: Presents student attendance data in a table, with dynamic rows populated
by database results.
Chart Visualization: Uses Chart.js to display a bar chart for visualizing attendance trends
over multiple months.
Responsive Design: Adapts the UI for various screen sizes, ensuring a mobile-friendly
layout.
Interactive Modal: Allows professors to raise a red flag for students via a modal popup.
Footer with Contact Info: Provides a simple footer with copyright and contact details.
Raise a query:
