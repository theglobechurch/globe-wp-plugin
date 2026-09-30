// Repeater for the link tree "Links" meta box
jQuery(function ($) {
  const list = $("#glb-tree-links");
  const template = $("#glb-tree-link-template").html();
  // Start above any existing row so new field names never collide
  let counter = list.children().length;

  list.sortable({
    handle: ".glb-tree-link__handle",
    items: "> .glb-tree-link",
    axis: "y",
  });

  $("#glb-tree-link-add").on("click", function () {
    list.append(template.replace(/__INDEX__/g, counter++));
  });

  list.on("click", ".glb-tree-link-delete", function (event) {
    event.preventDefault();
    $(this).closest(".glb-tree-link").remove();
  });
});
