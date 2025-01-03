<?php

use App\Models\Membership;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 4)->unique();
            $table->unsignedInteger('room_discount')->default(0)->comment('Discount percentage for room prices');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('membership_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('female')->default(0);
            $table->unsignedInteger('male')->default(0);
            $table->enum('type', ['standard', 'promotion'])->default('standard');
            $table->string('promotion_name')->nullable();
            $table->timestamp('effective_from');
            $table->timestamp('effective_to')->nullable();
            $table->foreignIdFor(Membership::class)->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['membership_id', 'type', 'effective_from'], 'unique_active_standard_price');

            $table->index(['membership_id', 'type', 'effective_from', 'effective_to'], 'idx_membership_price_lookup');
            $table->index(['type', 'effective_from', 'effective_to'], 'idx_price_date_range');
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

            $table->index('user_email');
            $table->index(['start_date', 'end_date']);

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->index(['membership_number', 'start_date', 'end_date'], 'idx_membership_number_period');
            $table->index(['user_id', 'start_date', 'end_date'], 'idx_active_membership');
        });

        DB::statement('ALTER TABLE memberships ADD CONSTRAINT check_room_discount CHECK (room_discount >= 0 AND room_discount <= 100)');

        DB::statement('ALTER TABLE membership_prices ADD CONSTRAINT check_prices CHECK (female >= 0 AND male >= 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_prices');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('membership_sequences');
        Schema::dropIfExists('user_memberships');
    }
};
