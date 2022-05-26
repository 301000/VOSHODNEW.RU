(function (window, document, Joomla) {
  "use strict";

  var id;

  function getColumnWidth(columns) {
    var columnWidth;

    switch (columns) {
      case 1:
        columnWidth = 600;
        break;
      case 2:
        columnWidth = 300;
        break;
      case 3:
        columnWidth = 200;
        break;
      case 4:
        columnWidth = 150;
        break;
      case 5:
        columnWidth = 120;
        break;
      case 6:
        columnWidth = 100;
        break;
      case 7:
        columnWidth = 90;
        break;
      case 8:
      case 9:
      case 10:
      case 11:
      case 12:
        columnWidth = 80;
        break;
      default:
        columnWidth = 150;
        break;
    }

    return columnWidth;
  }

  function getCellHeight(columns) {
    var cellHeight;

    switch (columns) {
      case 1:
      case 2:
      case 3:
      case 4:
        cellHeight = 150;
        break;
      case 5:
        cellHeight = 120;
        break;
      case 6:
        cellHeight = 100;
        break;
      case 7:
        cellHeight = 90;
        break;
      case 8:
      case 9:
      case 10:
      case 11:
      case 12:
        cellHeight = 80;
        break;
      default:
        cellHeight = 150;
        break;
    }

    return cellHeight;
  }

  function createGridItemHtml(element) {
    var html = "";
    html +=
      '<div class="' +
      element.class +
      '" data-width="' +
      element.width +
      '" data-height="' +
      element.height +
      '" data-index="' +
      element.index +
      '">';
    html += '<span class="item-number">' + element.index + "</span> ";
    html += '<span class="item-size">' + element.size + "</span>";
    html += '<div class="gridP-tools">';
    html +=
      '<span class="gridP-edit"><i class="fas fa-pencil-alt"></i></span> ';
    html += '<span class="gridP-remove"><i class="fas fa-times"></i></span>';
    html += "</div>";
    html += "</div>";

    return html;
  }

  function createElementFromHTML(htmlString) {
    var div = document.createElement("div");
    div.innerHTML = htmlString.trim();

    return div.firstChild;
  }

  function initDesigner() {
    var gridContainer = document.querySelector(".gridContainer");

    if (!gridContainer) return;

    var columns = parseInt(gridContainer.getAttribute("data-columns"), 10);
    var columnWidth = getColumnWidth(columns);
    var cellHeight = getCellHeight(columns);

    // Create array of elements for database use
    var dbElems = [];

    // Generate html from saved dbElems
    var options = Joomla.getOptions("com_minitekwall");
    var savedElements = options.elements;
    var elems = JSON.parse(savedElements);

    if (elems) {
      elems.forEach(function (a) {
        var html = createGridItemHtml(a);
        document
          .querySelector(".gridP")
          .appendChild(createElementFromHTML(html));
      });
    }

    // Initialize grid
    var $grid = new Packery(".gridP", {
      initLayout: false,
      resize: false,
      itemSelector: ".gridP-item",
      gutter: 5,
    });

    // Prepare grid, iterate over all items
    $grid.getItemElements().forEach(function (gridItem) {
      // Make all grid-items draggable
      makeDraggable(gridItem);

      // Fix items dimensions
      fixItemDimensions(gridItem, columnWidth, cellHeight);
    });

    function makeDraggable(itemElem) {
      var draggie = new Draggabilly(itemElem);
      $grid.bindDraggabillyEvents(draggie);

      // Order and layout after drag
      draggie.on("dragEnd", function () {
        $grid.layout();
        document.querySelector(".gridEditItem").style.display = "none";
      });
    }

    function fixItemDimensions(gridItem, columnWidth, cellHeight) {
      // Set items css width
      var data_width = parseInt(gridItem.getAttribute("data-width"), 10);
      var itemWidth =
        data_width * columnWidth +
        (data_width - 1) * 5; /* n * columnWidth + gutters */
      gridItem.style.width = itemWidth + "px";

      // Set items css height
      var data_height = parseInt(gridItem.getAttribute("data-height"), 10);
      var itemHeight =
        data_height * cellHeight +
        (data_height - 1) * 5; /* n * cellHeight + gutters */
      gridItem.style.height = itemHeight + "px";
    }

    function orderItems() {
      dbElems = [];
      var itemElems = $grid.getItemElements();

      itemElems.forEach(function (itemElem, i) {
        itemElem.querySelector(".item-number").textContent = i + 1;
        itemElem.setAttribute("data-index", i + 1);

        var elem = {};
        elem.class = itemElem.className;
        elem.width = itemElem.getAttribute("data-width");
        elem.height = itemElem.getAttribute("data-height");
        elem.index = itemElem.getAttribute("data-index");
        elem.size = itemElem.querySelector(".item-size").textContent;

        // Add elem to dbElems
        dbElems.push(elem);
      });

      // Update form elements textarea
      document.querySelector("#jform_elements").value = JSON.stringify(dbElems);
    }

    // css is ready, show grid
    document.querySelector(".gridP").style.display = "block";
    $grid.layout();

    // Listen for layout operation
    $grid.on("layoutComplete", function () {
      orderItems();
    });

    // Edit/Remove clicked element
    document.querySelector(".gridP").addEventListener("click", function (e) {
      e.preventDefault();

      // Remove element
      if (
        e.target &&
        (e.target.classList.contains("gridP-remove") ||
          e.target.closest(".gridP-remove"))
      ) {
        if (e.target.classList.contains("gridP-remove")) {
          var _this = e.target;
        } else {
          var _this = e.target.closest(".gridP-remove");
        }

        $grid.remove(_this.closest(".gridP-item"));
        $grid.layout();
      }

      // Edit element
      if (
        e.target &&
        (e.target.classList.contains("gridP-edit") ||
          e.target.closest(".gridP-edit"))
      ) {
        if (e.target.classList.contains("gridP-edit")) {
          var _this = e.target;
        } else {
          var _this = e.target.closest(".gridP-edit");
        }

        const tabLink = document.querySelector(
          '#myTab button[aria-controls="items"]'
        );
        tabLink.click(); // Select tab 'Items'
        var editItem = _this.closest(".gridP-item");
        var item_index = editItem.getAttribute("data-index");
        var item_size = editItem.querySelector(".item-size").textContent;
        document.querySelector(".gridEditItem").style.display = "block";
        document.querySelector(".edit-index").textContent = item_index;
        document.querySelector("#edit-size").value = item_size;
        document.querySelector("#edit-height").value = parseInt(
          editItem.getAttribute("data-height"),
          10
        );
        document.querySelector("#edit-width").value = parseInt(
          editItem.getAttribute("data-width"),
          10
        );
      }
    });

    // Update columns
    document
      .querySelector("#update-columns")
      .addEventListener("click", function (e) {
        e.preventDefault();

        columns = parseInt(document.querySelector("#jform_columns").value, 10);

        document.querySelector(".gridContainer").className = "gridContainer"; // Remove class gridP-col-x
        document
          .querySelector(".gridContainer")
          .classList.add("gridP-col-" + columns);
        document
          .querySelector(".gridContainer")
          .setAttribute("data-columns", columns);

        // Calculate new columnWidth/cellHeight
        columnWidth = getColumnWidth(columns);
        cellHeight = getCellHeight(columns);

        // Iterate over all items
        $grid.getItemElements().forEach(function (gridItem) {
          // Check if item fits in grid. If not, decrement data-width until it fits
          var grid_width = document.querySelector(".gridP").offsetWidth;
          var item_width = gridItem.offsetWidth;

          while (item_width > grid_width) {
            gridItem.setAttribute(
              "data-width",
              gridItem.getAttribute("data-width") - 1
            );
            var new_data_width = parseInt(
              gridItem.getAttribute("data-width"),
              10
            );
            item_width =
              new_data_width * columnWidth +
              (new_data_width - 1) * 5; /* n * columnWidth + gutters */
          }

          fixItemDimensions(gridItem, columnWidth, cellHeight);
        });

        $grid.layout();
      });

    // Update item
    document
      .querySelector("#edit-item")
      .addEventListener("click", function (e) {
        e.preventDefault();

        var this_index = document.querySelector(".edit-index").textContent;
        var new_size = document.querySelector("#edit-size").value;
        var new_width = document.querySelector("#edit-width").value;
        var new_height = document.querySelector("#edit-height").value;
        var editItem = document.querySelector(
          '.gridP-item[data-index="' + this_index + '"]'
        );
        editItem.setAttribute("data-width", new_width);
        editItem.setAttribute("data-height", new_height);

        // Update classes
        editItem.className = "";
        editItem.classList.add("gridP-item");
        var size_class;

        switch (new_size) {
          case "S":
            size_class = "gridP-small";
            break;
          case "L":
            size_class = "gridP-landscape";
            break;
          case "P":
            size_class = "gridP-portrait";
            break;
          case "B":
            size_class = "gridP-big";
            break;
        }

        editItem.classList.add(size_class);
        editItem.querySelector(".item-size").textContent = new_size;

        fixItemDimensions(editItem, columnWidth, cellHeight);

        // Check if item fits in grid. If not, decrement data-width until it fits
        var grid_width = document.querySelector(".gridP").offsetWidth;
        var item_width = editItem.offsetWidth;

        while (item_width > grid_width) {
          editItem.setAttribute(
            "data-width",
            editItem.getAttribute("data-width") - 1
          );
          document.querySelector("#edit-width").value =
            editItem.getAttribute("data-width");
          var new_data_width = parseInt(
            editItem.getAttribute("data-width"),
            10
          );
          item_width =
            new_data_width * columnWidth +
            (new_data_width - 1) * 5; /* n * columnWidth + gutters */
        }

        fixItemDimensions(editItem, columnWidth, cellHeight);

        $grid.layout();
      });

    // Add new item
    document.querySelector("#add-item").addEventListener("click", function (e) {
      e.preventDefault();

      var appended_data_width = parseInt(
        document.querySelector("#item-width").value,
        10
      );
      var appended_data_height = parseInt(
        document.querySelector("#item-height").value,
        10
      );
      var appended_size = document.querySelector("#item-size").value;
      var appended_class;

      switch (appended_size) {
        case "S":
          appended_class = "gridP-small";
          break;
        case "L":
          appended_class = "gridP-landscape";
          break;
        case "P":
          appended_class = "gridP-portrait";
          break;
        case "B":
          appended_class = "gridP-big";
          break;
      }

      // Create new item
      var html =
        '<div class="gridP-item ' +
        appended_class +
        '" data-width="' +
        appended_data_width +
        '" data-height="' +
        appended_data_height +
        '">' +
        '<span class="item-number"></span> ' +
        '<span class="item-size">' +
        appended_size +
        "</span>" +
        '<div class="gridP-tools">' +
        '<span class="gridP-edit"><i class="fas fa-pencil-alt"></i></span> ' +
        '<span class="gridP-remove"><i class="fas fa-times"></i></span>' +
        "</div>" +
        "</div>";

      var $item = createElementFromHTML(html);
      fixItemDimensions($item, columnWidth, cellHeight);

      // Append items to grid
      document.querySelector(".gridP").appendChild($item);

      // Add and lay out newly appended item
      $grid.appended($item);

      // Make new grid item draggable
      makeDraggable($item);

      $grid.layout();
    });

    // Reset input values when changing size
    document
      .querySelector("#item-size")
      .addEventListener("change", function () {
        var appended_size = this.value;

        switch (appended_size) {
          case "S":
            document.querySelector("#item-height").value = 1;
            document.querySelector("#item-width").value = 1;
            break;
          case "L":
            document.querySelector("#item-height").value = 1;
            document.querySelector("#item-width").value = 2;
            break;
          case "P":
            document.querySelector("#item-height").value = 2;
            document.querySelector("#item-width").value = 1;
            break;
          case "B":
            document.querySelector("#item-height").value = 2;
            document.querySelector("#item-width").value = 2;
            break;
        }
      });
  }

  function fixDesignerHeight() {
    var h = window.innerHeight;
    var header_h = document.querySelector("#header").offsetHeight;
    var subhead_h = document.querySelector("#subhead-container").offsetHeight;
    var designer_h = h - header_h - subhead_h;

    document.querySelector(".gridEditor").style.height = designer_h + "px";
  }

  function doneBrowserResizing() {
    fixDesignerHeight();
  }

  window.addEventListener("resize", function () {
    clearTimeout(id);
    id = setTimeout(doneBrowserResizing, 200);
  });

  document.addEventListener("DOMContentLoaded", function () {
    fixDesignerHeight();
    initDesigner();
  });
})(window, document, Joomla);
