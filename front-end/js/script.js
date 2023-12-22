// DARK MODE 
let toggleBtn = document.getElementById('toggle-btn');
let body = document.body;
let darkMode = localStorage.getItem('dark-mode');

const enableDarkMode = () =>{
   toggleBtn.classList.replace('fa-sun', 'fa-moon');
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
}

const disableDarkMode = () =>{
   toggleBtn.classList.replace('fa-moon', 'fa-sun');
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
}

if(darkMode === 'enabled'){
   enableDarkMode();
}

toggleBtn.onclick = (e) =>{
   darkMode = localStorage.getItem('dark-mode');
   if(darkMode === 'disabled'){
      enableDarkMode();
   }else{
      disableDarkMode();
   }
}

// BUTTON PROFILE
let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   search.classList.remove('active');
}


// BUTTON SEARCH
let search = document.querySelector('.header .flex .search-form');

document.querySelector('#search-btn').onclick = () =>{
   search.classList.toggle('active');
   profile.classList.remove('active');
}

// BUTTON MENU - SIDE BAR
document.addEventListener("DOMContentLoaded", function() {
   let sideBar = document.querySelector('.side-bar');
   let menuButton = document.querySelector('#menu-btn');
   let closeButton = document.querySelector('#close-btn');

   // Mengaktifkan sidebar saat halaman dimuat
   sideBar.classList.add('active');
   body.classList.add('active');

   menuButton.onclick = () => {
     sideBar.classList.toggle('active');
     body.classList.toggle('active');
   };

   closeButton.onclick = () => {
      sideBar.classList.add('active'); // Menghapus kelas 'active'
      body.classList.add('active'); // Menghapus kelas 'active'
   };
 });

//  WINDOW SCROLL
window.onscroll = () =>{
   profile.classList.remove('active');
   search.classList.remove('active');

   if(window.innerWidth < 1200){
      sideBar.classList.remove('active');
      body.classList.remove('active');
   }
}