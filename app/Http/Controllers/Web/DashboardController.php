<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Financial;
use App\Models\Information;
use App\Models\Learning;
use App\Models\MemberRegistration;
use App\Models\Organization;
use App\Models\Social;
use App\Models\Ticket;
use App\Models\Union;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalInformations = Information::count();
        $totalLearnings = Learning::count();
        $totalFinancials = Financial::count();
        $totalOrganizations = Organization::count();
        $totalSocials = Social::count();
        $totalUnions = Union::count();
        $totalVotes = Vote::count();
        $totalTickets = Ticket::count();
        
        // Dummy data for recentMembers
        // $recentMembers = collect([
        //     (object)[
        //         'id' => 1,
        //         'name' => 'Budi Santoso',
        //         'nik' => '1234567890123456',
        //         'department' => 'IT Department',
        //         'created_at' => now()->subDays(1),
        //         'status' => 'approved'
        //     ],
        //     (object)[
        //         'id' => 2,
        //         'name' => 'Siti Aminah',
        //         'nik' => '9876543210987654',
        //         'department' => 'Finance',
        //         'created_at' => now()->subDays(2),
        //         'status' => 'pending'
        //     ],
        //     (object)[
        //         'id' => 3,
        //         'name' => 'Ahmad Reza',
        //         'nik' => '3456789012345678',
        //         'department' => 'Human Resources',
        //         'created_at' => now()->subDays(3),
        //         'status' => 'approved'
        //     ],
        //     (object)[
        //         'id' => 4,
        //         'name' => 'Dewi Lestari',
        //         'nik' => '7654321098765432',
        //         'department' => 'Marketing',
        //         'created_at' => now()->subDays(4),
        //         'status' => 'pending'
        //     ],
        //     (object)[
        //         'id' => 5,
        //         'name' => 'Hendra Gunawan',
        //         'nik' => '2345678901234567',
        //         'department' => 'Operations',
        //         'created_at' => now()->subDays(5),
        //         'status' => 'approved'
        //     ],
        // ]);

        $recentMembers = MemberRegistration::latest()->limit(5)->get();

        return view('pages.dashboard', compact(
            'totalUsers',
            'totalInformations',
            'totalLearnings',
            'totalFinancials',
            'totalOrganizations',
            'totalSocials',
            'totalUnions',
            'totalVotes',
            'totalTickets',
            'recentMembers'
        ));
    }
}
