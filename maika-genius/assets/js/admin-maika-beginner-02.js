// Beginner's guide to What Maika Genius
document.addEventListener('DOMContentLoaded', () => {
  if (!localStorage.getItem("maikaExperiencedUserWhatMaikaGenius")) {
    document.getElementById("contentWhatMaikaGenius").style.display = 'block';
    localStorage.setItem("maikaExperiencedUserWhatMaikaGenius", true);
  }

  document.getElementById("whatMaikaGenius").onclick = function () {
    const contentWhatMaikaGenius = document.getElementById("contentWhatMaikaGenius");
    const state = changeDisplayStyle(contentWhatMaikaGenius);
  };

  function changeDisplayStyle(element) {
    const currentDisplay = window.getComputedStyle(element).display;
    if (currentDisplay === "none") {
      element.style.display = "block";
      return "block";
    } else {
      element.style.display = "none";
      return "none";
    }
  }
});
