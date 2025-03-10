<?php
include("header.php");

// Fetch courses from the database
$courses = getCourses($conn);
function getCourses($conn)
{
  $query = "SELECT id, name, description, price FROM courses";
  $result = mysqli_query($conn, $query);

  if ($result) {
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  } else {
    return []; // Return an empty array if the query fails
  }
}
?>

<style>
  /* Course Scroller Styles */
  .course-scroller {
    width: 100%;
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    background: #f8f9fa;
    padding: 10px 0;
    border-radius: 8px;
  }

  .course-track {
    display: flex;
    gap: 20px;
    animation: scrollCourses 15s linear infinite;
  }

  .course-item {
    flex: 0 0 auto;
    width: 250px;
    background: white;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    text-align: center;
  }

  @keyframes scrollCourses {
    0% {
      transform: translateX(100%);
    }

    100% {
      transform: translateX(-100%);
    }
  }

  .course-scroller:hover .course-track {
    animation-play-state: paused;
  }

  /* News Sidebar Styles */
  #news-container {
    width: 90%;
    height: 300px;
    overflow: hidden;
    position: relative;
    background: #f8f9fa;
    border-radius: 10px;
    padding: 10px;
  }

  #news-feed {
    position: absolute;
    bottom: 0;
    width: 100%;
    display: flex;
    flex-direction: column-reverse;
  }

  .news-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ccc;
    font-size: 14px;
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.5s, transform 0.5s;
  }

  .news-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 10px;
  }

  .news-item a {
    font-weight: bold;
    color: #007bff;
    text-decoration: none;
  }

  .news-item a:hover {
    text-decoration: underline;
  }

  @keyframes scrollUp {
    from {
      transform: translateY(100%);
    }

    to {
      transform: translateY(-100%);
    }
  }

  #news-feed:hover {
    animation-play-state: paused;
  }
</style>

<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">College News Circulars</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">News</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="container-fluid">
  <div class="row">
    <!-- Left Section (Courses and Carousel below) -->
    <div class="col-md-9">
      <!-- Courses Section -->
      <div class="card mb-3">
        <div class="card-header">
          <h5 class="card-title">Buy College Courses
            <button class="btn btn-tool" type="button" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </h5>
        </div>
        <div class="card-body">
          <p class="card-text">Get the best deals on college courses at the best prices.</p>
          <a href="#" class="btn btn-primary">Shop Now</a>

          <!-- Course Scroller -->
          <div class="course-scroller">
            <div class="course-track">
              <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                  <div class="course-item">
                    <h6><?php echo htmlspecialchars($course['name']); ?></h6>
                    <p><?php echo htmlspecialchars($course['description']); ?></p>
                    <p><strong>$<?php echo number_format($course['price'], 2); ?></strong></p>
                    <a href="courses.php?course_id=<?php echo $course['id']; ?>" class="btn btn-success btn-sm">Buy Now</a>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p>No courses available at the moment.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- News Carousel Below Courses -->
      <div class="mt-3 mb-5">
        <div id="newsCarousel" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner" id="news-slideshow">
            <!-- Dynamic News items will be added here -->
          </div>
          <a class="carousel-control-prev" href="#newsCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#newsCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Right: News Sidebar (at end) -->
    <aside class="col-md-3 border border-1 border-dark rounded p-3">
      <h3 class="text-center">Latest News</h3>
      <div id="news-container">
        <div id="news-feed">
          <!-- Dynamic News Feed Items Here -->
        </div>
      </div>
      <button id="loadMore" class="btn btn-primary btn-block mt-3">Load More</button>
    </aside>
  </div>
</div>

<!-- JavaScript for Dynamic Loading and Carousel -->
<script>
  const API_KEY = 'ea79d9e0494044fc8bd58c93e9ed452e';
  const NEWS_API_URL = `https://newsapi.org/v2/top-headlines?country=us&apiKey=${API_KEY}`;

  let currentPage = 1;
  const pageSize = 5;

  function fetchNews(loadMore = false) {
    fetch(`${NEWS_API_URL}&page=${currentPage}&pageSize=${pageSize}`)
      .then(response => response.json())
      .then(data => {
        if (!loadMore) {
          document.getElementById('news-feed').innerHTML = '';
          document.getElementById('news-slideshow').innerHTML = '';
        }

        let isFirst = document.querySelectorAll('.carousel-item').length === 0;

        data.articles.forEach((article, index) => {
          // Sidebar News
          const newsItem = document.createElement("div");
          newsItem.classList.add("news-item");
          newsItem.innerHTML = `
                    <img src="${article.urlToImage || 'https://via.placeholder.com/60'}" alt="News Image">
                    <div>
                        <a href="${article.url}" target="_blank">${article.title}</a>
                        <p>${article.source.name} | ${new Date(article.publishedAt).toLocaleDateString()}</p>
                    </div>
                `;
          const newsFeed = document.getElementById("news-feed");
          newsFeed.insertBefore(newsItem, newsFeed.firstChild);

          // Fade-in effect
          setTimeout(() => {
            newsItem.style.opacity = "1";
            newsItem.style.transform = "translateY(0)";
          }, 100 * index);

          // Slideshow News
          const slideItem = `
                    <div class="carousel-item ${isFirst ? 'active' : ''}">
                        <img src="${article.urlToImage || 'https://via.placeholder.com/800x400'}" class="d-block w-100" alt="News Image">
                        <div class="carousel-caption d-none d-md-block">
                            <h5><a href="${article.url}" target="_blank" style="color: white;">${article.title}</a></h5>
                            <p>${article.source.name} | ${new Date(article.publishedAt).toLocaleDateString()}</p>
                        </div>
                    </div>
                `;
          document.getElementById('news-slideshow').innerHTML += slideItem;
          isFirst = false;
        });

        currentPage++;
      })
      .catch(error => console.error('Error fetching news:', error));
  }

  document.getElementById('loadMore').addEventListener('click', function() {
    fetchNews(true);
  });

  window.onload = () => {
    fetchNews();
    setTimeout(startScrolling, 3000);
  };

  function startScrolling() {
    const newsFeed = document.getElementById("news-feed");
    setInterval(() => {
      if (!newsFeed.matches(":hover")) {
        newsFeed.style.transition = "transform 1s linear";
        newsFeed.style.transform = "translateY(-40px)";
        setTimeout(() => {
          const firstItem = newsFeed.lastElementChild;
          if (firstItem) {
            newsFeed.prepend(firstItem);
            newsFeed.style.transition = "none";
            newsFeed.style.transform = "translateY(0)";
          }
        }, 1000);
      }
    }, 3000);
  }
</script>

<?php
include("footer.php");
?>