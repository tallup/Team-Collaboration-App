# Team Collaboration App

A modern team collaboration application built with Laravel 12 and Tailwind CSS v4.

## Features

- **User Authentication**: Register, login, and logout functionality
- **Team Management**: Create and manage teams with members and roles
- **Project Management**: Create projects within teams with status tracking
- **Task Management**: Create and assign tasks with priorities and due dates
- **Comments System**: Add comments to tasks and projects with nested replies
- **Dashboard**: Overview of teams, projects, and tasks with statistics
- **Modern UI**: Clean, responsive interface built with Tailwind CSS

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS v4, Alpine.js
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Authentication**: Laravel's built-in authentication system

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd team-collab-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed --class=UserSeeder
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## Default Users

The seeder creates the following test users:

- **Admin**: admin@example.com / password
- **John Doe**: john@example.com / password
- **Jane Smith**: jane@example.com / password
- **Bob Johnson**: bob@example.com / password

## Database Schema

### Core Tables
- `users` - User accounts
- `teams` - Team information
- `projects` - Project information
- `tasks` - Task information
- `comments` - Comments on tasks and projects

### Pivot Tables
- `team_user` - Team membership with roles
- `project_user` - Project membership with roles

## Key Features

### Teams
- Create teams with descriptions
- Public/private team visibility
- Role-based access (member, admin, owner)
- Team member management

### Projects
- Create projects within teams
- Project status tracking (planning, active, completed, on_hold)
- Due date management
- Progress calculation

### Tasks
- Create tasks within projects
- Assign tasks to team members
- Priority levels (low, medium, high, urgent)
- Status tracking (todo, in_progress, review, completed)
- Due date and time tracking

### Comments
- Add comments to tasks and projects
- Nested comment replies
- User attribution

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Database Migrations
```bash
php artisan migrate
php artisan migrate:rollback
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
