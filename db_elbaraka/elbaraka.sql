-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 24, 2025 at 05:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elbaraka`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `heading`, `description`, `image_url`) VALUES
(1, 'Nous sommes ELBARAKA', 'Bienvenue chez Elbaraka, votre restaurant-café idéal pour savourer des plats délicieux dans une ambiance chaleureuse et conviviale. Créé en 2025, Elbaraka vous propose un menu varié , en passant par des desserts raffinés et des boissons rafraîchissantes. Chaque détail est pensé pour vous offrir une expérience culinaire unique, alliant qualité, saveur et élégance. Profitez d’un espace accueillant, parfait pour partager un moment agréable entre amis, en famille ou en solo. Découvrez notre univers et laissez-vous séduire par notre passion pour la gastronomie.', '../images/about/about-img.png');

--
-- Triggers `about`
--
DELIMITER $$
CREATE TRIGGER `enforce_single_row_before_insert` BEFORE INSERT ON `about` FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM about) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Only one row is allowed in the about table.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Burger', '2025-04-23 03:10:18'),
(2, 'Pizza', '2025-04-23 03:10:18'),
(3, 'Pasta', '2025-04-23 03:10:18'),
(4, 'Frites', '2025-04-23 03:10:18'),
(5, 'Salade', '2025-04-23 03:10:18');

-- --------------------------------------------------------

--
-- Table structure for table `footer`
--

CREATE TABLE `footer` (
  `id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hours_days` varchar(255) NOT NULL,
  `hours_time` varchar(50) NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `footer`
--

INSERT INTO `footer` (`id`, `address`, `phone`, `email`, `hours_days`, `hours_time`, `facebook`, `instagram`, `twitter`) VALUES
(1, '123 Rue Elbaraka, Casablanca', '+212 522 23 45 67', 'Elbaraka@gmail.com', 'tout les jours', '10:00 AM - 10:00 PM', 'https://www.facebook.com/elbaraka', 'https://www.instagram.com/elbaraka', 'https://twitter.com/elbaraka');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) NOT NULL,
  `sales_count` int(11) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `discount`, `image_url`, `sales_count`, `category_id`, `created_at`) VALUES
(1, 'Pizza aux Fruits de Mer', 'La Pizza aux Fruits de Mer est une pizza savoureuse garnie d’une sauce tomate parfumée, d’un mélange de crevettes, calamars et moules sautés à l’ail, et recouverte de fromage fondant.', 200.00, 10, '../images/menu/f1.png', 150, 2, '2025-04-24 00:30:00'),
(2, 'Burger Maison', 'Le Burger Maison est un savoureux burger avec un steak de bœuf haché juteux dans un pain moelleux, garni de fromage cheddar, de rondelles de tomate.', 150.00, 30, '../images/menu/f2.png', 200, 1, '2025-04-22 00:30:00'),
(3, 'Pizza Napolitaine', 'La Pizza Napolitaine est une pizza traditionnelle à pâte fine, garnie de sauce tomate San Marzano, de mozzarella di bufala, de basilic frais et d\'huile d\'olive.', 170.00, 12, '../images/menu/f3.png', 0, 2, '2025-04-24 00:30:00'),
(4, 'Homemade Vegan Burgers', 'Homemade Vegan Burgers sont des burgers savoureux à base de haricots noirs ou de pois chiches écrasés, mélangés avec des flocons d\'avoine, des oignons, de l\'ail et des carottes', 120.00, 0, '../images/menu/f7.png', 9, 1, '2025-04-22 00:30:00'),
(5, 'Burger Fusion au Curry', 'Le Chicken Curry Delight Burger est un burger savoureux avec un poulet pané au curry, servi dans un pain moelleux avec une sauce au yaourt, de la salade, des tomates et de la coriandre.', 140.00, 4, '../images/menu/f7.png', 0, 1, '2025-04-21 00:30:00'),
(6, 'Buttery Tomato Pasta', 'Le Buttery Tomato Pasta est un plat de pâtes savoureux préparé avec des pâtes al dente enrobées de beurre, de tomates cerises sautées à l\'ail, de basilic frais et de parmesan.', 100.00, 0, '../images/menu/f9.png', 0, 4, '2025-04-22 00:30:00'),
(7, 'Burger BBQ recipe', 'Le Burger BBQ est fait de steak de bœuf, fromage cheddar, sauce BBQ, bacon, oignons caramélisés, cornichons, tomates et salade, le tout dans un pain à burger. C\'est un burger savoureux et généreux, parfait pour les amateurs de BBQ.', 130.00, 15, '../images/menu/Coffee-Rubbed-Burgers-with-BBQ-Sauce.jpg', 1, 1, '2025-04-22 00:30:00'),
(8, 'Barbecue Bacon Burger', 'Le Barbecue Bacon Burger est un burger garni de bacon croustillant, de viande hachée de bœuf, de fromage fondant, de salade, de tomates, et d\'oignons, le tout nappé d\'une sauce barbecue. Il est servi dans un pain burger classique.', 235.00, 8, '../images/menu/delicious-classic-beef-burger-with-cherry-tomatoes_23-2148290641.avif', 10, 1, '2025-04-22 00:30:00'),
(9, 'Donner burger BBQ', 'Le Donner Burger est un burger inspiré du Doner Kebab, composé de viande grillée (souvent de l\'agneau, du poulet ou du bœuf), de légumes frais comme la salade, les tomates et les oignons, avec des sauces (blanche et pimentée). Il est servi dans un pain burger ou pita, et parfois avec du fromage selon les préférences.', 180.00, 0, '../images/menu/bbq-burger-980x653.webp', 3, 1, '2025-04-22 00:30:00'),
(11, 'Pasta al limone', 'Pâtes légères et rafraîchissantes avec une sauce crémeuse au citron, parmesan et herbes fraîches pour une touche d’élégance.', 200.00, 0, '../images/menu/pasta1-removebg-preview.png', 0, 3, '2025-04-22 00:30:00'),
(12, 'Burger', 'Burger gourmet avec filet de poulet croustillant, légumes frais et sauce maison dans un pain brioche moelleux.', 150.00, 0, '../images/menu/f2.png', 0, 1, '2025-04-15 00:30:00'),
(13, 'Frite à l\'ail', 'Frites dorées et croustillantes parfumées à l\'ail frais et saupoudrées de persil pour un accompagnement savoureux.', 50.00, 0, '../images/menu/frite-removebg-preview.png', 0, 4, '2025-04-22 00:30:00'),
(14, 'Salade', 'Salade fraîche composée de légumes de saison, fromage et vinaigrette maison pour une option légère et savoureuse.', 200.00, 0, '../images/menu/salad1-removebg-preview.png', 7, 5, '2025-04-22 00:30:00'),
(15, 'Salade des légumes', 'Mélange croquant de légumes frais avec olives et vinaigrette légère pour une entrée rafraîchissante et équilibrée.', 100.00, 0, '../images/menu/salad-removebg-preview.png', 2, 5, '2025-04-22 00:30:00'),
(16, 'Salade de poulet', 'Salade complète avec poulet tendre, légumes croquants et sauce onctueuse pour un repas léger et nourrissant.', 200.00, 0, '../images/menu/salad2-removebg-preview.png', 10, 5, '2025-04-22 00:30:00'),
(17, 'Pizza', 'Pizza classique avec sauce tomate, fromage fondant et olives pour une saveur méditerranéenne authentique.', 170.00, 0, '../images/menu/f3.png', 0, 2, '2025-04-24 00:30:00'),
(18, 'Frites de patates', 'Frites maison coupées à la main et cuites à la perfection pour un accompagnement croustillant et savoureux.', 70.00, 0, '../images/menu/frite-removebg-preview (1).png', 15, 4, '2025-04-22 00:30:00'),
(19, 'Spicy penne Pasta', 'Pâtes penne relevées avec une sauce épicée et des herbes fraîches pour les amateurs de saveurs intenses.', 180.00, 0, '../images/menu/pasta-removebg-preview.png', 0, 3, '2025-04-22 00:30:00'),
(20, 'Frite', 'Frites classiques croustillantes à l\'extérieur et moelleuses à l\'intérieur, parfaites pour accompagner vos plats.', 40.00, 0, '../images/menu/f5.png', 0, 4, '2025-04-22 00:30:00'),
(21, 'Pizza mixte', 'Pizza généreusement garnie de divers ingrédients pour satisfaire toutes les envies en une seule bouchée.', 150.00, 0, '../images/menu/f6.png', 0, 2, '2025-04-22 00:30:00'),
(22, 'Burger au poulet', 'Burger savoureux avec filet de poulet croustillant, légumes frais et sauce maison dans un pain moelleux.', 120.00, 0, '../images/menu/f7.png', 0, 1, '2025-04-22 00:30:00'),
(23, 'Burger Tex-Mex', 'Burger épicé avec viande assaisonnée, fromage fondu et garnitures tex-mex pour une explosion de saveurs.', 140.00, 0, '../images/menu/f8.png', 3, 1, '2025-04-22 00:30:00'),
(24, 'Spaghetti alla Caprese', 'Spaghetti avec sauce tomate fraîche, mozzarella et basilic pour un classique italien simple et délicieux.', 100.00, 0, '../images/menu/f9.png', 0, 3, '2025-04-22 00:30:00');

--
-- Triggers `menu_items`
--
DELIMITER $$
CREATE TRIGGER `check_discount_before_insert` BEFORE INSERT ON `menu_items` FOR EACH ROW BEGIN
    IF NEW.discount < 0 OR NEW.discount > 100 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'La remise doit être comprise entre 0 et 100';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_discount_before_update` BEFORE UPDATE ON `menu_items` FOR EACH ROW BEGIN
    IF NEW.discount < 0 OR NEW.discount > 100 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'La remise doit être comprise entre 0 et 100';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `id_card` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `notes` text DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','delivered','cancelled') DEFAULT 'pending',
  `order_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `day_name` varchar(50) NOT NULL,
  `day_of_week` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `highlight_text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `day_name`, `day_of_week`, `title`, `description`, `highlight_text`) VALUES
(1, 'Lundi', 1, '? Lundi - Offre Famille', 'Réservez une table pour votre famille : {highlight}', '2 personnes ne paient pas !'),
(2, 'Mardi', 2, '? Mardi - Menu Gratuit Enfant', 'Pour chaque plat adulte, un menu enfant est {highlight}.', 'offert'),
(3, 'Mercredi', 3, '? Mercredi - 2 pour 1 Pizza', 'Achetez une pizza, {highlight}', 'la 2e est gratuite'),
(4, 'Jeudi', 4, '? Jeudi - Pasta Gourmande', 'Pour tout plat de pâtes commandé, une petite salade est {hightlight}!', 'offerte'),
(5, 'Vendredi', 5, '? Vendredi - -10% sur pâtisseries', '{highlight} sur toutes les pâtisseries.', '10% de réduction'),
(6, 'Samedi', 6, '🍽 Samedi - Menu Spécial Week-end', 'Menu complet à prix réduit pour toute la famille.', 'Menu spécial week-end'),
(7, 'Dimanche', 0, '🍽 Dimanche - Livraison Gratuite', 'Livraison offerte sur toutes les commandes à partir de 100 DH.', 'Livraison gratuite');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `guests` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `testimonial` text NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `testimonial`, `image_url`) VALUES
(1, 'Moana', 'Chez Elbaraka, on ne se contente pas de manger, on passe un vrai bon moment. Le cadre est agréable, le personnel est au petit soin, et les plats sont un régal. Que demander de plus ?', '../images/client_avis/F1.jpeg'),
(2, 'Hafid', 'J’ai eu une petite demande spéciale lors de ma commande, et elle a été parfaitement respectée. C’est agréable de se sentir écouté et respecté en tant que client.', '../images/client_avis/4H.jpeg'),
(3, 'Nabil', 'Même lors des heures de pointe, la livraison reste rapide et les plats arrivent impeccables. C’est rare de trouver un service aussi constant', '../images/client_avis/4H.jpeg'),
(4, 'Karim', 'L’équipe prend le temps d’écouter et de répondre aux besoins des clients. Que ce soit sur place ou pour une commande, on se sent toujours bien pris en charge.', '../images/client_avis/3H.jpeg'),
(5, 'Sabah', 'Chaque commande est un plaisir : les portions sont généreuses, les ingrédients sont frais, et la présentation donne vraiment envie. On sent que tout est fait avec soin.', '../images/client_avis/3F.jpeg'),
(6, 'Lina', 'J’ai commandé en ligne et j’ai été agréablement surpris : mon plat est arrivé en moins de 30 minutes, bien chaud et parfaitement emballé. Bravo pour cette organisation !', '../images/client_avis/2F.jpeg'),
(7, 'Safa', 'Le personnel d’Elbaraka est toujours souriant et professionnel. On sent qu’ils aiment leur travail, et ça se reflète dans l’accueil chaleureux et l’efficacité du service.', '../images/client_avis/F1.jpeg'),
(8, 'Soufiane', 'En tant que client de Elbaraka, je trouve que le service est excellent. Le personnel est amical et attentif, ce qui rend l\'expérience encore plus agréable. Concernant la livraison, elle s\'est déroulée sans accroc : le plat est arrivé chaud et bien emballé, ce qui est toujours un plus. Le temps de livraison était raisonnable, environ 30 minutes, ce qui est parfait pour un repas rapide.', '../images/client_avis/2H.jpeg'),
(9, 'Ali', 'je dois dire que le service est remarquable. Le personnel est accueillant et fait tout pour que les clients se sentent à l\'aise. La livraison a été rapide et efficace, avec un délai d\'environ 30 minutes. Les plats sont arrivés en parfait état, bien chauds et soigneusement emballés, ce qui témoigne d\'une attention aux détails.', '../images/client_avis/1H.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('admin','limited') NOT NULL DEFAULT 'limited',
  `full_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `type`, `full_name`, `created_at`) VALUES
(1, 'admin', '$2y$10$RdbdCLF16UnRHTl0Y0JucOk0dL49QY.6NFJrL1XUWNOXVVqPITMkW', 'admin', 'Souhaib', '2025-04-23 05:25:09');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `prevent_admin_deletion` BEFORE DELETE ON `users` FOR EACH ROW BEGIN
    IF OLD.username = 'admin' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'L''utilisateur « admin » ne peut pas être supprimé.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `prevent_admin_username_update` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    -- Only prevent the admin username from being changed
    IF OLD.username = 'admin' AND NEW.username != 'admin' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Updating username for admin is not allowed';
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `footer`
--
ALTER TABLE `footer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `day_of_week` (`day_of_week`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `footer`
--
ALTER TABLE `footer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;