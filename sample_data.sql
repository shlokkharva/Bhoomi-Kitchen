-- ============================================================
-- RESTAURANT MANAGEMENT SYSTEM - SAMPLE DATA
-- Run AFTER database.sql schema
-- ============================================================
USE restaurant_db;

-- Sample Menu Items (using Unsplash food images)
INSERT INTO menu_items (category_id, name, description, price, image_url) VALUES
-- Starters (cat 1)
(1, 'Paneer Tikka', 'Soft paneer cubes marinated in spiced yogurt, grilled to perfection.', 249.00, 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=400'),
(1, 'Veg Spring Rolls', 'Crispy golden rolls filled with seasoned mixed vegetables.', 179.00, 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400'),
(1, 'Chicken Wings', 'Smoky BBQ glazed wings with blue cheese dip.', 349.00, 'https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=400'),

-- Main Course (cat 2)
(2, 'Butter Chicken', 'Tender chicken in rich, creamy tomato-butter sauce served with naan.', 399.00, 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400'),
(2, 'Dal Makhani', 'Slow-cooked black lentils simmered overnight in butter and cream.', 299.00, 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=400'),
(2, 'Grilled Salmon', 'Atlantic salmon fillet with lemon herb butter and seasonal vegetables.', 599.00, 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=400'),
(2, 'Margherita Pizza', 'Classic wood-fired pizza with San Marzano tomatoes and fresh mozzarella.', 349.00, 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=400'),

-- Desserts (cat 3)
(3, 'Chocolate Lava Cake', 'Warm chocolate cake with a gooey molten center, served with vanilla ice cream.', 199.00, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400'),
(3, 'Gulab Jamun', 'Soft milk solids dumplings soaked in rose-flavored sugar syrup.', 129.00, 'https://images.unsplash.com/photo-1598030304671-5aa1d6f21128?w=500&q=80'),

-- Drinks (cat 4)
(4, 'Mango Lassi', 'Refreshing yogurt-based drink blended with sweet Alphonso mangoes.', 99.00, 'https://images.unsplash.com/photo-1546548970-71785318a17b?w=400'),
(4, 'Cold Coffee', 'Chilled espresso blended with milk and a hint of chocolate.', 149.00, 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400'),
(4, 'Fresh Lime Soda', 'Zesty sparkling lime soda, sweet or salted — your choice.', 79.00, 'https://images.unsplash.com/photo-1437418747212-8d9709afab22?w=400');
