$("#yourDropdownId").select2({
  ajax: {
    url: productUrl,
    dataType: "json",
    data: function (params) {
      var query = {
        search: params.term,
        page: params.page || 1,
      };

      // Query parameters will be ?search=[term]&page=[page]
      return query;
    },
    processResults: function (data, params) {
      console.log(data);
      return {
        results: $.map(data.items, function (item) {
          return {
            text: item.item_name,
            id: item.item_id,
          };
        }),
      };
    },
    cache: true,
  },
  language: {
    noResults: function () {
      return "<a  data-toggle='modal' data-target='#myModal' href='javascript:void();'>Open Model</a>";
    },
  },
  escapeMarkup: function (markup) {
    return markup;
  },
});

$("#select2").select2({
  placeholder: "This is my placeholder",
  language: {
    noResults: function () {
      return `<button style="width: 100%" type="button"
         class="btn btn-primary" 
         onClick='task()'>+ Add New Item</button>
         </li>`;
    },
  },

  escapeMarkup: function (markup) {
    return markup;
  },
});

function task() {
  alert("Hello world! ");
}

on("select2:close", function () {
  var el, newOption, newval;
  el = $(this);
  if (el.val() !== null && el.val()[0] === "") {
    el.val(el.val().slice(1));
    el.trigger("change");
    newval = prompt("Enter new value:");
    if (newval !== null && newval !== "") {
      newOption = new Option(newval, newval, true, true);
      return el.append(newOption).trigger("change");
    }
  }
});
