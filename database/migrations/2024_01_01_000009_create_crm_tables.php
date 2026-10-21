<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_code', 50)->unique();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 150);
            $table->string('email', 191)->nullable();
            $table->string('phone', 25)->nullable();
            $table->enum('source', ['website','phone','whatsapp','walkin','referral','social','email','other'])->default('website');
            $table->string('product_interest')->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->text('message')->nullable();
            $table->enum('status', ['new','contacted','interested','followup','negotiation','converted','lost'])->default('new');
            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');
            $table->decimal('estimated_value', 12, 2)->default(0);
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->date('followup_date')->nullable();
            $table->time('followup_time')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->foreignId('converted_order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('lost_reason')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
            $table->index('followup_date');
        });

        Schema::create('lead_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['call','whatsapp','email','meeting','other'])->default('call');
            $table->dateTime('scheduled_at');
            $table->text('notes')->nullable();
            $table->text('outcome')->nullable();
            $table->enum('status', ['pending','completed','missed','cancelled'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('status');
            $table->index('scheduled_at');
        });

        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('note');
            $table->enum('visibility', ['public','internal'])->default('internal');
            $table->timestamps();
        });

        Schema::create('customer_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('note');
            $table->enum('visibility', ['public','internal'])->default('internal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_notes');
        Schema::dropIfExists('lead_notes');
        Schema::dropIfExists('lead_followups');
        Schema::dropIfExists('leads');
    }
};
