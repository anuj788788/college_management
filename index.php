<?php
// Start the session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Include the header
include 'header.php';

// Include database connection
require 'dbcon.php';

// Function to get user details from the database
function getUserDetails($conn, $username)
{
    $query = "SELECT * FROM students WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Fetch the user details and store them in session variables
if (isset($_SESSION['username'])) {
    $userDetails = getUserDetails($conn, $_SESSION['username']);
}

// Generate the unique referral link
$referralPhone = urlencode($userDetails['phone'] ?? ''); // Ensure the phone number is URL encoded
$referralLink = "http://localhost/college_management/signup.php?referral_phone=" . $referralPhone;

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <?php
            // Check if the user is logged in
            if (isset($_SESSION['username'])) {
                // Display a message indicating the user is already logged in
                echo '<div class="alert alert-info alert-dismissible fade show" id="loginMessage">
                    You are logged in as ' . htmlspecialchars($_SESSION['username']) . '.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';

                // update user status to inactive if the user is a student and has not paid the admission fee
                if (
                    $userDetails['role_as'] == 0 &&
                    (
                        $userDetails['status'] == 'Inactive' ||
                        $userDetails['status'] === null ||
                        strtolower($userDetails['status']) == 'n/a'
                    )
                ) {
                    $updateQuery = "UPDATE students SET status = 'Inactive' WHERE username = ?";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bind_param('s', $_SESSION['username']);
                    $stmt->execute();
                }


                // Check if the user clicked the "logout" button
                if (isset($_POST['logout'])) {
                    // Unset all session variables
                    session_unset();

                    // Destroy the session
                    session_destroy();

                    // Redirect to the login page
                    header('Location: login.php');
                    exit();
                }
            } else {
                echo '<div class="alert alert-warning">You are not logged in. Please log in to continue.</div>';
            }

            ?>


            <!-- Left: Courses Section -->
            <?php
            // Secure database query with error handling
            $query = "SELECT * FROM courses";
            $result = $conn->query($query);

            if (!$result) {
                die("Query failed: " . $conn->error);
            }
            $courses = $result->fetch_all(MYSQLI_ASSOC);
            ?>

            <!-- AdminLTE 3 Course Scroller -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Available Courses</h3>
                </div>
                <div class="card-body">
                    <div class="course-scroller">
                        <button class="scroll-btn prev-btn" aria-label="Previous courses">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="course-track">
                            <?php if (!empty($courses)): ?>
                                <?php foreach ($courses as $course): ?>
                                    <div class="card course-item" data-url="courses.php?course_id=<?= htmlspecialchars($course['id']) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($course['name']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($course['description']) ?></p>
                                            <p class="badge badge-success p-2">$<?= number_format($course['price'], 2) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center">No courses available at the moment.</p>
                            <?php endif; ?>
                        </div>
                        <button class="scroll-btn next-btn" aria-label="Next courses">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <style>
                .course-scroller {
                    overflow: hidden;
                    position: relative;
                    padding: 15px;
                    max-width: 100%;
                    -webkit-overflow-scrolling: touch;
                    display: flex;
                    align-items: center;
                }

                .course-track {
                    display: flex;
                    gap: 15px;
                    transition: transform 0.5s ease-in-out;
                    will-change: transform;
                    flex: 1;
                    min-width: 0;
                    /* Prevents overflow */
                }

                .course-item {
                    flex: 0 0 auto;
                    width: 250px;
                    background: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                    text-align: center;
                    cursor: pointer;
                    transition: transform 0.3s, box-shadow 0.3s;
                    user-select: none;
                }

                .course-item:hover {
                    transform: scale(1.05);
                    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
                }

                .scroll-btn {
                    position: relative;
                    background: #ffffff;
                    border: none;
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    cursor: pointer;
                    z-index: 1;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    transition: background-color 0.3s;
                }

                .scroll-btn:hover {
                    background: #f0f0f0;
                }

                .prev-btn {
                    margin-right: 10px;
                }

                .next-btn {
                    margin-left: 10px;
                }

                .scroll-btn:disabled {
                    opacity: 0.5;
                    cursor: not-allowed;
                }

                @media (max-width: 768px) {
                    .course-item {
                        width: 200px;
                    }

                    .scroll-btn {
                        width: 30px;
                        height: 30px;
                    }
                }
            </style>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const track = document.querySelector(".course-track");
                    const scroller = document.querySelector(".course-scroller");
                    const prevBtn = document.querySelector(".prev-btn");
                    const nextBtn = document.querySelector(".next-btn");

                    // Safety checks
                    if (!track || !scroller || !prevBtn || !nextBtn) return;
                    const courseItems = track.querySelectorAll(".course-item");
                    if (courseItems.length === 0) return;

                    let scrollAmount = 0;
                    let direction = 1;
                    const speed = 2;
                    const scrollStep = 250; // Amount to scroll with buttons
                    let animationFrame = null;

                    function updateButtonStates() {
                        const maxScroll = track.scrollWidth - scroller.clientWidth;
                        prevBtn.disabled = scrollAmount >= 0;
                        nextBtn.disabled = Math.abs(scrollAmount) >= maxScroll;
                    }

                    function autoScroll() {
                        scrollAmount += direction * speed;
                        const maxScroll = track.scrollWidth - scroller.clientWidth;

                        if (scrollAmount >= 0) {
                            direction = -1;
                            scrollAmount = 0;
                        } else if (Math.abs(scrollAmount) >= maxScroll) {
                            direction = 1;
                            scrollAmount = -maxScroll;
                        }

                        track.style.transform = `translateX(${scrollAmount}px)`;
                        updateButtonStates();
                        animationFrame = requestAnimationFrame(autoScroll);
                    }

                    function startScrolling() {
                        if (!animationFrame) {
                            animationFrame = requestAnimationFrame(autoScroll);
                        }
                    }

                    function stopScrolling() {
                        if (animationFrame) {
                            cancelAnimationFrame(animationFrame);
                            animationFrame = null;
                        }
                    }

                    function scrollToPosition(newPosition) {
                        stopScrolling();
                        scrollAmount = Math.max(-(track.scrollWidth - scroller.clientWidth), Math.min(0, newPosition));
                        track.style.transform = `translateX(${scrollAmount}px)`;
                        updateButtonStates();
                    }

                    // Button controls
                    prevBtn.addEventListener("click", () => {
                        scrollToPosition(scrollAmount + scrollStep);
                    });

                    nextBtn.addEventListener("click", () => {
                        scrollToPosition(scrollAmount - scrollStep);
                    });

                    // Only start scrolling if content overflows
                    if (track.scrollWidth > scroller.clientWidth) {
                        startScrolling();
                    }

                    // Event Listeners
                    scroller.addEventListener("mouseenter", stopScrolling);
                    scroller.addEventListener("mouseleave", startScrolling);

                    courseItems.forEach(item => {
                        item.addEventListener("click", function(e) {
                            e.preventDefault();
                            const url = this.getAttribute("data-url");
                            if (url) window.location.href = url;
                        });
                    });

                    // Initial button state
                    updateButtonStates();
                });
            </script>
            <!-- End: Courses Section -->






            <!-- Button to open the modal -->
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#inviteModal">
                Invite friends via referral
            </button>

            <!-- Modal -->
            <div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="inviteModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="inviteModalLabel">Invite Your Friends</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Share your referral link with your friends:</p>
                            <input type="text" id="referralLinkInput" class="form-control mb-3" value="<?php echo htmlspecialchars($referralLink, ENT_QUOTES, 'UTF-8'); ?>" readonly>

                            <div class="d-flex justify-content-between">
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($referralLink); ?>" target="_blank" class="btn btn-facebook">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                                <!-- WhatsApp -->
                                <a href="https://wa.me/?text=<?php echo urlencode($referralLink); ?>" target="_blank" class="btn btn-success">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                                <!-- Telegram -->
                                <a href="https://t.me/share/url?url=<?php echo urlencode($referralLink); ?>" target="_blank" class="btn btn-info">
                                    <i class="fab fa-telegram-plane"></i> Telegram
                                </a>
                                <!-- Twitter -->
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($referralLink); ?>" target="_blank" class="btn btn-twitter">
                                    <i class="fab fa-twitter"></i> Twitter
                                </a>
                                <!-- Copy Link -->
                                <button id="copyLink" class="btn btn-secondary">
                                    <i class="fas fa-link"></i> Copy Link
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <h1>Dashboard</h1>
            <!-- small box info for courses cart  -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Courses</h3>
                            <p>View available courses</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="courses.php" class="small-box-footer">View Courses <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                <!-- Cart -->
                <?php
                // Database Connection
                $host = "localhost";
                $dbname = "phpadminpanel";  // Corrected database name
                $username = "root";  // Default username for localhost
                $password = "";  // No password

                // Create MySQLi connection
                $conn = new mysqli($host, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Database connection failed: " . $conn->connect_error);
                }

                // Assume user is logged in with a session
                $loggedInUser = $_SESSION['username'] ?? '';

                $quantity = 0;

                if (!empty($loggedInUser)) {
                    $stmt = $conn->prepare("SELECT SUM(quantity) AS quantity FROM cart WHERE username = ?");
                    $stmt->bind_param("s", $loggedInUser);
                    $stmt->execute();
                    $stmt->bind_result($quantity);
                    $stmt->fetch();
                    $stmt->close();
                }

                // Ensure quantity is at least 0
                $quantity = $quantity ?: 0;
                ?>

                <!-- Cart -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><strong><?php echo $quantity; ?></strong></h3>
                            <p>Course(s) Purchased</p>

                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="cart.php" class="small-box-footer">View Courses <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                <!-- Referral -->
                <?php
                include 'dbcon.php';

                // Get the logged-in user's username from the session
                $username = $_SESSION['username'];

                // Fetch the user's phone number from the database
                $sql_user = "SELECT phone FROM students WHERE username = ?";
                $stmt_user = $conn->prepare($sql_user);
                $stmt_user->bind_param("s", $username);
                $stmt_user->execute();
                $result_user = $stmt_user->get_result();
                $user_data = $result_user->fetch_assoc();

                // If user exists, get the phone number
                if ($user_data) {
                    $my_phone = $user_data['phone'];

                    // Query to count referrals where referral_phone matches the logged-in user's phone
                    $sql = "SELECT COUNT(*) AS total_referrals FROM students WHERE referral_phone = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $my_phone);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $total_referrals = $row['total_referrals'];
                } else {
                    $total_referrals = 0; // Default to 0 if no user data found
                }
                ?>

                <!-- Referrals -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $total_referrals; ?></h3>
                            <p>My Referrals</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="referral.php" class="small-box-footer">View Referrals <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>



                <!-- College News -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>College News</h3>
                            <p>View college news</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <a href="news.php" class="small-box-footer">View News <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- College Admission Fee -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>Admission Fee</h3>
                            <p>Pay admission fee</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <a href="pay.php" class="small-box-footer">Pay Fee <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <!-- My Wallet history -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>My Wallet</h3>
                            <p>View wallet history</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <a href="wallet.php" class="small-box-footer">View Wallet <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <?php

                // Check if the user is logged in
                if (!isset($_SESSION['username'])) {
                    echo "<script>window.location = 'login.php';</script>";
                    exit();
                }

                // Fetch current user's details
                $username = $_SESSION['username'];
                $sql_user = "SELECT id, name, email, phone, balance, status, role_as, created_at, updated_at 
             FROM students 
             WHERE username=? LIMIT 1";

                $stmt = $conn->prepare($sql_user);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $current_user = $stmt->get_result()->fetch_assoc();

                // Fetch all students referred by the current user with level tracking
                function fetchReferrals($conn, $referral_name, $referral_phone, $level = 1)
                {
                    $sql = "SELECT id, name, email, phone, referral_name, referral_phone, age, balance, status, created_at, role_as 
            FROM students 
            WHERE referral_name=? AND referral_phone=?
            ORDER BY created_at DESC";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $referral_name, $referral_phone);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $referrals = [];
                    while ($row = $result->fetch_assoc()) {
                        $row['level'] = $level;
                        $courses_sql = "SELECT courses.name AS course_name, courses.price, cart.created_at AS purchase_date 
                        FROM cart 
                        JOIN courses ON cart.course_id = courses.id 
                        WHERE cart.username = ? AND cart.payment_status = '1'";
                        $courses_stmt = $conn->prepare($courses_sql);
                        $courses_stmt->bind_param("s", $row['name']);
                        $courses_stmt->execute();
                        $courses_result = $courses_stmt->get_result();
                        $row['purchased_courses'] = $courses_result->fetch_all(MYSQLI_ASSOC);

                        $row['children'] = fetchReferrals($conn, $row['name'], $row['phone'], $level + 1);
                        $referrals[] = $row;
                    }
                    return $referrals;
                }

                $students = fetchReferrals($conn, $current_user['name'], $current_user['phone']);

                // Calculate income by level
                function calculateIncomeByLevel($students, &$income_by_level)
                {
                    foreach ($students as $student) {
                        $level = $student['level'];
                        if (!isset($income_by_level[$level])) {
                            $income_by_level[$level] = 0;
                        }
                        if (!empty($student['purchased_courses'])) {
                            foreach ($student['purchased_courses'] as $course) {
                                $price = $course['price'];
                                $income_by_level[$level] += $price;
                            }
                        }
                        if (!empty($student['children'])) {
                            calculateIncomeByLevel($student['children'], $income_by_level);
                        }
                    }
                }

                $income_by_level = [];
                calculateIncomeByLevel($students, $income_by_level);

                // Calculate grand total income
                $grand_total_income = array_sum($income_by_level);
                ?>

                <!-- Assume this is within your existing HTML structure, e.g., after <div class="content-wrapper"> -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Level 1 Income -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>$<?php echo number_format($income_by_level[1] ?? 0, 2); ?></h3>
                                        <p>Level 1 Income - Earnings from courses</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <a href="income.php" class="small-box-footer">View Income <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- Level 2 Income -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>$<?php echo number_format($income_by_level[2] ?? 0, 2); ?></h3>
                                        <p>Level 2 Income - Earnings from referrals</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <a href="income.php" class="small-box-footer">View Income <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- Level 3 Income -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>$<?php echo number_format($income_by_level[3] ?? 0, 2); ?></h3>
                                        <p>Level 3 Income - Earnings from referrals</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <a href="income.php" class="small-box-footer">View Income <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- Level 4 Income -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3>$<?php echo number_format($income_by_level[4] ?? 0, 2); ?></h3>
                                        <p>Level 4 Income - Earnings from referrals</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <a href="income.php" class="small-box-footer">View Income <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- Level 5 Income -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>$<?php echo number_format($income_by_level[5] ?? 0, 2); ?></h3>
                                        <p>Level 5 Income - Earnings from referrals</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <a href="income.php" class="small-box-footer">View Income <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <!-- Grand Total Income -->
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3>$<?php echo number_format($grand_total_income, 2); ?></h3>
                                        <p>Grand Total Income - All Levels</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <a href="income.php" class="small-box-footer">View Details <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div><!-- /.content -->

            </div>
        </div>

    </div>
</div>

<div class="container d-flex justify-content-left align-items-center m-1" style="min-height: 50vh; border: 1px solid #000;">
    <div class="row m-1">
        <div class="col-md-12 m-1" style="border: 1px solid #000;">




            <h1>Welcome, <?php echo htmlspecialchars($userDetails['name'] ?? ''); ?>!</h1>

            <p>Here are your details:</p>

            <ul>
                <li><strong>Username:</strong> <?php echo htmlspecialchars($userDetails['username'] ?? 'N/A'); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($userDetails['email'] ?? 'N/A'); ?></li>
                <li><strong>Age:</strong> <?php echo htmlspecialchars($userDetails['age'] ?? 'N/A'); ?></li>
                <li><strong>Phone:</strong> <?php echo htmlspecialchars($userDetails['phone'] ?? 'N/A'); ?></li>
                <li><strong>Referral Name:</strong> <?php echo htmlspecialchars($userDetails['referral_name'] ?? 'N/A'); ?></li>
                <li><strong>Referral Phone:</strong> <?php echo htmlspecialchars($userDetails['referral_phone'] ?? 'N/A'); ?></li>
                <li><strong>Referral Link:</strong> <a href="<?php echo $referralLink; ?>"><?php echo $referralLink; ?></a></li>
                <li><strong>Created At:</strong>
                    <?php
                    // Check if 'updated_at' is available
                    if (!empty($userDetails['updated_at'])) {
                        // Create a DateTime object from the 'updated_at' field
                        $date = new DateTime($userDetails['created_at']);
                        // Format the date as 'YYYY-Mmm-DD HH:MM:SS AM/PM'
                        echo $date->format('Y-M-d h:i:s A');
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </li>

                <li><strong>Updated At:</strong>
                    <?php
                    // Check if 'updated_at' is available
                    if (!empty($userDetails['updated_at'])) {
                        // Create a DateTime object from the 'updated_at' field
                        $date = new DateTime($userDetails['updated_at']);
                        // Format the date as 'YYYY-Mmm-DD HH:MM:SS AM/PM'
                        echo $date->format('Y-M-d h:i:s A');
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </li>

                <li><strong>Status:</strong> <?php echo htmlspecialchars($userDetails['status'] ?? 'Inactive'); ?></li>
                <li><strong>Role As:</strong>
                    <?php
                    if (isset($userDetails['role_as'])) {
                        $role_as = $userDetails['role_as'];
                        if ($role_as == 0) {
                            echo "Student";
                        } elseif ($role_as == 1) {
                            echo "Admin";
                        } else {
                            echo "Invalid Role";
                        }
                    } else {
                        echo "N/A";
                    }
                    ?>
                </li>
                <li><strong>Balance:</strong> <?php echo htmlspecialchars($userDetails['balance'] ?? '00.00'); ?></li>

                <?php
                // Student will pay an admission fee of $100 to get active status if not active except admin
                if (
                    $userDetails['role_as'] == 0 &&
                    (
                        $userDetails['status'] == 'Inactive' ||
                        $userDetails['status'] === null ||
                        strtolower($userDetails['status']) == 'n/a'
                    )
                ) {
                    echo '<li><strong>Pay Fees:</strong> <a href="pay.php">Pay $100 fees to achieve active status</a></li>';
                }
                ?>
            </ul>

        </div>

        <!-- Genology tree of the user and his/her downline referral-->
        <div class="col-md-12">
            <h2>Referral</h2>
            <div class="tree">
                <ul>
                    <li>
                        <a href="#">
                            <strong><?php echo htmlspecialchars($userDetails['name'] ?? ''); ?></strong>
                        </a>
                        <ul>
                            <?php
                            // Query to retrieve the downline referrals of the current user
                            $query = "SELECT * FROM students WHERE referral_phone = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param('s', $userDetails['phone']);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // Loop through the downline referrals
                            while ($row = $result->fetch_assoc()) {
                                echo '<li>
                                    <a href="#">
                                        <strong>' . htmlspecialchars($row['name']) . '</strong>
                                    </a>
                                </li>';
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('copyLink').addEventListener('click', function() {
        const copyText = document.getElementById('referralLinkInput');
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand('copy');
        alert("Referral link copied to clipboard!");
    });
</script>
<?php include 'footer.php'; ?>