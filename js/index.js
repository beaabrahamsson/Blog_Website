/*
 * @Author: Beatrice Abrahamsson
 * @Email: beaabrahamsson6@gmail.com
 * @Date: 2022-03-16 12:57:19
 * @Last Modified by: Beatrice Abrahamsson
 * @Last Modified time: 2022-03-18 08:10:19
 * @Description: Description
 */

"use strict";

// When page loads, functions are called
window.onload = visitCounter();
window.onload = hideUsers();
window.onload = showComment();

//Function to show number of visits on page
function visitCounter() {
  let counterContainer = document.querySelector(".website-counter");
  let visitCount = localStorage.getItem("page_view");

  // Check if page_view entry is present
  if (visitCount) {
    visitCount = Number(visitCount) + 1;
    localStorage.setItem("page_view", visitCount);
  } else {
    visitCount = 1;
    localStorage.setItem("page_view", 1);
  }
  counterContainer.innerHTML = visitCount;
}



//Function to show/hide the mobile menu */
function mobileMenu() {
    let menu = document.getElementById("links");
    if (menu.style.display === "block") {
      menu.style.display = "none";
    } else {
      menu.style.display = "block";
    }
  }

  //function to hide list of users
function hideUsers() {
  let target = document.getElementById("hideUsers");
  let btn = document.getElementById("toggle");
  if(typeof(target) != 'undefined' && target != null) {
    btn.onclick = function () {
    if (target.style.display !== "none") {
      target.style.display = "none";
      btn.textContent = 'Visa lista';
    } else {
      target.style.display = "block";
      btn.textContent = 'Dölj lista';
    }
  };
  }

}
//Function to show comment section/form
function showComment() {
  let target = document.getElementById("commentForm");
  let btn = document.getElementById("toggleComments");
  if(typeof(target) != 'undefined' && target != null) {
    btn.onclick = function () {
    if (target.style.display !== "block") {
      target.style.display = "block";
      btn.style.display = "none";
    } else {
      target.style.display = "none";
    }
  };
  }
}
  