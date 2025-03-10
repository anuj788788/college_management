<?php
// Start the session
session_start();
include 'dbcon.php';

// Include the header
include("header.php");

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
    $row['level'] = $level; // Add level to each referral
    $courses_sql = "SELECT courses.name, courses.price 
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
        $income_by_level[$level] += $course['price'];
      }
    }
    if (!empty($student['children'])) {
      calculateIncomeByLevel($student['children'], $income_by_level);
    }
  }
}

$income_by_level = [];
calculateIncomeByLevel($students, $income_by_level);

// Separate students into two lists: purchased and not purchased
$students_with_courses = [];
$students_without_courses = [];

function categorizeStudents($students, &$students_with_courses, &$students_without_courses)
{
  foreach ($students as $student) {
    if (!empty($student['purchased_courses'])) {
      $students_with_courses[] = $student;
    } else {
      $students_without_courses[] = $student;
    }
    if (!empty($student['children'])) {
      categorizeStudents($student['children'], $students_with_courses, $students_without_courses);
    }
  }
}

categorizeStudents($students, $students_with_courses, $students_without_courses);
?>

<title>Referral Network</title>

<style>
  .node circle {
    stroke: #fff;
    stroke-width: 2px;
  }

  .node text {
    font-size: 14px;
    fill: #212529;
  }

  .link {
    fill: none;
    stroke: #adb5bd;
    stroke-width: 2px;
  }

  .tooltip-card {
    position: absolute;
    background: white;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    pointer-events: none;
    font-size: 14px;
    display: none;
  }

  .list-group-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .list-group-item span {
    font-weight: bold;
  }

  .income-card {
    margin-bottom: 20px;
  }
</style>

<section class="content">
  <div class="container">
    <h1 class="text-center"><i class="fas fa-users"></i> Referral Network</h1>

    <!-- Current User Details -->
    <div class="card card-primary col-md-6">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user"></i> Your Details</h3>
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
      </div>
      <div class="card-body border-bottom">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($current_user['status']); ?></p>
        <p><strong>Role:</strong> <?php echo $current_user['role_as'] === 0 ? 'Student' : 'Admin'; ?></p>
        <p><strong>Registered At:</strong> <?php echo htmlspecialchars($current_user['created_at']); ?></p>
        <p><strong>Updated At:</strong> <?php echo htmlspecialchars($current_user['updated_at']); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($current_user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($current_user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($current_user['phone']); ?></p>
        <p><strong>Balance:</strong> $<?php echo number_format($current_user['balance'], 2); ?></p>
      </div>
    </div>

    <!-- Referral Income by Level -->
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Referral Income by Level</h3>
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
      </div>
      <div class="card-body">
        <!-- Chart Container -->
        <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
          <canvas id="incomeChart"></canvas>
        </div>
        <!-- Income Cards -->
        <div class="row mt-4">
          <?php for ($level = 1; $level <= 5; $level++): ?>
            <div class="col-md-4 income-card">
              <div class="card card-outline card-<?php echo $level % 2 == 0 ? 'success' : 'primary'; ?>">
                <div class="card-header">
                  <h4 class="card-title">Level <?php echo $level; ?></h4>
                </div>
                <div class="card-body">
                  <p><strong>Income:</strong> $<?php echo number_format($income_by_level[$level] ?? 0, 2); ?></p>
                </div>
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>

    <!-- Referral Visualization -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-sitemap"></i> My Team</h3>
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
      </div>
      <div class="card-body">
        <div id="tree-container" style="width: 100%; height: 600px; overflow: auto;"></div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <!-- Students Who Have Purchased Courses -->
        <div class="card card-success col-md-6">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-check-circle"></i> My Paid Directs</h3>
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
          <div class="card-body">
            <?php if (!empty($students_with_courses)): ?>
              <ul class="list-group">
                <?php foreach ($students_with_courses as $student): ?>
                  <li class="list-group-item">
                    <span><?php echo htmlspecialchars($student['name']); ?></span>
                    <small>(<?php echo count($student['purchased_courses']); ?> course(s))</small>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p>No students have purchased courses yet.</p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Students Who Have Not Purchased Courses -->
        <div class="card card-danger col-md-6">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-times-circle"></i> My Unpaid Directs</h3>
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
          <div class="card-body">
            <?php if (!empty($students_without_courses)): ?>
              <ul class="list-group">
                <?php foreach ($students_without_courses as $student): ?>
                  <li class="list-group-item">
                    <span><?php echo htmlspecialchars($student['name']); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p>All students have purchased courses.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://d3js.org/d3.v6.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const students = <?php echo json_encode($students); ?>;
    const currentUser = <?php echo json_encode($current_user); ?>;
    const data = {
      name: currentUser.name,
      children: students
    };

    const width = 1000,
      height = 600;
    const svg = d3.select("#tree-container")
      .append("svg")
      .attr("width", width)
      .attr("height", height)
      .append("g")
      .attr("transform", "translate(50,20)");

    const tooltip = d3.select("body")
      .append("div")
      .attr("class", "tooltip-card");

    const tree = d3.tree().size([height - 100, width - 200]);
    const root = d3.hierarchy(data);
    tree(root);

    svg.selectAll(".link")
      .data(root.links())
      .enter()
      .append("path")
      .attr("class", "link")
      .attr("d", d3.linkHorizontal()
        .x(d => d.y)
        .y(d => d.x));

    const node = svg.selectAll(".node")
      .data(root.descendants())
      .enter()
      .append("g")
      .attr("class", "node")
      .attr("transform", d => `translate(${d.y},${d.x})`);

    node.append("circle")
      .attr("r", 8)
      .attr("fill", d => d.data.purchased_courses && d.data.purchased_courses.length > 0 ? "#28a745" : "#007bff")
      .attr("stroke", "#fff")
      .attr("stroke-width", "2px")
      .style("cursor", "pointer");

    node.append("text")
      .attr("dy", ".35em")
      .attr("x", d => d.children ? -12 : 12)
      .style("text-anchor", d => d.children ? "end" : "start")
      .style("font-size", "14px")
      .text(d => d.data.name);

    node.on("mouseover", function(event, d) {
        let tooltipContent;

        if (d.depth === 0) {
          tooltipContent = "You";
        } else {
          let coursesHtml = "";
          if (d.data.purchased_courses && d.data.purchased_courses.length > 0) {
            coursesHtml = "<strong>Purchased Courses:</strong><ul>" +
              d.data.purchased_courses.map(course =>
                `<li>${course.name} - $${course.price}</li>`
              ).join("") +
              "</ul>";
          } else {
            coursesHtml = "<strong>No courses purchased.</strong>";
          }

          tooltipContent = `
          <div><strong>Name:</strong> ${d.data.name}</div>
          <div><strong>Email:</strong> ${d.data.email || "N/A"}</div>
          <div><strong>Phone:</strong> ${d.data.phone || "N/A"}</div>
          <div><strong>Balance:</strong> $${d.data.balance || "0.00"}</div>
          <div><strong>Status:</strong> ${d.data.status || "N/A"}</div>
          <div><strong>Role:</strong> ${d.data.role_as === 0 ? "Student" : "Admin"}</div>
          <div><strong>Registered At:</strong> ${d.data.created_at}</div>
          <div><strong>Updated At:</strong> ${d.data.updated_at || "N/A"}</div>
          <div><strong>Referral Name:</strong> ${d.data.referral_name || "N/A"}</div>
          <div><strong>Referral Phone:</strong> ${d.data.referral_phone || "N/A"}</div>
          <div><strong>Level:</strong> ${d.data.level}</div>
          <div><strong>Children:</strong> ${d.children ? d.children.length : 0}</div>
          ${coursesHtml}
        `;
        }

        tooltip.style("display", "block")
          .html(tooltipContent)
          .style("left", (event.pageX + 10) + "px")
          .style("top", (event.pageY + 10) + "px");
      })
      .on("mouseout", function() {
        tooltip.style("display", "none");
      });
  });

  // Chart.js configuration
  const incomeData = <?php echo json_encode(array_values(array_map(function ($level) use ($income_by_level) {
                        return $income_by_level[$level] ?? 0;
                      }, range(1, 5)))); ?>;

  const ctx = document.getElementById('incomeChart').getContext('2d');
  const incomeChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5'],
      datasets: [{
        label: 'Referral Income ($)',
        data: incomeData,
        backgroundColor: [
          'rgba(40, 167, 69, 0.7)', // Green for Level 1
          'rgba(0, 123, 255, 0.7)', // Blue for Level 2
          'rgba(40, 167, 69, 0.7)', // Green for Level 3
          'rgba(0, 123, 255, 0.7)', // Blue for Level 4
          'rgba(40, 167, 69, 0.7)' // Green for Level 5
        ],
        borderColor: [
          'rgba(40, 167, 69, 1)',
          'rgba(0, 123, 255, 1)',
          'rgba(40, 167, 69, 1)',
          'rgba(0, 123, 255, 1)',
          'rgba(40, 167, 69, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Income ($)'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Referral Levels'
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `$${context.parsed.y.toFixed(2)}`;
            }
          }
        }
      }
    }
  });
</script>
<?php include("footer.php"); ?>