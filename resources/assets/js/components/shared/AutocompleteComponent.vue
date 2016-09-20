<template>
    <div>
        <div class="form-group">
            <label :for="autocompleteFieldId">{{ autocompleteField | capitalize }}</label>
            <input
                v-model="chosenOption.name"
                v-on:keyup="respondToKeyup($event.keyCode)"
                v-on:blur="showDropdown = false"
                type="text"
                :id="autocompleteFieldId"
                :name="autocompleteFieldId"
                :placeholder="autocompleteField"
                class="form-control"
            >
        </div>

        <div
            v-show="showDropdown"
            class="autocomplete-dropdown"
        >
            <div
                v-for="option in autocompleteOptions"
                v-bind:class="{'selected': currentIndex === $index}"
                class="autocomplete-dropdown-item">
                {{ option.name }}
            </div>
        </div>
    </div>

</template>

<script>
    module.exports = {
        template: '#autocomplete-template',
        data: function () {
            return {
                autocompleteOptions: [],
                chosenOption: {
                    name: ''
                },
                showDropdown: false,
                currentIndex: 0
            };
        },
        components: {},
        methods: {

            /**
             *
             * @param keycode
             */
            respondToKeyup: function (keycode) {
                if (keycode !== 13 && keycode !== 38 && keycode !== 40 && keycode !== 39 && keycode !== 37) {
                    //not enter, up, down, right or left arrows
                    this.populateOptions();
                }
                else if (keycode === 38) {
                    //up arrow pressed
                    if (this.currentIndex !== 0) {
                        this.currentIndex--;
                    }
                }
                else if (keycode === 40) {
                    //down arrow pressed
                    if (this.autocompleteOptions.length - 1 !== this.currentIndex) {
                        this.currentIndex++;
                    }
                }
                else if (keycode === 13) {
                    this.respondToEnter();
                }
            },

            /**
             *
             */
            populateOptions: function () {
                //fill the dropdown
                store.showLoading();
                this.$http.get(this.url + '?typing=' + this.chosenOption.name).then(function (response) {
                    this.autocompleteOptions = response.data.data;
                    this.showDropdown = true;
                    this.currentIndex = 0;
                    store.hideLoading();
                }, function (response) {
                    helpers.handleResponseError(response);
                });
            },

            /**
             *
             */
            respondToEnter: function () {
                if (this.showDropdown) {
                    //enter is for the autocomplete
                    this.selectOption();
                }
                else {
                    //enter is to add the entry
                    this.insertItemFunction();
                }
            },

            /**
             *
             */
            selectOption: function () {
                this.chosenOption = this.autocompleteOptions[this.currentIndex];
                this.showDropdown = false;
                if (this.idToFocusAfterAutocomplete) {
                    var that = this;
                    setTimeout(function () {
                        $("#" + that.idToFocusAfterAutocomplete).focus();
                    }, 100);
                }
                this.$dispatch('option-chosen', this.chosenOption);
            },

            /**
             *
             * @param response
             */
            handleResponseError: function (response) {
                $.event.trigger('response-error', [response]);
                this.showLoading = false;
            }
        },
        props: [
            'url',
            'autocompleteField',
            'insertItemFunction',
            'idToFocusAfterAutocomplete'
        ],
        ready: function () {

        }
    };
</script>

