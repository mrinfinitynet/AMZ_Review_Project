# Project Architecture Documentation

## Overview
This project uses a distributed architecture with a centralized database on a cloud server and multiple local admin panels.

## Architecture Components

### Cloud Server (Claude Server)
- **Admin Panel**: Full-featured administrative interface
- **Database**: Centralized MySQL/PostgreSQL database
- **Role**: Central data hub and management system

### Local Panels
- **Admin Panel**: Local administrative interface
- **Database**: No local database (connects remotely to cloud server)
- **Role**: Remote administration from multiple devices

## Benefits

### Centralized Management
- **Single Source of Truth**: All data is stored in one centralized database
- **Consistent Data**: No synchronization issues between multiple databases
- **Simplified Maintenance**: Updates and maintenance happen in one location

### Multi-Device Access
- **Multiple Devices**: Access admin panel from multiple devices simultaneously
- **Location Independence**: Manage your application from anywhere
- **Shared Access**: Multiple administrators can work with the same data

### Scalability
- **Easy Expansion**: Add new local panels without database duplication
- **Resource Efficiency**: Local panels are lightweight (no database overhead)
- **Centralized Updates**: Apply changes once on the server, reflect everywhere

## Architecture Diagram

```
┌─────────────────────────────────────┐
│      Cloud Server (Claude)          │
│                                      │
│  ┌──────────────┐  ┌─────────────┐ │
│  │ Admin Panel  │  │  Database   │ │
│  └──────────────┘  └─────────────┘ │
│                                      │
└──────────────┬──────────────────────┘
               │
               │ Remote Connection
               │
    ┏━━━━━━━━━━┻━━━━━━━━━━┓
    ┃                      ┃
┌───▼──────┐         ┌────▼──────┐
│ Local    │         │ Local     │
│ Panel 1  │         │ Panel 2   │
│          │         │           │
│ Admin    │         │ Admin     │
│ Panel    │         │ Panel     │
│          │         │           │
│ (No DB)  │   ...   │ (No DB)   │
└──────────┘         └───────────┘
```

## Technical Implementation

### Connection Setup
1. Local panels connect to cloud server via secure API endpoints
2. Authentication via API tokens or session management
3. All database operations happen on cloud server
4. Local panels send requests and receive responses

### Security Considerations
- Use HTTPS for all communications
- Implement API authentication and authorization
- Rate limiting to prevent abuse
- Regular security audits and updates

### Performance Optimization
- Cache frequently accessed data locally (temporary)
- Implement efficient API queries
- Use pagination for large datasets
- Optimize network requests

## Getting Started

### Important: Same Codebase
Both the Claude server and local panels run **the same Laravel project**. The only difference is the `.env` configuration:
- **Claude Server**: Has database configuration (normal Laravel setup)
- **Local Panels**: Has `CLAUDE_URL` set to the API endpoint

### Cloud Server Setup

1. **Deploy Laravel Application**
   ```bash
   git clone your-repository
   cd your-project
   composer install
   npm install && npm run build
   ```

2. **Configure Environment File**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure Database** (in `.env`)
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   # Leave CLAUDE_URL empty for server mode
   CLAUDE_URL=

   # Set API token for authentication
   CLAUDE_API_TOKEN=your-secure-random-token-here
   ```

4. **Run Migrations**
   ```bash
   php artisan migrate
   ```

5. **Start the Server**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

### Local Panel Setup

1. **Clone the Same Project**
   ```bash
   git clone your-repository
   cd your-project
   composer install
   npm install && npm run build
   ```

2. **Configure Environment for API Mode** (in `.env`)
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Set Claude Server URL** (in `.env`)
   ```env
   # Set the Claude server URL
   CLAUDE_URL=https://your-claude-server.com

   # Use the same API token as the server
   CLAUDE_API_TOKEN=your-secure-random-token-here

   # You can leave database config empty or commented out
   # DB_CONNECTION=mysql
   # DB_HOST=127.0.0.1
   ```

4. **Start Local Panel**
   ```bash
   php artisan serve
   ```

### API Endpoints

The following API endpoints are available:

#### Admin Management
- `GET /api/admins` - Get all admins
- `POST /api/admins` - Create new admin
- `GET /api/admins/{id}` - Get admin by ID
- `PUT /api/admins/{id}` - Update admin
- `DELETE /api/admins/{id}` - Delete admin
- `POST /api/admins/search` - Search admins

#### Project Management
- `GET /api/projects` - Get all projects
- `POST /api/projects` - Create new project
- `GET /api/projects/{id}` - Get project by ID
- `PUT /api/projects/{id}` - Update project
- `DELETE /api/projects/{id}` - Delete project
- `POST /api/projects/search` - Search projects

#### Account Management
- `GET /api/accounts` - Get all accounts
- `POST /api/accounts` - Create new account
- `GET /api/accounts/{id}` - Get account by ID
- `PUT /api/accounts/{id}` - Update account
- `DELETE /api/accounts/{id}` - Delete account
- `POST /api/accounts/search` - Search accounts

#### Post Management
- `GET /api/posts` - Get all posts
- `POST /api/posts` - Create new post
- `GET /api/posts/{id}` - Get post by ID
- `PUT /api/posts/{id}` - Update post
- `DELETE /api/posts/{id}` - Delete post
- `POST /api/posts/search` - Search posts

### API Authentication

All API requests must include the Bearer token in the header:

```bash
curl -H "Authorization: Bearer your-secure-random-token-here" \
     https://your-claude-server.com/api/admins
```

### Using the ClaudeApiService

In your controllers or services on the local panel, you can use the `ClaudeApiService`:

```php
use App\Services\ClaudeApiService;

class YourController extends Controller
{
    protected $claudeApi;

    public function __construct(ClaudeApiService $claudeApi)
    {
        $this->claudeApi = $claudeApi;
    }

    public function index()
    {
        // Check if API mode is enabled
        if ($this->claudeApi->isEnabled()) {
            // Fetch from Claude server
            $admins = $this->claudeApi->getAll('admins');
        } else {
            // Use local database
            $admins = Admin::all();
        }

        return view('admins.index', compact('admins'));
    }
}
```

### Testing the Setup

1. **Test Claude Server**
   ```bash
   curl -H "Authorization: Bearer your-token" \
        http://your-server/api/admins
   ```

2. **Test Local Panel Connection**
   - Access your local panel through browser
   - It should fetch data from Claude server via API
   - Check logs for any connection errors

## Maintenance

### Regular Tasks
- Monitor server performance
- Review and rotate API keys
- Update security patches
- Backup central database regularly
- Monitor API usage and logs

### Troubleshooting
- Check network connectivity if local panels can't connect
- Verify API credentials and tokens
- Review server logs for errors
- Ensure cloud server has adequate resources

## Future Enhancements
- Implement real-time synchronization with WebSockets
- Add offline mode with data queuing
- Enhanced caching strategies
- Multi-region deployment support
