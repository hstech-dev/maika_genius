(function() {
    window.MaikaEngine = window.MaikaEngine || {q: []};
    let searchParams = new URLSearchParams(window.location.search);
    MaikaEngine.q.push(['initLiveChat', {
        theme: {
            primaryColor: maikaEngineData.primaryColor,
            title: maikaEngineData.title,
            visitorMsgBg: '',
        },
        cid: maikaEngineData.cid,
    }]);
})();