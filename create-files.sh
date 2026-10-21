#!/bin/bash

# Migrations
touch database/migrations/2024_01_01_000001_create_roles_permissions_tables.php
touch database/migrations/2024_01_01_000002_create_users_table.php
touch database/migrations/2024_01_01_000003_create_customer_agent_profiles.php
touch database/migrations/2024_01_01_000004_create_addresses_table.php
touch database/migrations/2024_01_01_000005_create_catalog_tables.php
touch database/migrations/2024_01_01_000006_create_products_tables.php
touch database/migrations/2024_01_01_000007_create_cart_wishlist_coupons.php
touch database/migrations/2024_01_01_000008_create_orders_tables.php
touch database/migrations/2024_01_01_000009_create_crm_tables.php
touch database/migrations/2024_01_01_000010_create_reviews_returns_support.php
touch database/migrations/2024_01_01_000011_create_settings_notifications_logs.php

# Models
touch app/Models/Role.php
touch app/Models/Permission.php
touch app/Models/CustomerProfile.php
touch app/Models/AgentProfile.php
touch app/Models/Category.php
touch app/Models/Brand.php
touch app/Models/Attribute.php
touch app/Models/AttributeValue.php
touch app/Models/Product.php
touch app/Models/ProductImage.php
touch app/Models/ProductVariant.php
touch app/Models/Cart.php
touch app/Models/CartItem.php
touch app/Models/Order.php
touch app/Models/OrderItem.php
touch app/Models/OrderStatusHistory.php
touch app/Models/Lead.php
touch app/Models/LeadFollowup.php
touch app/Models/LeadNote.php
touch app/Models/Review.php
touch app/Models/Coupon.php
touch app/Models/CouponUsage.php
touch app/Models/Wishlist.php
touch app/Models/Address.php
touch app/Models/Payment.php
touch app/Models/Invoice.php
touch app/Models/Setting.php
touch app/Models/ActivityLog.php
touch app/Models/Banner.php
touch app/Models/NewsletterSubscriber.php
touch app/Models/ReturnModel.php
touch app/Models/Refund.php
touch app/Models/SupportTicket.php
touch app/Models/TicketMessage.php
touch app/Models/Enquiry.php

# Services
touch app/Services/CartService.php
touch app/Services/OrderService.php
touch app/Services/LeadService.php
touch app/Services/ProductService.php
touch app/Services/ActivityLogger.php

# Middleware
touch app/Http/Middleware/RoleMiddleware.php
touch app/Http/Middleware/PermissionMiddleware.php
touch app/Http/Middleware/EnsureUserIsActive.php

# Requests
touch app/Http/Requests/ProductStoreRequest.php
touch app/Http/Requests/ProductUpdateRequest.php
touch app/Http/Requests/LeadStoreRequest.php

# Controllers - Auth
touch app/Http/Controllers/Auth/AuthController.php

# Controllers - Frontend
touch app/Http/Controllers/Frontend/HomeController.php
touch app/Http/Controllers/Frontend/ShopController.php
touch app/Http/Controllers/Frontend/CartController.php
touch app/Http/Controllers/Frontend/CheckoutController.php
touch app/Http/Controllers/Frontend/WishlistController.php

# Controllers - Customer
touch app/Http/Controllers/Customer/CustomerDashboardController.php
touch app/Http/Controllers/Customer/CustomerOrderController.php

# Controllers - Admin
touch app/Http/Controllers/Admin/DashboardController.php
touch app/Http/Controllers/Admin/ProductController.php
touch app/Http/Controllers/Admin/CategoryController.php
touch app/Http/Controllers/Admin/BrandController.php
touch app/Http/Controllers/Admin/OrderController.php
touch app/Http/Controllers/Admin/CustomerController.php
touch app/Http/Controllers/Admin/LeadController.php
touch app/Http/Controllers/Admin/FollowupController.php
touch app/Http/Controllers/Admin/SettingController.php
touch app/Http/Controllers/Admin/ActivityLogController.php
touch app/Http/Controllers/Admin/UserController.php
touch app/Http/Controllers/Admin/RoleController.php

# Controllers - Agent
touch app/Http/Controllers/Agent/AgentDashboardController.php
touch app/Http/Controllers/Agent/AgentLeadController.php

# Seeders
touch database/seeders/DatabaseSeeder.php
touch database/seeders/RolePermissionSeeder.php
touch database/seeders/AdminUserSeeder.php
touch database/seeders/SettingSeeder.php
touch database/seeders/SampleDataSeeder.php

# Views - Layouts
touch resources/views/layouts/frontend.blade.php
touch resources/views/layouts/admin.blade.php

# Views - Partials
touch resources/views/partials/header.blade.php
touch resources/views/partials/footer.blade.php
touch resources/views/partials/toast.blade.php
touch resources/views/partials/admin-sidebar.blade.php
touch resources/views/partials/admin-topbar.blade.php
touch resources/views/partials/agent-sidebar.blade.php

# Views - Components
touch resources/views/components/product-card.blade.php
touch resources/views/components/rating.blade.php

# Views - Auth
touch resources/views/auth/login.blade.php
touch resources/views/auth/register.blade.php

# Views - Home
touch resources/views/home/index.blade.php

# Views - Shop
touch resources/views/shop/index.blade.php
touch resources/views/shop/show.blade.php

# Views - Cart
touch resources/views/cart/index.blade.php

# Views - Checkout
touch resources/views/checkout/index.blade.php
touch resources/views/checkout/success.blade.php

# Views - Customer
touch resources/views/customer/dashboard.blade.php
touch resources/views/customer/orders.blade.php
touch resources/views/customer/wishlist.blade.php
touch resources/views/customer/profile.blade.php

# Views - Admin
touch resources/views/admin/dashboard.blade.php
touch resources/views/admin/orders/index.blade.php
touch resources/views/admin/orders/show.blade.php
touch resources/views/admin/products/index.blade.php
touch resources/views/admin/products/create.blade.php
touch resources/views/admin/products/edit.blade.php
touch resources/views/admin/products/show.blade.php
touch resources/views/admin/customers/index.blade.php
touch resources/views/admin/customers/show.blade.php
touch resources/views/admin/crm/leads/index.blade.php
touch resources/views/admin/crm/leads/create.blade.php
touch resources/views/admin/crm/leads/show.blade.php
touch resources/views/admin/crm/followups/index.blade.php
touch resources/views/admin/settings/index.blade.php

# Views - Agent
touch resources/views/agent/dashboard.blade.php
touch resources/views/agent/leads/index.blade.php
touch resources/views/agent/leads/show.blade.php

# Views - PDF
touch resources/views/pdf/invoice.blade.php

# Public assets
touch public/assets/css/store.css
touch public/assets/css/admin.css
touch public/assets/js/store.js
touch public/assets/js/admin.js

# Routes
touch routes/admin.php
touch routes/agent.php
touch routes/customer.php

echo "✅ All files created!"
echo ""
echo "📊 Total files: $(find database/migrations app resources/views public/assets routes -type f -newermt '1 minute ago' 2>/dev/null | wc -l)"
