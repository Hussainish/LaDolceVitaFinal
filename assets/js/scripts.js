document.addEventListener("DOMContentLoaded", function() {
    // this script will fetch the header - aka navigation bar - to the pages
    fetch('/src/views/header.php')
      .then(response => response.text())
      .then(data => {
        //the navigation bar will be fetched to div or header with the id "navbar" on the top of each page.
        document.getElementById('navbar').innerHTML = data;
      })
      // in case fetching the file fails , it will print an error in the browser console.
      .catch(error => console.error('Error loading header:', error));
  });
  