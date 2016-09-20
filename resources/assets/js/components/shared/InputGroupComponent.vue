<template>
    <div class="input-group">
        <!--Label-->
        <label :for="id" class="input-group-addon">
            {{label}}
            <!--Tooltip-->
            <span v-if="tooltipId" class="tooltipster fa fa-question-circle" data-tooltipster='{"side":"bottom"}' data-tooltip-content="#{{tooltipId}}"></span>
        </label>

        <!--Text input-->
        <input
            v-if="!options"
            v-model="model"
            v-on:keyup.13="enter()"
            type="text"
            :id="id"
            class="form-control"
        >

        <!--Select-->
        <select
            v-if="options"
            v-model="model"
            v-on:keyup.13="enter()"
            id="id"
            class="form-control"
        >
            <option
                v-for="option in options"
                v-bind:value="option"
            >
                {{getOptionText(option)}}
            </option>
        </select>
    </div>




</template>

<script>
    module.exports = {
        methods: {
            getOptionText: function (option) {
                var text = '';
                if (!this.optionsProp) {
                    text = option;
                }

                else {
                    text = option[this.optionsProp];
                }

                if (this.optionsAdditionalText) {
                    text+= this.optionsAdditionalText;
                }

                return text;
            }
        },
        props: [
            'label',
            'model',
            'id',
            'options',
            'optionsProp',
            //Text to add after each option
            'optionsAdditionalText',
            'tooltipId',
            //Method to run on enter
            'enter'
        ]
    };
</script>