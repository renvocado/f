// Initialize AOS (Animate On Scroll)
document.addEventListener('DOMContentLoaded', function() {
  AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    offset: 100
  });
  
  // Add loading animation to buttons
  const buttons = document.querySelectorAll('.btn');
  buttons.forEach(button => {
    button.addEventListener('click', function(e) {
      if (this.href && !this.classList.contains('btn-secondary')) {
        const originalText = this.innerHTML;
        this.innerHTML = '<span class="loading"></span> Loading...';
        
        setTimeout(() => {
          this.innerHTML = originalText;
        }, 2000);
      }
    });
  });
  
  // Add subtle hover effect to goal cards
  const goalCards = document.querySelectorAll('.goal-card');
  goalCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-8px)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
    });
  });
  
  // Add focus effect to search input
  const searchInput = document.querySelector('.search-box input');
  if (searchInput) {
    searchInput.addEventListener('focus', function() {
      this.parentElement.style.transform = 'scale(1.02)';
    });
    
    searchInput.addEventListener('blur', function() {
      this.parentElement.style.transform = 'scale(1)';
    });
  }
});

// Additional animations for recipe cards
document.addEventListener('DOMContentLoaded', function() {
  // Add staggered animation delay to recipe cards
  const recipeCards = document.querySelectorAll('.recipe-card');
  recipeCards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
  });
  
  // Smooth scroll to top when sorting
  const sortSelect = document.querySelector('.sort-select');
  if (sortSelect) {
    sortSelect.addEventListener('change', function() {
      // Smooth scroll to top after a short delay to show loading
      setTimeout(() => {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      }, 100);
    });
  }
  
  // Add loading state to goal filters
  const goalFilters = document.querySelectorAll('.goal-filter');
  goalFilters.forEach(filter => {
    filter.addEventListener('click', function(e) {
      if (!this.classList.contains('active')) {
        // Add subtle loading feedback
        this.style.opacity = '0.7';
        setTimeout(() => {
          this.style.opacity = '1';
        }, 500);
      }
    });
  });
});

// Additional functionality for recipe page
document.addEventListener('DOMContentLoaded', function() {
  // Add input validation for serving calculator
  const servingInput = document.querySelector('.serving-input');
  if (servingInput) {
    servingInput.addEventListener('input', function() {
      let value = parseInt(this.value);
      if (value < 1) this.value = 1;
      if (value > 10) this.value = 10;
    });
    
    servingInput.addEventListener('change', function() {
      if (!this.value || this.value < 1) this.value = 1;
    });
  }
  
  // Add smooth scrolling for section navigation
  const sectionHeaders = document.querySelectorAll('.section-header');
  sectionHeaders.forEach(header => {
    header.style.cursor = 'pointer';
    header.addEventListener('click', function() {
      const section = this.parentElement;
      section.scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
      });
    });
  });
  
 
  
  // Add image zoom effect
  const recipeImage = document.querySelector('.recipe-image');
  if (recipeImage) {
    recipeImage.addEventListener('click', function() {
      this.classList.toggle('zoomed');
    });
  }
});

// Shared form enhancements (used by login/register forms)
document.addEventListener('DOMContentLoaded', function() {
  const inputs = document.querySelectorAll('.form-input');
  inputs.forEach(input => {
    input.addEventListener('focus', function() {
      this.parentElement.classList.add('focused');
    });
    
    input.addEventListener('blur', function() {
      if (!this.value) {
        this.parentElement.classList.remove('focused');
      }
    });

    if (input.value) {
      input.parentElement.classList.add('focused');
    }
  });

  const forms = document.querySelectorAll('.login-form');
  forms.forEach(form => {
    form.addEventListener('submit', function() {
      const btn = this.querySelector('.btn-login');
      if (!btn) return;
      const btnText = btn.querySelector('.btn-text');
      if (btnText) btnText.textContent = 'Memproses...';
      btn.disabled = true;
    });
  });
});


