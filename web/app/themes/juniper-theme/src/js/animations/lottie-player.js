// select all 
let targetBtns = document.querySelectorAll('.btn.btn-underline:not(.cardgrid-btn)')

targetBtns.forEach((btn, index) => {
   window[`linkBtn-${index}`] = bodymovin.loadAnimation({
      // animationData: { /* ... */ },
      container: btn, // required
      path: lottieData.assetPath + '/json/dark-link-animation.json', // required
      renderer: 'svg', // required
      loop: false, // optional
      autoplay: false, // optional
      name: "Link Animation", // optional
   });

   let directionMenu = 1;
   btn.addEventListener('mouseenter', (e) => {
      window[`linkBtn-${index}`].setDirection(directionMenu);
      window[`linkBtn-${index}`].play();
   });

   btn.addEventListener('mouseleave', (e) => {
      window[`linkBtn-${index}`].setDirection(-directionMenu);
      window[`linkBtn-${index}`].play();
   });
})
