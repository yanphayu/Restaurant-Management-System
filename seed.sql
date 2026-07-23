USE rms_mid;

-- USERS
INSERT INTO users (user_name, user_password, user_role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staff'),
('staff2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staff');

-- RESTAURANT TABLES
INSERT INTO restaurant_tables (table_name, capacity, status) VALUES
('Table 1', 2, 'Available'),
('Table 2', 2, 'Available'),
('Table 3', 4, 'Occupied'),
('Table 4', 4, 'Available'),
('Table 5', 6, 'Reserved'),
('Table 6', 8, 'Available'),
('Table 7', 2, 'Occupied'),
('Table 8', 4, 'Available');

-- FOOD CATEGORIES
INSERT INTO categories (category_name, category_icon) VALUES
('Pizza', 'local_pizza'),
('Burgers', 'fastfood'),
('Sushi', 'restaurant_menu'),
('Pasta', 'ramen_dining'),
('Salads', 'eco'),
('Desserts', 'cake'),
('Drinks', 'local_cafe'),
('Appetizers', 'skillet');

-- FOODS
INSERT INTO foods (category_id, food_name, food_description, food_price, food_image, status) VALUES
(1, 'Margherita Pizza', 'Classic tomato, mozzarella, and basil', 12.99, 'margherita.jpg', 'Available'),
(1, 'Pepperoni Pizza', 'Loaded with pepperoni and cheese', 14.99, 'pepperoni.jpg', 'Available'),
(1, 'BBQ Chicken Pizza', 'Grilled chicken, BBQ sauce, red onion', 15.99, 'bbq_chicken.jpg', 'Available'),
(2, 'Classic Burger', 'Beef patty, lettuce, tomato, pickles', 10.99, 'classic_burger.jpg', 'Available'),
(2, 'Cheese Burger', 'Double cheese, caramelized onions', 12.99, 'cheese_burger.jpg', 'Available'),
(2, 'Chicken Burger', 'Crispy chicken fillet with mayo', 11.99, 'chicken_burger.jpg', 'Available'),
(3, 'Salmon Nigiri', 'Fresh salmon over seasoned rice', 8.99, 'salmon_nigiri.jpg', 'Available'),
(3, 'California Roll', 'Crab, avocado, cucumber roll', 9.99, 'california_roll.jpg', 'Available'),
(3, 'Dragon Roll', 'Eel, cucumber topped with avocado', 13.99, 'dragon_roll.jpg', 'Available'),
(4, 'Spaghetti Carbonara', 'Creamy egg sauce, pancetta, parmesan', 13.99, 'carbonara.jpg', 'Available'),
(4, 'Penne Arrabbiata', 'Spicy tomato sauce with garlic', 11.99, 'arrabbiata.jpg', 'Available'),
(4, 'Fettuccine Alfredo', 'Rich cream sauce with parmesan', 12.99, 'alfredo.jpg', 'Available'),
(5, 'Caesar Salad', 'Romaine, croutons, parmesan dressing', 8.99, 'caesar.jpg', 'Available'),
(5, 'Greek Salad', 'Feta, olives, cucumber, tomato', 9.99, 'greek.jpg', 'Available'),
(6, 'Chocolate Lava Cake', 'Warm molten center, vanilla ice cream', 7.99, 'lava_cake.jpg', 'Available'),
(6, 'Tiramisu', 'Espresso-soaked ladyfingers, mascarpone', 8.99, 'tiramisu.jpg', 'Available'),
(6, 'Cheesecake', 'New York style with berry compote', 7.49, 'cheesecake.jpg', 'Available'),
(7, 'Iced Lemon Tea', 'Refreshing chilled lemon tea', 3.99, 'lemon_tea.jpg', 'Available'),
(7, 'Fresh Orange Juice', 'Freshly squeezed orange juice', 4.99, 'orange_juice.jpg', 'Available'),
(7, 'Espresso', 'Double shot espresso', 3.49, 'espresso.jpg', 'Available'),
(8, 'Garlic Bread', 'Toasted with garlic butter and herbs', 5.99, 'garlic_bread.jpg', 'Available'),
(8, 'Mozzarella Sticks', 'Golden fried with marinara dip', 6.99, 'mozz_sticks.jpg', 'Available'),
(8, 'Soup of the Day', 'Ask your server for today\'s selection', 4.99, 'soup.jpg', 'Available');

-- ORDERS
INSERT INTO orders (user_id, table_id, order_date, status, total_amount) VALUES
(2, 3, '2026-07-23 10:30:00', 'Completed', 35.97),
(3, 7, '2026-07-23 11:15:00', 'Pending', 24.98),
(2, 1, '2026-07-23 12:00:00', 'Paid', 29.98),
(3, 4, '2026-07-22 18:30:00', 'Paid', 42.96),
(2, 5, '2026-07-22 19:00:00', 'Paid', 51.95),
(3, 2, '2026-07-21 12:45:00', 'Paid', 18.98),
(2, 6, '2026-07-21 13:30:00', 'Paid', 63.94),
(3, 8, '2026-07-20 19:15:00', 'Paid', 37.97),
(2, 3, '2026-07-20 20:00:00', 'Paid', 22.98),
(3, 4, '2026-07-19 18:00:00', 'Paid', 45.96);

-- ORDER DETAILS
INSERT INTO order_details (order_id, food_id, quantity, price, subtotal) VALUES
(1, 1, 1, 12.99, 12.99),
(1, 4, 1, 10.99, 10.99),
(1, 18, 1, 3.99, 3.99),
(1, 21, 1, 5.99, 5.99),
(2, 5, 1, 12.99, 12.99),
(2, 13, 1, 8.99, 8.99),
(2, 18, 1, 3.99, 3.99),
(3, 7, 2, 8.99, 17.98),
(3, 10, 1, 13.99, 13.99),
(4, 2, 1, 14.99, 14.99),
(4, 11, 1, 11.99, 11.99),
(4, 15, 1, 7.99, 7.99),
(4, 19, 1, 4.99, 4.99),
(5, 3, 1, 15.99, 15.99),
(5, 9, 1, 13.99, 13.99),
(5, 16, 1, 8.99, 8.99),
(5, 17, 1, 7.49, 7.49),
(5, 20, 1, 3.49, 3.49),
(5, 22, 1, 6.99, 6.99),
(6, 4, 1, 10.99, 10.99),
(6, 14, 1, 9.99, 9.99),
(7, 1, 2, 12.99, 25.98),
(7, 12, 1, 12.99, 12.99),
(7, 8, 2, 9.99, 19.98),
(7, 23, 1, 4.99, 4.99),
(8, 6, 1, 11.99, 11.99),
(8, 10, 1, 13.99, 13.99),
(8, 15, 1, 7.99, 7.99),
(8, 20, 1, 3.49, 3.49),
(9, 5, 1, 12.99, 12.99),
(9, 13, 1, 8.99, 8.99),
(10, 2, 1, 14.99, 14.99),
(10, 4, 1, 10.99, 10.99),
(10, 11, 1, 11.99, 11.99),
(10, 16, 1, 8.99, 8.99);
