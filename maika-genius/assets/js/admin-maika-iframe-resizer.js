(function () {
  let iframes = document.querySelectorAll("iframe[id^='MAIKA_IFRAME']");
  let s = document.createElement('script');
  s.setAttribute('src', 'https://hub.askmaika.ai/app/lib/iframe-resizer/js/iframeResizer.min.js');
  s.onload = function () {
    iframes.forEach(function (iframe) {
      iFrameResize({}, iframe);
    });
  }
  document.head.appendChild(s);
})();
