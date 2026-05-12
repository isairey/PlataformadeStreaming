import MovieManager from './movieManager.js';

const nav = document.querySelector('nav');

window.addEventListener('scroll', () => {
  if (window.scrollY > 0) {
    nav.style.backgroundColor = 'rgb(20,20,20)'; 
  } else {
    nav.style.backgroundColor = 'transparent';
  }
});

const footer = document.querySelector('.service-code');

footer.addEventListener('click', () => {
  footer.innerHTML = '286-128';
});

function selectProfile(profileName) {
  localStorage.setItem('selectedProfile', profileName);
}

document.addEventListener('DOMContentLoaded', () => {
  const selectedProfile = localStorage.getItem('selectedProfile');
  if (selectedProfile) {
    const profileIcon = document.querySelector('.nav-item.icon img');
    profileIcon.src = `../assets/images/Icon/Profile/${selectedProfile}.png`;
  }
  
  MovieManager.init();
});