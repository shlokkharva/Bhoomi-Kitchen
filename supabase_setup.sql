-- ============================================================
-- RESTAURANT MANAGEMENT SYSTEM - POSTGRESQL SCHEMA (SUPABASE)
-- ============================================================

-- Drop existing types if they exist
DROP TYPE IF EXISTS waiter_status CASCADE;
DROP TYPE IF EXISTS table_status CASCADE;
DROP TYPE IF EXISTS order_status CASCADE;
DROP TYPE IF EXISTS payment_method CASCADE;
DROP TYPE IF EXISTS payment_status CASCADE;

-- Create Custom Types
CREATE TYPE waiter_status AS ENUM ('pending', 'approved', 'rejected');
CREATE TYPE table_status AS ENUM ('available', 'occupied');
CREATE TYPE order_status AS ENUM ('pending', 'accepted', 'preparing', 'serving', 'completed');
CREATE TYPE payment_method AS ENUM ('cash', 'card', 'online');
CREATE TYPE payment_status AS ENUM ('pending', 'completed');

-- Create Tables
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE waiters (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    status waiter_status DEFAULT 'pending',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE menu_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE menu_items (
    id SERIAL PRIMARY KEY,
    category_id INT REFERENCES menu_categories(id) ON DELETE SET NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255)
);

CREATE TABLE tables (
    id SERIAL PRIMARY KEY,
    table_number INT UNIQUE NOT NULL,
    capacity INT NOT NULL,
    status table_status DEFAULT 'available'
);

CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    waiter_id INT REFERENCES waiters(id),
    table_id INT REFERENCES tables(id),
    status order_status DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL,
    tax_amount DECIMAL(10,2) NOT NULL,
    service_charge DECIMAL(10,2) NOT NULL,
    tip_amount DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INT REFERENCES orders(id) ON DELETE CASCADE,
    menu_item_id INT REFERENCES menu_items(id),
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    order_id INT REFERENCES orders(id),
    method payment_method NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status payment_status DEFAULT 'completed',
    payment_date TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Insert Default Data
INSERT INTO admins (username, password) VALUES ('admin', '$2y$10$wE/.7d9w9F/l6N1yU2T4ZOYwQvM7F.m2pU8wL2J6k7qE9lD5wzP1a');

INSERT INTO tables (table_number, capacity) VALUES (1, 2), (2, 2), (3, 4), (4, 4), (5, 6), (6, 8);

INSERT INTO menu_categories (name) VALUES ('Starters'), ('Main Course'), ('Desserts'), ('Drinks');

-- Sample Menu Items
INSERT INTO menu_items (category_id, name, description, price, image_url) VALUES
(1, 'Paneer Tikka', 'Soft paneer cubes marinated in spiced yogurt, grilled to perfection.', 249.00, 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=400'),
(1, 'Veg Spring Rolls', 'Crispy golden rolls filled with seasoned mixed vegetables.', 179.00, 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400'),
(1, 'Chicken Wings', 'Smoky BBQ glazed wings with blue cheese dip.', 349.00, 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=400'),
(2, 'Butter Chicken', 'Tender chicken in rich, creamy tomato-butter sauce served with naan.', 399.00, 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400'),
(2, 'Dal Makhani', 'Slow-cooked black lentils simmered overnight in butter and cream.', 299.00, 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=400'),
(2, 'Grilled Salmon', 'Atlantic salmon fillet with lemon herb butter and seasonal vegetables.', 599.00, 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=400'),
(2, 'Margherita Pizza', 'Classic wood-fired pizza with San Marzano tomatoes and fresh mozzarella.', 349.00, 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400'),
(3, 'Chocolate Lava Cake', 'Warm chocolate cake with a gooey molten center, served with vanilla ice cream.', 199.00, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400'),
(3, 'Gulab Jamun', 'Soft milk solids dumplings soaked in rose-flavored sugar syrup.', 129.00, 'https://images.unsplash.com/photo-1598030304671-5aa1d6f21128?w=500&q=80'),
(4, 'Mango Lassi', 'Refreshing yogurt-based drink blended with sweet Alphonso mangoes.', 99.00, 'https://images.unsplash.com/photo-1546548970-71785318a17b?w=400'),
(4, 'Cold Coffee', 'Chilled espresso blended with milk and a hint of chocolate.', 149.00, 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400'),
(4, 'Fresh Lime Soda', 'Zesty sparkling lime soda, sweet or salted — your choice.', 79.00, 'https://images.unsplash.com/photo-1437418747212-8d9709afab22?w=400');
