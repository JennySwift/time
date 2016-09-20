<template>
    <div
        v-show="showPopup"
        transition="popup-outer"
        v-on:click="closePopup($event)"
        class="popup-outer animate"
    >

        <div
            v-show="showPopup"
            transition="popup-inner"
            :id="id"
            class="popup-inner scrollbar-container animate"
        >

            <div class="content">
                <slot name="content"></slot>
            </div>

            <div class="buttons">
                <slot name="buttons">
                    <button
                        v-on:click="showPopup = false"
                        v-link="{path: redirectTo}"
                        class="btn btn-default"
                    >
                        <span v-if="!update && !destroy">Close</span>
                        <span v-else>Cancel</span>
                    </button>

                    <button
                        v-if="destroy"
                        v-on:click="destroy()"
                        v-link="{path: redirectTo}"
                        class="btn btn-danger"
                    >
                        Delete
                    </button>

                    <button
                        v-if="update"
                        v-on:click="update()"
                        v-link="{path: redirectTo}"
                        class="btn btn-success"
                    >
                        Save
                    </button>
                </slot>
            </div>

        </div>
    </div>

</template>

<script>
    module.exports = {
        template: '#popup-template',
        data: function () {
            return {

            };
        },
        components: {},
        methods: {
            /**
             *
             */
            closePopup: function ($event) {
                if ($($event.target).hasClass('popup-outer')) {
                    this.showPopup = false;
                    router.go(this.redirectTo);
                }
            },
        },
        props: [
            'showPopup',
            'id',
            'redirectTo',
            'update',
            'destroy'
        ],
        ready: function () {
            // helpers.scrollbars();
        }
    };
</script>

