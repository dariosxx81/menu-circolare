jQuery(document).ready(function ($) {
  "use strict";

  // Make menu items sortable
  $("#mcm-menu-items").sortable({
    handle: ".mcm-item-handle",
    cursor: "move",
    opacity: 0.8,
    update: function (event, ui) {
      let positions = [];
      $("#mcm-menu-items .mcm-item").each(function (index) {
        positions.push($(this).data("id"));
      });

      $.post(
        mcmAjax.ajaxurl,
        {
          action: "mcm_update_positions",
          nonce: mcmAjax.nonce,
          positions: positions,
        },
        function (response) {
          if (response.success) {
            showNotice("success", response.data);
          }
        },
      );
    },
  });

  // Update slider value displays
  $("#circle_size").on("input", function () {
    $("#circle_size_value").text($(this).val() + "px");
  });

  $("#circle_distance").on("input", function () {
    $("#circle_distance_value").text($(this).val() + "px");
  });

  $("#central_circle_size").on("input", function () {
    $("#central_circle_size_value").text($(this).val() + "px");
  });

  // Mobile slider handlers
  $("#circle_size_mobile").on("input", function () {
    $("#circle_size_mobile_value").text($(this).val() + "px");
  });

  $("#circle_distance_mobile").on("input", function () {
    $("#circle_distance_mobile_value").text($(this).val() + "px");
  });

  $("#central_circle_size_mobile").on("input", function () {
    $("#central_circle_size_mobile_value").text($(this).val() + "px");
  });

  // Save settings form
  $("#mcm-settings-form").on("submit", function (e) {
    e.preventDefault();

    const formData = {
      action: "mcm_save_settings",
      nonce: mcmAjax.nonce,
      background_color: $("#background_color").val(),
      circle_color: $("#circle_color").val(),
      text_color: $("#text_color").val(),
      hover_scale: $("#hover_scale").val(),
      animation_duration: $("#animation_duration").val(),
      circle_size: $("#circle_size").val(),
      circle_distance: $("#circle_distance").val(),
      central_circle_size: $("#central_circle_size").val(),
      circle_size_mobile: $("#circle_size_mobile").val(),
      circle_distance_mobile: $("#circle_distance_mobile").val(),
      central_circle_size_mobile: $("#central_circle_size_mobile").val(),
    };

    $.post(mcmAjax.ajaxurl, formData, function (response) {
      if (response.success) {
        showNotice("success", response.data);
        updatePreview();
      } else {
        showNotice("error", response.data);
      }
    });
  });

  // Save individual item
  $(document).on("click", ".mcm-save-item", function () {
    const $item = $(this).closest(".mcm-item");
    const itemId = $item.data("id");
    const isCentral =
      $item.data("is-central") || $(this).data("is-central") || 0;

    const itemData = {
      action: "mcm_save_item",
      nonce: mcmAjax.nonce,
      id: itemId,
      title: $item.find(".mcm-item-title").val(),
      url: $item.find(".mcm-item-url").val(),
      icon_path: $item.find(".mcm-item-icon").val(),
      active: $item.find(".mcm-item-active").length
        ? $item.find(".mcm-item-active").is(":checked")
          ? 1
          : 0
        : 1,
      is_central: isCentral,
    };

    $.post(mcmAjax.ajaxurl, itemData, function (response) {
      if (response.success) {
        showNotice("success", response.data.message || response.data);
        if (response.data.id) {
          $item.data("id", response.data.id);
        }
        updatePreview();
      } else {
        showNotice("error", response.data);
      }
    });
  });

  // Delete item
  $(document).on("click", ".mcm-delete-item", function () {
    if (!confirm("Sei sicuro di voler eliminare questo elemento?")) {
      return;
    }

    const $item = $(this).closest(".mcm-item");
    const itemId = $item.data("id");

    $.post(
      mcmAjax.ajaxurl,
      {
        action: "mcm_delete_item",
        nonce: mcmAjax.nonce,
        id: itemId,
      },
      function (response) {
        if (response.success) {
          $item.fadeOut(300, function () {
            $(this).remove();
          });
          showNotice("success", response.data);
          updatePreview();
        }
      },
    );
  });

  // Toggle active status
  $(document).on("change", ".mcm-item-active", function () {
    const $item = $(this).closest(".mcm-item");
    const itemId = $item.data("id");
    const isActive = $(this).is(":checked") ? 1 : 0;

    $.post(
      mcmAjax.ajaxurl,
      {
        action: "mcm_toggle_active",
        nonce: mcmAjax.nonce,
        id: itemId,
        active: isActive,
      },
      function (response) {
        if (response.success) {
          showNotice("success", response.data);
          updatePreview();
        }
      },
    );
  });

  // Upload icon
  $(document).on("click", ".mcm-upload-icon", function (e) {
    e.preventDefault();

    const $button = $(this);
    const $item = $button.closest(".mcm-item");

    const mediaUploader = wp.media({
      title: "Seleziona Icona",
      button: {
        text: "Usa questa icona",
      },
      multiple: false,
      library: {
        type: ["image/svg+xml", "image/png", "image/jpeg"],
      },
    });

    mediaUploader.on("select", function () {
      const attachment = mediaUploader
        .state()
        .get("selection")
        .first()
        .toJSON();
      const imageUrl = attachment.url; // Use full URL instead of filename

      $item.find(".mcm-item-icon").val(imageUrl); // Save full URL
      $item.find(".mcm-item-preview img").attr("src", imageUrl);
    });

    mediaUploader.open();
  });

  // Add new item
  $("#mcm-add-item").on("click", function () {
    const newItemHtml = `
            <div class="mcm-item" data-id="0">
                <div class="mcm-item-handle">
                    <span class="dashicons dashicons-menu"></span>
                </div>
                <div class="mcm-item-preview">
                    <img src="" alt="" style="display:none;" />
                </div>
                <div class="mcm-item-content">
                    <input type="text" class="mcm-item-title" value="" placeholder="Titolo" />
                    <input type="url" class="mcm-item-url" value="" placeholder="https://esempio.com" />
                    <input type="text" class="mcm-item-icon" value="" placeholder="nome-icona.svg" readonly />
                    <button type="button" class="button mcm-upload-icon">
                        <span class="dashicons dashicons-upload"></span> Carica Icona
                    </button>
                </div>
                <div class="mcm-item-actions">
                    <button type="button" class="button mcm-save-item" data-id="0">
                        <span class="dashicons dashicons-yes"></span>
                    </button>
                    <button type="button" class="button mcm-delete-item" data-id="0">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                    <label class="mcm-toggle">
                        <input type="checkbox" class="mcm-item-active" checked data-id="0" />
                        <span class="mcm-toggle-slider"></span>
                    </label>
                </div>
            </div>
        `;

    $("#mcm-menu-items").append(newItemHtml);
  });

  // Copy shortcode
  $(".mcm-copy-shortcode").on("click", function () {
    const shortcode = "[mesa_circular_menu]";
    navigator.clipboard.writeText(shortcode).then(function () {
      showNotice("success", "Shortcode copiato negli appunti!");
    });
  });

  // Helper: Show notice
  function showNotice(type, message) {
    const noticeClass = type === "success" ? "notice-success" : "notice-error";
    const notice = $(`
            <div class="notice ${noticeClass} is-dismissible" style="display:none;">
                <p>${message}</p>
            </div>
        `);

    $(".mcm-admin-wrap h1").after(notice);
    notice.slideDown(200);

    setTimeout(function () {
      notice.slideUp(200, function () {
        $(this).remove();
      });
    }, 3000);
  }

  // Helper: Update preview
  function updatePreview() {
    // Reload preview iframe
    const frame = document.getElementById("mcm-preview-frame");
    if (frame) {
      frame.src = frame.src;
    }
  }
});
