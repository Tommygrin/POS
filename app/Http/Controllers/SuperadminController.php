<?php

namespace App\Http\Controllers;

use App\Business;
use App\BusinessLocation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Lightweight, built-in super admin area.
 *
 * This is NOT the paid "Superadmin" module from Ultimate Fosters. It is a small
 * replacement that lives in the core application and lets the system
 * administrator(s) see and manage every business and user on the installation.
 *
 * Access is restricted by the `superadmin` middleware, which only allows users
 * whose username is listed in the ADMINISTRATOR_USERNAMES env variable
 * (comma separated). See config/constants.php -> administrator_usernames.
 *
 * The routes live in routes/superadmin.php and are only registered when the
 * paid module (Modules/Superadmin) is NOT present, so installing the real
 * module later will transparently take over the /superadmin URL.
 */
class SuperadminController extends Controller
{
    /**
     * Dashboard with installation-wide statistics.
     */
    public function index()
    {
        $stats = [
            'businesses' => Business::count(),
            'active_businesses' => Business::where('is_active', 1)->count(),
            'inactive_businesses' => Business::where('is_active', 0)->count(),
            'new_businesses_30d' => Business::where('created_at', '>=', now()->subDays(30))->count(),
            'users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'locations' => BusinessLocation::count(),
            'sales' => DB::table('transactions')
                ->where('type', 'sell')
                ->where('status', 'final')
                ->count(),
        ];

        $recent_businesses = Business::with('owner')
            ->withCount(['locations'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('superadmin.index')
            ->with(compact('stats', 'recent_businesses'));
    }

    /**
     * List all businesses with search + pagination.
     */
    public function businesses(Request $request)
    {
        $query = Business::with(['owner', 'currency'])
            ->withCount(['locations']);

        if ($request->filled('search')) {
            $term = '%'.trim($request->input('search')).'%';
            $query->where(function ($q) use ($term) {
                $q->where('business.name', 'like', $term)
                    ->orWhereHas('owner', function ($oq) use ($term) {
                        $oq->where('username', 'like', $term)
                            ->orWhere('email', 'like', $term)
                            ->orWhere('first_name', 'like', $term)
                            ->orWhere('last_name', 'like', $term);
                    });
            });
        }

        if ($request->input('status') === 'active') {
            $query->where('is_active', 1);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', 0);
        }

        $businesses = $query->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        // Users per business (one query instead of N).
        $user_counts = User::select('business_id', DB::raw('count(*) as total'))
            ->whereIn('business_id', $businesses->pluck('id'))
            ->groupBy('business_id')
            ->pluck('total', 'business_id');

        return view('superadmin.businesses.index')
            ->with(compact('businesses', 'user_counts'));
    }

    /**
     * Show a single business with its owner, users and locations.
     */
    public function showBusiness($id)
    {
        $business = Business::with(['owner', 'currency', 'locations'])
            ->findOrFail($id);

        $users = User::where('business_id', $id)
            ->orderBy('id')
            ->get();

        $sales_count = DB::table('transactions')
            ->where('business_id', $id)
            ->where('type', 'sell')
            ->where('status', 'final')
            ->count();

        return view('superadmin.businesses.show')
            ->with(compact('business', 'users', 'sales_count'));
    }

    /**
     * Activate / deactivate a business.
     *
     * Inactive businesses cannot log in (core login already enforces
     * business.is_active).
     */
    public function toggleBusinessActive($id)
    {
        $business = Business::findOrFail($id);

        // Never let the super admin lock themselves out.
        if ((int) $business->id === (int) auth()->user()->business_id && $business->is_active) {
            return back()->with('status', [
                'success' => 0,
                'msg' => __('core_superadmin.cannot_deactivate_own_business'),
            ]);
        }

        $business->is_active = ! $business->is_active;
        $business->save();

        return back()->with('status', [
            'success' => 1,
            'msg' => $business->is_active
                ? __('core_superadmin.business_activated')
                : __('core_superadmin.business_deactivated'),
        ]);
    }

    /**
     * List all users across all businesses.
     */
    public function users(Request $request)
    {
        $query = User::with('business:id,name');

        if ($request->filled('search')) {
            $term = '%'.trim($request->input('search')).'%';
            $query->where(function ($q) use ($term) {
                $q->where('username', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term)
                    ->orWhere('surname', 'like', $term);
            });
        }

        if ($request->filled('business_id')) {
            $query->where('business_id', $request->input('business_id'));
        }

        if (in_array($request->input('status'), ['active', 'inactive', 'terminated'])) {
            $query->where('status', $request->input('status'));
        }

        $users = $query->orderBy('id', 'desc')
            ->paginate(25)
            ->withQueryString();

        $businesses = Business::orderBy('name')->pluck('name', 'id');

        return view('superadmin.users.index')
            ->with(compact('users', 'businesses'));
    }

    /**
     * Toggle a user's status between active and inactive.
     */
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        if ((int) $user->id === (int) auth()->id()) {
            return back()->with('status', [
                'success' => 0,
                'msg' => __('core_superadmin.cannot_change_own_status'),
            ]);
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        return back()->with('status', [
            'success' => 1,
            'msg' => __('core_superadmin.user_status_updated'),
        ]);
    }
}
