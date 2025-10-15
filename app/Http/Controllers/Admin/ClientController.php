<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of clients
     */
    public function index()
    {
        $clients = Client::orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.pages.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client
     */
    public function create()
    {
        return view('admin.pages.clients.create');
    }

    /**
     * Store a newly created client
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clients,name',
            'code' => 'required|string|max:255|unique:clients,code|alpha_dash',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'generate_key' => 'nullable|boolean'
        ]);

        $clientData = [
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
            'sort_order' => $request->sort_order ?? 0
        ];

        // Generate access key if requested
        if ($request->has('generate_key') && $request->generate_key) {
            $clientData['key'] = Client::generateKey();
        }

        $client = Client::create($clientData);

        $message = 'Client created successfully!';
        if (isset($clientData['key'])) {
            $message .= ' Access Key: ' . $clientData['key'];
        }

        return redirect()
            ->route('admin.clients.index')
            ->with('success', $message);
    }

    /**
     * Show the form for editing a client
     */
    public function edit(Client $client)
    {
        return view('admin.pages.clients.edit', compact('client'));
    }

    /**
     * Update the specified client
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clients,name,' . $client->id,
            'code' => 'required|string|max:255|unique:clients,code,' . $client->id . '|alpha_dash',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer'
        ]);

        $client->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
            'sort_order' => $request->sort_order ?? 0
        ]);

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client updated successfully!');
    }

    /**
     * Remove the specified client
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client deleted successfully!');
    }

    /**
     * Toggle client active status
     */
    public function toggleStatus(Client $client)
    {
        $client->update([
            'is_active' => !$client->is_active
        ]);

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client status updated successfully!');
    }

    /**
     * Generate or regenerate access key for a client
     */
    public function generateKey(Client $client)
    {
        $key = Client::generateKey();
        $client->update(['key' => $key]);

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Access key generated successfully! Key: ' . $key);
    }

    /**
     * Remove access key from a client
     */
    public function removeKey(Client $client)
    {
        $client->update(['key' => null]);

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Access key removed successfully!');
    }
}
