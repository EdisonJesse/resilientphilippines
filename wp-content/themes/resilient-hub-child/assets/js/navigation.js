(function () {
  var toggle = document.querySelector(".rp-menu-toggle");
  var nav = document.querySelector("#rp-primary-navigation");

  if (!toggle || !nav) {
    return;
  }

  toggle.addEventListener("click", function () {
    var isOpen = toggle.getAttribute("aria-expanded") === "true";
    toggle.setAttribute("aria-expanded", String(!isOpen));
    nav.classList.toggle("is-open", !isOpen);
    document.body.classList.toggle("rp-menu-is-open", !isOpen);
  });
})();
