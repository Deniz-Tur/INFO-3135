# The Golden Plate - Restaurant Reservation System

A comprehensive restaurant management system built with PHP and MySQL, featuring reservation management, staff scheduling, and administrative controls.

##  Features

### Customer Features
- **Online Reservations**: Browse available tables and make reservations
- **Real-time Availability**: Check table availability by date and time
- **Reservation Management**: View and manage your bookings

### Admin Features
- **Reservation Management**: View, approve, and manage all customer reservations
- **Staff Scheduling**: Create and manage employee work schedules with a visual calendar
- **Employee Management**: Track staff information and roles
- **Dashboard Analytics**: Monitor reservations and restaurant operations

### Staff Scheduling System
- Interactive calendar view for staff schedules
- Add, edit, and delete employee shifts
- Day-by-day schedule breakdown
- Role-based access control

##  Technologies Used

- **Backend**: PHP 8.2
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache (XAMPP)
- **Version Control**: Git & GitHub

##  Prerequisites

- XAMPP (or similar LAMP/WAMP stack)
- PHP 8.2 or higher
- MySQL 5.7 or higher
- Web browser (Chrome, Firefox, Safari, Edge)
- Git (for version control)

##  Installation & Setup

### 1. Clone the Repository
```bash
cd C:\xampp\htdocs
git clone https://github.com/Deniz-Tur/INFO-3135.git
cd INFO-3135
```

### 2. Database Setup

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database named `golden_plate`
3. Import the database structure:
   - Click on the `golden_plate` database
   - Go to "Import" tab
   - Select your SQL file (if you have one)
   - Click "Go"

### 3. Configure Database Connection

Edit `includes/db.php` with your database credentials:
```php
$host = 'localhost';
$dbname = 'golden_plate';
$username = 'root';
$password = ''; // Your MySQL password
```

### 4. Start XAMPP

1. Open XAMPP Control Panel
2. Start Apache
3. Start MySQL

### 5. Access the Application

Open your browser and navigate to:
```
http://localhost/the-golden-plate/
```

##  Project Structure
```
INFO-3135/
├── the-golden-plate/
│   ├── schedule/
│   │   ├── calendar.php          # Staff calendar view
│   │   ├── add_schedule.php      # Add new shifts
│   │   ├── edit_schedule.php     # Edit existing shifts
│   │   └── delete_schedule.php   # Delete shifts
│   ├── includes/
│   │   ├── db.php               # Database connection
│   │   ├── header.php           # Common header
│   │   └── footer.php           # Common footer
│   ├── index.php                # Homepage
│   ├── admin_dashboard.php      # Admin dashboard
│   ├── admin_reservations.php   # Reservation management
│   ├── login.php               # User login
│   ├── logout.php              # User logout
│   └── style.css               # Global styles
└── README.md                    # This file
```

##  User Roles

### Admin
- Full access to all features
- Manage reservations
- Create/edit staff schedules
- View analytics
<img width="1678" height="1416" alt="Admin Dashboard" src="https://github.com/user-attachments/assets/ceeaf3fd-15ef-4582-a5cc-cb4a42582498" />

### Staff
- View assigned schedules
- Limited reservation access
  <img width="1542" height="1498" alt="staaffcreation" src="https://github.com/user-attachments/assets/2c5b1328-1a17-47be-a2b0-5e44938356bc" />

<img width="1656" height="1514" alt="staffcalendar" src="https://github.com/user-attachments/assets/3764e6bb-f008-41e1-83bf-144777cbd888" />

### Customer
- Make reservations
- View own bookings
- <img width="1658" height="1422" alt="user reservation" src="https://github.com/user-attachments/assets/57e16822-29f8-4c96-b6f4-a5f22bf08f31" />


##  Default Login Credentials

**Admin Account:**
- Username: `admin`
- Password: `admin123`

 **Important**: Change default passwords in production!

##  Database Schema

### Main Tables

- `users` - User accounts and authentication
- `employees` - Staff information
- `schedules` - Employee work schedules
- `reservations` - Customer reservations
- `tables` - Restaurant table information

##  Key Features Implementation

### Staff Scheduling
- Calendar-based interface
- Drag-and-drop functionality (future enhancement)
- Shift conflict detection
- Export schedules (future enhancement)

### Reservation System
- Real-time table availability
- Email confirmations (future enhancement)
- Cancellation management
- Waitlist functionality (future enhancement)

##  Troubleshooting

### Common Issues

**Problem**: "Database connection failed"
- **Solution**: Check database credentials in `includes/db.php`
- Ensure MySQL service is running in XAMPP

**Problem**: "Page not found" errors
- **Solution**: Verify Apache is running
- Check that files are in `C:\xampp\htdocs\the-golden-plate\`

**Problem**: Changes not appearing
- **Solution**: Clear browser cache (Ctrl+F5)
- Restart Apache in XAMPP

##  Development Workflow

### Making Changes

1. Edit files locally in `C:\xampp\htdocs\the-golden-plate\`
2. Test changes in browser
3. Commit changes:
```bash
git add .
git commit -m "Description of changes"
git push origin main
```

### Pulling Updates
```bash
cd C:\xampp\htdocs\the-golden-plate
git pull origin main
```

##  Future Enhancements

- [ ] Email notifications for reservations
- [ ] SMS reminders
- [ ] Online payment integration
- [ ] Customer reviews and ratings
- [ ] Multi-language support
- [ ] Mobile responsive design improvements
- [ ] Advanced reporting and analytics
- [ ] Table layout visualization

##  Developer

**Nikita** **JingXinSu** **AsmaalMasrii** **Deniz-Tur**
- Institution: Kwantlen Polytechnic University
- Course: INFO-3135
- Year: 2024-2025

##  License

This project is created for educational purposes as part of the INFO-3135 course.

##  Contributing

This is a student project. If you're a classmate and want to collaborate:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

##  Support

For issues or questions:
- Create an issue on GitHub
- Contact through KPU email

##  Acknowledgments

- Kwantlen Polytechnic University
- INFO-3135 Course Instructor
- PHP Documentation
- MySQL Documentation

---

**Last Updated**: February 2026

**Repository**: [https://github.com/Deniz-Tur/INFO-3135](https://github.com/Deniz-Tur/INFO-3135)
