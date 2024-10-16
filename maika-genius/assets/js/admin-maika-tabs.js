document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('.maika-tab');
  const contents = document.querySelectorAll('.maika-tab-content');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('maika-active'));
      tab.classList.add('maika-active');

      const target = tab.getAttribute('data-tab');

      contents.forEach(content => {
        if (content.id === target) {
          content.style.display = 'block';
        } else {
          content.style.display = 'none';
        }
      });

      //Change link when click
      let currentUrl = window.location.href;
      let urlObj = new URL(currentUrl);
      urlObj.searchParams.set("tab", target);
      history.pushState(null, '', urlObj.toString());
    });
  });
});
