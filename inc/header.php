<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HealthyBite Drinks - Resep Jus & Smoothie Sehat</title>
  <meta name="description" content="Temukan resep jus dan smoothie sehat untuk berbagai kebutuhan kesehatan seperti detox, energi, diet, dan relaksasi.">
  
  <link rel="stylesheet" href="assets/style.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
  <header class="header">
    <div class="container">
      <div class="header-content">
        <div class="logo">
          <h1>HealthyBite Drinks</h1>
          <p class="tagline">Pilih tujuan atau cari resep jus/smoothie.</p>
        </div>
        
        <form action="recommend.php" method="get" class="search-form">
          <div class="search-box">
            <input type="text" name="q" placeholder="Cari: pisang, oat, bayam...">
            <button class="btn-search" type="submit">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>
        </form>
      </div>
    </div>
  </header>