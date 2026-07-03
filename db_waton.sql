-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 11:13 PM
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
-- Database: `db_waton`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_waton_admin`
--

CREATE TABLE `tbl_waton_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_username` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_photo` varchar(255) NOT NULL,
  `admin_level` enum('1','2','3') NOT NULL,
  `admin_created` date NOT NULL DEFAULT current_timestamp(),
  `admin_updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_waton_admin`
--

INSERT INTO `tbl_waton_admin` (`admin_id`, `admin_username`, `admin_email`, `admin_password`, `admin_photo`, `admin_level`, `admin_created`, `admin_updated`) VALUES
(19, 'Charles Mathew Toledo', 'charlesmathew.toledo@olivarezcollegetagaytay.edu.ph', '1eb8cf54250b600a5fd8712f8aeb0a25', 'adminphoto/awardee pic-gigapixel-low_res-scale-2_00x.png', '1', '2025-03-29', '2025-03-29'),
(20, 'superadmin', 'superadmin@email.com', 'ac497cfaba23c4184cb03b97e8c51e0a', 'adminphoto/IMG20240708003626-gigapixel-low_res-scale-4_00x.jpg', '1', '2025-04-16', '2025-04-16'),
(21, 'admin', 'admin@email.com', '0192023a7bbd73250516f069df18b500', 'adminphoto/boo.PNG', '2', '2025-05-04', '2025-05-17'),
(23, 'editor', 'editor@email.com', '50116a1a3b67657572a00ea8c6680cb9', 'adminphoto/mkay.jpg', '3', '2025-05-04', '2025-05-04'),
(27, 'ligma', 'ligma@email.com', 'df9e617b8adb79d58d7de1cc210b63e8', 'adminphoto/WIN_20240402_20_32_25_Pro.jpg', '1', '2025-05-08', '2025-05-17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_waton_category`
--

CREATE TABLE `tbl_waton_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_photo` varchar(255) NOT NULL,
  `category_desc` text NOT NULL,
  `category_added` date NOT NULL DEFAULT current_timestamp(),
  `category_updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_waton_category`
--

INSERT INTO `tbl_waton_category` (`category_id`, `category_name`, `category_photo`, `category_desc`, `category_added`, `category_updated`) VALUES
(8, 'Body Care', '../catphoto/body.jpg', 'Comprehensive solutions for cleansing, moisturizing, and maintaining healthy skin across the entire body.', '2025-04-19', '2025-04-20'),
(10, 'Face Care', '../catphoto/face.webp', 'Glow up time! Clean, smooth, and selfie-ready—every day.', '2025-04-19', '2025-04-20'),
(11, 'Hair Care', '../catphoto/hair.webp', 'Great hair days start here—wash, nourish, and style with ease.', '2025-04-19', '2025-04-20'),
(12, 'Beard Care', '../catphoto/beard.jpg', 'Tame the mane—smooth, shape, and show off that beard in style!', '2025-04-19', '2025-05-17'),
(32, 'Gym Equipment', '../../catphoto/gym.jpg', 'Muscle up! Be the best version of yourself, healthier than everyone else.', '2025-05-15', '2025-05-15'),
(33, 'Gym Supplements', '../../catphoto/gymsup.webp', 'It\'s like drugs, but for gymrats.', '2025-05-15', '2025-05-15'),
(34, 'Fragrance', '../../catphoto/fragrance.webp', 'Stop smelling bad like everyone else.', '2025-05-15', '2025-05-15'),
(35, 'Oral Care', '../../catphoto/oralcare.webp', 'Smile wide! Fresh breath and sparkle, one brush at a time.', '2025-05-15', '2025-05-15'),
(36, 'Slaves', '../catphoto/black.jpg', 'skincare not making you white? surround yourself with black people to stand out, limited edition!', '2025-05-17', '2025-05-17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_waton_order`
--

CREATE TABLE `tbl_waton_order` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `order_date` date NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,0) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  `order_payment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_waton_page`
--

CREATE TABLE `tbl_waton_page` (
  `page_id` int(11) NOT NULL,
  `page_logo` varchar(255) NOT NULL,
  `page_sitetitle` varchar(255) NOT NULL,
  `page_tagline` varchar(255) NOT NULL,
  `page_banner1` varchar(255) NOT NULL,
  `page_banner1link` varchar(255) NOT NULL,
  `page_banner2` varchar(255) NOT NULL,
  `page_banner2link` varchar(255) NOT NULL,
  `page_banner3` varchar(255) NOT NULL,
  `page_banner3link` varchar(255) NOT NULL,
  `page_banner4` varchar(255) NOT NULL,
  `page_banner4link` varchar(255) NOT NULL,
  `page_banner5` varchar(255) NOT NULL,
  `page_banner5link` varchar(255) NOT NULL,
  `page_socmed1` varchar(255) NOT NULL,
  `page_socmed1icon` varchar(255) NOT NULL,
  `page_socmed2` varchar(255) NOT NULL,
  `page_socmed2icon` varchar(255) NOT NULL,
  `page_socmed3` varchar(255) NOT NULL,
  `page_socmed3icon` varchar(255) NOT NULL,
  `page_socmed4` varchar(255) NOT NULL,
  `page_socmed4icon` varchar(255) NOT NULL,
  `page_socmed5` varchar(255) NOT NULL,
  `page_socmed5icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_waton_page`
--

INSERT INTO `tbl_waton_page` (`page_id`, `page_logo`, `page_sitetitle`, `page_tagline`, `page_banner1`, `page_banner1link`, `page_banner2`, `page_banner2link`, `page_banner3`, `page_banner3link`, `page_banner4`, `page_banner4link`, `page_banner5`, `page_banner5link`, `page_socmed1`, `page_socmed1icon`, `page_socmed2`, `page_socmed2icon`, `page_socmed3`, `page_socmed3icon`, `page_socmed4`, `page_socmed4icon`, `page_socmed5`, `page_socmed5icon`) VALUES
(1, '../pagephoto/watonlogo.png', 'waton', 'do better than everyone else', '../pagephoto/banner1.png', 'http://localhost/waton/catprod.php?id=36', '../pagephoto/banner2.png', 'http://localhost/waton/catprod.php?id=10', '../pagephoto/banner3.png', 'http://localhost/waton/catprod.php?id=11', '../pagephoto/banner4.png', 'http://localhost/waton/catprod.php?id=32', '../pagephoto/banner5.png', 'http://localhost/waton/catprod.php?id=34', 'https://www.watsons.com.ph/', '../pagephoto/watsons_logo_stacked_circle._SS300_QL85_FMpng_.png', 'https://www.facebook.com/WatsonsPH/', '../pagephoto/facebook-app-round-white-icon.png', 'https://www.instagram.com/watsonsph/?hl=en', '../pagephoto/[CITYPNG.COM]HD White Round Outline Instagram Logo Icon PNG - 2000x2000.png', 'https://www.youtube.com/user/watsonsph', '../pagephoto/pngtree-youtube-social-media-round-icon-png-image_6315993.png', 'https://ph.linkedin.com/company/watsonsphilippines?trk=affiliated-pages', '../pagephoto/circle-linkedin-512.webp');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_waton_product`
--

CREATE TABLE `tbl_waton_product` (
  `prod_id` int(11) NOT NULL,
  `prod_photo` varchar(255) NOT NULL,
  `prod_name` varchar(255) NOT NULL,
  `prod_desc` text NOT NULL,
  `prod_shots` varchar(255) NOT NULL,
  `prod_price` decimal(10,2) NOT NULL,
  `prod_stock` int(11) NOT NULL,
  `prod_by` tinyint(1) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `prod_created` date NOT NULL DEFAULT current_timestamp(),
  `prod_updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_waton_product`
--

INSERT INTO `tbl_waton_product` (`prod_id`, `prod_photo`, `prod_name`, `prod_desc`, `prod_shots`, `prod_price`, `prod_stock`, `prod_by`, `user_id`, `category_id`, `prod_created`, `prod_updated`) VALUES
(12, '../prodphoto/Derma-Roller-Face-Acne-Scars-R-1678795558.webp', 'Derma Roller Face Acne Scars Remover Beauty Products', 'GET THE GLOW: Feel your skin fresh, soft and awakened. Our Derma Roller promotes a vibrant and healthy-looking skin & complexion. Suitable to all age groups.\r\nEASY & EFFORTLESS: Our micro-needle roller is the perfect complement to your regular skin care routine. Use your favorite skin care products after Derma Rolling and maximize their effectiveness.\r\nHOW TO USE: Dermarolling for hair growth is very simple. Use a derma roller on dry hair, place the derma roller where you have hair thinning, or instill hair growth (e.g. The hairline, or the side of the temples). Roll the derma roller horizontally, vertically & diagonally. Use twice a week for the best results.\r\nSEE THE DIFFERENCE: Our Premium Derma Rollers help diminish Wrinkles, Deep Stretch Marks, Dark Circles, Dead Skin Cells and Acne Scars with regular usage. Also helps in Hair Regrowth, slowing down Ageing Skin and Hyperpigmentation.\r\nINSTRUCTIONS: Avoid prolonged sun exposure for at least 24 hours after treatment. Disinfect or Sanitize the roller with warm water or alcohol after each use.', '', 20.19, 36, 0, 0, 10, '2025-04-19', '2025-05-17'),
(13, '../prodphoto/sunscreen.avif', 'Cetaphil, Sheer Mineral Face Liquid Sunscreen, SPF 50, 1.7 fl oz (50 ml) ', '    Dermatologist Recommended for Sensitive Skin\r\n    Broad Spectrum SPF 50\r\n    Formulated for Sensitive Skin\r\n    Microbiome Gentle \r\n    Vitamin E\r\n    Water Resistant (80 Minutes)\r\n    Ideal for Use Under Makeup\r\n    Ultra-Lightweight Formula, Dry Matte Finish\r\n    Accepted National Eczema Association\r\n    Won\'t Clog Pores\r\n    Fragrance Free\r\n    Paraben Free\r\n    Mineral Oil Free\r\n    Hypoallergenic\r\n\r\nAn ultra-lightweight, 100% mineral active sunscreen that reflects both UVA and UVB rays to help prevent sunburn. This formula dries with a matte finish, making it ideal for application under makeup or for daily wear by itself.\r\n\r\nUse: Helps prevent sunburn. ', '', 15.61, 46, 0, 0, 8, '2025-04-19', '2025-04-20'),
(14, '../prodphoto/cerave.jpg', 'CeraVe Hydrating Facial Cleanser | Moisturizing Face Wash For Dry Skin | Hyaluronic Acid + Ceramides + Glycerin | Hydrating Cleanser For Normal To Dry Skin | National Eczema Association Certified', 'CeraVe Hydrating Daily Facial Cleanser cleanses, hydrates, and helps restore the protective skin barrier with three essential ceramides and hyaluronic acid.', '', 24.81, 0, 0, 0, 10, '2025-04-19', '2025-04-20'),
(15, '../prodphoto/celeteque.jpg', 'Celeteque® DermoScience™ Hydration Facial Moisturizer', 'A water-based and oil-free moisturizer with unique hydrogel formula that moisturizes the skin without the greasy after-feel. Makes use only of derma-grade ingredients and co-created with dermatologists necessary to promote overall skin health. Merging science + aesthetics, it\'s skin care smart enough for you.\r\n\r\n• With Triple Moisturizing System that contains Glycerin, Pro-Vitamin B5, and Aloe Vera\r\n• It works deep down to provide lasting relief from skin dryness.\r\n• Dermatologist tested\r\n• Hypoallergenic\r\n• Water-based and oil-free\r\n• Non-comedogenic\r\n• Paraben-free\r\n• Cruelty-free\r\n• Fragance-free', '', 3.22, 120, 0, 0, 10, '2025-04-19', '2025-04-20'),
(17, '../prodphoto/shampoo.avif', 'Pantene Pro-V Nutri-Plex Smooth & Sleek Shampoo', 'Pantene Pro-V Nutri-Plex Smooth & Sleek Shampoo deeply cleanses your hair while restoring it from within. Due to its rich formula, this shampoo is incredibly nourishing and strengthens each hair strand. Besides, it tackles roughness and frizz which results in a smooth and silky look. Thus, this shampoo makes the hair more manageable, yet always bouncy. On top of that, the shampoo hydrates the hair and scalp so they do not end up dry. Last but not least, you can count on it to strip your hair from impurities and oils and leave it with a healthy look.', '', 6.51, 230, 0, 0, 11, '2025-04-19', '2025-04-20'),
(18, '../prodphoto/conditioner.jpg', 'Pantene Daily Moisture Renewal Conditioner', 'Visibly replenish dry, lifeless hair and protect inner hair bonds against future damage. Gently cleanse and hydrate your strands with a dose of added moisture. Our Daily Moisture Renewal Shampoo provides a nutrient-rich lather that penetrates deep into hair to deliver 2X the moisture needed to hydrate and replenish dry hair. Get a burst of hydration with every wash as our moisturizing conditioner works to instantly nourish dry hair for optimal softness from root to tip. \r\n\r\nCrafted to hydrate all hair types and is gentle enough for use on permed or color-treated hair. Free of parabens and colorants for no harsh stripping.', '', 18.12, 12, 0, 0, 11, '2025-04-19', '2025-04-20'),
(19, '../prodphoto/beardoil.jpg', 'Bulldog Skincare For Men, Original Beard Oil, 1 fl oz (30 ml) ', 'Nature down to a science \r\n\r\nYou never know where the best ingredients from science and nature will come together to unleash the best in you. Oh wait, it\'s right here inside this bottle. How convenient. \r\n\r\nTime for your beard to shine. \r\n\r\nGet your shine on. This fast-absorbing beard oil combines a blend of thoughtfully curated ingredients, like aloe vera. It\'s formulated to soften, tame, and condition coarse facial hair, leaving your beard with a healthy-looking shine that doesn\'t appear greasy. Plus, our signature fragrance made with a blend of natural ingredients will leave it smelling great. \r\n\r\nAlways free from artificial colors and synthetic fragrances. ', '', 9.89, 56, 0, 0, 12, '2025-04-19', '2025-04-20'),
(21, '../prodphoto/bodywash.avif', 'Dove Body Wash Deeply Nourishing 1L', 'Dove Deeply Nourishing Body Wash has revolutionary NutriumMoisture&trade, technology alongside mild cleansers and 1/4 moisturizing cream, that nourishes better than milk to help your skin retain its natural moisture, leaving you with soft, smooth and bouncy skin. It creates a rich lather that replenishes your skin&rsquo,s nutrients while also leaving it feeling clean and cared for. The gentle formula in this Dove body wash helps maintain your skin&rsquo,s moisture barrier while delivering natural skin nourishment that goes deep into the surface layers of the skin. Suitable for daily use.', '', 9.11, 61, 0, 0, 8, '2025-04-19', '2025-04-20'),
(22, '../prodphoto/deo.webp', 'Old Spice Classic Deodorant for Men, Original Scent, 3.25 oz', 'Old Spice men\'s Deodorant reduces underarm odor for 24 hours. To use, turn the base to raise the anti-perspirant and swipe armpits for lasting sweat reduction. What\'s better than smelling like man I\'m so glad you asked, because the only thing better than smelling like a man is smelling like a man who knows how to smell manly. I\'m talking about the sophisticated scent of a man who uses Old Spice Classic Men\'s Deodorant, Invisible Solid Stick, Original Scent. Old Spice Classic Men\'s Deodorant original remembers what It\'s like to be an upstanding citrus and clove scent, before manscaping was a thing.', '', 20.39, 12, 0, 0, 8, '2025-04-19', '2025-04-20'),
(24, '../prodphoto/gel.webp', 'Eco Styler Olive Oil Styling Hair Gel, 16oz ', 'Style and protect in one easy step with Eco Style Olive Oil Styling Gel. Most hair gels keep styles in place, but contain alcohol which can dry out hair. When applied to hair that\'s already dry or damaged due to chemical processing, hair gels can inadvertently make hair even unhealthier. Eco Styler hair gel is alcohol-free, with a Level 10 hold to secure any style without compromising hair health. The nourishing formula is rich in deeply emollient olive oil to help nurture dry hair as it locks looks in place, adding a brilliant shine. Olive oil also helps your scalp regulate its moisture level, safeguarding against dryness with ever use. The lightweight gel has a lightweight feel that\'s never stiff or tacky and doesn\'t flake. Each tub of Eco Style olive gel contains 16 oz.', '', 20.86, 6, 0, 0, 11, '2025-04-19', '2025-04-20'),
(25, '../prodphoto/pomade.avif', 'GATSBY Styling Pomade Matte Moulder', 'Matte Moulder is hybrid type which is a mixture of base pomade and wax. It is perfect for shaping high rise hairstyle. It forms a trendy, retro hairstyle that looks more natural and shine-free with comb or hand styling.', '', 3.56, 11, 0, 0, 11, '2025-04-19', '2025-04-20'),
(26, '../prodphoto/shavingcream.jpg', 'Human Nature Shaving Cream for Men 100ml', '99.7% Natural | Vegan| Paraben-Free\r\n\r\nGroom with ease and care for your face like a champ! With soothing skin aloe that helps moisturize skin after shave and natural glycerin gives a smooth glide. ', '', 3.23, 18, 0, 0, 12, '2025-04-19', '2025-04-20'),
(27, '../prodphoto/nailclip.jpg', 'BEAUTY TOOL NAIL CLIPPER/NAIL CUTTER (BIG)', 'has a sharp, straight cutting edge that will leave a clean and smooth shape. The non-slip grip and sturdy handle provides added control for more precise clips. In addition, the built in guard keeps nail clippings from flying out, making clean up easy. It is a convenient size and great for traveliNG', '', 0.70, 35, 0, 0, 8, '2025-04-19', '2025-04-20'),
(28, '../prodphoto/eyecream.avif', 'Luxe Organix Bright Eyes Eye Cream 80% Galactomyces 15g', 'Luxe Organix Bright Eyes Eye Cream is packed with 80% Galactomyces Ferment Filtrate that helps reduces dark circles and puffines while providing intense moisture to fight off wrinkles. It also helps in absorption into the skin maximizing other ingredients like Niacinamide and Adenosine. Say goodbye to tired, dark looking bags with Luxe Organix Bright Eyes!', '', 3.56, 15, 0, 0, 10, '2025-04-19', '2025-04-20'),
(29, '../prodphoto/dryshampoo.avif', 'Palmolive® Naturals Fresh & Fragrant Natural Geranium Dry Shampoo', 'We get that today’s busy and on-the-go lifestyle isn’t easy. So we want to make your hair one less worry.\r\n\r\nInstantly refresh your hair in just 1 minute! Infused with Natural Geranium, our lightweight formula instantly absorbs oil with just a few quick sprays. Enjoy naturally beautiful hair that’s always delightfully fresh and fragrant!', '', 7.07, 12, 0, 0, 11, '2025-04-19', '2025-04-20'),
(30, '../prodphoto/beardsghampoo.avif', 'Bulldog Skincare For Men, Beard Shampoo & Conditioner + Aloe Vera, Original , 6.7 fl oz (200 ml) ', 'Enjoy a breath of fresh beard.\r\n\r\nKeep your beard looking, feeling and smelling its best with this 2-in-1 shampoo and conditioner. How you ask? With our special formula of thoughtfully curated ingredients, including aloe vera, and our refreshing signature scent. It cleans and freshens beards, leaving them feeling nourished and conditioned. Got it? Good. Always free from artificial colors and synthetic fragrance. ', '', 10.93, 68, 0, 0, 12, '2025-04-19', '2025-04-20'),
(31, '../prodphoto/aftershave.avif', 'Nivea Men Protect & Care Aftershave Balm 100ml', 'Nivea Men Protect & Care After Shave Balm looks after all skin types, protecting them from all kinds of external threats such as dehydration. In fact, a small portion is capable of smoothing and regenerating your complexion post-shaving, replenishing it deeply. In other words, it visibly minimizes redness as well as tightness feelings, moisturizing from within. At the same time, it reinforces one\'s defensive barrier, keeping harmful free radicals away. Thanks to a soft texture that promotes effortless absorption this also inhibits unpleasant greasiness. All in all, this is ideal for quick and frequent temperature shifts that challenge complexions as its formula combines Aloe Vera with Pro-Vitamin B5!', '', 12.98, 0, 0, 0, 12, '2025-04-19', '2025-04-20'),
(32, '../prodphoto/From-Your-Feed-Ice-Roller-for-Face-Pink-Facial-Massage-to-Reduce-Puffiness_c01035b5-d6bf-4058-829a-c59c38d345c8.eb44abd9e3d655e999570f622c49a389.webp', 'Ice Roller', 'Give yourself a spa treatment at home with this Ice Roller Tube. The Ice Roller Tube helps cool, calm, and reduce your skin\'s puffiness. The combination of cold temperature and light massage action acts quickly to tighten pores and revive skin. It can also be used to provide relief from headaches and migraines. Just add water, place in your freezer and massage all over your face to relax.', '', 33.66, 0, 0, 0, 10, '2025-04-21', '2025-04-21'),
(33, '../prodphoto/lotion.webp', 'Nivea Aloe Vera Body Lotion 625ml', 'Nivea Aloe Vera Body Lotion 625ml is a nourishing skincare essential that will leave your skin feeling refreshed, hydrated, and beautifully soft. With a complete action that provides five benefits in one single product, this body lotion is a true treat for your skin. This is thanks to a caring formula that combines the hydrating properties of Nivea\'s Deep Moisture Serum and Aloe Vera. These hydrating ingredients work to replenish and lock in moisture, consequently leaving your skin feeling supple and smooth. As a matter of fact, this body lotion provides intense hydration that lasts for up to 48 hours! Therefore, you get to say goodbye to dryness and hello to a silky, touchable skin texture.\r\n\r\nAt the same time, it offers a refreshing feeling. Besides, the lightweight and non-greasy formula absorbs quickly, allowing you to indulge in an effortless and enjoyable experience. All in all, the regular use of this body moisturizer helps to improve the overall condition of your skin as it nourishes and revitalizes, thus promoting healthy-looking skin. In conclusion, this nurturing lotion envelops your skin in the soothing embrace of Aloe Vera so you can experience the ultimate hydration and softness. Like so, the long-lasting hydration keeps your skin feeling comfortable and protected throughout the day. A true pampering moisturizer perfect to use every day and all year round to care for your body\'s skin.', '', 11.24, 61, 0, 0, 8, '2025-04-21', '2025-04-21'),
(34, '../prodphoto/beardbalm.jpg', 'King C. Gillette Soft Beard Balm 100ml', 'King C. Gillette Soft Beard Balm relies exclusively on natural ingredients to moisturize rougher beards, softening, and smoothing for greater personal comfort. As part of a range that introduces innovative as well as highly effective grooming solutions, this feather-textured balm prevents dryness from becoming a serious issue. Thanks to a blend that includes Cocoa Butter, Argan Oil, and Shea Butter, this conditions facial hair from roots to tips while also being very easy to apply. At the same, a small portion is enough to enhance the skin\'s moisture barrier, blocking different external threats, such as damaging free radicals. Ideally, resort to this as a leave-in treatment, allowing it to work for about three minutes and then rinse off. Regardless, you\'re likely to experience the ultimate level of softness as you work it in thoroughly through your beard!', '', 16.22, 50, 0, 0, 12, '2025-05-05', '2025-05-05'),
(40, '../prodphoto/WIN_20240402_20_32_25_Pro.jpg', 'human', 'human', '', 1.99, 2, 0, 0, NULL, '2025-05-08', '2025-05-08'),
(41, '../prodphoto/boo.PNG', 'Ghost', 'boo', '', 4.99, 132, 0, 0, 8, '2025-05-13', '2025-05-13'),
(42, 'prodphoto/bremod.avif', 'BREMOD Performance Styling Spray 325ml', 'Shake evenly before use according to the desired hair style.Keep 2-3cm away from the scalp while spraying this product. Make your hair styling then spray again for a fix point to increase the hardness of the hair.', '', 4.11, 34, 1, 8, 11, '2025-05-16', '2025-05-16'),
(43, '../prodphoto/curlcream.avif', 'MISE EN SCENE Curling Essence 2x Volume 150ml', 'After Shampoo And Conditioner Towel Dry Hair Put Ample Amount From Mid To Ends Of The Hair Then Start Styling', '', 8.59, 45, 0, 0, 11, '2025-05-16', '2025-05-16'),
(44, 'prodphoto/vitress.avif', 'VITRESS Hair Protect Cuticle Coat 30ml', 'Vitress Heat Protect Cuticle Coat has a Thermo-Shield Complex that moisturizes and protects the hair from the harmful effects of frequent curling, ironing, or blow-drying making your hair revitalized and nourished.', '', 1.72, 234, 1, 8, 8, '2025-05-16', '2025-05-16'),
(45, 'prodphoto/hairiron.webp', 'Professional Black Digital Hair Straightener Iron', 'Plate size: 4.5x11cm, Hair Iron 28cm long', '', 22.95, 3, 1, 8, 11, '2025-05-16', '2025-05-16'),
(48, 'prodphoto/dumbel.jpg', 'Rising DB001 Rubber Hex Dumbbell - Single (30lbs)', 'These safe, attractive dumbbells have contoured, knurled chrome handles and durable rubber-covered heads for increased safety and comfort. The heads are torque-threaded and permanently affixed to a thick 35 mm solid steel shaft improving the strength of the head/handle joint. ', '', 43.18, 235, 1, 9, 32, '2025-05-16', '2025-05-16'),
(49, 'prodphoto/hairdryer.png', 'Revlon 1875W Ionic Hair Dryer | Lightweight Design for Silky Smooth Blowouts | 3 Heat/Speed Settings | Reduce Frizz and Add Precision with Concentrator Attachment', 'Dare to be new kind of bold with Revlon Hair Tools. Whether you\'re after salon-style blowouts, one-of-a-kind waves and curls, the perfect hair accessory, or you want to reinvent your style, Revlon has the tools to take your hair where it\'s never been before. This ultra-lightweight design features static-eliminating, frizz-fighting ions to deliver silky, smooth blowouts. This hair dryer is equipped with 3 heat and speed settings for styling versatility to achieve the look you love. Plus, added Concentrator attachment gives you maximum precision. With hanging ring for easy storage. Dare to go there with Revlon Hair Tools.', '', 37.44, 12, 1, 10, 11, '2025-05-16', '2025-05-16'),
(50, '../prodphoto/treadmill.avif', 'Smart Folding 10% Motorised Incline Treadmill RUN500', 'A performance treadmill for running at up to 16 km/h, with a motorised incline of 10%, that connects to all the best fitness apps. No assembly required. Folds up small for easy storage.\r\n\r\nThe RUN500 treadmill has been designed for demanding athletes who want a performance product that\'s easy to put away after a workout.', '', 836.29, 335, 0, NULL, 32, '2025-05-17', '2025-05-17'),
(51, '../prodphoto/kettlebell.jpg', 'Rising DB013 Kettlebell (32 kg)', 'Combine muscle strengthening and cardio training! Training with a KETTLEBELL increases your strength, power, flexibility and resistance. ', '', 82.39, 0, 0, NULL, 32, '2025-05-17', '2025-05-17'),
(52, 'prodphoto/81s3gZovYoL._AC_SL1500_.jpg', 'Resistance Bands Set for Men, Women, Exercise & Workout. Fitness Bands for Leg & Bicep Work. Workout Bands for Working Out. Stretch Bands for Physical Therapy. Strength Bands. Elastic Weight Training. ', '[Premium Heavy Duty Resistance Bands] There are many resistance bands set out there, but not all of them are the same. Tribe resistance bands, exercise bands and workout bands are made of the highest quality components and are assembled right here, in the USA. If you are looking for unrivaled quality premium fitness bands for exercise to improve your home workout gear and equipment, look no further than Tribe', '', 14.99, 235, 1, 10, 32, '2025-05-17', '2025-05-17'),
(53, 'prodphoto/statbike.avif', 'Ultra-Comfortable, Self-Powered Connected Exercise Bike EB900', 'What if you could ride on your porch without needing miles of extension cord? You can do it with this bike. Set it up wherever you like, then pedal to provide the electricity it needs.\r\n\r\nDiscover the connected exercise bike: your ideal fitness buddy! Compatible with Kinomap and E-Connected apps and other connected belts and watches.', '', 501.34, 1, 1, 10, 32, '2025-05-17', '2025-05-17'),
(54, 'prodphoto/supplements-for-bodybuilding-1000x1000.webp', 'Pharma Science Muscle Building Supplements for Bodybuilding, For Personal, Packaging Size: 100gm', 'Body Booster is a Trademark, GMP, Halal and ISO Certified product. This is made up of precious and rare herbs. Body Booster is not only ordinary muscle gaining product but also works according to its name which improves entire health and develops body capabilities incredibly. As a result, it increases your stamina power, energy level, and makes the immune system and Resistance power strong. In fact, it not only enhances your Diet but also increases weight. This is one of the most effective ways to increase weight, by nutrition gain from your daily diet.', '', 11.67, 456, 1, 9, 33, '2025-05-17', '2025-05-17'),
(55, 'prodphoto/wheygolds.avif', 'Optimum Nutrition, Gold Standard® 100% Whey, Chocolate Peanut Butter, 2 lb (907 g) ', 'Optimum® Nutrition has been trusted to provide the highest quality in post-workout recovery, pre-workout energy, and on-the-go sports nutrition for over 35 years and in 90+ countries. After careful supplier selection, ingredients are tested to assure purity, potency and composition. We hold ourselves to the highest production standards, all so you can unlock your body´s full potential. \r\n\r\nGold Standard 100% Whey™ is designed for maximum mixability and superior drinkability.', '', 60.41, 418, 1, 9, 33, '2025-05-17', '2025-05-17'),
(56, 'prodphoto/creatine.jpg', 'Optimum Nutrition Micronized Creatine Monohydrate Powder, Blueberry Lemonade Creatine, 60 Servings, 360 Grams (Packaging May Vary) ', '\r\n    Flavored Creatine by Optimum Nutrition in a tasty blueberry lemonade flavor\r\n    5g of Creatine Monohydrate per serving\r\n    No scoop included (1 tsp per serving)\r\n    Optimum Nutrition is the World\'s #1 Sports Nutrition Brand. Banned substance tested - Highest quality control measures so you feel comfortable and safe consuming the product\r\n    Can support increases in energy, endurance and recovery with a well-blanaced diet and consistent exercise program\r\n', '', 17.97, 453, 1, 9, 33, '2025-05-17', '2025-05-17'),
(57, '../prodphoto/KillIt_BlueberryLemonade_Tier_WEB.webp', 'Kill It Pre-Workout', 'Now Kill It Pre-Workout is more hardcore than ever! This outstanding pre-workout gives lifters the option of 1 or 2 scoops, which is perfect for customizing it for the type of workout you need that day. Kill It Pre-workout features an exceptional combination of fully disclosed, effectively dosed ingredients in a balanced formula! ', '', 39.99, 34, 0, NULL, 33, '2025-05-17', '2025-05-17'),
(58, '../prodphoto/ckfparfum.avif', 'CALVIN KLEIN One 100ml', 'ck one is an accessible clean and easy scent. It is meant to be used lavishly and smells great on everyone.', '', 76.98, 66666, 0, NULL, 34, '2025-05-17', '2025-05-17'),
(59, '../prodphoto/giorgio-armani-009627gi_02.webp', 'Giorgio Armani Emporio Armani Stronger with You Intensely Edp for Him', 'More deep and addictive, the Emporio armani stronger with you intensely allows you to reveal your most stylish and sexy facet!', '', 83.45, 50, 0, NULL, 34, '2025-05-17', '2025-05-17'),
(60, '../prodphoto/diorparfum.webp', 'Christian Dior Sauvage 100ml', 'Dior launches its new fragrance Sauvage, with the name originating from the fragrance Eau Sauvage from 1966, although the two don’t belong to the same collection. Sauvage is inspired by wild, open spaces; blue sky that covers rocky landscapes, hot under the desert sun. ', '', 52.30, 4, 0, NULL, 34, '2025-05-17', '2025-05-17'),
(61, '../prodphoto/afnaf8.webp', 'Afnan 9am Dive Cologne', '9Am Dive takes on a unique and invigorating olfactory journey that starts with a zesty and fresh mix of pink pepper, lemon, mint and black currant. The fruity aroma continues with hints of apple but gets infused with an outdoorsy vibe thanks to cedar and incense. The enticement continues as the cologne finishes with a memorable base of jasmine, ginger, patchouli and sandalwood.', '', 29.99, 48, 0, NULL, 34, '2025-05-17', '2025-05-17'),
(62, '../prodphoto/creedparfum.webp', 'Creed for Men Aventus Cologne', 'Aventus awakens the senses with a crisp, sweet and fruity blend of apple, bergamot, blackcurrant and pineapple before unveiling middle notes of birch wood, jasmine, patchouli and rose. Its luxuriously honeyed and indolic center transitions into a base of ambergris, musk, oakmoss and vanilla, creating a sensual earthy and dark sugary finish with marine undertones.', '', 369.99, 468, 0, NULL, 34, '2025-05-17', '2025-05-17'),
(63, '../prodphoto/orahex.avif', 'ORAHEX ORAL RINSE REG 120ML', 'Orahex oral rinse contains 0.12% Chlorhexidine Gluconate which is known as the gold standard anti-microbial agent. Approximately 30% of Chlorhexidine Gluconate is retained in the oral cavity because of its substantive property. Orahex oral rinse contains alcohol.', '', 2.60, 20, 0, NULL, 35, '2025-05-17', '2025-05-17'),
(64, '../prodphoto/listerine.avif', 'Listerine Total Care Mouthwash 100ml - For Complete Oral Care,Toothbrush Routine,Use with Toothpaste', '6 Benefits in 1!\r\nHelps prevent cavities and reduces plaque formation\r\nMaintains healthy gums\r\nHelps keep teeth naturally white\r\nKills mouth germs and freshens breath', '', 1.99, 400, 0, NULL, 35, '2025-05-17', '2025-05-17'),
(65, 'prodphoto/oralbelec.avif', 'ORAL B iO3 Handle Toothbrush', 'Contents: 1 Oral-B iO Series 3 Rechargeable Electric Toothbrush (Matte White), 1 Oral-B iO Ultimate Clean Replacement Brush Head, 1 Charger', '', 62.68, 50, 1, 8, 35, '2025-05-17', '2025-05-17'),
(66, 'prodphoto/oralrinse.avif', 'PERFECT SMILE Whitening Oral Rinse 250ml', 'Perfect Smile Whitening Oral Rinse does not only freshen your breath in an instant but also whitenins your teeth in 7days!\r\nThis whitening kit is good for 7 days of use, complete with teeth color check sheet.\r\n\r\nGreen Tea extract- heps fight cavities, gum disease and bad breath giving you a refreshing feeling after a long day\r\nVitamin C- making it a natural teeth whitening agent that gently removes stains giving you whiter teeth with every use.', '', 3.56, 356, 1, 8, 35, '2025-05-17', '2025-05-17'),
(67, 'prodphoto/nigga.jpg', 'Black Man', 'your greatest lifetime servant, resellable, and tradable', '', 0.20, 1, 1, 10, 36, '2025-05-17', '2025-05-17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_waton_review`
--

CREATE TABLE `tbl_waton_review` (
  `rating_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(5) NOT NULL,
  `review` text NOT NULL,
  `review_created` date NOT NULL DEFAULT current_timestamp(),
  `review_updated` date NOT NULL DEFAULT current_timestamp(),
  `review_photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_waton_user`
--

CREATE TABLE `tbl_waton_user` (
  `user_id` int(11) NOT NULL,
  `user_fname` varchar(255) NOT NULL,
  `user_lname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_photo` varchar(255) NOT NULL,
  `user_created` date NOT NULL DEFAULT current_timestamp(),
  `user_updated` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_waton_user`
--

INSERT INTO `tbl_waton_user` (`user_id`, `user_fname`, `user_lname`, `username`, `user_email`, `user_password`, `user_photo`, `user_created`, `user_updated`) VALUES
(6, 'Charles', 'Mathew', 'charlesem69', 'charlesem69@email.com', 'd444f8201a449a38265a1ce8483c9aa9', 'userphoto/WIN_20240402_20_32_25_Pro.jpg', '2025-03-29', '2025-04-11'),
(7, 'CHarlese', 'Em', 'aeae', 'aeae@email.com', '202cb962ac59075b964b07152d234b70', 'userphoto/a98b5f62-8355-404b-957e-eae58c01fbad.jpg', '2025-04-04', '2025-04-11'),
(8, 'User', 'Name', 'user', 'user@email.com', '6ad14ba9986e3615423dfca256d04e3f', 'userphoto/awardee pic 2-gigapixel-very_compressed-scale-2_00x.png', '2025-04-20', '2025-05-17'),
(9, 'Sick', 'Man', 'buyer', 'buyer@email.com', '40e868c2d8064888a2a3365a63a84d58', 'userphoto/boo.PNG', '2025-05-16', '2025-05-16'),
(10, 'Cust', 'Omer', 'customer', 'customer@email.com', 'f4ad231214cb99a985dff0f056a36242', 'userphoto/a98b5f62-8355-404b-957e-eae58c01fbad.jpg', '2025-05-16', '2025-05-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_waton_admin`
--
ALTER TABLE `tbl_waton_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_waton_category`
--
ALTER TABLE `tbl_waton_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_waton_order`
--
ALTER TABLE `tbl_waton_order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tbl_waton_page`
--
ALTER TABLE `tbl_waton_page`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `tbl_waton_product`
--
ALTER TABLE `tbl_waton_product`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `tbl_waton_review`
--
ALTER TABLE `tbl_waton_review`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `tbl_waton_user`
--
ALTER TABLE `tbl_waton_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_waton_admin`
--
ALTER TABLE `tbl_waton_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_waton_category`
--
ALTER TABLE `tbl_waton_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_waton_order`
--
ALTER TABLE `tbl_waton_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_waton_page`
--
ALTER TABLE `tbl_waton_page`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_waton_product`
--
ALTER TABLE `tbl_waton_product`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `tbl_waton_review`
--
ALTER TABLE `tbl_waton_review`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_waton_user`
--
ALTER TABLE `tbl_waton_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
