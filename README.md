🗂️ PHP MVC User Management & Record System

A full-stack, object-oriented PHP web application built from scratch following the Model-View-Controller (MVC) architectural pattern. Designed for scalable record management, the application features SQLite database integration via PDO, session-based authentication, a custom mathematical pagination engine, and a mobile-responsive interface enhanced with client-side JavaScript.

✨ Key Features

🏛️ Pure PHP MVC & OOP Architecture: Strict separation of concerns across data models (UserData, UserDataSet), controllers, and presentation views without heavy external framework overhead.

📄 Custom Pagination Engine: Dedicated Pagination service class utilizing ceiling division (ceil()) and dynamic SQL LIMIT/OFFSET calculations to chunk large datasets into fast, navigable pages.

🔐 Secure Authentication & State Management: Session-driven user login/logout ($_SESSION) paired with static user state caching to eliminate redundant database queries across page loads.

🛡️ SQL Injection Protection: All database operations utilize PHP Data Objects (PDO) with prepared statements and explicit parameter binding (PDO::PARAM_INT, PDO::PARAM_STR).

⚡ Batch REST API Media Seeding: Automated seeding script (populatePfp.php) that fetches profile assets from the randomuser.me API in a single batch HTTP request and recycles URLs across all database records using a modulo (%) algorithm.

📱 Responsive UI & Client-Side JS: Mobile-first layout built with semantic HTML5 and CSS Flexbox/Grid, integrated with vanilla JavaScript for dynamic DOM manipulation and interactive user controls.
