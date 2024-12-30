<?php

use App\Models\Membership;
use App\Models\MembershipPrice;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('female')->default(0);
            $table->unsignedInteger('male')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 4)->unique();
            $table->foreignIdFor(MembershipPrice::class)->unique()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('room_discount')->default(0)->comment('Discount percentage for room prices');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('membership_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('membership_code', 4)->unique();
            $table->unsignedInteger('last_assigned_sequence')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignIdFor(Membership::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('membership_number');
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_gender');
            $table->string('membership_name');
            $table->unsignedInteger('membership_price_at_joining');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE memberships ADD CONSTRAINT check_room_discount CHECK (room_discount >= 0 AND room_discount <= 100)');
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_prices');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('membership_sequences');
        Schema::dropIfExists('user_memberships');
    }
};
