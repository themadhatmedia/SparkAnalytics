<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'referrance_code')) {
        if (Schema::hasColumn('users', 'referrance_code')) {
            $users = User::where('type', 'company')->where('referrance_code', 0)->get();
            foreach ($users as $user) {
                do {
                    $referrance_code = rand(100000, 999999);
                    $checkCode = User::where('type', 'company')->where('referrance_code', $referrance_code)->get();
                } while ($checkCode->count());
                $user->referrance_code = $referrance_code;
                $user->save();
            }
        }
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['referrance_code', 'used_referrance']);
        });
    }
};
