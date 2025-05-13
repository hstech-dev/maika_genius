function getCartToken() {
    return fetch('/wp-json/wc/store/v1/cart', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.headers.get('cart-token') || '')
    .catch(error => {
        console.error('Error get cart-token:', error);
        return '';
    });
}

getCartToken().then(function(cartToken) {
    // console.log("cartToken: " + cartToken);
    (function() {
        window.MaikaEngine = window.MaikaEngine || { q: [] };
        MaikaEngine.q.push(['initLiveChat', {
            cid: maikaEngineData.cid,
            actBySettings: true,
            cartToken: cartToken,
        }]);
    })();
});