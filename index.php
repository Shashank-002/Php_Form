<?php
$errors = [];
$success_message = '';

function validate_form($post_data, $file_data)
{
    global $errors, $success_message;

    if (empty($post_data['first_name'])) {
        $errors['first_name'] = "First name is required.";
    }

    if (empty($post_data['last_name'])) {
        $errors['last_name'] = "Last name is required.";
    }

    if (empty($post_data['dob'])) {
        $errors['dob'] = "Date of Birth is required.";
    }

    if (empty($post_data['email'])) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($post_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($post_data['phone'])) {
        $errors['phone'] = "Phone number is required.";
    } elseif (!preg_match('/^[1-9][0-9]{9}$/', $post_data['phone'])) {
        $errors['phone'] = "Phone number must be exactly 10 digits, and it cannot start with 0.";
    }

    if (empty($post_data['address'])) {
        $errors['address'] = "Street address is required.";
    }

    if (empty($post_data['city'])) {
        $errors['city'] = "City is required.";
    }

    if (empty($post_data['state'])) {
        $errors['state'] = "State is required.";
    }

    if (empty($post_data['zip_code'])) {
        $errors['zip_code'] = "ZIP Code is required.";
    } elseif (!preg_match('/^\d{6}$/', $post_data['zip_code'])) {
        $errors['zip_code'] = "ZIP code must be a 6-digit number.";
    }

    if (!isset($post_data['newsletter'])) {
        $errors['newsletter'] = "Please select an option.";
    }

    if (empty($post_data['interests'])) {
        $errors['interests'] = "Please select at least one interest.";
    }

    if (!empty($post_data['comments']) && strlen($post_data['comments']) > 500) {
        $errors['comments'] = "Comments cannot exceed 500 characters.";
    }

    if (isset($file_data['profile_image']) && $file_data['profile_image']['error'] == 0) {
        $image = $file_data['profile_image'];
        $image_name = $image['name'];
        $image_tmp_name = $image['tmp_name'];
        $image_size = $image['size'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($image_ext, $allowed_ext)) {
            $errors['profile_image'] = "Invalid image file format. Only JPG, JPEG, PNG, and GIF are allowed.";
        } elseif ($image_size > 2 * 1024 * 1024) {
            $errors['profile_image'] = "File size too large. Maximum size is 2MB.";
        }
    } else {
        $errors['profile_image'] = "Profile image is required.";
    }

    if (empty($errors)) {
        $success_message = "Form submitted successfully!";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    validate_form($_POST, $_FILES);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center my-4 min-h-screen">
    <div class="w-full max-w-2xl bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-center text-gray-700 mb-6">USER FORM</h2>

        <!-- Success Message -->
        <?php if ($success_message): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">

            <!-- First Name -->
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-600">First Name</label>
                <input type="text" id="first_name" name="first_name" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                <?php if (isset($errors['first_name'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['first_name']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-600">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                <?php if (isset($errors['last_name'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['last_name']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Date of Birth -->
            <div class="mb-4">
                <label for="dob" class="block text-sm font-medium text-gray-600">Date of Birth</label>
                <input type="date" id="dob" name="dob" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>" onchange="this.blur();" onkeydown="return false;">
                <?php if (isset($errors['dob'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['dob']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" id="email" name="email" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['email']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-600">Phone</label>
                <input type="text" id="phone" name="phone" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" maxlength="10"
                    oninput="this.value=this.value.replace(/[^0-9]/g, '');"
                    pattern="^[1-9][0-9]{9}$" placeholder="Enter Phone Number">
                <?php if (isset($errors['phone'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['phone']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-600">Street Address</label>
                <input type="text" id="address" name="address" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                <?php if (isset($errors['address'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['address']; ?></p>
                <?php endif; ?>
            </div>

            <!-- City -->
            <div class="mb-4">
                <label for="city" class="block text-sm font-medium text-gray-600">City</label>
                <input type="text" id="city" name="city" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                <?php if (isset($errors['city'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['city']; ?></p>
                <?php endif; ?>
            </div>

            <!-- State -->
            <div class="mb-4">
                <label for="state" class="block text-sm font-medium  text-gray-600">State</label>
                <select id="state" name="state" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="">Select State</option>
                    <option value="Uttar Pradesh" <?php echo (isset($_POST['state']) && $_POST['state'] == 'Uttar Pradesh') ? 'selected' : ''; ?>>Uttar Pradesh</option>
                    <option value="Madhya Pradesh" <?php echo (isset($_POST['state']) && $_POST['state'] == 'Madhya Pradesh') ? 'selected' : ''; ?>>Madhya Pradesh</option>
                    <option value="Bihar" <?php echo (isset($_POST['state']) && $_POST['state'] == 'Bihar') ? 'selected' : ''; ?>>Bihar</option>
                    <option value="Haryana" <?php echo (isset($_POST['state']) && $_POST['state'] == 'Haryana') ? 'selected' : ''; ?>>Haryana</option>
                    <option value="Rajasthan" <?php echo (isset($_POST['state']) && $_POST['state'] == 'Rajasthan') ? 'selected' : ''; ?>>Rajasthan</option>
                </select>
                <?php if (isset($errors['state'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['state']; ?></p>
                <?php endif; ?>
            </div>

            <!-- ZIP Code -->
            <div class="mb-4">
                <label for="zip_code" class="block text-sm font-medium text-gray-600">ZIP Code</label>
                <input type="text" id="zip_code" name="zip_code" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                    value="<?php echo htmlspecialchars($_POST['zip_code'] ?? ''); ?>"
                    maxlength="6" oninput="this.value=this.value.replace(/[^0-9]/g, '');">
                <?php if (isset($errors['zip_code'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['zip_code']; ?></p>
                <?php endif; ?>
            </div>


            <!-- Newsletter Subscription -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600">Newsletter Subscription</label>
                <div class="flex items-center space-x-4">
                    <label>
                        <input type="radio" name="newsletter" value="yes" class="mr-2" <?php echo (isset($_POST['newsletter']) && $_POST['newsletter'] == 'yes') ? 'checked' : ''; ?>> Yes
                    </label>
                    <label>
                        <input type="radio" name="newsletter" value="no" class="mr-2" <?php echo (isset($_POST['newsletter']) && $_POST['newsletter'] == 'no') ? 'checked' : ''; ?>> No
                    </label>
                </div>
                <?php if (isset($errors['newsletter'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['newsletter']; ?></p>
                <?php endif; ?>
            </div>


            <!-- Interests -->
            <div class="mb-4">
                <label for="interests" class="block text-sm font-medium text-gray-600">Interests</label>
                <div class="relative">
                    <button type="button" id="dropdownButton" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-left">
                        Select Interests
                    </button>
                    <div id="dropdown" class="absolute hidden mt-2 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10">
                        <label class="block px-4 py-2">
                            <input type="checkbox" name="interests[]" value="technology" <?php echo (isset($_POST['interests']) && in_array('technology', $_POST['interests'])) ? 'checked' : ''; ?>>
                            Technology
                        </label>
                        <label class="block px-4 py-2">
                            <input type="checkbox" name="interests[]" value="sports" <?php echo (isset($_POST['interests']) && in_array('sports', $_POST['interests'])) ? 'checked' : ''; ?>>
                            Sports
                        </label>
                        <label class="block px-4 py-2">
                            <input type="checkbox" name="interests[]" value="music" <?php echo (isset($_POST['interests']) && in_array('music', $_POST['interests'])) ? 'checked' : ''; ?>>
                            Music
                        </label>
                        <label class="block px-4 py-2">
                            <input type="checkbox" name="interests[]" value="art" <?php echo (isset($_POST['interests']) && in_array('art', $_POST['interests'])) ? 'checked' : ''; ?>>
                            Art
                        </label>
                        <label class="block px-4 py-2">
                            <input type="checkbox" name="interests[]" value="travel" <?php echo (isset($_POST['interests']) && in_array('travel', $_POST['interests'])) ? 'checked' : ''; ?>>
                            Travel
                        </label>
                    </div>
                </div>
                <?php if (isset($errors['interests'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['interests']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Additional Comments -->
            <div class="mb-4">
                <label for="comments" class="block text-sm font-medium text-gray-600">Comments (Optional)</label>
                <textarea name="comments" id="comments" rows="4" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($_POST['comments'] ?? ''); ?></textarea>
                <?php if (isset($errors['comments'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['comments']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Profile Image Upload -->
            <div class="mb-4">
                <label for="profile_image" class="block text-sm font-medium text-gray-600">Profile Image</label>
                <input type="file" id="profile_image" name="profile_image" class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-md">
                <?php if (isset($errors['profile_image'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['profile_image']; ?></p>
                <?php endif; ?>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 text-center">
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-500">
                    Submit
                </button>
            </div>
        </form>
    </div>
</body>
<script>
    document.getElementById('dropdownButton').addEventListener('click', function() {
        var dropdown = document.getElementById('dropdown');
        dropdown.classList.toggle('hidden');
    });

    window.addEventListener('click', function(event) {
        if (!event.target.closest('#dropdownButton') && !event.target.closest('#dropdown')) {
            document.getElementById('dropdown').classList.add('hidden');
        }
    });
</script>

</html>