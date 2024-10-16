(function () {
  let iframe = document.getElementById('MAIKA_IFRAME');
  let s = document.createElement('script');
  s.setAttribute('src', 'https://hub.askmaika.ai/app/lib/iframe-resizer/js/iframeResizer.min.js');
  s.onload = function () {
    iFrameResize({}, iframe);
  }
  document.head.appendChild(s);
})();
