// Load the media selection tool
jQuery(function ($) {
  // on upload button click
  $("body").on("click", ".glb-upload", function (event) {
    event.preventDefault(); // prevent default link click and page refresh

    const button = $(this);
    const imageId = button.next().next().val();

    const customUploader = wp
      .media({
        title: "Insert image", // modal window title
        library: {
          // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
          type: "image",
        },
        button: {
          text: "Use this image", // button label text
        },
        multiple: false,
      })
      .on("select", function () {
        // it also has "open" and "close" events
        const attachment = customUploader
          .state()
          .get("selection")
          .first()
          .toJSON();
        button
          .removeClass("button")
          .html('<img src="' + attachment.url + '" alt="" class="widefat">'); // add image instead of "Upload Image"
        button.next().show(); // show "Remove image" link
        button.next().next().val(attachment.id); // Populate the hidden field with image ID
      });

    // already selected images
    customUploader.on("open", function () {
      if (imageId) {
        const selection = customUploader.state().get("selection");
        attachment = wp.media.attachment(imageId);
        attachment.fetch();
        selection.add(attachment ? [attachment] : []);
      }
    });

    customUploader.open();
  });
  // on remove button click
  $("body").on("click", ".glb-remove", function (event) {
    event.preventDefault();
    const button = $(this);
    button.next().val(""); // emptying the hidden field
    button.hide().prev().addClass("button").html("Upload image"); // replace the image with text
  });
});

// Submit the tag form
jQuery(function ($) {
  let numberOfTags = 0;
  let newNumberOfTags = 0;

  // when there are some terms are already created
  if (!$("#the-list").children("tr").first().hasClass("no-items")) {
    numberOfTags = $("#the-list").children("tr").length;
  }

  // after a term has been added via AJAX
  $(document).ajaxComplete(function (event, xhr, settings) {
    newNumberOfTags = $("#the-list").children("tr").length;
    if (parseInt(newNumberOfTags) > parseInt(numberOfTags)) {
      // refresh the actual number of tags variable
      numberOfTags = newNumberOfTags;

      // empty custom fields right here
      $(".glb-remove").each(function () {
        // empty hidden field
        $(this).next().val("");
        // hide remove image button
        $(this).hide().prev().addClass("button").text("Upload image");
      });
    }
  });
});
