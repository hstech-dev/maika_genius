document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('.maika-tab');
  const contents = document.querySelectorAll('.maika-tab-content');

  const dataTabs = document.querySelector('.maika-tabs');
  const dataCid = dataTabs?.getAttribute('data-tabs-cid');
  const dataSecretKey = dataTabs?.getAttribute('data-tabs-secretKey');
  const dataDomainWeb = dataTabs?.getAttribute('data-tabs-domainWeb');

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

      // Update the iframe into the DOM tree if needed
      switch(target) {
        case "settings":
          const iframeContainerSettings = document.getElementById('iframe_maika_container_'+target);
          if(iframeContainerSettings){
            const iframeHTMLSettings = `<iframe id='MAIKA_IFRAME_settings' src='https://hub.askmaika.ai/app/site?cid=${dataCid}&secret_key=${dataSecretKey}&display_mode=embed&wp_domain=${dataDomainWeb}' style='border: none; height: auto; width: 100%; min-height: 800px'></iframe>`;
            iframeContainerSettings.innerHTML = iframeHTMLSettings;
          }
          break;
        case "product-descriptor":
          const iframeContainerProductDescriptor = document.getElementById('iframe_maika_container_'+target);
          if(iframeContainerProductDescriptor){
            const iframeHTMLProductDescriptor = `<iframe id='MAIKA_IFRAME_product-descriptor' src='https://hub.askmaika.ai/app/woo_prod_revise?cid=${dataCid}&secret_key=${dataSecretKey}&display_mode=embed&wp_domain=${dataDomainWeb}' style='border: none; height: auto; width: 100%; min-height: 800px'></iframe>`;
            iframeContainerProductDescriptor.innerHTML = iframeHTMLProductDescriptor;
          }
          break;
      }

      // Remove iframes in other tabs
      const list_iframes = ['iframe_maika_container_settings', 'iframe_maika_container_product-descriptor'];
      for(const ifs of list_iframes){
        if(ifs.includes(target)){
          continue;
        }

        let rm_frames = document.getElementById(ifs);
        if(rm_frames){
          rm_frames.innerHTML = '';
        }
      }
      // Iframe resize
      window.maika_iframe_resize();

      // Change link when changing tab
      let currentUrl = window.location.href;
      let urlObj = new URL(currentUrl);
      urlObj.searchParams.set("tab", target);
      history.pushState(null, '', urlObj.toString());
    });
  });
});
