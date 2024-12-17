(function() {
    window.MaikaEngine = window.MaikaEngine || {q: []};
    let searchParams = new URLSearchParams(window.location.search);
    MaikaEngine.q.push(['initLiveChat', {
        cid: maikaEngineData.cid,
    }]);
})();