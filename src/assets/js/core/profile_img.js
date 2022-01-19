export const readurl = (input, img, icon) => {
  if (input.files && input.files[0]) {
    let reader = new FileReader();
    reader.onload = function (e) {
      img.attr("src", e.target.result);
      icon.css({
        display: "none",
      });
    };
    reader.readAsDataURL(input.files[0]);
  }
};
