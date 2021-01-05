import { mapState } from 'vuex';
import helpers from './../helpers';
import validation from './../validation';

export default {
    mixins: [ helpers, validation ],
    model: {
        prop: 'value',
        event: 'input'
    },
    props: {
        fieldId: {
            type: String,
            required: false,
            default: '',
        },
        label: {
            type: String,
            required: false,
            default: '',
        },
        description: {
            type: [ String ],
            required: false,
            default: '',
        },
        value: {
            type: [ String, Number ],
            required: false,
            default: '',
        },
        options: {
            type: Array,
            required: false,
        },
        showDefaultOption: {
            type: Boolean,
            default: false,
        },
        defaultOption: {
            type: Object,
            required: false,
        },
        optionsSource: {
            type: Object,
            required: false,
        },
        name: {
            type: [String, Number],
            required: false,
            default: '',
        },
        placeholder: {
            type: [String, Number],
            required: false,
            default: '',
        },
        validationFeedback: {
            type: Object,
            required: false,
        },
        validation: {
            type: Array,
            required: false,
        },
    },

    mounted() {
        this.setup();
    },

    watch: {
        local_value() {
            this.$emit( 'update', this.local_value );
        },

        theOptions() {
            if ( ! this.valueIsValid( this.local_value ) ) {
                this.local_value = '';
            }
        },
    },

    computed: {
        ...mapState({
            fields: 'fields',
        }),

        theCurrentOptionLabel() {
            if ( ! this.optionsInObject ) { return ''; }
            if ( typeof this.optionsInObject[ this.value ] === 'undefined' ) { return ''; }

            return this.optionsInObject[ this.value ];
        },

        theOptions() {
            if ( this.hasOptionsSource ) {
                return this.hasOptionsSource;
            }

            if ( ! this.options || typeof this.options !== 'object' ) {
                return ( this.defaultOption ) ? [ this.defaultOption ] : [];
            }

            return this.options;
        },

        hasOptionsSource() {
            if ( ! this.optionsSource || typeof this.optionsSource !== 'object' ) {
                return false;
            }

            if ( typeof this.optionsSource.where !== 'string' ) {
                return false;
            }

            let terget_fields = this.getTergetFields( { path: this.optionsSource.where } );
            
            if ( ! terget_fields || typeof terget_fields !== 'object' ) {
                return false;
            }

            let filter_by = null;
            if ( typeof this.optionsSource.filter_by === 'string' && this.optionsSource.filter_by.length ) {
                filter_by = this.optionsSource.filter_by;
            }

            if ( filter_by ) {
                filter_by = this.getTergetFields( { path: this.optionsSource.filter_by } );
            }
            
            let has_sourcemap = false;

            if ( this.optionsSource.source_map && typeof this.optionsSource.source_map === 'object'  ) {
                has_sourcemap = true;
            }

            if ( ! has_sourcemap && ! filter_by ) {
                return terget_fields;
            }

            if ( has_sourcemap ) {
                terget_fields = this.mapDataByMap( terget_fields, this.optionsSource.source_map );
            }

            if ( filter_by ) {
                terget_fields = this.filterDataByValue( terget_fields, filter_by );
            }

            if ( ! terget_fields && typeof terget_fields !== 'object' ) {
                return false;
            }

            return terget_fields;
        },
    },

    data() {
        return {
            local_value: '',
            local_value_ms: [],
            optionsInObject: {},
            show_option_modal: false,
            clickEvent: null,
        }
    },

    methods: {
        setup() {
            if ( this.defaultOption || typeof this.defaultOption === 'object' ) {
                this.default_option = this.defaultOption;
            }

            if ( this.valueIsValid( this.value ) ) {
                this.local_value = this.value;
            }

            this.optionsInObject = this.convertOptionsToObject();

            const self = this;
            document.addEventListener( 'click', function() {
                self.show_option_modal = false;
            });
            
        },

        update_value( value ) {
            this.local_value = ( ! isNaN( Number( value ) ) ) ? Number( value ) : value;
        },

        updateOption( value ) {
            this.update_value( value );
            this.show_option_modal = false;
        },

        toggleTheOptionModal() {
            let self = this;

            setTimeout( function() {
                self.show_option_modal = ! self.show_option_modal;
            }, 0);
        },

        valueIsValid( value ) {
            let options_values = this.theOptions.map( option => {
                if ( typeof option.value !== 'undefined' ) {
                    return ( ! isNaN( Number( option.value ) ) ) ? Number( option.value ) : option.value
                }
            });

            return options_values.includes( value );
        },

        convertOptionsToObject() {
            if ( ! ( this.theOptions && Array.isArray( this.theOptions ) ) ) { return null; }

            let option_object = {};
            for ( let option in this.theOptions ) {
                if ( typeof this.theOptions[ option ].value === 'undefined' ) { continue; }

                let label = ( this.theOptions[ option ].label ) ? this.theOptions[ option ].label : '';
                option_object[ this.theOptions[ option ].value ] = label;
            }

            return option_object;
        }

        /* syncValidationWithLocalState( validation_log ) {
            return validation_log;
        } */
    },
}