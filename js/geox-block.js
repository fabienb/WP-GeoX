(function(blocks, element, editor, components) {
    var el = element.createElement;
    var InnerBlocks = editor.InnerBlocks;
    var TextControl = components.TextControl;
    var InspectorControls = editor.InspectorControls;
    var PanelBody = components.PanelBody;

    blocks.registerBlockType('geox/conditional-container', {
        title: 'GeoX Conditional Container',
        icon: 'location',
        category: 'layout',
        attributes: {
            include: {
                type: 'string',
                default: '',
            },
            exclude: {
                type: 'string',
                default: '',
            },
        },

        edit: function(props) {
            var include = props.attributes.include;
            var exclude = props.attributes.exclude;

            function onChangeInclude(newInclude) {
                props.setAttributes({ include: newInclude });
            }

            function onChangeExclude(newExclude) {
                props.setAttributes({ exclude: newExclude });
            }

            return [
                el(InspectorControls, { key: 'controls' },
                    el(PanelBody, { title: 'GeoX Settings' },
                        el(TextControl, {
                            label: 'Include (Country codes, city names, or continent codes)',
                            value: include,
                            onChange: onChangeInclude,
                            help: 'Enter comma-separated values to include',
                        }),
                        el(TextControl, {
                            label: 'Exclude (Country codes, city names, or continent codes)',
                            value: exclude,
                            onChange: onChangeExclude,
                            help: 'Enter comma-separated values to exclude',
                        })
                    )
                ),
                el('div', { className: props.className },
                    el('div', { className: 'geox-container' },
                        el(InnerBlocks)
                    )
                )
            ];
        },

        save: function(props) {
            return el('div', {
                className: 'geox-conditional-container',
                'data-include': props.attributes.include,
                'data-exclude': props.attributes.exclude
            },
                el(InnerBlocks.Content)
            );
        },
    });
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components
));
