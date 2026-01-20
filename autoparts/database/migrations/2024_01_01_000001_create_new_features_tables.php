<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Reviews - skip if exists
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->morphs('reviewable');
                $table->tinyInteger('rating');
                $table->string('title')->nullable();
                $table->text('comment');
                $table->text('pros')->nullable();
                $table->text('cons')->nullable();
                $table->boolean('is_verified_purchase')->default(false);
                $table->string('status')->default('pending');
                $table->integer('helpful_count')->default(0);
                $table->integer('not_helpful_count')->default(0);
                $table->text('store_reply')->nullable();
                $table->timestamp('replied_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('review_images')) {
            Schema::create('review_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('review_id')->constrained()->onDelete('cascade');
                $table->string('image_path');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('review_votes')) {
            Schema::create('review_votes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('review_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('vote');
                $table->timestamps();
                $table->unique(['review_id', 'user_id']);
            });
        }

        // Questions & Answers
        if (!Schema::hasTable('questions')) {
            Schema::create('questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('question');
                $table->string('status')->default('pending');
                $table->integer('helpful_count')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('answers')) {
            Schema::create('answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('question_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('answer');
                $table->boolean('is_store_answer')->default(false);
                $table->string('status')->default('pending');
                $table->integer('helpful_count')->default(0);
                $table->timestamps();
            });
        }

        // Coupons
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('code')->unique();
                $table->string('type');
                $table->decimal('discount_value', 10, 2);
                $table->decimal('min_order_amount', 10, 2)->nullable();
                $table->decimal('max_discount', 10, 2)->nullable();
                $table->integer('usage_limit')->nullable();
                $table->integer('usage_limit_per_user')->default(1);
                $table->integer('times_used')->default(0);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_public')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('coupon_usages')) {
            Schema::create('coupon_usages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->decimal('discount_amount', 10, 2);
                $table->timestamps();
            });
        }

        // Loyalty Points
        if (!Schema::hasTable('loyalty_points')) {
            Schema::create('loyalty_points', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->integer('balance')->default(0);
                $table->integer('total_earned')->default(0);
                $table->integer('total_redeemed')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('loyalty_transactions')) {
            Schema::create('loyalty_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('type');
                $table->integer('points');
                $table->string('description');
                $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
                $table->integer('balance_after');
                $table->timestamps();
            });
        }

        // Notifications
        if (!Schema::hasTable('custom_notifications')) {
            Schema::create('custom_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('type');
                $table->string('title');
                $table->text('message');
                $table->json('data')->nullable();
                $table->string('action_url')->nullable();
                $table->string('icon')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // Messages
        if (!Schema::hasTable('conversations')) {
            Schema::create('conversations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_one_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('user_two_id')->constrained('users')->onDelete('cascade');
                $table->string('subject')->nullable();
                $table->timestamp('last_message_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
                $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
                $table->text('message');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('message_attachments')) {
            Schema::create('message_attachments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('message_id')->constrained()->onDelete('cascade');
                $table->string('file_path');
                $table->string('file_name');
                $table->string('file_type');
                $table->integer('file_size');
                $table->timestamps();
            });
        }

        // Return Requests
        if (!Schema::hasTable('return_requests')) {
            Schema::create('return_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('store_id')->constrained()->onDelete('cascade');
                $table->string('type');
                $table->string('status')->default('pending');
                $table->text('notes')->nullable();
                $table->text('store_note')->nullable();
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamp('received_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('return_items')) {
            Schema::create('return_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('return_id')->constrained('return_requests')->onDelete('cascade');
                $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
                $table->integer('quantity');
                $table->text('reason');
                $table->decimal('amount', 10, 2);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('return_images')) {
            Schema::create('return_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('return_id')->constrained('return_requests')->onDelete('cascade');
                $table->string('image_path');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('return_status_histories')) {
            Schema::create('return_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('return_id')->constrained('return_requests')->onDelete('cascade');
                $table->string('status');
                $table->text('note')->nullable();
                $table->unsignedBigInteger('changed_by')->nullable();
                $table->timestamps();
            });
        }

        // User Cars
        if (!Schema::hasTable('car_makes')) {
            Schema::create('car_makes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_ar')->nullable();
                $table->string('slug')->unique();
                $table->string('logo')->nullable();
                $table->string('country')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('car_models')) {
            Schema::create('car_models', function (Blueprint $table) {
                $table->id();
                $table->foreignId('make_id')->constrained('car_makes')->onDelete('cascade');
                $table->string('name');
                $table->string('name_ar')->nullable();
                $table->string('slug');
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('car_years')) {
            Schema::create('car_years', function (Blueprint $table) {
                $table->id();
                $table->foreignId('model_id')->constrained('car_models')->onDelete('cascade');
                $table->integer('year');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('user_cars')) {
            Schema::create('user_cars', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('make_id')->constrained('car_makes');
                $table->foreignId('model_id')->constrained('car_models');
                $table->integer('year');
                $table->string('nickname')->nullable();
                $table->string('vin')->nullable();
                $table->string('plate_number')->nullable();
                $table->string('color')->nullable();
                $table->integer('mileage')->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_compatibilities')) {
            Schema::create('product_compatibilities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('car_make_id')->constrained('car_makes');
                $table->foreignId('car_model_id')->constrained('car_models');
                $table->integer('year_from')->nullable();
                $table->integer('year_to')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Tickets
        if (!Schema::hasTable('tickets')) {
            Schema::create('tickets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('ticket_number')->unique();
                $table->string('subject');
                $table->string('category');
                $table->string('priority')->default('medium');
                $table->string('status')->default('open');
                $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamp('closed_at')->nullable();
                $table->tinyInteger('rating')->nullable();
                $table->text('feedback')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ticket_replies')) {
            Schema::create('ticket_replies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('message');
                $table->boolean('is_staff')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ticket_attachments')) {
            Schema::create('ticket_attachments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
                $table->string('file_path');
                $table->string('file_name');
                $table->timestamps();
            });
        }

        // Blog
        if (!Schema::hasTable('blog_categories')) {
            Schema::create('blog_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blog_tags')) {
            Schema::create('blog_tags', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blog_posts')) {
            Schema::create('blog_posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('category_id')->nullable()->constrained('blog_categories')->onDelete('set null');
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('excerpt')->nullable();
                $table->longText('content');
                $table->string('featured_image')->nullable();
                $table->string('status')->default('draft');
                $table->integer('views_count')->default(0);
                $table->timestamp('published_at')->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blog_post_tag')) {
            Schema::create('blog_post_tag', function (Blueprint $table) {
                $table->foreignId('post_id')->constrained('blog_posts')->onDelete('cascade');
                $table->foreignId('tag_id')->constrained('blog_tags')->onDelete('cascade');
                $table->primary(['post_id', 'tag_id']);
            });
        }

        if (!Schema::hasTable('blog_comments')) {
            Schema::create('blog_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained('blog_posts')->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->text('content');
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }

        // Gift Cards
        if (!Schema::hasTable('gift_cards')) {
            Schema::create('gift_cards', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->decimal('amount', 10, 2);
                $table->decimal('balance', 10, 2);
                $table->foreignId('purchaser_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('recipient_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('recipient_email')->nullable();
                $table->string('recipient_name')->nullable();
                $table->text('message')->nullable();
                $table->string('design')->default('default');
                $table->timestamp('send_date')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('redeemed_at')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gift_card_transactions')) {
            Schema::create('gift_card_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('gift_card_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('type');
                $table->decimal('amount', 10, 2);
                $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
                $table->timestamps();
            });
        }

        // Auctions
        if (!Schema::hasTable('auctions')) {
            Schema::create('auctions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('store_id')->constrained()->onDelete('cascade');
                $table->decimal('starting_price', 10, 2);
                $table->decimal('current_price', 10, 2);
                $table->decimal('reserve_price', 10, 2)->nullable();
                $table->decimal('buy_now_price', 10, 2)->nullable();
                $table->decimal('min_increment', 10, 2);
                $table->timestamp('starts_at');
                $table->timestamp('ends_at');
                $table->string('status')->default('active');
                $table->unsignedBigInteger('winner_id')->nullable();
                $table->decimal('final_price', 10, 2)->nullable();
                $table->timestamp('sold_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('auction_bids')) {
            Schema::create('auction_bids', function (Blueprint $table) {
                $table->id();
                $table->foreignId('auction_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->boolean('is_auto_bid')->default(false);
                $table->timestamps();
            });
        }

        // Bundles
        if (!Schema::hasTable('bundles')) {
            Schema::create('bundles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('discount_percentage')->default(0);
                $table->string('image')->nullable();
                $table->string('status')->default('active');
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('bundle_items')) {
            Schema::create('bundle_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bundle_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->timestamps();
            });
        }

        // Flash Sales
        if (!Schema::hasTable('flash_sales')) {
            Schema::create('flash_sales', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamp('starts_at');
                $table->timestamp('ends_at');
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('flash_sale_items')) {
            Schema::create('flash_sale_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flash_sale_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->decimal('sale_price', 10, 2);
                $table->integer('quantity_limit');
                $table->integer('per_user_limit')->default(1);
                $table->integer('sold_quantity')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('flash_sale_notifications')) {
            Schema::create('flash_sale_notifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flash_sale_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('email');
                $table->timestamp('notified_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('flash_sale_purchases')) {
            Schema::create('flash_sale_purchases', function (Blueprint $table) {
                $table->id();
                $table->foreignId('flash_sale_item_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
                $table->integer('quantity');
                $table->timestamps();
            });
        }

        // Price Alerts
        if (!Schema::hasTable('price_alerts')) {
            Schema::create('price_alerts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->decimal('target_price', 10, 2);
                $table->decimal('original_price', 10, 2);
                $table->string('status')->default('active');
                $table->timestamp('notified_at')->nullable();
                $table->timestamps();
            });
        }

        // Installments
        if (!Schema::hasTable('installments')) {
            Schema::create('installments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->decimal('original_amount', 10, 2);
                $table->decimal('total_amount', 10, 2);
                $table->decimal('monthly_amount', 10, 2);
                $table->decimal('interest_rate', 5, 2);
                $table->integer('months');
                $table->string('status')->default('active');
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('installment_payments')) {
            Schema::create('installment_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('installment_id')->constrained()->onDelete('cascade');
                $table->integer('installment_number');
                $table->decimal('amount', 10, 2);
                $table->date('due_date');
                $table->string('status')->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->string('payment_method')->nullable();
                $table->timestamps();
            });
        }

        // Subscriptions
        if (!Schema::hasTable('subscription_plans')) {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->integer('duration_days');
                $table->json('features')->nullable();
                $table->integer('discount_percentage')->default(0);
                $table->boolean('free_shipping')->default(false);
                $table->boolean('priority_support')->default(false);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('plan_id')->constrained('subscription_plans');
                $table->timestamp('starts_at');
                $table->timestamp('ends_at');
                $table->string('status')->default('active');
                $table->decimal('amount_paid', 10, 2);
                $table->timestamp('cancelled_at')->nullable();
                $table->timestamps();
            });
        }

        // Affiliate
        if (!Schema::hasTable('affiliates')) {
            Schema::create('affiliates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('code')->unique();
                $table->decimal('commission_rate', 5, 2)->default(5);
                $table->string('status')->default('pending');
                $table->integer('total_clicks')->default(0);
                $table->integer('total_orders')->default(0);
                $table->decimal('total_earnings', 10, 2)->default(0);
                $table->decimal('available_balance', 10, 2)->default(0);
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('account_holder')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_clicks')) {
            Schema::create('affiliate_clicks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('affiliate_id')->constrained()->onDelete('cascade');
                $table->string('ip_address');
                $table->text('user_agent')->nullable();
                $table->string('referer')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_commissions')) {
            Schema::create('affiliate_commissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('affiliate_id')->constrained()->onDelete('cascade');
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->decimal('commission_rate', 5, 2);
                $table->string('status')->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_withdrawals')) {
            Schema::create('affiliate_withdrawals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('affiliate_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->string('payment_method');
                $table->string('status')->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_materials')) {
            Schema::create('affiliate_materials', function (Blueprint $table) {
                $table->id();
                $table->string('type');
                $table->string('name');
                $table->text('content')->nullable();
                $table->string('image')->nullable();
                $table->string('size')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Reports
        if (!Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->morphs('reportable');
                $table->text('reason');
                $table->string('category');
                $table->string('status')->default('pending');
                $table->timestamp('resolved_at')->nullable();
                $table->unsignedBigInteger('resolved_by')->nullable();
                $table->text('resolution_note')->nullable();
                $table->timestamps();
            });
        }

        // Search History
        if (!Schema::hasTable('search_histories')) {
            Schema::create('search_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('query');
                $table->integer('results_count')->default(0);
                $table->timestamps();
            });
        }

        // Product Views
        if (!Schema::hasTable('product_views')) {
            Schema::create('product_views', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('ip_address')->nullable();
                $table->timestamp('viewed_at');
                $table->timestamps();
            });
        }

        // Newsletter
        if (!Schema::hasTable('newsletter_subscribers')) {
            Schema::create('newsletter_subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('email');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->string('token');
                $table->string('status')->default('active');
                $table->json('preferences')->nullable();
                $table->timestamp('unsubscribed_at')->nullable();
                $table->timestamps();
            });
        }

        // FAQ
        if (!Schema::hasTable('faq_categories')) {
            Schema::create('faq_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('icon')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('faq_categories')->onDelete('cascade');
                $table->text('question');
                $table->text('answer');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->integer('helpful_count')->default(0);
                $table->integer('not_helpful_count')->default(0);
                $table->timestamps();
            });
        }

        // Pages
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->longText('content');
                $table->string('status')->default('active');
                $table->integer('views_count')->default(0);
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->timestamps();
            });
        }

        // Contact Messages
        if (!Schema::hasTable('contact_messages')) {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('subject');
                $table->text('message');
                $table->string('status')->default('unread');
                $table->timestamp('replied_at')->nullable();
                $table->timestamps();
            });
        }

        // Wallet
        if (!Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->decimal('balance', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
                $table->string('type');
                $table->decimal('amount', 10, 2);
                $table->string('description');
                $table->string('reference')->nullable();
                $table->string('payment_method')->nullable();
                $table->string('status')->default('completed');
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_categories');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('product_views');
        Schema::dropIfExists('search_histories');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('affiliate_materials');
        Schema::dropIfExists('affiliate_withdrawals');
        Schema::dropIfExists('affiliate_commissions');
        Schema::dropIfExists('affiliate_clicks');
        Schema::dropIfExists('affiliates');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('installment_payments');
        Schema::dropIfExists('installments');
        Schema::dropIfExists('price_alerts');
        Schema::dropIfExists('flash_sale_purchases');
        Schema::dropIfExists('flash_sale_notifications');
        Schema::dropIfExists('flash_sale_items');
        Schema::dropIfExists('flash_sales');
        Schema::dropIfExists('bundle_items');
        Schema::dropIfExists('bundles');
        Schema::dropIfExists('auction_bids');
        Schema::dropIfExists('auctions');
        Schema::dropIfExists('gift_card_transactions');
        Schema::dropIfExists('gift_cards');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_post_tag');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('product_compatibilities');
        Schema::dropIfExists('user_cars');
        Schema::dropIfExists('car_years');
        Schema::dropIfExists('car_models');
        Schema::dropIfExists('car_makes');
        Schema::dropIfExists('return_status_histories');
        Schema::dropIfExists('return_images');
        Schema::dropIfExists('return_items');
        Schema::dropIfExists('return_requests');
        Schema::dropIfExists('message_attachments');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('custom_notifications');
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('loyalty_points');
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('review_votes');
        Schema::dropIfExists('review_images');
        Schema::dropIfExists('reviews');
    }
};
