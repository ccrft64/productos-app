<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Spatie\Permission\Models\Role; // Import Spatie Role model

class CustomRegisterController extends Controller
{
    protected $creator;
    protected $registerResponse;

    /**
     * Constructor to inject Fortify's user creator and register response.
     *
     * @param CreatesNewUsers $creator
     * @param RegisterResponse $registerResponse
     */
    public function __construct(CreatesNewUsers $creator, RegisterResponse $registerResponse)
    {
        $this->creator = $creator;
        $this->registerResponse = $registerResponse;
    }

    /**
     * Show the registration view, passing the intended role type as a prop.
     * This method will be hit by both /register and /register-admin GET requests.
     *
     * @param string $roleType The type of role ('admin' or 'user') to be passed to the Vue component.
     * @return \Inertia\Response
     */
    public function create(string $roleType = 'user')
    {
        // Optional: Basic validation to prevent invalid roleType being passed
        if (!in_array($roleType, ['admin', 'user'])) {
            abort(404); // Or redirect to the default register page
        }

        return inertia('Auth/Register', [
            'roleType' => $roleType, // Pass the role type as a prop to the Vue component
        ]);
    }

    /**
     * Handle an incoming registration request.
     * This method will be hit by the POST request from the registration form.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Laravel\Fortify\Contracts\RegisterResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        // Validate that role_type is present and valid
        $request->validate([
            'role_type' => ['required', 'string', 'in:admin,user'],
        ]);

        try {
            // Use Fortify's default user creation logic (validation, hashing password, etc.)
            $user = $this->creator->create($request->all());

            // Assign role based on 'role_type' from the request payload
            $roleName = $request->input('role_type');

            // Ensure the role exists before assigning
            $role = Role::firstOrCreate(['name' => $roleName]);
            $user->assignRole($role);

            // Log the user in after registration (consistent with default Jetstream behavior)
            auth()->login($user);

            return $this->registerResponse->toResponse($request);

        } catch (ValidationException $e) {
            // Re-throw validation exceptions so Inertia handles them correctly
            throw $e;
        }
    }
}