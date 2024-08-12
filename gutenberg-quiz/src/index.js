wp.blocks.registerBlockType("ourplugin/attentionquiz", {
  // Information
  title: "Attention Quiz",
  description: "A simple plugin to add attention quiz in posts.",
  icon: "smiley",
  category: "common",

  // attributes
  attributes: {
    skyColor: {
      type: "string",
    },
    grassColor: {
      type: "string",
    },
  },

  // Edit (on Backend)
  edit: function (props) {
    function theskyColor(event) {
      props.setAttributes({ skyColor: event.target.value });
    }
    function thegrassColor(event) {
      props.setAttributes({ grassColor: event.target.value });
    }
    return (
      <div>
        <input
          type="text"
          placeholder="Sky Color"
          value={props.attributes.skyColor}
          onChange={theskyColor}
        />
        <input
          type="text"
          placeholder="Grass Color"
          value={props.attributes.grassColor}
          onChange={thegrassColor}
        />
      </div>
    );
  },
});

