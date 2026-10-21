<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('title')->nullable();
            $table->text('review')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->text('admin_reply')->nullable();
            $table->timestamps();
        });

        // Returns
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number', 50)->unique();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['refund','replacement','return'])->default('return');
            $table->text('reason')->nullable();
            $table->enum('status', ['requested','approved','pickup_scheduled','received','processing','completed','rejected'])->default('requested');
            $table->decimal('amount', 12, 2)->default(0);
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        // Return items
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->string('condition', 60)->nullable();
            $table->timestamps();
        });

        // Refunds
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('method', 60)->nullable();
            $table->string('reference', 150)->nullable();
            $table->enum('status', ['pending','processed','failed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // Support tickets — table name: support_tickets
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subject');
            $table->string('category', 100)->nullable();
            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');
            $table->enum('status', ['open','in_progress','waiting','resolved','closed'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Ticket messages — foreign key points to support_tickets ✅
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->enum('sender_type', ['customer','admin','agent','system']);
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->boolean('is_internal')->default(false);
            $table->timestamps();
        });

        // Enquiries
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 191)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('source', 50)->nullable();
            $table->enum('status', ['new','read','replied','closed'])->default('new');
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('return_items');
        Schema::dropIfExists('returns');
        Schema::dropIfExists('reviews');
    }
};
