
particlesJS("particles-js", {
  "particles": {
      "number": {
          "value": 12,
          "density": {
              "enable": true,
              "value_area": 800
          }
      },
      "color": {
          "value": "#44d4c8"
      },
      "shape": {
          "type": "polygon",
          "stroke": {
              "width": 0,
              "color": "#000"
          },
          "polygon": {
              "nb_sides": 6
          },
         
      },
      "opacity": {
          "value": 0.3,
          "random": true,
          "anim": {
              "enable": false,
              "speed": 1,
              "opacity_min": 0.1,
              "sync": false
          }
      },
      "size": {
        //this is animation shape size
          "value": 90,
          "random": true,
          "anim": {
              "enable": true,
              "speed": 10,
              "size_min":20,
              "sync": false
          }
      },
      "line_linked": {
          "enable": false,
          "distance": 200,
          "color": "#ffffff",
          "opacity": 1,
          "width": 2
      },
      "move": {
          "enable": true,
          "speed": 8,
          "direction": "none",
          "random": false,
          "straight": false,
          "out_mode": "out",
          "bounce": false,
          "attract": {
              "enable": false,
              "rotateX": 600,
              "rotateY": 1200
          }
      }
  },
  "interactivity": {
      "detect_on": "canvas",
      "events": {
          "onhover": {
              "enable": true,
              "mode": "repulse"
          },
          "onclick": {
              "enable": false,
              "mode": "repulse"
          },
          "resize": true
      },
      "modes": {
          "grab": {
              "distance": 400,
              "line_linked": {
                  "opacity": 1
              }
          },
          "bubble": {
              "distance": 400,
              "size": 40,
              "duration": 2,
              "opacity": 8,
              "speed": 3
          },
          "repulse": {
              "distance": 200,
              "duration": 0.4
          },
          "push": {
              "particles_nb": 4
          },
          "remove": {
              "particles_nb": 2
          }
      }
  },
  "retina_detect": true
});
var count_particles, stats, update;
stats = new Stats;
stats.setMode(0);
stats.domElement.style.position = 'absolute';
stats.domElement.style.left = '0px';
stats.domElement.style.top = '0px';
document.body.appendChild(stats.domElement);
count_particles = document.querySelector('.js-count-particles');
update = function () {
  stats.begin();
  stats.end();
  if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) {
      count_particles.innerText = window.pJSDom[0].pJS.particles.array.length;
  }
  requestAnimationFrame(update);
};
requestAnimationFrame(update);








document.addEventListener("DOMContentLoaded", function () {
  setTimeout(function () {
    var spinnerMain = document.querySelector('.spinner-main');
    var content = document.querySelector('.content');

    // Check if elements were found before applying styles
    if (spinnerMain) {
      spinnerMain.style.display = 'none';
    }

    if (content) {
      content.style.display = 'block';
    }
  }, 2000);
});





$(document).ready(function () {
  $("#testimonial-slider").owlCarousel({
    items: 2,
    itemsDesktop: [1000, 2],
    itemsDesktopSmall: [990, 1],
    itemsTablet: [768, 1],
    pagination: true,
    navigation: false,
    navigationText: ["", ""],
    slideSpeed: 1000,
    autoPlay: true
  });
});


function truncateText(element, maxLength) {
  let truncated = element.innerText;

  if (truncated.length > maxLength) {
      truncated = truncated.substr(0, maxLength) + '...';
  }

  return truncated;
}

const paragraphElements = document.getElementsByClassName('myParagraph');

for (const element of paragraphElements) {
  element.innerText = truncateText(element, 100);
}


function sidebartitle(element, maxLength) {
  let truncated = element.innerText;

  if (truncated.length > maxLength) {
      truncated = truncated.substr(0, maxLength) + '...';
  }

  return truncated;
}

const  postsidebarTitle = document.getElementsByClassName('sidebarTitle');

for (const element of postsidebarTitle ) {
  element.innerText = sidebartitle(element, 30);
}







