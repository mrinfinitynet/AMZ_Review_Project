# API Mode Implementation Guide

## The Problem You Identified

You were absolutely correct! When `CLAUDE_URL` was set to a wrong/invalid URL like `https://masudrana.top`, the application was still showing local database content (accounts, projects, etc.). This is a **CRITICAL SECURITY FLAW** because:

1. It defeats the purpose of API-only mode
2. Data could leak from local database even when it should be disabled
3. You can't trust that the system is actually using the remote server

## The Solution: Repository Pattern

We've implemented a **Repository Pattern** that:
- ✅ **Automatically routes** to API or Database based on `CLAUDE_URL`
- ✅ **Fails properly** if API is configured but unreachable
- ✅ **Never falls back** to local database when API mode is enabled
- ✅ **Prevents data leaks** from local database in API mode

---

## How It Works

### **Repository Pattern Flow:**

```
User Request
     ↓
Controller
     ↓
Repository (checks config)
     ↓
   ┌─────────────────┐
   │ Is CLAUDE_URL   │
   │ set?            │
   └─────────────────┘
     ↓             ↓
   YES           NO
     ↓             ↓
 API Call    Local Database
     ↓             ↓
 Success?      Return Data
     ↓
   ┌─────┐
   │ YES │ → Return API Data
   └─────┘
     ↓
   ┌─────┐
   │ NO  │ → Throw Error (NO FALLBACK!)
   └─────┘
```

---

## Using Repositories in Your Controllers

### **Example: Admin Controller**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminRepo;

    public function __construct(AdminRepository $adminRepo)
    {
        $this->adminRepo = $adminRepo;
    }

    /**
     * Display all admins
     */
    public function index()
    {
        try {
            $admins = $this->adminRepo->paginate(15);

            return view('admin.admins.index', compact('admins'));
        } catch (\Exception $e) {
            // If API mode is enabled and fails, show proper error
            return back()->with('error', 'Cannot connect to Claude server: ' . $e->getMessage());
        }
    }

    /**
     * Show single admin
     */
    public function show($id)
    {
        try {
            $admin = $this->adminRepo->find($id);

            return view('admin.admins.show', compact('admin'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot fetch admin: ' . $e->getMessage());
        }
    }

    /**
     * Create new admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        try {
            $admin = $this->adminRepo->create($validated);

            return redirect()->route('admin.admins.index')
                ->with('success', 'Admin created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot create admin: ' . $e->getMessage());
        }
    }

    /**
     * Update admin
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
        ]);

        try {
            $admin = $this->adminRepo->update($id, $validated);

            return redirect()->route('admin.admins.index')
                ->with('success', 'Admin updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot update admin: ' . $e->getMessage());
        }
    }

    /**
     * Delete admin
     */
    public function destroy($id)
    {
        try {
            $this->adminRepo->delete($id);

            return redirect()->route('admin.admins.index')
                ->with('success', 'Admin deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot delete admin: ' . $e->getMessage());
        }
    }

    /**
     * Search admins
     */
    public function search(Request $request)
    {
        try {
            $admins = $this->adminRepo->search([
                'query' => $request->get('q')
            ]);

            return view('admin.admins.index', compact('admins'));
        } catch (\Exception $e) {
            return back()->with('error', 'Cannot search admins: ' . $e->getMessage());
        }
    }
}
```

---

## Available Repositories

### 1. **AdminRepository**
```php
$adminRepo = app(App\Repositories\AdminRepository::class);

// Get all admins
$admins = $adminRepo->all();

// Get with pagination
$admins = $adminRepo->paginate(15);

// Find by ID
$admin = $adminRepo->find(1);

// Create
$admin = $adminRepo->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
]);

// Update
$admin = $adminRepo->update(1, ['name' => 'Jane Doe']);

// Delete
$adminRepo->delete(1);

// Search
$admins = $adminRepo->search(['query' => 'john']);
```

### 2. **ProjectRepository**
```php
$projectRepo = app(App\Repositories\ProjectRepository::class);

// Same methods as AdminRepository
$projects = $projectRepo->paginate(15);
$project = $projectRepo->find(1);
$project = $projectRepo->create([...]);
```

### 3. **AccountRepository**
```php
$accountRepo = app(App\Repositories\AccountRepository::class);

// Same methods as AdminRepository
$accounts = $accountRepo->paginate(15);
```

### 4. **PostRepository**
```php
$postRepo = app(App\Repositories\PostRepository::class);

// Same methods as AdminRepository
$posts = $postRepo->paginate(15);
```

---

## Configuration Modes

### **Mode 1: Local Database (Claude Server)**

**.env:**
```env
# Leave CLAUDE_URL empty
CLAUDE_URL=

# Set token for incoming API requests
CLAUDE_API_TOKEN=your-secure-token-123

# Database config
DB_CONNECTION=mysql
DB_DATABASE=review
DB_USERNAME=root
DB_PASSWORD=
```

**Behavior:**
- ✅ Uses LOCAL database
- ✅ Repositories access database directly
- ✅ Acts as API server for remote panels
- ✅ Can serve API requests from local panels

---

### **Mode 2: API Mode (Local Panel)**

**.env:**
```env
# Set CLAUDE_URL to your server
CLAUDE_URL=https://masudrana.top

# Same token as server
CLAUDE_API_TOKEN=your-secure-token-123

# Database config can be empty or commented
# DB_CONNECTION=mysql
```

**Behavior:**
- ✅ Uses REMOTE API calls only
- ✅ Local database is NEVER accessed
- ✅ If API fails, proper error is shown
- ✅ NO fallback to local database
- ❌ Cannot access local database even if it exists

---

## Testing API Mode

### Test 1: With WRONG URL (Your Issue)

**.env:**
```env
CLAUDE_URL=https://wrong-url-that-does-not-exist.com
CLAUDE_API_TOKEN=some-token
```

**Expected Result:**
```
❌ Error: Cannot connect to Claude server
❌ Cannot fetch data from API
❌ No local database fallback
```

**What You Should See:**
- Error messages in your views
- Exception thrown by repositories
- NO local data displayed

---

### Test 2: With CORRECT URL

**.env:**
```env
CLAUDE_URL=https://masudrana.top
CLAUDE_API_TOKEN=correct-matching-token
```

**Expected Result:**
```
✅ Data fetched from API
✅ All operations go through API
✅ No local database access
```

---

### Test 3: Empty URL (Local Mode)

**.env:**
```env
CLAUDE_URL=
CLAUDE_API_TOKEN=some-token
```

**Expected Result:**
```
✅ Data fetched from LOCAL database
✅ All operations use local DB
✅ No API calls made
```

---

## Security Benefits

### ✅ **No Data Leaks**
When API mode is enabled, local database is NEVER accessed. Even if you have local data, it won't be shown.

### ✅ **Fail Securely**
If API is unreachable, the app shows an error instead of falling back to potentially outdated/insecure local data.

### ✅ **Clear Error Messages**
You'll know immediately if your API configuration is wrong:
```
"Cannot connect to Claude server: Connection refused"
"Cannot fetch admin: API request failed"
```

### ✅ **No Mixed Data**
You can't accidentally mix local and remote data. It's either 100% API or 100% database.

---

## Migration from Direct Models to Repositories

### **Old Way (Direct Models - INSECURE):**
```php
// ❌ This accesses local DB even in API mode
$admins = Admin::all();
```

### **New Way (Repository - SECURE):**
```php
// ✅ This respects API mode
$admins = $adminRepo->all();
```

---

## Recommended Setup

### **For Development (Local Machine):**
```env
CLAUDE_URL=
DB_CONNECTION=mysql
DB_DATABASE=review
```

### **For Production (Remote Panels):**
```env
CLAUDE_URL=https://your-production-server.com
CLAUDE_API_TOKEN=super-secure-token-here
# No DB config needed
```

---

## Summary

✅ **Problem Solved:** No more local database fallback when API mode is enabled

✅ **Secure:** API mode strictly enforces API-only access

✅ **Clear Errors:** You'll know immediately if API connection fails

✅ **Easy to Use:** Repositories work exactly like models, just dependency inject them

✅ **Automatic:** No need to check `config('claude.enabled')` in every controller - repositories handle it

---

## Need Help?

If you encounter issues:

1. Check `.env` for correct `CLAUDE_URL`
2. Verify `CLAUDE_API_TOKEN` matches on both server and panel
3. Test API endpoint manually: `curl https://your-server.com/api/admins`
4. Check Laravel logs: `storage/logs/laravel.log`

Your concerns were 100% valid, and this solution addresses them completely!
