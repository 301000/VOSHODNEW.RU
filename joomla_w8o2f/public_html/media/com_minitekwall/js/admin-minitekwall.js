(function (document) {
  "use strict";

  function checkGridRadio() {
    var grid_radio_input_checked = document.querySelectorAll(
      ".grid-radio-input:checked"
    );

    if (grid_radio_input_checked) {
      grid_radio_input_checked.forEach(function (a) {
        a.closest(".grid-radio").classList.add("active");
      });
    }

    var grid_radio_inputs = document.querySelectorAll(".grid-radio-input");

    if (grid_radio_inputs) {
      grid_radio_inputs.forEach(function (a) {
        a.addEventListener("change", function () {
          a.closest(".controls")
            .querySelectorAll(".grid-radio")
            .forEach(function (b) {
              b.classList.remove("active");
            });

          this.setAttribute("checked", "checked");
          this.closest(".grid-radio").classList.add("active");
        });
      });
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    checkGridRadio();
  });
})(document);
