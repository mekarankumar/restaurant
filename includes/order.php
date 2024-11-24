<?php
session_start();
include_once('../includes/db.php');
include_once('../includes/head.php');
include_once('../includes/header.php');

// Fetch categories for the dropdown
$categories = [];
$stmt = $conn->prepare("SELECT id, name FROM categories");
if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
$stmt->close();

// Get selected category ID from the dropdown
$categoryId = isset($_GET['category']) && $_GET['category'] !== '' ? (int)$_GET['category'] : null;
?>

<body class="order-page">
    <div class="container-fluid" style="margin-top: 7rem;">
        <h2 class="text-white text-center mb-4">Order Your Food</h2>

        <!-- Category Filter -->
        <div class="text-center mb-4">
            <form method="GET" class="d-inline-block">
                <label for="category" class="text-white">Select Category:</label>
                <select name="category" id="category" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                    <option value="">All</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $categoryId === $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Food Items List Container -->
        <div class="food-items-list-container">
            <div class="food-items-list">
                <?php
                // Prepare query based on selected category
                if ($categoryId) {
                    $stmt = $conn->prepare("SELECT * FROM menu WHERE category = ?");
                    $stmt->bind_param("i", $categoryId);
                } else {
                    $stmt = $conn->prepare("SELECT * FROM menu");
                }

                // Execute query and display items
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "
                                <div class='food-item-card'>
                                    <img src='../images/{$row['image']}' alt='{$row['name']}' class='food-image'>
                                    <div class='food-details'>
                                        <h5 class='food-title'>{$row['name']}</h5>
                                        <p class='food-price'>Price: ₹{$row['price']}</p>
                                        <p class='food-description'>{$row['description']}</p>
                                    </div>
                                    <div class='food-call' style='padding-right: 50px;'>
                                        <a href='tel:+91XXXXXXXXXX' class='btn btn-primary'>Call Now</a>
                                    </div>
                                </div>
                            ";
                        }
                    } else {
                        echo "<h4 class='text-white'>No food items available in this category. 😅</h4>";
                    }
                }
                $stmt->close();
                ?>
            </div>
        </div>
    </div>

    <!-- Modal for Zoomed Image -->
    <div class="food-image-modal" id="imageModal">
        <button class="close-btn" onclick="closeModal()">×</button>
        <img src="" alt="Zoomed Image" id="modalImage">
    </div>

    <?php include_once('../includes/footer.php'); ?>

    <script>
        // Function to open the modal and display the image
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            modal.style.display = 'flex'; // Show the modal
        }

        // Function to close the modal
        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none'; // Hide the modal
        }

        // Attach click event to all food images
        document.addEventListener('DOMContentLoaded', () => {
            const foodImages = document.querySelectorAll('.food-image');
            foodImages.forEach((image) => {
                image.addEventListener('click', () => openModal(image.src));
            });
        });
    </script>
</body>