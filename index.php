<?php
include 'config.php';
session_start();

$latest_rooms = mysqli_query($conn, "SELECT rooms.*, room_types.name AS room_type FROM rooms 
LEFT JOIN room_types ON rooms.room_type_id = room_types.id 
ORDER BY rooms.created_at DESC LIMIT 4");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hotel Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
  />
  <style>

  .slider-fade {
    transition: opacity 1s ease-in-out;
  }
  .btn-glow {
    box-shadow: 0 0 15px rgba(99, 102, 241, 0.6);
  }
  .animate-fade-in {
    opacity: 0;
    animation: fadeIn 1s forwards;
  }
  .animate-fade-in.delay-200 {
    animation-delay: 0.2s;
  }
  @keyframes fadeIn {
    to {
      opacity: 1;
    }
  }


  </style>
</head>
<body class="bg-gray-50">
<!-- Navbar -->
<nav class="bg-white shadow-md py-4 sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
    <h1 class="text-3xl font-extrabold text-indigo-700 tracking-tight">VistaLuxe</h1>
    <ul class="flex space-x-6 text-gray-700 font-medium">
      <li><a href="index.php" class="hover:text-indigo-600 transition">Home</a></li>
      <li><a href="reserve_room.php" class="hover:text-indigo-600 transition">Rooms</a></li>
      <li><a href="#about" class="hover:text-indigo-600 transition">About</a></li>
      <li><a href="#contact" class="hover:text-indigo-600 transition">Contact</a></li>

      <?php if (isset($_SESSION['user'])): ?>
        <li><a href="my_reservations.php" class="hover:text-indigo-600 transition">My Reservations</a></li>
      <?php endif; ?>

      <?php if (isset($_SESSION['user'])): ?>
        <li><a href="user_profile.php" class="hover:text-indigo-600 transition">Profile</a></li>
        <li><a href="logout.php" class="hover:text-indigo-600 transition">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php" class="hover:text-indigo-600 transition">Login</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<!-- Hero Slider Section -->
<section class="relative h-[85vh] overflow-hidden">
  <!-- Slider Images -->
  <div id="sliderContainer" class="absolute inset-0">
  <div class="absolute inset-0 bg-cover bg-center slider-fade" style="background-image: url('img/3.jpg');" data-title="Welcome to Our Hotel" data-subtitle="Experience the best comfort with us."></div>
  <div class="absolute inset-0 bg-cover bg-center slider-fade" style="background-image: url('img/blog1.jpg');" data-title="Elegant Rooms" data-subtitle="Spacious, elegant, and modern rooms."></div>
  <div class="absolute inset-0 bg-cover bg-center slider-fade" style="background-image: url('img/33.jpg');" data-title="Delicious Cuisine" data-subtitle="Enjoy gourmet meals prepared by top chefs."></div>
  <div class="absolute inset-0 bg-cover bg-center slider-fade" style="background-image: url('img/44.jpg');" data-title="Relax at Our Spa" data-subtitle="Unwind with our exclusive spa services."></div>
  <div class="absolute inset-0 bg-cover bg-center slider-fade" style="background-image: url('img/pool1.jpg');" data-title="Infinity Pool" data-subtitle="Swim with a view of the horizon."></div>
</div>


  <!-- Overlay -->
  <div class="absolute inset-0 bg-black bg-opacity-60"></div>

  <!-- Text Content -->
  <div class="relative z-10 flex flex-col justify-center items-center h-full text-center px-4 text-white">
    <h1 id="sliderTitle" class="text-5xl sm:text-6xl font-extrabold mb-4 animate-fade-in">
      Welcome to Our Hotel
    </h1>
    <p id="sliderSubtitle" class="text-lg sm:text-xl mb-6 text-gray-200 animate-fade-in delay-200">
      Experience the best comfort with us.
    </p>
    <a href="#rooms" class="bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 px-6 rounded-full shadow-lg transition transform hover:scale-105 duration-300">
      Explore More
    </a>
  </div>
</section>

<!-- Rooms Section -->
<section id="rooms" class="py-20 bg-gradient-to-br from-white via-blue-50 to-blue-100">
  <div class="max-w-7xl mx-auto px-6">
    <h2 class="text-4xl font-extrabold text-center text-gray-800 mb-4">Our Rooms</h2>
    <p class="text-center text-gray-600 mb-12">Explore our selection of stylish and comfortable rooms designed for relaxation and elegance.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
      <?php
        $rooms = [
          ["title" => "Deluxe Room", "desc" => "Sea view with balcony.", "price" => 150, "img" => "img/single.jpg"],
          ["title" => "Superior Suite", "desc" => "Modern suite with lounge.", "price" => 220, "img" => "img/Superior Suite.jpg"],
          ["title" => "Family Room", "desc" => "Perfect for families.", "price" => 180, "img" => "img/Family Room.jpg"],
          ["title" => "Classic Room", "desc" => "Traditional and cozy.", "price" => 130, "img" => "img/Classic Room.jpg"],
          ["title" => "Panoramic Suite", "desc" => "Views across the city.", "price" => 250, "img" => "img/Panoramic Suite.jpg"],
          ["title" => "Executive Room", "desc" => "Spacious and luxurious.", "price" => 200, "img" => "img/Executive Room.jpg"]
        ];
        foreach ($rooms as $room): ?>
        <div class="bg-white rounded-3xl overflow-hidden shadow-xl hover:scale-[1.02] transition-transform duration-300 group">
          <div class="relative">
            <img src="<?= $room['img'] ?>" class="w-full h-48 object-cover group-hover:opacity-90 transition" alt="<?= $room['title'] ?>">
            <div class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-3 py-1 rounded-full shadow">Featured</div>
          </div>
          <div class="p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-2"><?= $room['title'] ?></h3>
            <p class="text-gray-600 mb-4"><?= $room['desc'] ?></p>
            <div class="flex items-center justify-between">
              <span class="text-indigo-700 font-bold text-lg">$<?= $room['price'] ?><span class="text-sm text-gray-500"> / night</span></span>
              <?php if (isset($_SESSION['user'])): ?>
                <a href="reserve_room.php?room=<?= urlencode($room['title']) ?>"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-5 rounded-lg transition">
                  Book Now
                </a>
              <?php else: ?>
                <a href="login.php"
                   class="bg-gray-400 hover:bg-gray-500 text-white text-sm font-semibold py-2 px-5 rounded-lg transition">
                  Login to Book
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>



<!-- About Section -->
<section id="about" class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-12">
    <!-- Image -->
    <div class="md:w-1/2">
      <img src="img/Luxury & Comfort in Every Corner.jpg" alt="About Image" class="rounded-lg shadow-lg">
    </div>

    <!-- Text -->
    <div class="md:w-1/2">
      <h3 class="text-sm uppercase text-indigo-500 font-semibold mb-2">About Us</h3>
      <h2 class="text-4xl font-bold text-gray-800 mb-4">Luxury & Comfort in Every Corner</h2>
      <p class="text-gray-600 mb-6 leading-relaxed">
        Welcome to our hotel – where comfort meets elegance. Our rooms are designed with your relaxation in mind, offering modern amenities, stunning views, and premium service.
      </p>
      <a href="about.php" class="inline-block bg-indigo-600 text-white py-2 px-6 rounded-lg hover:bg-indigo-500 transition">Learn More</a>
    </div>
  </div>
</section>



<!-- Client Reviews Section -->
<section class="py-20 bg-gray-100">
  <div class="max-w-7xl mx-auto px-4 text-center">
    <h2 class="text-4xl font-extrabold text-gray-800 mb-4">What Our Clients Say</h2>
    <p class="text-gray-600 mb-12">Read the experiences and feedback from our happy guests</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php
      $reviews = mysqli_query($conn, "
        SELECT r.comment, r.rating, r.created_at, u.first_name, u.last_name
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        ORDER BY r.created_at DESC
        LIMIT 6
      ");
      while ($row = mysqli_fetch_assoc($reviews)) :
      ?>
        <div class="bg-white p-6 rounded-2xl shadow-lg transition transform hover:-translate-y-1 hover:shadow-xl">
          <div class="flex items-center mb-4">
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm uppercase mr-3">
              <?= strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'], 0, 1)) ?>
            </div>
            <div class="text-left">
              <h4 class="text-md font-semibold text-gray-800"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></h4>
              <p class="text-sm text-gray-500"><?= date('F j, Y', strtotime($row['created_at'])) ?></p>
            </div>
          </div>
          <p class="text-black italic mb-3">"<?= htmlspecialchars($row['comment']) ?>"</p>
          <div class="flex items-center justify-between text-sm text-yellow-500 font-semibold">
            <span>⭐ <?= $row['rating'] ?>/5</span>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>



<?php
include 'config.php';
$contact_success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['contact_submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $message = mysqli_real_escape_string($conn, $_POST["message"]);

    if (mysqli_query($conn, "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')")) {
        $contact_success = true;
    }
}
?>

<!-- Contact Section -->
<section id="contact" class="py-20 bg-gradient-to-br from-blue-100 via-white to-purple-100">
  <div class="max-w-4xl mx-auto px-6 text-center">
    <h2 class="text-4xl font-extrabold text-blue-900 mb-4">Get in Touch</h2>
    <p class="text-gray-700 mb-10">Have questions or special requests? Fill out the form and we’ll get back to you shortly.</p>

    <?php if ($contact_success): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-left" role="alert">
        <strong>✅ Success!</strong> Your message was sent successfully. Thank you for contacting us!
      </div>
    <?php endif; ?>

    <form action="#contact" method="POST" class="bg-white/80 backdrop-blur p-10 rounded-2xl shadow-2xl space-y-6 text-left">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-800">Name</label>
        <input type="text" id="name" name="name" required
               class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-800">Email</label>
        <input type="email" id="email" name="email" required
               class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
      </div>

      <div>
        <label for="message" class="block text-sm font-medium text-gray-800">Message</label>
        <textarea id="message" name="message" rows="5" required
                  class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
      </div>

      <div class="text-center">
        <button type="submit" name="contact_submit"
                class="bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold px-8 py-3 rounded-lg hover:from-purple-700 hover:to-blue-700 transition">
          Send Message
        </button>
      </div>
    </form>
  </div>
</section>



<!-- Footer -->
<footer class="bg-gradient-to-tr from-blue-950 via-blue-900 to-blue-800 text-white mt-16">
  <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-1 md:grid-cols-4 gap-10">

    <!-- Logo -->
    <div>
      <h3 class="text-3xl font-bold mb-4">VistaLuxe</h3>
      <p class="text-sm text-gray-300 leading-relaxed">
        Experience comfort and luxury at every step. We warmly welcome you to our hotel.
      </p>
    </div>

    <!-- Useful Links -->
    <div>
      <h4 class="text-lg font-semibold mb-4">Useful Links</h4>
      <ul class="space-y-2 text-sm text-gray-300">
        <li><a href="#" class="hover:text-white transition">Home</a></li>
        <li><a href="#rooms" class="hover:text-white transition">Rooms</a></li>
        <li><a href="#about" class="hover:text-white transition">About</a></li>
        <li><a href="#contact" class="hover:text-white transition">Contact</a></li>
      </ul>
    </div>

    <!-- Contact Info -->
    <div>
      <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
      <ul class="space-y-2 text-sm text-gray-300">
        <li>📍 Dëshmorët St., Tirana</li>
        <li>📞 +355 69 123 4567</li>
        <li>✉️ info@vistaluxe.com</li>
      </ul>
    </div>

    <!-- Newsletter -->
    <div>
      <h4 class="text-lg font-semibold mb-4">Newsletter</h4>
      <p class="text-sm text-gray-300 mb-3">Subscribe to receive the latest offers and updates.</p>
      <form class="flex">
        <input type="email" placeholder="Your email"
               class="w-full p-2 rounded-l-md text-gray-800 focus:outline-none focus:ring focus:ring-blue-300">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-500 text-white px-4 rounded-r-md transition">
          Subscribe
        </button>
      </form>
    </div>
  </div>

  <div class="text-center text-gray-400 py-4 border-t border-blue-700 text-sm">
    &copy; <?= date('Y') ?> VistaLuxe Hotel. All rights reserved.
  </div>
</footer>




<script>
  const slides = document.querySelectorAll("#sliderContainer > div");
  const titleEl = document.getElementById("sliderTitle");
  const subtitleEl = document.getElementById("sliderSubtitle");

  let index = 0;

  // Fillimisht bëj vetëm slide-in të parë të dukshëm me animacion
  slides.forEach((slide, i) => {
    slide.classList.add("opacity-0", "transition-opacity", "duration-1000");
    if (i === 0) {
      slide.classList.remove("opacity-0");
    }
  });

  setInterval(() => {
    // Hiq animacionin nga slide aktual
    slides[index].classList.add("opacity-0");

    // Lëviz te slajdi tjetër
    index = (index + 1) % slides.length;

    // Vendos titullin dhe përshkrimin
    titleEl.classList.add("opacity-0");
    subtitleEl.classList.add("opacity-0");

    setTimeout(() => {
      titleEl.textContent = slides[index].dataset.title;
      subtitleEl.textContent = slides[index].dataset.subtitle;
      titleEl.classList.remove("opacity-0");
      subtitleEl.classList.remove("opacity-0");
    }, 300);

    // Shfaq slajdin e ri
    slides[index].classList.remove("opacity-0");

  }, 5000);
</script>

</body>
</html>
