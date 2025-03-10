<?php
include 'header.php';

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "phpadminpanel";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

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

// Function to fetch referrals recursively
function fetchReferrals($conn, $referral_name, $referral_phone)
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
    $courses_sql = "SELECT courses.name AS course_name, courses.price 
                       FROM cart 
                       JOIN courses ON cart.course_id = courses.id 
                       WHERE cart.username = ? AND cart.payment_status = '1'";

    $courses_stmt = $conn->prepare($courses_sql);
    $courses_stmt->bind_param("s", $row['name']);
    $courses_stmt->execute();
    $row['purchased_courses'] = $courses_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $row['children'] = fetchReferrals($conn, $row['name'], $row['phone']);
    $referrals[] = $row;
  }
  return $referrals;
}

$students = fetchReferrals($conn, $current_user['name'], $current_user['phone']);

// Calculate sales
function calculateSales($referrals, &$sales_data)
{
  foreach ($referrals as $student) {
    if (!empty($student['purchased_courses'])) {
      foreach ($student['purchased_courses'] as $course) {
        $sales_data[] = [
          'buyer' => $student['name'],
          'course_name' => $course['course_name'],
          'price' => $course['price']
        ];
      }
    }
    if (!empty($student['children'])) {
      calculateSales($student['children'], $sales_data);
    }
  }
}

$sales_data = [];
calculateSales($students, $sales_data);

$total_sales = array_sum(array_column($sales_data, 'price'));

$sales_by_referral = [];
foreach ($sales_data as $sale) {
  $buyer = $sale['buyer'];
  if (!isset($sales_by_referral[$buyer])) {
    $sales_by_referral[$buyer] = 0;
  }
  $sales_by_referral[$buyer] += $sale['price'];
}

$sales_labels = array_keys($sales_by_referral);
$sales_values = array_values($sales_by_referral);
?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  body {
    font-family: Arial, sans-serif;
  }

  .node circle {
    fill: #007bff;
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

  .card {
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
  }
</style>

<div class="container">
  <h1 class="text-centre"><i class="fas fa-users"></i> My Team & Sales</h1>

  <div class="card">
    <h2>Your Details</h2>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($current_user['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($current_user['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($current_user['phone']); ?></p>
    <p><strong>Balance:</strong> $<?php echo number_format($current_user['balance'], 2); ?></p>
  </div>

  <div class="card">
    <h2>My Team</h2>
    <p><strong>Total Referrals:</strong> <?php echo count($students); ?></p>
    <p><strong>Hover on a node to view details.</strong></p>
    <div id="tree-container" style="width: 100%; height: 600px; overflow: auto;"></div>
  </div>

  <div class="card">
    <h2>My Sales</h2>
    <p><strong>Total Sales:</strong> $<?php echo number_format($total_sales, 2); ?></p>
    <table border="1" cellspacing="0" cellpadding="5" style="width: 100%;">
      <tr>
        <th>Buyer</th>
        <th>Course Name</th>
        <th>Price</th>
      </tr>
      <?php if (!empty($sales_data)): ?>
        <?php foreach ($sales_data as $sale): ?>
          <tr>
            <td><?php echo htmlspecialchars($sale['buyer']); ?></td>
            <td><?php echo htmlspecialchars($sale['course_name']); ?></td>
            <td>$<?php echo number_format($sale['price'], 2); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="3">No sales yet.</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>

  <div class="card">
    <h2>Sales Distribution</h2>
    <canvas id="salesPieChart" width="400" height="200"></canvas>
  </div>
</div>

<script src="https://d3js.org/d3.v6.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const students = <?php echo json_encode($students); ?>;
    const currentUser = <?php echo json_encode($current_user); ?>;
    const data = {
      name: currentUser.name,
      children: students
    };

    const width = 1000;
    const height = 600;

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

    // Draw links
    svg.selectAll(".link")
      .data(root.links())
      .enter()
      .append("path")
      .attr("class", "link")
      .attr("d", d3.linkHorizontal()
        .x(d => d.y)
        .y(d => d.x));

    // Create nodes
    const node = svg.selectAll(".node")
      .data(root.descendants())
      .enter()
      .append("g")
      .attr("class", "node")
      .attr("transform", d => `translate(${d.y},${d.x})`);

    // Add circles
    node.append("circle")
      .attr("r", 8)
      .attr("fill", d => d.data.purchased_courses && d.data.purchased_courses.length > 0 ? "#28a745" : "#007bff")
      .attr("stroke", "#fff")
      .attr("stroke-width", "2px")
      .style("cursor", "pointer");

    // Add text labels
    node.append("text")
      .attr("dy", ".35em")
      .attr("x", d => d.children ? -12 : 12)
      .style("text-anchor", d => d.children ? "end" : "start")
      .style("font-size", "14px")
      .text(d => d.data.name);

    // Tooltip functionality
    node.on("mouseover", function(event, d) {
        let tooltipContent;

        // Check if this is the root node (current user)
        if (d.depth === 0) {
          tooltipContent = "It's You";
        } else {
          let coursesHtml = "";
          if (d.data.purchased_courses && d.data.purchased_courses.length > 0) {
            coursesHtml = "<strong>Purchased Courses:</strong><ul>" +
              d.data.purchased_courses.map(course =>
                `<li>${course.course_name} - $${course.price}</li>`
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

    // Pie chart
    const ctx = document.getElementById('salesPieChart').getContext('2d');
    const salesPieChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: <?php echo json_encode($sales_labels); ?>,
        datasets: [{
          label: 'Sales by Referral',
          data: <?php echo json_encode($sales_values); ?>,
          backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8'],
          borderColor: '#ffffff',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top'
          },
          tooltip: {
            callbacks: {
              label: function(tooltipItem) {
                const label = tooltipItem.label || '';
                const value = tooltipItem.raw || 0;
                return `${label}: $${value.toFixed(2)}`;
              }
            }
          }
        }
      }
    });
  });
</script>

<?php include 'footer.php'; ?>