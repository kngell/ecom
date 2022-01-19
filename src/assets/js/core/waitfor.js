export default function imagesLoaded(selector) {
  return Promise.all(
    Array.from(selector).map((img) => {
      if (img.complete)
        if (img.naturalHeight !== 0) {
          return Promise.resolve();
        } else {
          return Promise.reject(img);
        }
      return new Promise((resolve, reject) => {
        img.addEventListener("load", resolve);
        img.addEventListener("error", () => reject(img));
      });
    })
  );
}

export var waitForEl = function (selector, callback) {
  if (jQuery(selector).length) {
    callback();
  } else {
    setTimeout(function () {
      waitForEl(selector, callback);
    }, 100);
  }
};
