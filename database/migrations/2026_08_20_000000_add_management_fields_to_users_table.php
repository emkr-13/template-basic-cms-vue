<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('status')->default('active')->index();
            $table->boolean('must_change_password')->default(false);
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropSoftDeletes();
            $table->dropColumn(['status', 'must_change_password', 'invited_at', 'invitation_sent_at']);
        });
    }
};
