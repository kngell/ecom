//Image config
export const imageConfig = {
  // Configure the available styles.
  styles: ["alignLeft", "alignCenter", "alignRight"],

  // Configure the available image resize options.
  resizeOptions: [
    {
      name: "resizeImage:original",
      value: null,
      icon: "original",
    },
    {
      name: "resizeImage:25",
      value: "25",
      icon: "small",
    },
    {
      name: "resizeImage:50",
      value: "50",
      icon: "medium",
    },
    {
      name: "resizeImage:75",
      value: "75",
      icon: "large",
    },
    {
      name: "resizeImage:100",
      value: "100",
      icon: "big",
    },
  ],
  // You need to configure the image toolbar, too, so it shows the new style
  // buttons as well as the resize buttons.
  toolbar: [
    "imageTextAlternative",
    "toggleImageCaption",
    "|",
    "imageStyle:alignLeft",
    "imageStyle:alignCenter",
    "imageStyle:alignRight",
    "|",
    "imageResize",
  ],
};

// Font Config
export const fontColorConfig = [
  {
    color: "hsl(0, 0%, 0%)",
    label: "Black",
  },
  {
    color: "hsl(0, 0%, 30%)",
    label: "Dim grey",
  },
  {
    color: "hsl(0, 0%, 60%)",
    label: "Grey",
  },
  {
    color: "hsl(0, 0%, 90%)",
    label: "Light grey",
  },
  {
    color: "hsl(0, 0%, 100%)",
    label: "White",
    hasBorder: true,
  },

  // ...
];
export const fontbgColorConfig = [
  {
    color: "hsl(0, 75%, 60%)",
    label: "Red",
  },
  {
    color: "hsl(30, 75%, 60%)",
    label: "Orange",
  },
  {
    color: "hsl(60, 75%, 60%)",
    label: "Yellow",
  },
  {
    color: "hsl(90, 75%, 60%)",
    label: "Light green",
  },
  {
    color: "hsl(120, 75%, 60%)",
    label: "Green",
  },

  // ...
];

// Heading config
export const headings = [
  {
    model: "paragraph",
    title: "Paragraph",
    class: "ck-heading_paragraph",
  },
  {
    model: "heading1",
    view: "h1",
    title: "Heading 1",
    class: "ck-heading_heading1",
  },
  {
    model: "heading2",
    view: "h2",
    title: "Heading 2",
    class: "ck-heading_heading2",
  },
  {
    model: "heading3",
    view: "h3",
    title: "Heading 3",
    class: "ck-heading_heading3",
  },
];
//link config
export const linkConfig = {
  // Automatically add target="_blank" and rel="noopener noreferrer" to all external links.
  addTargetToExternalLinks: true,

  // Let the users control the "download" attribute of each link.
  decorators: [
    {
      mode: "manual",
      label: "Downloadable",
      attributes: {
        download: "download",
      },
    },
  ],
};

// Media config
export const mediaConfig = {};

//Table config
export const tableConfig = {
  contentToolbar: [
    "tableColumn",
    "tableRow",
    "mergeTableCells",
    "tableProperties",
    "tableCellProperties",
  ],

  // Configuration of the TableProperties plugin.
  tableProperties: {
    // ...
  },

  // Configuration of the TableCellProperties plugin.
  tableCellProperties: {
    // ...
  },
};
