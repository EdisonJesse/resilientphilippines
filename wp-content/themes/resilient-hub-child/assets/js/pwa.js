(function () {
  if (!("serviceWorker" in navigator) || !window.rpPwa) {
    return;
  }

  window.addEventListener("load", function () {
    navigator.serviceWorker.register(rpPwa.serviceWorkerUrl, { scope: "/" }).catch(function () {
      // Browsers require a secure origin for installable PWAs; local HTTP may skip registration.
    });
  });
})();
