<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%231a56db'/%3E%3Ctext x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-size='18' font-family='system-ui' fill='%23f59e0b'%3E&#x1F3E8;%3C/text%3E%3C/svg%3E"/>
  <title>Hotels – bookHotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" crossorigin="anonymous"/>
  <link rel="stylesheet" href="style.css"/>
  <link rel="stylesheet" href="hotels.css"/>
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand fw-800 fs-4" href="index.php">
      <i class="bi bi-building-fill text-warning me-1"></i>bookHotel
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link active" href="hotels.php">Hotels</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#destinations">Destinations</a></li>
        <li class="nav-item"><a class="nav-link" href="my-bookings.php">My Bookings</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
        <li class="nav-item ms-lg-3" id="navAuthSlot">
          <a class="btn btn-outline-warning btn-sm px-3" href="login.php">Login / Sign Up</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ========== PAGE HERO ========== -->
<section class="listing-hero">
  <div class="listing-hero-overlay"></div>
  <div class="container position-relative z-1 text-white py-5">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb breadcrumb-dark">
        <li class="breadcrumb-item"><a href="index.php" class="text-warning text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active text-white-50">Hotels</li>
      </ol>
    </nav>
    <h1 class="fw-800 display-5 mb-2">Find Your Perfect Hotel</h1>
    <p class="opacity-75 mb-4">Showing results across top destinations in India</p>
    <div class="listing-search-bar">
      <div class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
          <label class="form-label small fw-600 text-muted">WHERE</label>
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-geo-alt-fill text-warning"></i></span>
            <input type="text" class="form-control border-start-0 ps-0" placeholder="City, hotel or destination" value="India"/>
          </div>
        </div>
        <div class="col-6 col-md-2">
          <label class="form-label small fw-600 text-muted">CHECK-IN</label>
          <input type="date" class="form-control" id="checkin"/>
        </div>
        <div class="col-6 col-md-2">
          <label class="form-label small fw-600 text-muted">CHECK-OUT</label>
          <input type="date" class="form-control" id="checkout"/>
        </div>
        <div class="col-6 col-md-2">
          <label class="form-label small fw-600 text-muted">ROOMS & GUESTS</label>
          <select class="form-select">
            <option>1 Room, 2 Guests</option>
            <option>1 Room, 1 Guest</option>
            <option>2 Rooms, 4 Guests</option>
            <option>3 Rooms, 6 Guests</option>
          </select>
        </div>
        <div class="col-6 col-md-2">
          <button class="btn btn-warning w-100 fw-700 py-2">
            <i class="bi bi-search me-1"></i>Search
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== MAIN CONTENT ========== -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="row g-4">

      <!-- ===== FILTERS SIDEBAR ===== -->
      <div class="col-12 col-lg-3">
        <div class="filter-card">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-700 mb-0"><i class="bi bi-sliders me-2 text-danger"></i>Filters</h6>
            <a href="#" class="text-danger small fw-600 text-decoration-none">Clear All</a>
          </div>

          <!-- Location -->
          <div class="filter-group">
            <h6 class="filter-title">Location</h6>
            <div class="input-group input-group-sm mb-2">
              <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
              <input type="text" class="form-control" placeholder="Search location..."/>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-2">
              <span class="filter-chip active">All</span>
              <span class="filter-chip">Mumbai</span>
              <span class="filter-chip">Goa</span>
              <span class="filter-chip">Delhi</span>
              <span class="filter-chip">Jaipur</span>
              <span class="filter-chip">Kerala</span>
              <span class="filter-chip">Manali</span>
              <span class="filter-chip">Udaipur</span>
            </div>
          </div>

          <!-- Price Range -->
          <div class="filter-group">
            <h6 class="filter-title">Price Per Night</h6>
            <input type="range" class="form-range price-range" min="500" max="25000" value="15000" id="priceRange"
              oninput="document.getElementById('priceVal').textContent='₹'+this.value.toLocaleString()"/>
            <div class="d-flex justify-content-between mt-1">
              <span class="text-muted small">₹500</span>
              <span class="fw-700 small text-danger" id="priceVal">₹15,000</span>
              <span class="text-muted small">₹25,000</span>
            </div>
          </div>

          <!-- Star Rating -->
          <div class="filter-group">
            <h6 class="filter-title">Star Rating</h6>
            <div class="d-flex flex-column gap-2">
              <label class="filter-check"><input type="checkbox" checked/><span class="ms-2">5 Star <span class="text-warning ms-1">★★★★★</span></span></label>
              <label class="filter-check"><input type="checkbox" checked/><span class="ms-2">4 Star <span class="text-warning ms-1">★★★★</span></span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2">3 Star <span class="text-warning ms-1">★★★</span></span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2">2 Star <span class="text-warning ms-1">★★</span></span></label>
            </div>
          </div>

          <!-- Guest Rating -->
          <div class="filter-group">
            <h6 class="filter-title">Guest Rating</h6>
            <div class="d-flex flex-column gap-2">
              <label class="filter-check"><input type="checkbox" checked/><span class="ms-2">Excellent (4.5+)</span></label>
              <label class="filter-check"><input type="checkbox" checked/><span class="ms-2">Very Good (4.0+)</span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2">Good (3.5+)</span></label>
            </div>
          </div>

          <!-- Amenities -->
          <div class="filter-group">
            <h6 class="filter-title">Amenities</h6>
            <div class="d-flex flex-column gap-2">
              <label class="filter-check"><input type="checkbox" checked/><span class="ms-2"><i class="bi bi-wifi text-primary me-1"></i>Free WiFi</span></label>
              <label class="filter-check"><input type="checkbox" checked/><span class="ms-2"><i class="bi bi-droplet-fill text-info me-1"></i>Swimming Pool</span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2"><i class="bi bi-cup-hot text-warning me-1"></i>Breakfast Included</span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2"><i class="bi bi-car-front text-success me-1"></i>Free Parking</span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2"><i class="bi bi-fan text-secondary me-1"></i>Air Conditioning</span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2"><i class="bi bi-dumbbell text-danger me-1"></i>Gym / Fitness</span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2"><i class="bi bi-flower1 text-success me-1"></i>Spa &amp; Wellness</span></label>
            </div>
          </div>

          <!-- Property Type -->
          <div class="filter-group border-0 pb-0">
            <h6 class="filter-title">Property Type</h6>
            <div class="d-flex flex-column gap-2">
              <label class="filter-check"><input type="checkbox" checked/><span class="ms-2">Hotel</span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2">Resort</span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2">Villa</span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2">Homestay</span></label>
              <label class="filter-check"><input type="checkbox"/><span class="ms-2">Boutique Hotel</span></label>
            </div>
          </div>
        </div>
      </div>

      <!-- ===== HOTEL LISTINGS ===== -->
      <div class="col-12 col-lg-9">

        <!-- Sort Bar -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
          <p class="mb-0 text-muted small"><span class="fw-700 text-dark">9 hotels</span> found in India</p>
          <div class="d-flex align-items-center gap-2">
            <span class="text-muted small">Sort by:</span>
            <select class="form-select form-select-sm sort-select">
              <option>Recommended</option>
              <option>Price: Low to High</option>
              <option>Price: High to Low</option>
              <option>Rating: High to Low</option>
              <option>Newest First</option>
            </select>
          </div>
        </div>

        <!-- Hotel Cards Grid -->
        <div class="row g-4" id="hotelGrid">

          <!-- Card 1 -->
          <div class="col-12 col-md-6 col-xl-4" data-price="4299" data-rating="4.8" data-name="The Grand Palace" data-location="mumbai">
            <div class="hotel-card card border-0 shadow-sm h-100">
              <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=500&q=80" class="card-img-top hotel-img" alt="The Grand Palace"/>
                <span class="badge bg-success position-absolute top-0 start-0 m-2">Free Cancellation</span>
                <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
                <span class="stars-badge"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></span>
              </div>
              <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-700 mb-0">The Grand Palace</h6>
                  <span class="rating-badge">4.8 <i class="bi bi-star-fill"></i></span>
                </div>
                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Mumbai, Maharashtra</p>
                <p class="text-muted small mb-3 flex-grow-1">Iconic luxury hotel overlooking the Arabian Sea with world-class dining and premium spa facilities.</p>
                <div class="d-flex gap-1 flex-wrap mb-3">
                  <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                  <span class="amenity-tag"><i class="bi bi-droplet-fill"></i> Pool</span>
                  <span class="amenity-tag"><i class="bi bi-cup-hot"></i> Breakfast</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <div>
                    <span class="text-muted text-decoration-line-through small">₹6,500</span>
                    <div class="fw-800 text-primary fs-5">₹4,299<span class="fs-6 fw-400 text-muted">/night</span></div>
                  </div>
                  <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View Details</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="col-12 col-md-6 col-xl-4" data-price="5499" data-rating="4.6" data-name="Sunset Beach Resort" data-location="goa">
            <div class="hotel-card card border-0 shadow-sm h-100">
              <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=500&q=80" class="card-img-top hotel-img" alt="Sunset Beach Resort"/>
                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">Best Seller</span>
                <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
                <span class="stars-badge"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></span>
              </div>
              <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-700 mb-0">Sunset Beach Resort</h6>
                  <span class="rating-badge">4.6 <i class="bi bi-star-fill"></i></span>
                </div>
                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Goa, North Goa</p>
                <p class="text-muted small mb-3 flex-grow-1">Beachfront resort with stunning ocean views, water sports, and award-winning seafood restaurant.</p>
                <div class="d-flex gap-1 flex-wrap mb-3">
                  <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                  <span class="amenity-tag"><i class="bi bi-droplet-fill"></i> Pool</span>
                  <span class="amenity-tag"><i class="bi bi-car-front"></i> Parking</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <div>
                    <span class="text-muted text-decoration-line-through small">₹8,000</span>
                    <div class="fw-800 text-primary fs-5">₹5,499<span class="fs-6 fw-400 text-muted">/night</span></div>
                  </div>
                  <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View Details</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="col-12 col-md-6 col-xl-4" data-price="4680" data-rating="4.9" data-name="Heritage Haveli" data-location="jaipur">
            <div class="hotel-card card border-0 shadow-sm h-100">
              <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=500&q=80" class="card-img-top hotel-img" alt="Heritage Haveli"/>
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">35% OFF</span>
                <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
                <span class="stars-badge"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></span>
              </div>
              <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-700 mb-0">Heritage Haveli</h6>
                  <span class="rating-badge">4.9 <i class="bi bi-star-fill"></i></span>
                </div>
                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Jaipur, Rajasthan</p>
                <p class="text-muted small mb-3 flex-grow-1">Royal heritage property with authentic Rajasthani architecture, cultural performances and royal dining.</p>
                <div class="d-flex gap-1 flex-wrap mb-3">
                  <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                  <span class="amenity-tag"><i class="bi bi-cup-hot"></i> Breakfast</span>
                  <span class="amenity-tag"><i class="bi bi-fan"></i> AC</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <div>
                    <span class="text-muted text-decoration-line-through small">₹7,200</span>
                    <div class="fw-800 text-primary fs-5">₹4,680<span class="fs-6 fw-400 text-muted">/night</span></div>
                  </div>
                  <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View Details</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 4 -->
          <div class="col-12 col-md-6 col-xl-4" data-price="3299" data-rating="4.7" data-name="Mountain View Lodge" data-location="manali">
            <div class="hotel-card card border-0 shadow-sm h-100">
              <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=500&q=80" class="card-img-top hotel-img" alt="Mountain View Lodge"/>
                <span class="badge bg-info text-dark position-absolute top-0 start-0 m-2">New</span>
                <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
                <span class="stars-badge"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></span>
              </div>
              <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-700 mb-0">Mountain View Lodge</h6>
                  <span class="rating-badge">4.7 <i class="bi bi-star-fill"></i></span>
                </div>
                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Manali, Himachal Pradesh</p>
                <p class="text-muted small mb-3 flex-grow-1">Cosy mountain retreat with panoramic Himalayan views, wood-fired fireplaces and adventure activities.</p>
                <div class="d-flex gap-1 flex-wrap mb-3">
                  <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                  <span class="amenity-tag"><i class="bi bi-fire"></i> Fireplace</span>
                  <span class="amenity-tag"><i class="bi bi-cup-hot"></i> Breakfast</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <div>
                    <span class="text-muted text-decoration-line-through small">₹5,500</span>
                    <div class="fw-800 text-primary fs-5">₹3,299<span class="fs-6 fw-400 text-muted">/night</span></div>
                  </div>
                  <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View Details</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 5 -->
          <div class="col-12 col-md-6 col-xl-4" data-price="12499" data-rating="4.9" data-name="Lake Palace Udaipur" data-location="udaipur">
            <div class="hotel-card card border-0 shadow-sm h-100">
              <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=500&q=80" class="card-img-top hotel-img" alt="Lake Palace Udaipur"/>
                <span class="badge bg-success position-absolute top-0 start-0 m-2">Free Cancellation</span>
                <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
                <span class="stars-badge"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></span>
              </div>
              <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-700 mb-0">Lake Palace Udaipur</h6>
                  <span class="rating-badge">4.9 <i class="bi bi-star-fill"></i></span>
                </div>
                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Udaipur, Rajasthan</p>
                <p class="text-muted small mb-3 flex-grow-1">Floating palace on Lake Pichola offering unparalleled royal luxury with stunning sunset views.</p>
                <div class="d-flex gap-1 flex-wrap mb-3">
                  <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                  <span class="amenity-tag"><i class="bi bi-droplet-fill"></i> Pool</span>
                  <span class="amenity-tag"><i class="bi bi-flower1"></i> Spa</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <div>
                    <span class="text-muted text-decoration-line-through small">₹18,000</span>
                    <div class="fw-800 text-primary fs-5">₹12,499<span class="fs-6 fw-400 text-muted">/night</span></div>
                  </div>
                  <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View Details</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 6 -->
          <div class="col-12 col-md-6 col-xl-4" data-price="6799" data-rating="4.8" data-name="Kerala Backwater Resort" data-location="kerala">
            <div class="hotel-card card border-0 shadow-sm h-100">
              <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1582610116397-edb318620f90?w=500&q=80" class="card-img-top hotel-img" alt="Kerala Backwater Resort"/>
                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">Top Rated</span>
                <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
                <span class="stars-badge"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></span>
              </div>
              <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-700 mb-0">Kerala Backwater Resort</h6>
                  <span class="rating-badge">4.8 <i class="bi bi-star-fill"></i></span>
                </div>
                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Alleppey, Kerala</p>
                <p class="text-muted small mb-3 flex-grow-1">Serene resort on the famous backwaters with houseboat experiences and Ayurvedic treatments.</p>
                <div class="d-flex gap-1 flex-wrap mb-3">
                  <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                  <span class="amenity-tag"><i class="bi bi-flower1"></i> Ayurveda</span>
                  <span class="amenity-tag"><i class="bi bi-cup-hot"></i> Breakfast</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <div>
                    <span class="text-muted text-decoration-line-through small">₹9,000</span>
                    <div class="fw-800 text-primary fs-5">₹6,799<span class="fs-6 fw-400 text-muted">/night</span></div>
                  </div>
                  <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View Details</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 7 -->
          <div class="col-12 col-md-6 col-xl-4" data-price="8799" data-rating="4.7" data-name="The Imperial Delhi" data-location="delhi">
            <div class="hotel-card card border-0 shadow-sm h-100">
              <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=500&q=80" class="card-img-top hotel-img" alt="Imperial Delhi"/>
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">20% OFF</span>
                <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
                <span class="stars-badge"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></span>
              </div>
              <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-700 mb-0">The Imperial Delhi</h6>
                  <span class="rating-badge">4.7 <i class="bi bi-star-fill"></i></span>
                </div>
                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>New Delhi, Delhi</p>
                <p class="text-muted small mb-3 flex-grow-1">Historic luxury hotel in the heart of New Delhi with colonial charm and modern five-star amenities.</p>
                <div class="d-flex gap-1 flex-wrap mb-3">
                  <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                  <span class="amenity-tag"><i class="bi bi-droplet-fill"></i> Pool</span>
                  <span class="amenity-tag"><i class="bi bi-car-front"></i> Parking</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <div>
                    <span class="text-muted text-decoration-line-through small">₹11,000</span>
                    <div class="fw-800 text-primary fs-5">₹8,799<span class="fs-6 fw-400 text-muted">/night</span></div>
                  </div>
                  <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View Details</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 8 -->
          <div class="col-12 col-md-6 col-xl-4" data-price="4100" data-rating="4.5" data-name="Zen Garden Resort" data-location="kerala">
            <div class="hotel-card card border-0 shadow-sm h-100">
              <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1561501900-3701fa6a0864?w=500&q=80" class="card-img-top hotel-img" alt="Zen Garden Resort"/>
                <span class="badge bg-success position-absolute top-0 start-0 m-2">Free Cancellation</span>
                <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
                <span class="stars-badge"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></span>
              </div>
              <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-700 mb-0">Zen Garden Resort</h6>
                  <span class="rating-badge">4.5 <i class="bi bi-star-fill"></i></span>
                </div>
                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Munnar, Kerala</p>
                <p class="text-muted small mb-3 flex-grow-1">Nestled in lush tea plantations with valley views, yoga retreats, and organic farm dining experiences.</p>
                <div class="d-flex gap-1 flex-wrap mb-3">
                  <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                  <span class="amenity-tag"><i class="bi bi-flower1"></i> Yoga</span>
                  <span class="amenity-tag"><i class="bi bi-cup-hot"></i> Breakfast</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <div>
                    <span class="text-muted text-decoration-line-through small">₹6,200</span>
                    <div class="fw-800 text-primary fs-5">₹4,100<span class="fs-6 fw-400 text-muted">/night</span></div>
                  </div>
                  <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View Details</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 9 -->
          <div class="col-12 col-md-6 col-xl-4" data-price="5200" data-rating="4.8" data-name="Desert Bloom Luxury Camp" data-location="jaipur">
            <div class="hotel-card card border-0 shadow-sm h-100">
              <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1549294413-26f195200c16?w=500&q=80" class="card-img-top hotel-img" alt="Desert Bloom Camp"/>
                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">Unique Stay</span>
                <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
                <span class="stars-badge"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></span>
              </div>
              <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="fw-700 mb-0">Desert Bloom Luxury Camp</h6>
                  <span class="rating-badge">4.8 <i class="bi bi-star-fill"></i></span>
                </div>
                <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Jaisalmer, Rajasthan</p>
                <p class="text-muted small mb-3 flex-grow-1">Luxury desert camp under a blanket of stars with camel safaris, folk music and traditional Rajasthani cuisine.</p>
                <div class="d-flex gap-1 flex-wrap mb-3">
                  <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                  <span class="amenity-tag"><i class="bi bi-music-note"></i> Folk Show</span>
                  <span class="amenity-tag"><i class="bi bi-cup-hot"></i> All Meals</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-auto">
                  <div>
                    <span class="text-muted text-decoration-line-through small">₹7,800</span>
                    <div class="fw-800 text-primary fs-5">₹5,200<span class="fs-6 fw-400 text-muted">/night</span></div>
                  </div>
                  <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View Details</a>
                </div>
              </div>
            </div>
          </div>

        </div><!-- end #hotelGrid -->

        <!-- Pagination — rendered dynamically by pagination.js -->
        <div class="d-flex justify-content-center mt-5">
          <nav aria-label="Hotel listing pagination">
            <ul class="pagination pagination-custom" id="paginationList">
              <!-- Rendered dynamically by pagination.js -->
            </ul>
          </nav>
        </div>

      </div><!-- end listings col -->
    </div><!-- end row -->
  </div><!-- end container -->
</section>

<!-- ========== FOOTER ========== -->
<footer class="bg-dark text-white pt-5 pb-3">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-12 col-md-4">
        <h5 class="fw-800 mb-3"><i class="bi bi-building-fill text-warning me-1"></i>bookHotel</h5>
        <p class="text-white-50 small">Your trusted travel partner since 2015. We make hotel booking simple, affordable, and enjoyable for millions of travellers.</p>
        <div class="d-flex gap-3 mt-3">
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-facebook fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-twitter-x fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-instagram fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-youtube fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-linkedin fs-5"></i></a>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Company</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Press</a></li>
          <li><a href="#">Blog</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Support</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">Help Center</a></li>
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">Cancellation Policy</a></li>
          <li><a href="#">Safety Info</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Explore</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="hotels.php">Hotels</a></li>
          <li><a href="#">Destinations</a></li>
          <li><a href="my-bookings.php">My Bookings</a></li>
          <li><a href="#">Car Rentals</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Legal</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Use</a></li>
          <li><a href="#">Cookie Policy</a></li>
          <li><a href="#">Sitemap</a></li>
        </ul>
      </div>
    </div>
    <hr class="border-secondary"/>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <p class="text-white-50 small mb-0">© 2026 bookHotel Technologies Pvt. Ltd. All rights reserved.</p>
      <div class="d-flex gap-2">
        <img src="https://img.shields.io/badge/Visa-1A1F71?style=flat&logo=visa&logoColor=white" height="20" alt="Visa"/>
        <img src="https://img.shields.io/badge/Mastercard-EB001B?style=flat&logo=mastercard&logoColor=white" height="20" alt="Mastercard"/>
        <img src="https://img.shields.io/badge/UPI-1a73e8?style=flat&logo=google-pay&logoColor=white" height="20" alt="UPI"/>
        <img src="https://img.shields.io/badge/PayPal-003087?style=flat&logo=paypal&logoColor=white" height="20" alt="PayPal"/>
      </div>
    </div>
  </div>
</footer>

<button id="backToTop" class="btn btn-warning btn-sm rounded-circle shadow" aria-label="Back to top"
  onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="navbar.js"></script>
<script src="pagination.js"></script>
<script>
  // Navbar scroll + back-to-top
  window.addEventListener('scroll', () => {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
    document.getElementById('backToTop').classList.toggle('show', window.scrollY > 300);
  });

  // Wishlist toggle
  document.querySelectorAll('.btn-wishlist').forEach(btn => {
    btn.addEventListener('click', () => {
      const icon = btn.querySelector('i');
      icon.classList.toggle('bi-heart');
      icon.classList.toggle('bi-heart-fill');
      icon.classList.toggle('text-danger');
    });
  });

  // Default dates
  const t = new Date(), t1 = new Date(t), t2 = new Date(t);
  t1.setDate(t.getDate() + 1); t2.setDate(t.getDate() + 2);
  const fmt = d => d.toISOString().split('T')[0];
  document.getElementById('checkin').value  = fmt(t1);
  document.getElementById('checkout').value = fmt(t2);

  // ============================================================
  //  FILTER & SORT — delegates show/hide to pagination.js
  // ============================================================
  let activeLocation = 'all';
  let maxPrice = 25000;

  function allCards() {
    return [...document.querySelectorAll('[data-location]')];
  }

  function applyAllFilters() {
    const filtered = allCards().filter(card => {
      const loc   = card.getAttribute('data-location') || '';
      const price = parseInt(card.getAttribute('data-price')) || 0;
      const locOk   = (activeLocation === 'all') || (loc === activeLocation);
      const priceOk = price <= maxPrice;
      return locOk && priceOk;
    });

    if (window.Pagination) {
      window.Pagination.setFiltered(filtered);
    }
  }

  // Location chips
  document.querySelectorAll('.filter-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
      chip.classList.add('active');
      activeLocation = chip.textContent.trim().toLowerCase();
      applyAllFilters();
    });
  });

  // Price range slider
  document.getElementById('priceRange').addEventListener('input', function () {
    maxPrice = parseInt(this.value);
    applyAllFilters();
  });

  // Sort by
  document.querySelector('.sort-select').addEventListener('change', function () {
    const val  = this.value;
    const grid = document.getElementById('hotelGrid');
    const cards = allCards();

    cards.sort((a, b) => {
      const pa = parseInt(a.getAttribute('data-price'));
      const pb = parseInt(b.getAttribute('data-price'));
      const ra = parseFloat(a.getAttribute('data-rating'));
      const rb = parseFloat(b.getAttribute('data-rating'));
      const na = (a.getAttribute('data-name') || '').toLowerCase();
      const nb = (b.getAttribute('data-name') || '').toLowerCase();
      if (val === 'Price: Low to High')  return pa - pb;
      if (val === 'Price: High to Low')  return pb - pa;
      if (val === 'Rating: High to Low') return rb - ra;
      if (val === 'Newest First')        return na.localeCompare(nb);
      return 0;
    });

    cards.forEach(card => grid.appendChild(card));
    applyAllFilters();
  });
</script>
</body>
</html>
