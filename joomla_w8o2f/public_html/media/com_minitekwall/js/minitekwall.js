((document, Joomla) => {
  "use strict";

  class Mwall {
    /*
     * Constructor
     */
    constructor(options, id, count, source, path) {
      const self = this;
      this.options = options;
      this.widgetId = parseInt(id, 10);
      this.totalCount = parseInt(count, 10);
      this.sourceId = source;
      this.path = path;

      var wallSortings =
        this.options.mas_title_sorting != 0 ||
        this.options.mas_category_sorting != 0 ||
        this.options.mas_author_sorting != 0 ||
        this.options.mas_date_sorting != 0 ||
        this.options.mas_hits_sorting != 0 ||
        this.options.mas_sorting_direction != 0
          ? true
          : false;
      this.filtersEnabled =
        this.options.mas_category_filters != 0 ||
        this.options.mas_tag_filters != 0 ||
        this.options.mas_date_filters != 0 ||
        wallSortings
          ? true
          : false;
      this.pagination = parseInt(this.options.mas_pagination, 10);
      this.hoverBox = this.options.mas_hb != 0 ? true : false;

      // Initialize wall
      this.initializeWall();

      // Filters
      if (this.filtersEnabled) this.filtersSortings();

      // Pagination
      if (this.pagination != 0) this.initializePagination();

      // Pagination - Append
      if (this.pagination === 1) this.appendPagination();

      // Pagination - Infinite
      if (this.pagination === 4) this.infinitePagination();

      // Pagination - Pages
      if (this.pagination === 3) this.pagesPagination();

      // Pagination - Arrows
      if (this.pagination === 2) this.arrowsPagination();

      // Hover box
      if (this.hoverBox) {
        this.triggerHoverBox();

        // Modal images
        if (parseInt(this.options.mas_hb_zoom, 10)) this.initModalMessages();
      }
    }

    initializeWall() {
      const self = this;
      this.pageLimit = parseInt(this.options.mas_page_limit, 10);
      this.startLimit =
        parseInt(this.options.mas_starting_limit, 10) >
        parseInt(this.options.mas_global_limit, 10)
          ? parseInt(this.options.mas_global_limit, 10)
          : parseInt(this.options.mas_starting_limit, 10);
      this.startLimit =
        this.totalCount < this.startLimit ? this.totalCount : this.startLimit;
      this.lastPage = Math.ceil(
        (this.totalCount - this.startLimit) / this.pageLimit
      );
      this.endPage = parseInt(this.lastPage, 10) + 2;
      this.filtersMode = this.options.mas_filters_mode;
      this.filtersActive = this.options.mas_pag_keep_active != 0 ? true : false;
      this.closeFilters = this.options.mas_close_filters != 0 ? true : false;
      this.scrollToTop = this.options.mas_pag_scroll_to_top != 0 ? true : false;
      this.container = document.querySelector(
        "#mnwall_container_" + this.widgetId
      );
      this.gridType = parseInt(this.options.mas_grid, 10);

      switch (this.gridType) {
        case 98:
          this.layoutType = "columns";
          break;
        case 99:
          this.layoutType = "list";
          break;
        default:
          this.layoutType = "masonry";
          break;
      }

      this.layoutMode = this.options.mas_layout_mode;
      this.dbPosition = this.options.mas_db_position_columns;
      this.equalHeight =
        this.options.mas_force_equal_height != 0 ? true : false;
      this.sortBy = this.container.getAttribute("data-order");
      this.sortDirection =
        this.container.getAttribute("data-direction") == null
          ? ""
          : this.container.getAttribute("data-direction").toLowerCase();
      this.sortAscending = this.sortDirection == "asc" ? true : false;

      if (
        this.sortBy == "RAND()" ||
        this.sortBy == "rand" ||
        this.sortBy == "random" ||
        this.sourceId == "rss"
      ) {
        this.sortBy = ["index"];
        this.sortAscending = true;
      } else this.sortBy = [this.sortBy, "id", "title"];

      this.createSpinner(
        document.querySelector("#mnwall_loader_" + this.widgetId)
      );
      document.querySelector("#mnwall_loader_" + this.widgetId).style.display =
        "block";
      this.transitionDuration = parseInt(
        this.options.mas_transition_duration,
        10
      );
      this.transitionStagger = parseInt(
        this.options.mas_transition_stagger,
        10
      );
      this.scrollThreshold = 100;
      this.iso_container = document.querySelector(
        "#mnwall_iso_container_" + this.widgetId
      );
      this.wall;
      var effects = this.options.mas_effects
        ? this.options.mas_effects
        : ["fade"];
      var hiddenOpacity = 1;
      var hiddenTransform = "scale(1)";

      if (effects.includes("fade")) hiddenOpacity = 0;

      if (effects.includes("scale")) hiddenTransform = "scale(0.001)";

      imagesLoaded(self.iso_container, function () {
        self.wall = new Isotope(self.iso_container, {
          itemSelector: ".mnwall-item",
          layoutMode: self.layoutMode,
          vertical: {
            horizontalAlignment: 0,
          },
          initLayout: false,
          stagger: self.transitionStagger,
          transitionDuration: self.transitionDuration,
          hiddenStyle: {
            opacity: hiddenOpacity,
            transform: hiddenTransform,
          },
          visibleStyle: {
            opacity: 1,
            transform: "scale(1)",
          },
          getSortData: {
            ordering: "[data-ordering] parseInt",
            fordering: "[data-fordering] parseInt",
            hits: "[data-hits] parseInt",
            title: "[data-title]",
            id: "[data-id] parseInt",
            alias: "[data-alias]",
            date: "[data-date]",
            modified: "[data-modified]",
            start: "[data-start]",
            finish: "[data-finish]",
            category: "[data-category]",
            author: "[data-author]",
            index: "[data-index] parseInt",
          },
        });

        self.container.style.display = "block";

        self.wall.arrange({
          sortBy: self.sortBy,
          sortAscending: self.sortAscending,
        });

        self.fixEqualHeights("all");
        self.container.style.opacity = 1;
        document.querySelector(
          "#mnwall_loader_" + self.widgetId
        ).style.display = "none";
      });

      // Handle resize
      var _resize;

      window.addEventListener("resize", function () {
        clearTimeout(_resize);

        _resize = setTimeout(function () {
          self.doneBrowserResizing(self);
        }, 500);
      });
    }

    arrowsPagination() {
      const self = this;

      var _activeButtonCategory;
      var _activeButtonTag;
      var _activeButtonDate;

      // Previous arrow pagination
      self.container
        .querySelector(".mnwall_arrow_prev")
        .addEventListener("click", function (e) {
          e.preventDefault();

          if (
            this.classList.contains("disabled") ||
            this.classList.contains("mnwall-loading") ||
            self.container
              .querySelector(".mnwall_arrow_next")
              .classList.contains("mnwall-loading")
          )
            return false;

          var current = this;

          // Find page
          var page = this.getAttribute("data-page");
          page = parseInt(page, 10);
          var new_page = page - 1;
          var next_page = page + 1;

          // Check if there is a pending ajax request
          if (typeof ajax_request !== "undefined") {
            ajax_request.abort();
            self.container
              .querySelector(".mnwall_arrow_next")
              .classList.remove("mnwall-loading");
            self.container.querySelector(".more-results").style.display =
              "block";
            self.container.querySelector(".mnwall_arrow_loader").style.display =
              "none";
          }

          // Show loader
          this.classList.add("mnwall-loading");
          current.querySelector(".more-results").style.display = "none";
          current.querySelector(".mnwall_arrow_loader").style.display = "block";

          // Ajax request
          var ajax_request = Joomla.request({
            url:
              self.path +
              "index.php?option=com_minitekwall&task=masonry.getContent&widget_id=" +
              self.widgetId +
              "&page=" +
              page +
              "&grid=" +
              self.layoutType,
            method: "GET",
            onSuccess: (response, xhr) => {
              if (response.length > 3) {
                // Decrease page in link id
                self.container
                  .querySelector(".mnwall_arrow_prev")
                  .setAttribute("data-page", new_page);
                self.container
                  .querySelector(".mnwall_arrow_next")
                  .setAttribute("data-page", next_page);

                // Reset filters
                if (self.filtersEnabled && !self.filtersActive)
                  self.resetFilters();

                var newItems = self.htmlToElements(response, ".mnwall-item");

                newItems.forEach(function (a) {
                  self.iso_container.appendChild(a);
                  a.style.visibility = "hidden";
                });

                var elems = self.wall.getItemElements();

                imagesLoaded(self.wall, function () {
                  self.wall.remove(elems);

                  newItems.forEach(function (a) {
                    a.style.visibility = "visible";
                  });

                  self.wall.insert(newItems);
                  self.wall.updateSortData();
                  self.wall.arrange();
                  self.fixEqualHeights(newItems);

                  if (self.hoverBox) self.triggerHoverBox();

                  if (self.filtersEnabled && self.filtersMode == "dynamic") {
                    // Store active filters
                    if (self.filtersActive) {
                      if (
                        self.container.querySelector(
                          ".button-group-category"
                        ) &&
                        self.container
                          .querySelector(".button-group-category")
                          .querySelector(".mnw_filter_active")
                      )
                        _activeButtonCategory = self.container
                          .querySelector(".button-group-category")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");

                      if (
                        self.container.querySelector(".button-group-tag") &&
                        self.container
                          .querySelector(".button-group-tag")
                          .querySelector(".mnw_filter_active")
                      )
                        _activeButtonTag = self.container
                          .querySelector(".button-group-tag")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");

                      if (
                        self.container.querySelector(".button-group-date") &&
                        self.container
                          .querySelector(".button-group-date")
                          .querySelector(".mnw_filter_active")
                      )
                        _activeButtonDate = self.container
                          .querySelector(".button-group-date")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");
                    }

                    // Update filters
                    Joomla.request({
                      url:
                        self.path +
                        "index.php?option=com_minitekwall&task=masonry.getFilters&widget_id=" +
                        self.widgetId +
                        "&page=" +
                        page +
                        "&pagination=" +
                        self.pagination,
                      method: "GET",
                      onSuccess: (response, xhr) => {
                        if (response.length > 3) {
                          // Add new filters
                          self.container.querySelector(
                            ".mnwall_iso_filters"
                          ).innerHTML = response;

                          // Restore active filters
                          if (self.filtersActive) {
                            if (
                              undefined !== _activeButtonCategory &&
                              _activeButtonCategory.length
                            ) {
                              self.container
                                .querySelector(".button-group-category")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var cat_text = self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  ).textContent;

                                if (
                                  cat_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .cat-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .cat-label span span"
                                  ).textContent = cat_text;
                              }
                            }

                            if (
                              undefined !== _activeButtonTag &&
                              _activeButtonTag.length
                            ) {
                              self.container
                                .querySelector(".button-group-tag")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var tag_text = self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  ).textContent;

                                if (
                                  tag_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .tag-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .tag-label span span"
                                  ).textContent = tag_text;
                              }
                            }

                            if (
                              undefined !== _activeButtonDate &&
                              _activeButtonDate.length
                            ) {
                              self.container
                                .querySelector(".button-group-date")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var date_text = self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  ).textContent;

                                if (
                                  date_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .date-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .date-label span span"
                                  ).textContent = date_text;
                              }
                            }
                          }
                        }

                        self.activeFilters();
                        self.dropdownFilters();
                      },
                      onError: (xhr) => {
                        console.log(xhr);
                      },
                    });
                  }

                  // Hide loader
                  self.container
                    .querySelector(".mnwall_arrow_prev")
                    .classList.remove("mnwall-loading");
                  current.querySelector(".more-results").style.display =
                    "block";
                  current.querySelector(".mnwall_arrow_loader").style.display =
                    "none";

                  // Enable next button
                  self.container
                    .querySelector(".mnwall_arrow_next")
                    .classList.remove("disabled");

                  // Disable previous button on 1st page
                  if (new_page <= 0) {
                    if (new_page < 0) {
                      self.container
                        .querySelector(".mnwall_arrow_prev")
                        .setAttribute("data-page", 0);
                      self.container
                        .querySelector(".mnwall_arrow_next")
                        .setAttribute("data-page", 2);
                    }

                    // Disable previous button
                    self.container
                      .querySelector(".mnwall_arrow_prev")
                      .classList.add("disabled");
                  }

                  // Scroll to top
                  if (self.scrollToTop) {
                    setTimeout(function () {
                      self.container[0].scrollIntoView(true);
                    }, self.transitionDuration);
                  }
                });
              } else {
                // Disable previous button / Hide loader
                self.container
                  .querySelector(".mnwall_arrow_prev")
                  .classList.add("disabled");
                self.container.querySelector(
                  ".mnwall_arrow_loader"
                ).style.display = "none";
              }
            },
            onError: (xhr) => {
              console.log(xhr);
            },
          });
        });

      // Next arrow pagination
      self.container
        .querySelector(".mnwall_arrow_next")
        .addEventListener("click", function (e) {
          e.preventDefault();

          if (
            this.classList.contains("disabled") ||
            this.classList.contains("mnwall-loading") ||
            self.container
              .querySelector(".mnwall_arrow_prev")
              .classList.contains("mnwall-loading")
          )
            return false;

          var current = this;

          // Find page
          var page = this.getAttribute("data-page");
          page = parseInt(page, 10);
          var next_page = page + 1;
          var prev_page = page - 1;
          var end_page_next = next_page - 1;
          var end_page_prev = next_page - 3;

          // Check if there is a pending ajax request
          if (typeof ajax_request !== "undefined") {
            ajax_request.abort();
            self.container
              .querySelector(".mnwall_arrow_prev")
              .classList.remove("mnwall-loading");
            self.container.querySelector(".more-results").style.display =
              "block";
            self.container.querySelector(".mnwall_arrow_loader").style.display =
              "none";
          }

          // Show loader
          this.classList.add("mnwall-loading");
          current.querySelector(".more-results").style.display = "none";
          current.querySelector(".mnwall_arrow_loader").style.display = "block";

          // Ajax request
          var ajax_request = Joomla.request({
            url:
              self.path +
              "index.php?option=com_minitekwall&task=masonry.getContent&widget_id=" +
              self.widgetId +
              "&page=" +
              page +
              "&grid=" +
              self.layoutType,
            method: "GET",
            onSuccess: (response, xhr) => {
              if (response.length > 3) {
                // Increment page in link id
                self.container
                  .querySelector(".mnwall_arrow_next")
                  .setAttribute("data-page", next_page);
                self.container
                  .querySelector(".mnwall_arrow_prev")
                  .setAttribute("data-page", prev_page);

                // Reset filters
                if (self.filtersEnabled && !self.filtersActive)
                  self.resetFilters();

                var newItems = self.htmlToElements(response, ".mnwall-item");

                newItems.forEach(function (a) {
                  self.iso_container.appendChild(a);
                  a.style.visibility = "hidden";
                });

                var elems = self.wall.getItemElements();

                imagesLoaded(self.wall, function () {
                  self.wall.remove(elems);

                  newItems.forEach(function (a) {
                    a.style.visibility = "visible";
                  });

                  self.wall.insert(newItems);
                  self.wall.updateSortData();
                  self.wall.arrange();
                  self.fixEqualHeights(newItems);

                  if (self.hoverBox) self.triggerHoverBox();

                  if (self.filtersEnabled && self.filtersMode == "dynamic") {
                    // Store active filters
                    if (self.filtersActive) {
                      if (
                        self.container.querySelector(
                          ".button-group-category"
                        ) &&
                        self.container
                          .querySelector(".button-group-category")
                          .querySelector(".mnw_filter_active")
                      )
                        _activeButtonCategory = self.container
                          .querySelector(".button-group-category")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");

                      if (
                        self.container.querySelector(".button-group-tag") &&
                        self.container
                          .querySelector(".button-group-tag")
                          .querySelector(".mnw_filter_active")
                      )
                        _activeButtonTag = self.container
                          .querySelector(".button-group-tag")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");

                      if (
                        self.container.querySelector(".button-group-date") &&
                        self.container
                          .querySelector(".button-group-date")
                          .querySelector(".mnw_filter_active")
                      )
                        _activeButtonDate = self.container
                          .querySelector(".button-group-date")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");
                    }

                    // Update filters
                    Joomla.request({
                      url:
                        self.path +
                        "index.php?option=com_minitekwall&task=masonry.getFilters&widget_id=" +
                        self.widgetId +
                        "&page=" +
                        page +
                        "&pagination=" +
                        self.pagination,
                      method: "GET",
                      onSuccess: (response, xhr) => {
                        if (response.length > 3) {
                          // Add new filters
                          self.container.querySelector(
                            ".mnwall_iso_filters"
                          ).innerHTML = response;

                          // Restore active filters
                          if (self.filtersActive) {
                            if (
                              undefined !== _activeButtonCategory &&
                              _activeButtonCategory.length
                            ) {
                              self.container
                                .querySelector(".button-group-category")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var cat_text = self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  ).textContent;

                                if (
                                  cat_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .cat-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .cat-label span span"
                                  ).textContent = cat_text;
                              }
                            }

                            if (
                              undefined !== _activeButtonTag &&
                              _activeButtonTag.length
                            ) {
                              self.container
                                .querySelector(".button-group-tag")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var tag_text = self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  ).textContent;

                                if (
                                  tag_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .tag-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .tag-label span span"
                                  ).textContent = tag_text;
                              }
                            }

                            if (
                              undefined !== _activeButtonDate &&
                              _activeButtonDate.length
                            ) {
                              self.container
                                .querySelector(".button-group-date")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var date_text = self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  ).textContent;

                                if (
                                  date_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .date-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .date-label span span"
                                  ).textContent = date_text;
                              }
                            }
                          }
                        }

                        self.activeFilters();
                        self.dropdownFilters();
                      },
                      onError: (xhr) => {
                        console.log(xhr);
                      },
                    });
                  }

                  // Hide loader
                  self.container
                    .querySelector(".mnwall_arrow_next")
                    .classList.remove("mnwall-loading");
                  current.querySelector(".more-results").style.display =
                    "block";
                  current.querySelector(".mnwall_arrow_loader").style.display =
                    "none";

                  // Enable previous button
                  self.container
                    .querySelector(".mnwall_arrow_prev")
                    .classList.remove("disabled");

                  // Last page
                  if (
                    self.container
                      .querySelector(".mnwall_arrow_next")
                      .getAttribute("data-page") == self.endPage
                  )
                    self.container
                      .querySelector(".mnwall_arrow_next")
                      .classList.add("disabled");

                  // Scroll to top
                  if (self.scrollToTop) {
                    setTimeout(function () {
                      self.container[0].scrollIntoView(true);
                    }, self.transitionDuration);
                  }
                });
              } else {
                // Disable next button / Hide loader
                self.container
                  .querySelector(".mnwall_arrow_next")
                  .classList.add("disabled");
                self.container.querySelector(
                  ".mnwall_arrow_loader"
                ).style.display = "none";
                self.container
                  .querySelector(".mnwall_arrow_prev")
                  .setAttribute("data-page", end_page_prev);
                self.container
                  .querySelector(".mnwall_arrow_next")
                  .setAttribute("data-page", end_page_next);
              }
            },
            onError: (xhr) => {
              console.log(xhr);
            },
          });
        });
    }

    pagesPagination() {
      const self = this;

      var _activeButtonCategory;
      var _activeButtonTag;
      var _activeButtonDate;

      // Pages pagination
      self.container.querySelectorAll(".mnwall_page").forEach(function (a) {
        a.addEventListener("click", function (e) {
          e.preventDefault();

          if (
            self.container
              .querySelector(".mnwall_pages")
              .classList.contains("mnwall-loading")
          )
            return false;

          var current = this;

          self.container.querySelectorAll(".mnwall_page").forEach(function (a) {
            a.classList.remove("mnw_active");
          });

          // Find page
          var page = this.getAttribute("data-page");
          page = parseInt(page, 10);

          // Check if there is a pending ajax request
          if (typeof ajax_request !== "undefined") {
            ajax_request.abort();
            self.container.querySelector(".page-number").style.display =
              "inline-block";
            self.container.querySelector(".mnwall_page_loader").style.display =
              "none";
          }

          // Show loader
          self.container
            .querySelector(".mnwall_pages")
            .classList.add("mnwall-loading");
          current.querySelector(".page-number").style.display = "none";
          current.querySelector(".mnwall_page_loader").style.display = "block";

          // Ajax request
          var ajax_request = Joomla.request({
            url:
              self.path +
              "index.php?option=com_minitekwall&task=masonry.getContent&widget_id=" +
              self.widgetId +
              "&page=" +
              page +
              "&grid=" +
              self.layoutType,
            method: "GET",
            onSuccess: (response, xhr) => {
              if (response.length > 3) {
                // Reset filters
                if (self.filtersEnabled && !self.filtersActive)
                  self.resetFilters();

                var newItems = self.htmlToElements(response, ".mnwall-item");

                newItems.forEach(function (a) {
                  self.iso_container.appendChild(a);
                  a.style.visibility = "hidden";
                });

                var elems = self.wall.getItemElements();

                imagesLoaded(self.wall, function () {
                  self.wall.remove(elems);

                  newItems.forEach(function (a) {
                    a.style.visibility = "visible";
                  });

                  self.wall.insert(newItems);
                  self.wall.updateSortData();
                  self.wall.arrange();
                  self.fixEqualHeights(newItems);

                  if (self.hoverBox) self.triggerHoverBox();

                  if (self.filtersEnabled && self.filtersMode == "dynamic") {
                    // Store active filters
                    if (self.filtersActive) {
                      if (
                        self.container.querySelector(
                          ".button-group-category"
                        ) &&
                        self.container
                          .querySelector(".button-group-category")
                          .querySelector(".mnw_filter_active")
                      )
                        _activeButtonCategory = self.container
                          .querySelector(".button-group-category")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");

                      if (
                        self.container.querySelector(".button-group-tag") &&
                        self.container
                          .querySelector(".button-group-tag")
                          .querySelector(".mnw_filter_active")
                      )
                        _activeButtonTag = self.container
                          .querySelector(".button-group-tag")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");

                      if (
                        self.container.querySelector(".button-group-date") &&
                        self.container
                          .querySelector(".button-group-date")
                          .querySelector(".mnw_filter_active")
                      )
                        _activeButtonDate = self.container
                          .querySelector(".button-group-date")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");
                    }

                    // Update filters
                    Joomla.request({
                      url:
                        self.path +
                        "index.php?option=com_minitekwall&task=masonry.getFilters&widget_id=" +
                        self.widgetId +
                        "&page=" +
                        page +
                        "&pagination=" +
                        self.pagination,
                      method: "GET",
                      onSuccess: (response, xhr) => {
                        if (response.length > 3) {
                          // Add new filters
                          self.container.querySelector(
                            ".mnwall_iso_filters"
                          ).innerHTML = response;

                          // Restore active filters
                          if (self.filtersActive) {
                            if (
                              undefined !== _activeButtonCategory &&
                              _activeButtonCategory.length
                            ) {
                              self.container
                                .querySelector(".button-group-category")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var cat_text = self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  ).textContent;

                                if (
                                  cat_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .cat-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .cat-label span span"
                                  ).textContent = cat_text;
                              }
                            }

                            if (
                              undefined !== _activeButtonTag &&
                              _activeButtonTag.length
                            ) {
                              self.container
                                .querySelector(".button-group-tag")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var tag_text = self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  ).textContent;

                                if (
                                  tag_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .tag-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .tag-label span span"
                                  ).textContent = tag_text;
                              }
                            }

                            if (
                              undefined !== _activeButtonDate &&
                              _activeButtonDate.length
                            ) {
                              self.container
                                .querySelector(".button-group-date")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var date_text = self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  ).textContent;

                                if (
                                  date_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .date-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .date-label span span"
                                  ).textContent = date_text;
                              }
                            }
                          }
                        }

                        self.activeFilters();
                        self.dropdownFilters();
                      },
                      onError: (xhr) => {
                        console.log(xhr);
                      },
                    });
                  }

                  // Hide loader
                  self.container
                    .querySelector(".mnwall_pages")
                    .classList.remove("mnwall-loading");
                  current.querySelector(".page-number").style.display =
                    "inline-block";
                  current.querySelector(".mnwall_page_loader").style.display =
                    "none";

                  // Remove active class
                  if (!current.classList.contains("mnw_active"))
                    current.classList.add("mnw_active");

                  // Scroll to top
                  if (self.scrollToTop) {
                    setTimeout(function () {
                      self.container[0].scrollIntoView(true);
                    }, self.transitionDuration);
                  }
                });
              } else {
                // Hide loader
                self.container
                  .querySelector(".mnwall_pages")
                  .classList.remove("mnwall-loading");
                self.container.querySelector(
                  ".mnwall_page_loader"
                ).style.display = "none";
              }
            },
            onError: (xhr) => {
              console.log(xhr);
            },
          });
        });
      });
    }

    infinitePagination() {
      const self = this;

      var button = self.container.querySelector(".mnw-all");
      var page;
      var new_page;
      var _page;

      button.addEventListener("click", function (e) {
        e.preventDefault();
      });

      let infScroll = new InfiniteScroll(self.iso_container, {
        path: function () {
          page = button.getAttribute("data-page");
          page = parseInt(page, 10);

          if (page < self.endPage)
            return (
              self.path +
              "index.php?option=com_minitekwall&task=masonry.getContent&widget_id=" +
              self.widgetId +
              "&page=" +
              page +
              "&grid=" +
              self.layoutType
            );
        },
        append: ".mnwall-item", // required when prefill is enabled
        checkLastPage: true, // checks for returned path above
        prefill: true,
        domParseResponse: true,
        scrollThreshold: self.scrollThreshold,
        loadOnScroll: true,
        history: false,
        onInit: function () {
          this.on("append", onAppend);
        },
      });

      // Triggered after infinite scroll has appended items
      function onAppend(body, path, items, response) {
        // Pause prefill
        infScroll.stopPrefill();

        // Pause loadOnScroll
        infScroll.options.loadOnScroll = false;

        // Show loader
        self.container.querySelector(".mnw-infinite").style.display = "block";
        button.classList.add("mnwall-loading");
        self.container.querySelector(
          ".more-results span.more-results"
        ).style.display = "none";
        self.container.querySelector(".mnwall_append_loader").style.display =
          "block";

        if (items) {
          _page = page;
          var newItems = items;

          newItems.forEach(function (a) {
            self.iso_container.appendChild(a);
            a.style.visibility = "hidden";
          });

          // Increment page in button
          new_page = page + 1;
          button.setAttribute("data-page", new_page);

          imagesLoaded(self.wall, function () {
            // Reset filters
            if (self.filtersEnabled && !self.filtersActive) self.resetFilters();

            // Append items
            newItems.forEach(function (a) {
              a.style.visibility = "visible";
            });

            self.wall.appended(newItems);
            self.wall.updateSortData();
            self.wall.arrange();
            self.fixEqualHeights("all");

            if (self.hoverBox) self.triggerHoverBox();

            if (self.filtersEnabled && self.filtersMode == "dynamic") {
              // Store active filters
              if (self.filtersActive) {
                if (
                  self.container.querySelector(".button-group-category") &&
                  self.container
                    .querySelector(".button-group-category")
                    .querySelector(".mnw_filter_active")
                )
                  var _activeButtonCategory = self.container
                    .querySelector(".button-group-category")
                    .querySelector(".mnw_filter_active")
                    .getAttribute("data-filter");

                if (
                  self.container.querySelector(".button-group-tag") &&
                  self.container
                    .querySelector(".button-group-tag")
                    .querySelector(".mnw_filter_active")
                )
                  var _activeButtonTag = self.container
                    .querySelector(".button-group-tag")
                    .querySelector(".mnw_filter_active")
                    .getAttribute("data-filter");

                if (
                  self.container.querySelector(".button-group-date") &&
                  self.container
                    .querySelector(".button-group-date")
                    .querySelector(".mnw_filter_active")
                )
                  var _activeButtonDate = self.container
                    .querySelector(".button-group-date")
                    .querySelector(".mnw_filter_active")
                    .getAttribute("data-filter");
              }

              // Update filters
              Joomla.request({
                url:
                  self.path +
                  "index.php?option=com_minitekwall&task=masonry.getFilters&widget_id=" +
                  self.widgetId +
                  "&page=" +
                  _page +
                  "&pagination=" +
                  self.pagination,
                method: "GET",
                onSuccess: (response, xhr) => {
                  if (response.length > 3) {
                    // Add new filters
                    self.container.querySelector(
                      ".mnwall_iso_filters"
                    ).innerHTML = response;

                    // Restore active filters
                    if (self.filtersActive) {
                      if (
                        undefined !== _activeButtonCategory &&
                        _activeButtonCategory.length
                      ) {
                        self.container
                          .querySelector(".button-group-category")
                          .querySelector(".mnw_filter_active")
                          .classList.remove("mnw_filter_active");

                        if (
                          self.container
                            .querySelector(".button-group-category")
                            .querySelector(
                              '[data-filter="' + _activeButtonCategory + '"]'
                            )
                        ) {
                          self.container
                            .querySelector(".button-group-category")
                            .querySelector(
                              '[data-filter="' + _activeButtonCategory + '"]'
                            )
                            .classList.add("mnw_filter_active");

                          var cat_text = self.container
                            .querySelector(".button-group-category")
                            .querySelector(
                              '[data-filter="' + _activeButtonCategory + '"]'
                            ).textContent;

                          if (
                            cat_text &&
                            self.container.querySelector(
                              ".mnwall_iso_dropdown .cat-label span span"
                            )
                          )
                            self.container.querySelector(
                              ".mnwall_iso_dropdown .cat-label span span"
                            ).textContent = cat_text;
                        }
                      }

                      if (
                        undefined !== _activeButtonTag &&
                        _activeButtonTag.length
                      ) {
                        self.container
                          .querySelector(".button-group-tag")
                          .querySelector(".mnw_filter_active")
                          .classList.remove("mnw_filter_active");

                        if (
                          self.container
                            .querySelector(".button-group-tag")
                            .querySelector(
                              '[data-filter="' + _activeButtonTag + '"]'
                            )
                        ) {
                          self.container
                            .querySelector(".button-group-tag")
                            .querySelector(
                              '[data-filter="' + _activeButtonTag + '"]'
                            )
                            .classList.add("mnw_filter_active");

                          var tag_text = self.container
                            .querySelector(".button-group-tag")
                            .querySelector(
                              '[data-filter="' + _activeButtonTag + '"]'
                            ).textContent;

                          if (
                            tag_text &&
                            self.container.querySelector(
                              ".mnwall_iso_dropdown .tag-label span span"
                            )
                          )
                            self.container.querySelector(
                              ".mnwall_iso_dropdown .tag-label span span"
                            ).textContent = tag_text;
                        }
                      }

                      if (
                        undefined !== _activeButtonDate &&
                        _activeButtonDate.length
                      ) {
                        self.container
                          .querySelector(".button-group-date")
                          .querySelector(".mnw_filter_active")
                          .classList.remove("mnw_filter_active");

                        if (
                          self.container
                            .querySelector(".button-group-date")
                            .querySelector(
                              '[data-filter="' + _activeButtonDate + '"]'
                            )
                        ) {
                          self.container
                            .querySelector(".button-group-date")
                            .querySelector(
                              '[data-filter="' + _activeButtonDate + '"]'
                            )
                            .classList.add("mnw_filter_active");

                          var date_text = self.container
                            .querySelector(".button-group-date")
                            .querySelector(
                              '[data-filter="' + _activeButtonDate + '"]'
                            ).textContent;

                          if (
                            date_text &&
                            self.container.querySelector(
                              ".mnwall_iso_dropdown .date-label span span"
                            )
                          )
                            self.container.querySelector(
                              ".mnwall_iso_dropdown .date-label span span"
                            ).textContent = date_text;
                        }
                      }
                    }
                  }

                  self.activeFilters();
                  self.dropdownFilters();
                },
                onError: (xhr) => {
                  console.log(xhr);
                },
              });
            }

            // Hide loader
            button.classList.remove("mnwall-loading");
            self.container.querySelector(".mnw-infinite").style.display =
              "none";
            self.container.querySelector(
              ".more-results span.more-results"
            ).style.display = "block";
            self.container.querySelector(
              ".mnwall_append_loader"
            ).style.display = "none";

            // Last page
            if (button.getAttribute("data-page") == self.endPage) {
              button.classList.add("disabled");
              self.container.querySelector(".mnw-infinite").style.display =
                "block";
              self.container.querySelector(
                ".more-results span.more-results"
              ).style.display = "none";
              self.container.querySelector(
                ".more-results span.no-results"
              ).style.display = "block";
            }

            // Resume loadOnScroll
            if (page < self.endPage - 1) infScroll.options.loadOnScroll = true;

            // Resume prefill if needed
            let distance = infScroll.getPrefillDistance();

            if (distance > 0 && page < self.endPage - 1)
              infScroll.loadNextPage();
          });
        } else {
          button.classList.add("disabled");
          self.container.querySelector(
            ".more-results span.more-results"
          ).style.display = "none";
          self.container.querySelector(
            ".more-results span.no-results"
          ).style.display = "block";
        }
      }
    }

    appendPagination() {
      const self = this;

      self.container
        .querySelector(".more-results.mnw-all")
        .addEventListener("click", function (e) {
          e.preventDefault();

          if (
            this.classList.contains("disabled") ||
            this.classList.contains("mnwall-loading")
          )
            return false;

          // Find page
          var page = this.getAttribute("data-page");
          page = parseInt(page, 10);
          var new_page = page + 1;

          // Increment page in data-page
          this.setAttribute("data-page", new_page);

          // Check if there is a pending ajax request
          if (typeof ajax_request !== "undefined") {
            ajax_request.abort();
            self.container
              .querySelector(".more-results")
              .classList.remove("mnwall-loading");
            self.container.querySelector(
              ".more-results span.more-results"
            ).style.display = "block";
            self.container.querySelector(
              ".mnwall_append_loader"
            ).style.display = "none";
          }

          // Show loader
          self.container
            .querySelector(".more-results")
            .classList.add("mnwall-loading");
          self.container.querySelector(
            ".more-results span.more-results"
          ).style.display = "none";
          self.container.querySelector(".mnwall_append_loader").style.display =
            "block";

          // Ajax request
          var ajax_request = Joomla.request({
            url:
              self.path +
              "index.php?option=com_minitekwall&task=masonry.getContent&widget_id=" +
              self.widgetId +
              "&page=" +
              page +
              "&grid=" +
              self.layoutType,
            method: "GET",
            onSuccess: (response, xhr) => {
              if (response.length > 3) {
                var newItems = self.htmlToElements(response, ".mnwall-item");

                newItems.forEach(function (a) {
                  self.iso_container.appendChild(a);
                  a.style.visibility = "hidden";
                });

                imagesLoaded(self.wall, function () {
                  // Reset filters
                  if (self.filtersEnabled && !self.filtersActive)
                    self.resetFilters();

                  // Append items
                  newItems.forEach(function (a) {
                    a.style.visibility = "visible";
                  });

                  self.wall.appended(newItems);
                  self.wall.updateSortData();
                  self.wall.arrange();
                  self.fixEqualHeights("all");

                  if (self.hoverBox) self.triggerHoverBox();

                  if (self.filtersEnabled && self.filtersMode == "dynamic") {
                    // Store active filters
                    if (self.filtersActive) {
                      if (
                        self.container.querySelector(
                          ".button-group-category"
                        ) &&
                        self.container
                          .querySelector(".button-group-category")
                          .querySelector(".mnw_filter_active")
                      )
                        var _activeButtonCategory = self.container
                          .querySelector(".button-group-category")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");

                      if (
                        self.container.querySelector(".button-group-tag") &&
                        self.container
                          .querySelector(".button-group-tag")
                          .querySelector(".mnw_filter_active")
                      )
                        var _activeButtonTag = self.container
                          .querySelector(".button-group-tag")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");

                      if (
                        self.container.querySelector(".button-group-date") &&
                        self.container
                          .querySelector(".button-group-date")
                          .querySelector(".mnw_filter_active")
                      )
                        var _activeButtonDate = self.container
                          .querySelector(".button-group-date")
                          .querySelector(".mnw_filter_active")
                          .getAttribute("data-filter");
                    }

                    // Update filters
                    Joomla.request({
                      url:
                        self.path +
                        "index.php?option=com_minitekwall&task=masonry.getFilters&widget_id=" +
                        self.widgetId +
                        "&page=" +
                        page +
                        "&pagination=" +
                        self.pagination,
                      method: "GET",
                      onSuccess: (response, xhr) => {
                        if (response.length > 3) {
                          // Add new filters
                          self.container.querySelector(
                            ".mnwall_iso_filters"
                          ).innerHTML = response;

                          // Restore active filters
                          if (self.filtersActive) {
                            if (
                              undefined !== _activeButtonCategory &&
                              _activeButtonCategory.length
                            ) {
                              self.container
                                .querySelector(".button-group-category")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var cat_text = self.container
                                  .querySelector(".button-group-category")
                                  .querySelector(
                                    '[data-filter="' +
                                      _activeButtonCategory +
                                      '"]'
                                  ).textContent;

                                if (
                                  cat_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .cat-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .cat-label span span"
                                  ).textContent = cat_text;
                              }
                            }

                            if (
                              undefined !== _activeButtonTag &&
                              _activeButtonTag.length
                            ) {
                              self.container
                                .querySelector(".button-group-tag")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var tag_text = self.container
                                  .querySelector(".button-group-tag")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonTag + '"]'
                                  ).textContent;

                                if (
                                  tag_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .tag-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .tag-label span span"
                                  ).textContent = tag_text;
                              }
                            }

                            if (
                              undefined !== _activeButtonDate &&
                              _activeButtonDate.length
                            ) {
                              self.container
                                .querySelector(".button-group-date")
                                .querySelector(".mnw_filter_active")
                                .classList.remove("mnw_filter_active");

                              if (
                                self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  )
                              ) {
                                self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  )
                                  .classList.add("mnw_filter_active");

                                var date_text = self.container
                                  .querySelector(".button-group-date")
                                  .querySelector(
                                    '[data-filter="' + _activeButtonDate + '"]'
                                  ).textContent;

                                if (
                                  date_text &&
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .date-label span span"
                                  )
                                )
                                  self.container.querySelector(
                                    ".mnwall_iso_dropdown .date-label span span"
                                  ).textContent = date_text;
                              }
                            }
                          }
                        }

                        self.activeFilters();
                        self.dropdownFilters();
                      },
                      onError: (xhr) => {
                        console.log(xhr);
                      },
                    });
                  }

                  // Hide loader
                  self.container
                    .querySelector(".more-results")
                    .classList.remove("mnwall-loading");
                  self.container.querySelector(
                    ".more-results span.more-results"
                  ).style.display = "block";
                  self.container.querySelector(
                    ".mnwall_append_loader"
                  ).style.display = "none";
                  self.container.querySelector(".more-results").blur();

                  // Deduct remaining items number in button
                  var _remaining =
                    self.container.querySelector(
                      ".mnw-total-items"
                    ).textContent;
                  var remaining = parseInt(_remaining, 10) - self.pageLimit;
                  self.container.querySelector(".mnw-total-items").innerHTML =
                    remaining;

                  // Last page
                  if (
                    self.container
                      .querySelector(".more-results")
                      .getAttribute("data-page") == self.endPage
                  ) {
                    self.container
                      .querySelector(".more-results")
                      .classList.add("disabled");
                    self.container.querySelector(".mnw-total-items").innerHTML =
                      "0";
                    self.container.querySelector(
                      ".more-results span.more-results"
                    ).style.display = "none";
                    self.container.querySelector(
                      ".more-results span.no-results"
                    ).style.display = "block";
                  }
                });
              } else {
                self.container
                  .querySelector(".more-results")
                  .classList.add("disabled");
                self.container.querySelector(
                  ".more-results span.more-results"
                ).style.display = "none";
                self.container.querySelector(
                  ".more-results span.no-results"
                ).style.display = "block";
              }
            },
            onError: (xhr) => {
              console.log(xhr);
            },
          });
        });
    }

    initializePagination() {
      const self = this;

      // Last page
      if (
        self.container.querySelector(".more-results.mnw-all") &&
        self.container
          .querySelector(".more-results.mnw-all")
          .getAttribute("data-page") == self.endPage
      ) {
        self.container
          .querySelector(".more-results.mnw-all")
          .classList.add("disabled");
        self.container.querySelector(
          ".more-results.mnw-all span.more-results"
        ).style.display = "none";
        self.container.querySelector(
          ".more-results.mnw-all span.no-results"
        ).style.display = "block";
      }

      self.container.querySelectorAll(".mas_loader").forEach(function (a) {
        self.createSpinner(a);
      });
    }

    filtersSortings() {
      const self = this;
      var filters = {};

      // Bind filter button click
      if (self.container.querySelector(".mnwall_iso_filters_cont")) {
        self.container
          .querySelector(".mnwall_iso_filters_cont")
          .addEventListener("click", function (e) {
            e.preventDefault();

            if (e.target && e.target.classList.contains("mnwall-filter")) {
              var _this = e.target;

              // Show filter name in dropdown
              if (_this.closest(".mnwall_iso_dropdown")) {
                var data_filter_attr = _this.getAttribute("data-filter");

                if (
                  typeof data_filter_attr !== typeof undefined &&
                  data_filter_attr !== false
                ) {
                  var filter_text;

                  if (data_filter_attr.length) filter_text = _this.textContent;
                  else
                    filter_text = _this
                      .closest(".mnwall_iso_dropdown")
                      .querySelector(".dropdown-label span")
                      .getAttribute("data-label");

                  _this
                    .closest(".mnwall_iso_dropdown")
                    .querySelector(".dropdown-label span span").textContent =
                    filter_text;
                }
              }

              // Show reset button in pagination
              if (self.container.querySelector(".mnwall-reset-btn"))
                self.container.querySelector(
                  ".mnwall-reset-btn"
                ).style.display = "inline-block";

              // Get group key
              var filterGroup = _this
                .closest(".button-group")
                .getAttribute("data-filter-group");

              // Set filter for group
              filters[filterGroup] = _this.getAttribute("data-filter");

              // Combine filters
              var filterValue = "";

              for (var prop in filters) {
                filterValue += filters[prop];
              }

              // Set filter for Isotope
              self.wall.arrange({
                filter: filterValue,
              });

              // Hide reset button in pagination
              if (
                filterValue == "" &&
                self.container.querySelector(".mnwall-reset-btn")
              )
                self.container.querySelector(
                  ".mnwall-reset-btn"
                ).style.display = "none";
            }
          });
      }

      // Change active class on filter buttons
      this.activeFilters = function activeFilters() {
        self.container
          .querySelectorAll(".button-group")
          .forEach(function (buttonGroup) {
            buttonGroup
              .querySelectorAll(".mnwall-filter")
              .forEach(function (a) {
                a.addEventListener("click", function (e) {
                  e.preventDefault();

                  if (buttonGroup.querySelector(".mnw_filter_active"))
                    buttonGroup
                      .querySelector(".mnw_filter_active")
                      .classList.remove("mnw_filter_active");

                  a.classList.add("mnw_filter_active");
                });
              });
          });
      };

      this.activeFilters();

      // Dropdown filter list
      this.dropdownFilters = function dropdownFilters() {
        self.container
          .querySelector(".mnwall_iso_filters_cont")
          .querySelectorAll(".mnwall_iso_dropdown")
          .forEach(function (dropdownGroup) {
            dropdownGroup
              .querySelector(".dropdown-label")
              .addEventListener("click", function (e) {
                e.preventDefault();
                var filter_open;

                if (
                  this.closest(".mnwall_iso_dropdown").classList.contains(
                    "expanded"
                  )
                )
                  filter_open = true;
                else filter_open = false;

                self.container
                  .querySelectorAll(".mnwall_iso_dropdown")
                  .forEach(function (a) {
                    a.classList.remove("expanded");
                  });

                if (!filter_open)
                  this.closest(".mnwall_iso_dropdown").classList.add(
                    "expanded"
                  );
              });
          });

        // Close dropdowns
        document.addEventListener("mouseup", function (e) {
          var _target = e.target;
          var dropdowncontainers = self.container.querySelectorAll(
            ".mnwall_iso_dropdown"
          );

          if (!dropdowncontainers) return;

          if (!this.closeFilters) {
            // Close when click outside
            if (!_target.closest(".mnwall_iso_dropdown")) {
              dropdowncontainers.forEach(function (a) {
                a.classList.remove("expanded");
              });
            }
          } else {
            // Close when click inside
            if (
              _target.closest(".mnwall_iso_dropdown") &&
              !_target.closest(".dropdown-label")
            ) {
              dropdowncontainers.forEach(function (a) {
                a.classList.remove("expanded");
              });
            }
          }
        });
      };

      this.dropdownFilters();

      // Bind sort button click
      if (self.container.querySelector(".sorting-group-filters")) {
        self.container
          .querySelector(".sorting-group-filters")
          .querySelectorAll(".mnwall-filter")
          .forEach(function (a) {
            a.addEventListener("click", function (e) {
              e.preventDefault();

              // Show sorting name in dropdown
              if (this.closest(".mnwall_iso_dropdown")) {
                var sorting_text = this.textContent;
                this.closest(".mnwall_iso_dropdown").querySelector(
                  ".dropdown-label span span"
                ).textContent = sorting_text;
              }

              var sortValue = this.getAttribute("data-sort-value");

              // Add second ordering: id
              sortValue = [sortValue, "id"];

              // set filter for Isotope
              self.wall.arrange({
                sortBy: sortValue,
              });

              // Change active class on sorting filters
              self.container
                .querySelector(".sorting-group-filters")
                .querySelectorAll(".mnwall-filter")
                .forEach(function (a) {
                  a.classList.remove("mnw_filter_active");
                });
              this.classList.add("mnw_filter_active");
            });
          });
      }

      // Bind sorting direction button click
      if (self.container.querySelector(".sorting-group-direction")) {
        self.container
          .querySelector(".sorting-group-direction")
          .querySelectorAll(".mnwall-filter")
          .forEach(function (a) {
            a.addEventListener("click", function (e) {
              e.preventDefault();

              // Show sorting name in dropdown
              if (this.closest(".mnwall_iso_dropdown")) {
                var sorting_text = this.textContent;
                this.closest(".mnwall_iso_dropdown").querySelector(
                  ".dropdown-label span span"
                ).textContent = sorting_text;
              }

              var sortDirection = this.getAttribute("data-sort-value");
              var sort_Direction;

              if (sortDirection == "asc") sort_Direction = true;
              else sort_Direction = false;

              // set direction
              self.wall.arrange({
                sortAscending: sort_Direction,
              });

              // Change active class on sorting direction
              self.container
                .querySelector(".sorting-group-direction")
                .querySelectorAll(".mnwall-filter")
                .forEach(function (a) {
                  a.classList.remove("mnw_filter_active");
                });
              this.classList.add("mnw_filter_active");
            });
          });
      }

      // Dropdown sorting list
      var dropdownSortings = function dropdownSortings() {
        if (self.container.querySelector(".mnwall_iso_sortings")) {
          self.container
            .querySelector(".mnwall_iso_sortings")
            .querySelectorAll(".mnwall_iso_dropdown")
            .forEach(function (dropdownSorting) {
              dropdownSorting
                .querySelector(".dropdown-label")
                .addEventListener("click", function (e) {
                  e.preventDefault();
                  var sorting_open;

                  if (
                    this.closest(".mnwall_iso_dropdown").classList.contains(
                      "expanded"
                    )
                  )
                    sorting_open = true;
                  else sorting_open = false;

                  self.container
                    .querySelectorAll(".mnwall_iso_dropdown")
                    .forEach(function (a) {
                      a.classList.remove("expanded");
                    });

                  if (!sorting_open)
                    this.closest(".mnwall_iso_dropdown").classList.add(
                      "expanded"
                    );
                });
            });
        }
      };

      dropdownSortings();

      // Reset Filters and sortings
      this.resetFilters = function resetFilters() {
        self.container
          .querySelectorAll(".button-group")
          .forEach(function (buttonGroup) {
            if (buttonGroup.querySelector(".mnw_filter_active"))
              buttonGroup
                .querySelector(".mnw_filter_active")
                .classList.remove("mnw_filter_active");

            buttonGroup
              .querySelector("li:first-child a")
              .classList.add("mnw_filter_active");

            // Reset filters
            var filterGroup = buttonGroup.getAttribute("data-filter-group");
            filters[filterGroup] = "";

            // Hide reset button in pagination
            if (self.container.querySelector(".mnwall-reset-btn"))
              self.container.querySelector(".mnwall-reset-btn").style.display =
                "none";
          });

        // Reset dropdown filters text
        self.container
          .querySelectorAll(".mnwall_iso_dropdown")
          .forEach(function (dropdownGroup) {
            var filter_text = dropdownGroup
              .querySelector(".dropdown-label span")
              .getAttribute("data-label");
            dropdownGroup.querySelector(
              ".dropdown-label span span"
            ).textContent = filter_text;
          });

        // Get first item in sortBy array
        self.container
          .querySelectorAll(".sorting-group-filters")
          .forEach(function (sortingGroup) {
            sortingGroup
              .querySelectorAll(".mnwall-filter")
              .forEach(function (a) {
                a.classList.remove("mnw_filter_active");
              });
            if (
              sortingGroup.querySelector(
                'li a[data-sort-value="' + self.sortBy[0] + '"]'
              )
            ) {
              sortingGroup
                .querySelector('li a[data-sort-value="' + self.sortBy[0] + '"]')
                .classList.add("mnw_filter_active");
            }
          });

        self.container
          .querySelectorAll(".sorting-group-direction")
          .forEach(function (sortingGroupDirection) {
            sortingGroupDirection
              .querySelector(".mnw_filter_active")
              .classList.remove("mnw_filter_active");
            sortingGroupDirection
              .querySelector(
                'li a[data-sort-value="' + self.sortDirection + '"]'
              )
              .classList.add("mnw_filter_active");
          });

        // set filter for Isotope
        self.wall.arrange({
          filter: "",
          sortBy: self.sortBy,
          sortAscending: self.sortAscending,
        });
      };

      document
        .querySelectorAll(
          "#mnwall_reset_" +
            this.widgetId +
            ", #mnwall_container_" +
            this.widgetId +
            " .mnwall-reset-btn"
        )
        .forEach(function (a) {
          a.addEventListener("click", function (e) {
            e.preventDefault();

            self.resetFilters();
          });
        });
    }

    triggerHoverBox() {
      const self = this;

      if (self.gridType == 99 || self.gridType == 98) {
        self.container.querySelectorAll(".mnwall-item").forEach(function (a) {
          a.addEventListener("mouseenter", function (e) {
            switch (self.options.mas_hb_effect) {
              case "no":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "hoverShow"
                );
                break;
              case "1":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "hoverFadeIn"
                );
                break;
              case "2":
                this.querySelector(".mnwall-cover").classList.add(
                  "perspective"
                );
                this.querySelector(".mnwall-img-div").classList.add(
                  "flip",
                  "flipY",
                  "hoverFlipY"
                );
                break;
              case "3":
                this.querySelector(".mnwall-cover").classList.add(
                  "perspective"
                );
                this.querySelector(".mnwall-img-div").classList.add(
                  "flip",
                  "flipX",
                  "hoverFlipX"
                );
                break;
              case "4":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "slideInRight"
                );
                break;
              case "5":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "slideInLeft"
                );
                break;
              case "6":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "slideInTop"
                );
                break;
              case "7":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "slideInBottom"
                );
                break;
              case "8":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "mnwzoomIn"
                );
                break;
              default:
                this.querySelector(".mnwall-hover-box").classList.add(
                  "hoverFadeIn"
                );
                break;
            }
          });

          a.addEventListener("mouseleave", function (e) {
            switch (self.options.mas_hb_effect) {
              case "no":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "hoverShow"
                );
                break;
              case "1":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "hoverFadeIn"
                );
                break;
              case "2":
                this.querySelector(".mnwall-img-div").classList.remove(
                  "hoverFlipY"
                );
                break;
              case "3":
                this.querySelector(".mnwall-img-div").classList.remove(
                  "hoverFlipX"
                );
                break;
              case "4":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "slideInRight"
                );
                break;
              case "5":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "slideInLeft"
                );
                break;
              case "6":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "slideInTop"
                );
                break;
              case "7":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "slideInBottom"
                );
                break;
              case "8":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "mnwzoomIn"
                );
                break;
              default:
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "hoverFadeIn"
                );
                break;
            }
          });
        });
      }

      if (self.gridType != 98 && self.gridType != 99) {
        self.container.querySelectorAll(".mnwall-item").forEach(function (a) {
          a.addEventListener("mouseenter", function (e) {
            switch (self.options.mas_hb_effect) {
              case "no":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "hoverShow"
                );
                break;
              case "1":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "hoverFadeIn"
                );
                break;
              case "2":
                this.classList.add("perspective");
                this.querySelector(".mnwall-item-outer-cont").classList.add(
                  "flip",
                  "flipY",
                  "hoverFlipY"
                );
                break;
              case "3":
                this.classList.add("perspective");
                this.querySelector(".mnwall-item-outer-cont").classList.add(
                  "flip",
                  "flipX",
                  "hoverFlipX"
                );
                break;
              case "4":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "animated",
                  "slideInRight"
                );
                break;
              case "5":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "animated",
                  "slideInLeft"
                );
                break;
              case "6":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "animated",
                  "slideInTop"
                );
                break;
              case "7":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "animated",
                  "slideInBottom"
                );
                break;
              case "8":
                this.querySelector(".mnwall-hover-box").classList.add(
                  "animated",
                  "mnwzoomIn"
                );
                break;
              default:
                this.querySelector(".mnwall-hover-box").classList.add(
                  "hoverFadeIn"
                );
                break;
            }
          });

          a.addEventListener("mouseleave", function (e) {
            switch (self.options.mas_hb_effect) {
              case "no":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "hoverShow"
                );
                break;
              case "1":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "hoverFadeIn"
                );
                break;
              case "2":
                this.querySelector(".mnwall-item-outer-cont").classList.remove(
                  "hoverFlipY"
                );
                break;
              case "3":
                this.querySelector(".mnwall-item-outer-cont").classList.remove(
                  "hoverFlipX"
                );
                break;
              case "4":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "slideInRight"
                );
                break;
              case "5":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "slideInLeft"
                );
                break;
              case "6":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "slideInTop"
                );
                break;
              case "7":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "slideInBottom"
                );
                break;
              case "8":
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "mnwzoomIn"
                );
                break;
              default:
                this.querySelector(".mnwall-hover-box").classList.remove(
                  "hoverFadeIn"
                );
                break;
            }
          });
        });
      }
    }

    doneBrowserResizing(_this) {
      _this.fixEqualHeights("all");
      _this.wall.arrange();
    }

    fixEqualHeights(items) {
      const self = this;

      if (
        this.gridType == 98 &&
        this.layoutMode == "fitRows" &&
        this.dbPosition == "below" &&
        this.equalHeight
      ) {
        var max_height = 0;

        if (items == "all") {
          this.container
            .querySelectorAll(".mnwall-item-inner")
            .forEach(function (a) {
              a.style.height = "auto";

              if (a.offsetHeight > max_height) max_height = a.offsetHeight;
            });
        } else {
          items.forEach(function (a) {
            var _this_item_inner = a.querySelector(".mnwall-item-inner");
            _this_item_inner.style.height = "auto";

            if (_this_item_inner.offsetHeight > max_height)
              max_height = _this_item_inner.offsetHeight;
          });
        }

        this.container
          .querySelectorAll(".mnwall-item-inner")
          .forEach(function (a) {
            a.style.height = max_height + "px";
          });

        setTimeout(function () {
          self.wall.arrange();
        }, 1);
      }
    }

    createSpinner(divIdentifier) {
      var spinner_options = {
        lines: 9,
        length: 4,
        width: 3,
        radius: 3,
        corners: 1,
        rotate: 0,
        direction: 1,
        color: "#000",
        speed: 1,
        trail: 52,
        shadow: false,
        hwaccel: false,
        className: "spinner",
        zIndex: 2e9,
        top: "50%",
        left: "50%",
      };

      var target = divIdentifier;

      if (target) {
        var spinner = new Spinner(spinner_options).spin();
        target.innerHTML = "";
        target.appendChild(spinner.el);
      }

      return;
    }

    htmlToElements(htmlString, selector) {
      var div = document.createElement("div");
      div.innerHTML = htmlString.trim();

      return div.querySelectorAll(selector);
    }

    initModalMessages() {
      var zoomWall = document.querySelector("#zoomWall_" + this.widgetId);

      if (!zoomWall) return;

      zoomWall.addEventListener("show.bs.modal", function (e) {
        // Button that triggered the modal
        var button = e.relatedTarget;

        // Update the title
        if (zoomWall.querySelector(".modal-title")) {
          var title = button.getAttribute("data-title");
          zoomWall.querySelector(".modal-title").textContent = title;
        }

        // Update the image
        var image = button.getAttribute("data-src");
        zoomWall.querySelector("img").setAttribute("src", image);
      });
    }
  }

  window.Mwall = {
    initialise: (options, id, count, source, path) =>
      new Mwall(options, id, count, source, path),
  };
})(document, Joomla);
