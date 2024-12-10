function showDetails(element) {
  // Pause the scroll animation
  document.querySelector('.scroll-container').classList.add('paused');
  
  // Add any necessary visual changes to the clicked box (optional)
  element.classList.add('highlighted');
}


