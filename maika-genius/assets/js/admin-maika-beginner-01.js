// Beginner's guide to creating your keys
document.addEventListener('DOMContentLoaded', () => {
  if (!localStorage.getItem("maikaExperiencedUserCreateYourKeys")) {
    document.getElementById("contentCreateYourKeys").style.display = 'block';
    localStorage.setItem("maikaExperiencedUserCreateYourKeys", true);
    //localStorage.setItem("maikaDisplayCreateYourKeys", "none");
  }
  // else{
  //   const defaultState_contentCreateYourKeys = localStorage.getItem("maikaDisplayCreateYourKeys") ? localStorage.getItem("maikaDisplayCreateYourKeys") : "block";
  //   document.getElementById("contentCreateYourKeys").style.display = defaultState_contentCreateYourKeys;
  // }

  document.getElementById("createYourKeys").onclick = function () {
    const contentCreateYourKeys = document.getElementById("contentCreateYourKeys");
    const state = changeDisplayStyle(contentCreateYourKeys);
    //localStorage.setItem("maikaDisplayCreateYourKeys", state);
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
