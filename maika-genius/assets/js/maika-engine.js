(function () {
    window.MaikaEngine = window.MaikaEngine || { q: [] };
    MaikaEngine.q.push(['initLiveChat', {
        cid: maikaEngineData.cid,
        actBySettings: true,
        intSource: 'maika_genius',
    }]);
})();

// document.addEventListener('DOMContentLoaded', function() {
//     const ajaxUrl = '/wp-admin/admin-ajax.php';

//     const data = new URLSearchParams();
//     data.append('action', 'init_woocommerce_session_maika_genius');

//     fetch(ajaxUrl, {
//         method: 'POST',
//         body: data,
//         headers: { 
//             'Content-Type': 'application/x-www-form-urlencoded'
//         }
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error(`HTTP error! status: ${response.status}`);
//         }
//         return response.json();
//     })
//     .then(data => {
//         if (data.success) {
//             console.log('Success: ', data.data.message);

//             (function () {
//                 window.MaikaEngine = window.MaikaEngine || { q: [] };
//                 // console.log('cid: ', maikaEngineData.cid);
//                 MaikaEngine.q.push(['initLiveChat', {
//                     cid: maikaEngineData.cid,
//                     actBySettings: true,
//                     intSource: 'maika_genius',
//                 }]);
//             })();
//             // ---------------------------------------------------------------------
//             // ---------------------------------------------------------------------

//         } else {
//             console.error('Error init WooCommerce session:', data.data.message);
//         }
//     })
//     .catch(error => {
//         console.error('Error call AJAX:', error);
//     });
// });